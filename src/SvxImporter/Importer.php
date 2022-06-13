<?php

namespace Waldekgraban\SvxAdapter\SvxImporter;

use Waldekgraban\SvxAdapter\Enums\Message;

abstract class Importer
{
    protected function getFileContent(string $filePath): string
    {
        $fileContent = file_get_contents($filePath);

        if(gettype($fileContent) === "boolean") {
            $this->stopWithErrorMessage($filePath);
        }
            
        return $fileContent;
    }

    private function stopWithErrorMessage(string $filePath): void
    {
        $message = Message::FILE_IMPORT_ERROR . " File indicated: " . $filePath . " - Check that the path is correct.";
        $this->kill($message);
    }

    private function kill(string $message): void
    {
        die($message);
    }
    
}

