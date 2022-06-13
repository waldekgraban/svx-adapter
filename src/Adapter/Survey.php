<?php

namespace Waldekgraban\SvxAdapter\Adapter;

use Waldekgraban\SvxAdapter\Adapter\Commands\Calibration;
use Waldekgraban\SvxAdapter\Adapter\Commands\CalibrationCollection;
use Waldekgraban\SvxAdapter\Adapter\Commands\Data;
use Waldekgraban\SvxAdapter\Adapter\Commands\DataCollection;
use Waldekgraban\SvxAdapter\Adapter\Commands\Date;
use Waldekgraban\SvxAdapter\Adapter\Commands\DateCollection;
use Waldekgraban\SvxAdapter\Adapter\Commands\DateRange;
use Waldekgraban\SvxAdapter\Adapter\Commands\Team;
use Waldekgraban\SvxAdapter\Adapter\Commands\TeamCollection;
use Waldekgraban\SvxAdapter\Adapter\Commands\Unit;
use Waldekgraban\SvxAdapter\Adapter\Commands\UnitCollection;
use Waldekgraban\SvxAdapter\Adapter\Converters\CalibrationConverter;
use Waldekgraban\SvxAdapter\Adapter\Converters\DataConverter;
use Waldekgraban\SvxAdapter\Adapter\Converters\DateConverter;
use Waldekgraban\SvxAdapter\Adapter\Converters\MeasurementConverter;
use Waldekgraban\SvxAdapter\Adapter\Converters\TeamConverter;
use Waldekgraban\SvxAdapter\Adapter\Converters\UnitConverter;
use Waldekgraban\SvxAdapter\Adapter\Enums\LineType;
use Waldekgraban\SvxAdapter\Adapter\Parser\Line;
use Waldekgraban\SvxAdapter\Adapter\Parser\LineCollection;

class Survey
{
    public $name;

    public $title;

    public $calibrations;

    public $units;

    public $team;

    public $dates;

    public $data;

    final public function __construct(LineCollection $lines = null)
    {
        $this->lines = $lines;

        $this->calibrations = new CalibrationCollection();
        $this->units        = new UnitCollection();
        $this->team         = new TeamCollection();
        $this->dates        = new DateCollection();
        $this->data         = new DataCollection();

        $this->convertLines();
    }

    public function convertLines()
    {
        $data = new Data();

        foreach ($this->lines as $line) {
            $lineType = $line->getType();

            /**
             * For now skip all comments.
             */
            if ($lineType === LineType::COMMENT) {
                continue;
            }

            $value = $this->convertLine($line);

            if ($value instanceof Data) {
                $data = $value;
            }

            if ($lineType === LineType::INFORMATION) {
                $this->addInformation($line->getTitle(), $value);
            } elseif ($lineType === LineType::MEASUREMENT) {
                $data->addMeasurement($value);
            }
        }

        if (!$this->getData()->contains($data)) {
            $this->addData($data);
        }
    }

    public function convertLine(Line $line)
    {
        if ($line->getType() === LineType::MEASUREMENT) {
            return (new MeasurementConverter())->convert($line);
        }

        switch ($line->getTitle()) {
            case 'begin':
                return $line->getData()->getContent();
            case 'title':
                return trim($line->getData()->getContent(), '\"');
            case 'calibrate':
                return (new CalibrationConverter())->convert($line);
            case 'units':
                return (new UnitConverter())->convert($line);
            case 'team':
                return (new TeamConverter())->convert($line);
            case 'date':
                return (new DateConverter())->convert($line);
            case 'data':
                return (new DataConverter())->convert($line);
        }
    }

    public function addInformation($lineTitle, $value)
    {
        switch ($lineTitle) {
            case 'begin':
                return $this->setName($value);
            case 'title':
                return $this->setTitle($value);
            case 'calibrate':
                return $this->addCalibrations($value);
            case 'units':
                return $this->addUnits($value);
            case 'team':
                return $this->addTeam($value);
            case 'date':
                if ($value instanceof DateRange) {
                    return $this->addDateRange($value);
                } elseif ($value instanceof Date) {
                    return $this->addDate($value);
                }
            case 'data':
                return $this->addData($value);
        }
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function addCalibrations(CalibrationCollection $calibrations)
    {
        $calibrations->each(function ($calibration) {
            $this->addCalibration($calibration);
        });

        return $this;
    }

    public function addCalibration(Calibration $calibration)
    {
        $this->calibrations->append($calibration);

        return $this;
    }

    public function addUnits(UnitCollection $unit)
    {
        $unit->each(function ($unit) {
            $this->addUnit($unit);
        });

        return $this;
    }

    public function addUnit(Unit $unit)
    {
        $this->units->append($unit);

        return $this;
    }

    public function addTeam(Team $team)
    {
        $this->team->append($team);

        return $this;
    }

    public function addDate(Date $date)
    {
        $this->dates->append($date);

        return $this;
    }

    public function addDateRange(DateRange $dateRange)
    {
        $this->dates->append($dateRange);

        return $this;
    }

    public function addData(Data $data)
    {
        $this->data->append($data);

        return $this;
    }

    public function getUnits()
    {
        return $this->units;
    }

    public function getData()
    {
        return $this->data;
    }
}
