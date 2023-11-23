<?php

namespace Src\Controller;

use Src\Model\DataMapper\DataMapper;

abstract class AbstractController
{
    protected array $url;
    protected DataMapper $dataMapper;

    /**
     * @param array $url
     */
    public function __construct(array $url)
    {
        $this->dataMapper = $this->dataMapper();
        $this->url = $url;
    }


    abstract public function getTypeDataMapper(): DataMapper;

    public function dataMapper(): DataMapper
    {
        return $this->getTypeDataMapper();
    }
}