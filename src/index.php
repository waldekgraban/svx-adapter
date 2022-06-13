<?php

namespace Waldekgraban\SvxAdapter;

require_once "../vendor/autoload.php";

use Waldekgraban\SvxAdapter\SvxImporter\Svx;

$filename = __DIR__ . '/SvxExampleFiles/black_howk_down.svx';

$importer = new Svx;
$importer->importSvx($filename);




