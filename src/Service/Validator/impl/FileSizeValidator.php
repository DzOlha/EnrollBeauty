<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class FileSizeValidator implements IValidator
{
    private $maxSizeInMB = 5;

    /**
     * @param $maxSizeInMB
     */
    public function __construct($maxSizeInMB = null)
    {
        $this->maxSizeInMB = $maxSizeInMB ?? $this->maxSizeInMB;
    }

    /**
     * @param $value $_FILES['name-of-file-input-in-post']
     * @return bool
     */
    public function validate($value): bool {
        $fileSizeBytes = $value['size'];

        // Convert bytes to megabytes
        $fileSizeMB = $fileSizeBytes / (1024 * 1024);

        if($fileSizeMB <= $this->maxSizeInMB) {
            return true;
        }
        return false;
    }
}