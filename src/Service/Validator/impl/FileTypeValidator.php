<?php

namespace Src\Service\Validator\impl;

use Src\Service\Validator\IValidator;

class FileTypeValidator implements IValidator
{
    private array $allowedExtensions = ['jpg', 'svg', 'png', 'jpeg'];

    /**
     * @param array $allowedExtensions
     *
     */
    public function __construct(array $allowedExtensions = [])
    {
        $this->allowedExtensions = count($allowedExtensions) > 0 ? $allowedExtensions : $this->allowedExtensions;
    }

    /**
     * @param $value $_FILES['name-of-file-input-in-post']
     * @return bool
     */
    public function validate($value): bool
    {
        $fileName = $value['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (in_array($fileExtension, $this->allowedExtensions)) {
           return true;
        } else {
            return false;
        }
    }

}