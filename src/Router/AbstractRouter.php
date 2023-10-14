<?php

namespace Src\Router;

abstract class AbstractRouter
{
    abstract public function route(): void;
    public function redirect($to): void
    {
        header("Location: $to");
    }

    public function getUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);

            return explode('/', $url);
        }
        return [];
    }
}