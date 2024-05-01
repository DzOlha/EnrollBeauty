<?php

namespace Src\Controller\Web;

class OpenWebController extends WebController
{
    public function __construct(array $url)
    {
        parent::__construct($url);
    }

    /**
     * @return void
     *
     * /web/open/worker/....
     */
    public function worker()
    {
        if(isset($this->url[3])) {
            /**
             * url = /web/open/worker/profile
             */
            if ($this->url[3] === 'profile') {
                $this->view(
                    WORKER_PAGES['public_profile']['path'],
                    WORKER_PAGES['public_profile']['data']
                );
            }
        }
    }
}