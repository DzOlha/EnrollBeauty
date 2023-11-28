<?php

namespace Src\Helper\Builder\impl;

use Src\Helper\Builder\IBuilder;

class UrlBuilder implements IBuilder
{
    private string $url;

    /**
     * @param string $url
     */
    public function __construct(string $url = '')
    {
        $this->url = $url;
    }


    public function baseUrl(string $base) {
        $this->url .= $base;
        return $this;
    }

    /**
     * @param string $controllerType
     * @return $this
     *
     * web/api
     */
    public function controllerType(string $controllerType) {
        $this->url .= $controllerType.'/';
        return $this;
    }

    /**
     * @param string $prefix
     * @return $this
     *
     * user/admin/worker
     */
    public function controllerPrefix(string $prefix) {
        $this->url .= $prefix.'/';
        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function controllerMethod(string $method) {
        $this->url .= $method.'/';
        return $this;
    }

    public function get(string $getName, $getValue) {
        $this->url .= "?$getName=$getValue";
        return $this;
    }

    public function andGet(string $getName, $getValue) {
        $this->url .= "&$getValue=$getValue";
        return $this;
    }

    public function build() {
        return $this->url;
    }
}