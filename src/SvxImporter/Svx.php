<?php

namespace Waldekgraban\SvxAdapter\SvxImporter;

class Svx extends Importer
{
    public function importSvx(string $filePath): void
    {
        $this->file = $this->getFileContent($filePath);
    }
}
