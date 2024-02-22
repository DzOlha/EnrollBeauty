<?php

namespace Src\Helper\Uploader;

interface IUploader
{
    public function upload($file, $fileRandName, $folder): bool;
}