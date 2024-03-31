<?php
namespace Src\Service\Validator\impl;

use Src\Service\Generator\impl\ImageNameGenerator;
use Src\Service\Validator\impl\FileSizeValidator;
use Src\Service\Validator\impl\FileTypeValidator;
use Src\Service\Validator\IValidator;

class ImageValidator implements IValidator
{
    private $maxSizeInMB = 5;
    private array $allowedExtensions = ['jpg', 'svg', 'png', 'jpeg'];

    /**
     * @param $maxSize
     * @param $arrayExtensions
     */
    public function __construct($maxSize = null, $arrayExtensions = null)
    {
        $this->maxSizeInMB = $maxSize ?? $this->maxSizeInMB;
        $this->allowedExtensions = $arrayExtensions ?? $this->allowedExtensions;
    }

    /**
     * @param $value $_FILE['key_of_a_photo']
     * @return bool
     *
     * if successfully validated by Size and Type -> return true
     */
    public function validate($value): bool
    {
        $result = $this->validateImageAndSetRandomName($value);
        if(isset($result['error'])) {
            return false;
        }
        return true;
    }

    /**
     * @param $file $_FILE['photo']
     * @return string[]|true
     *
     * If successfully validated ->
     * the 'random_name' field is added to the $_FILE['photo']
     */
    public function validateImageAndSetRandomName(&$file)
    {
        $fileSizeValidator = new FileSizeValidator($this->maxSizeInMB);
        if(!$fileSizeValidator->validate($file)) {
            return [
                'error' => 'The image size should not exceed 5 МB'
            ];
        }
        $fileTypeValidator = new FileTypeValidator($this->allowedExtensions);
        if(!$fileTypeValidator->validate($file)) {
            return [
                'error' => 'The image should have the extension like .jpg, .jpeg, .png, .svg'
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