<?php

namespace Src\Service\Generator\impl;

use Src\Service\Generator\IGenerator;

class ImageNameGenerator implements IGenerator
{
    /**
     * @param $pattern $_FILES['name-of-file-input-in-post']
     * @return string
     */
    public function generate($pattern = null): string
    {
        $value = $pattern;
        $extension = pathinfo($value['name'], PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        return md5(microtime()).'.'.$extension;
    }

    public static function generateRandomName($fileExtension): string
    {
        $extension = strtolower($fileExtension);

        return md5(microtime()).'.'.$extension;
    }
}