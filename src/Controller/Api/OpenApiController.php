<?php

namespace Src\Controller\Api;

use PhpParser\JsonDecoder;
use Src\DB\Database\MySql;
use Src\Helper\Decoder\impl\ReadJsonDecoder;
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

            /**
             * url = /api/open/worker/get/
             */
            if($this->url[3] === 'get') {
                /**
                 *  url = /api/open/worker/get/all
                 */
                if($this->url[4] === 'all') {
                    $this->_getWorkersAll();
                }

                /**
                 * url = /api/open/worker/get/services/
                 */
                if($this->url[4] === 'services') {
                    if(isset($this->url[5])) {
                        /**
                         *  url = /api/user/worker/get/services/all
                         */
                        if($this->url[5] === 'all') {
                            $this->_getServicesForWorker();
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

    /**
     * url = /api/open/service/...
     */
    public function service()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/open/service/pricing
             */
            if ($this->url[3] === 'pricing') {
                if(isset($this->url[4])) {
                    /**
                     * url = /api/open/service/pricing/get/
                     */
                    if($this->url[4] === 'get') {
                        if(isset($this->url[5])) {
                            /**
                             * url = /api/open/service/pricing/get/all
                             */
                            if($this->url[5] === 'all') {
                                $this->_getServicePricingAll();
                            }
                        }
                    }
                }
            }

            /**
             * url = /api/open/service/get/
             */
            if($this->url[3] === 'get') {
                /**
                 *  url = /api/open/service/get/all
                 */
                if($this->url[4] === 'all') {
                    $this->_getServicesAll();
                }

                /**
                 * url = /api/open/service/get/workers/
                 */
                if($this->url[4] === 'workers') {
                    if(isset($this->url[5])) {
                        /**
                         *  url = /api/user/service/get/workers/all
                         */
                        if($this->url[5] === 'all') {
                            $this->_getWorkersForService();
                        }
                    }
                }
            }
        }
    }

    /**
     * url = /api/open/affiliate/...
     */
    public function affiliate()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/open/affiliate/get/
             */
            if($this->url[3] === 'get') {
                /**
                 *  url = /api/open/affiliate/get/all
                 */
                if($this->url[4] === 'all') {
                    $this->_getAffiliatesAll();
                }
            }
        }
    }

    /**
     * url = /api/open/schedule/...
     */
    public function schedule()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/open/schedule/search/
             */
            if($this->url[3] === 'search') {
               $this->_searchSchedule();
            }
        }
    }

    /**
     * @return void
     * url = /api/open/department/
     */
    public function department()
    {
        if (isset($this->url[3])) {
            /**
             * url = /api/open/department/get/
             */
            if($this->url[3] === 'get') {
                /**
                 *  url = /api/open/department/get/all-limited
                 */
                if($this->url[4] === 'all-limited') {
                    $this->_getDepartmentsCards();
                }
            }
        }
    }

    /**
     * @return void
     *
     *  url = /api/open/service/pricing/get/all
     */
    protected function _getServicePricingAll()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $result = $this->dataMapper->selectServicePricingAll();
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting service pricing'
                ], 400);
            }

            foreach ($result as &$department) {
                $department['services'] = ReadJsonDecoder::decode($department['services']);
            }
            $this->returnJson([
                'success' => true,
                'data' => [
                    'departments' => $result
                ]
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * url = /api/open/department/get/all-limited
     */
    protected function _getDepartmentsCards()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['limit'])) {
                $this->_missingRequestFields();
            }
            $limit = htmlspecialchars(trim($_GET['limit']));
            $result = $this->dataMapper->selectDepartmentsFull($limit);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting departments!'
                ]);
            }
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $this->_methodNotAllowed(['GET']);
        }
    }
}