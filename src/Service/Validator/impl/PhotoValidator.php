<?php

namespace Src\Service\Validator\impl;

use Src\Service\Generator\impl\ImageNameGenerator;
use Src\Service\Validator\IValidator;

class PhotoValidator implements IValidator
{
    public function validate($value): bool
    {
        // TODO: Implement validate() method.
    }

    /**
     * @param $file $_FILES['name-of-photo-in-post']
     * @return bool|string[]
     */
    public static function validateImageAndSetRandomName(&$file)
    {
        if(empty($file)) {
            return false;
        }
        $fileSizeValidator = new FileSizeValidator();
        if(!$fileSizeValidator->validate($file)) {
            return [
                'error' => 'The maximum image size is 5 MB!'
            ];
        }
        $fileTypeValidator = new FileTypeValidator();
        if(!$fileTypeValidator->validate($file)) {
            return [
                'error' => "Your main photo should be one of the following format: .jpg, .jpeg, .png, .svg"
            ];
        }
        /**
         * Generate random name for the photo to use it to upload to the folder
         */
        $generator = new ImageNameGenerator();
        $randomPhotoName = $generator->generate($file);

        $file['random_name'] = $randomPhotoName;

        return true;
    }
}