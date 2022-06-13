<?php

namespace Waldekgraban\SvxAdapter\Adapter\Parser;

use Waldekgraban\SvxAdapter\Adapter\Parser\LineCollection;
use Waldekgraban\SvxAdapter\Adapter\Support\Collection;

class LineStack extends Collection
{
    final public static function fromLines(LineCollection $allLines)
    {
        $stackCollection = new static();
        $stack           = [];

        $current = null;

        foreach ($allLines as $line) {
            if ($line->getTitle() === 'begin') {
                $current = new LineCollection();

                $stack[] = $current;
            }

            if ($current === null) {
                throw new \Exception('bad file :(');
            }

            $current->append($line);

            if ($line->getTitle() === 'end') {
                $stackCollection->append($current);

                array_pop($stack);
                $current = end($stack);
            }
        }

        return $stackCollection->reverse();
    }
}
