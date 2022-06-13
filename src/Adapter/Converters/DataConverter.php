<?php

namespace Waldekgraban\SvxAdapter\Adapter\Converters;

use Waldekgraban\SvxAdapter\Adapter\Commands\Data;
use Waldekgraban\SvxAdapter\Adapter\Parser\Line;

class DataConverter
{
    public function convert(Line $line)
    {
        $values = $line->getData()->getValues();

        $style    = array_shift($values);
        $ordering = $values;

        return new Data($style, $ordering);
    }
}
