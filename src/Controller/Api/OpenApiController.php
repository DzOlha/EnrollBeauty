<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\OpenDataMapper;
use Src\Model\DataSource\extends\OpenDataSource;

class OpenApiController extends ApiController
{
    public function __construct(array $url)
    {
        parent::__construct($url);
    }

    public function getTypeDataMapper(): DataMapper
    {
        return new OpenDataMapper(new OpenDataSource(MySql::getInstance()));
    }

    /**
     * url = /api/open/worker/...
     */
    public function worker()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/open/worker/profile
             */
            if ($this->url[3] === 'profile') {
                if(isset($this->url[4])) {
                    /**
                     * url = /api/open/worker/profile/get/
                     */
                    if($this->url[4] === 'get') {
                        if(isset($this->url[5])) {
                            /**
                             * url = /api/open/worker/profile/get/one
                             */
                            if($this->url[5] === 'one') {
                                $this->_getWorkerProfileById();
                            }
                        }
                    }
                }
            }
        }
    }
    protected function _getWorkerProfileById()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['id'])) {
                $this->_missingRequestFields();
            }

            $id = (int)htmlspecialchars(trim($_GET['id']));

            $result = $this->dataMapper->selectWorkerPublicProfileById($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting worker public profile'
                ], 404);
            }

            $result['description'] = $result['description'] !== null
                                    ? html_entity_decode($result['description'])
                                    : '';
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }
}