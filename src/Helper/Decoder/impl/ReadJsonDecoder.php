<?php

namespace Src\Helper\Decoder\impl;

use Src\Helper\Decoder\IDecoder;

class ReadJsonDecoder implements IDecoder
{
    /**
     * @param $data - key in $_POST array for encoded data
     * @return array
     */
    public static function decode($data)
    {
        $data = str_replace('\&quot;', '"', $data);

        return json_decode($data, true);
    }
}