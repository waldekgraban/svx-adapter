<?php

namespace Waldekgraban\SvxAdapter\Adapter\Converters;

use Waldekgraban\SvxAdapter\Adapter\Commands\Measurement;
use Waldekgraban\SvxAdapter\Adapter\Parser\Line;

class MeasurementConverter
{
    const SEPARATOR = '[SEP]';

    public function convert(Line $line)
    {
        $content = preg_replace('/\s+/', static::SEPARATOR, $line->getContent());
        $values  = explode(static::SEPARATOR, $content);

        return new Measurement($values);
    }
}
