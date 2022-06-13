<?php

namespace Waldekgraban\SvxAdapter;

require_once "../vendor/autoload.php";

use Waldekgraban\SvxAdapter\SvxImporter\Svx;
use Waldekgraban\SvxAdapter\Adapter\Parser\Parser;

$filename = __DIR__ . '/SvxExampleFiles/black_howk_down.svx';

$importer = new Svx;
$importer->importSvx($filename);

$parser = Parser::make($importer->file);

$surveys = $parser->parse();
$survey = $surveys->first();

$data   = $survey->getData()->first();

foreach ($data->getMeasurements() as $measurement) {
    dump($measurement->getValues());
}






