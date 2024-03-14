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

    /**
     * @return void
     *
     * /web/open/user/....
     */
    public function user()
    {
        if (isset($this->url[3])) {
            /**
             * url = /web/open/user/profile
             */
            if ($this->url[3] === 'profile') {
                $this->view(
                    USER_PAGES['public_profile']['path'],
                    USER_PAGES['public_profile']['data']
                );
            }
        }
    }
}