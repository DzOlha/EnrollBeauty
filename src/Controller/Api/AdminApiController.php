<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Http\HttpCode;
use Src\Helper\Http\HttpRequest;
use Src\Helper\Provider\Folder\FolderProvider;
use Src\Helper\Session\SessionHelper;
use Src\Helper\Trimmer\impl\RequestTrimmer;
use Src\Helper\Uploader\impl\FileUploader;
use Src\Model\DataMapper\DataMapper;
use Src\Model\DataMapper\extends\AdminDataMapper;
use Src\Model\DataMapper\extends\UserDataMapper;
use Src\Model\DataSource\extends\AdminDataSource;
use Src\Model\DataSource\extends\UserDataSource;
use Src\Model\Entity\Gender;
use Src\Service\Auth\Admin\AdminAuthService;
use Src\Service\Auth\AuthService;
use Src\Service\Auth\User\UserAuthService;
use Src\Service\Validator\impl\EmailValidator;
use Src\Service\Validator\impl\NameValidator;
use Src\Service\Validator\impl\PhotoValidator;
use Src\Service\Validator\impl\StreetAddressValidator;

class AdminApiController extends WorkerApiController
{
    protected AuthService $authService;

    /**
     * @param ?AuthService $authService
     */
    public function __construct(array $url, AuthService $authService = null)
    {
        parent::__construct($url);
        $this->authService = $authService ?? new AdminAuthService(
            $this->dataMapper, new UserAuthService(
                new UserDataMapper(new UserDataSource(MySql::getInstance()))
            )
        );
    }

    public function getTypeDataMapper(): DataMapper
    {
        return new AdminDataMapper(new AdminDataSource(MySql::getInstance()));
    }

    public function checkPermission(array $url = []): void
    {
        if(!SessionHelper::getAdminSession()) {
            $this->_accessDenied();
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/profile/
     */
    public function profile() {
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/profile/get
             */
            if($this->url[3] === 'get') {
                $this->_getAdminInfo();
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/
     */
    public function worker() {
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/worker/get
             */
            if($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * /api/admin/worker/get/one
                     */
                    if($this->url[4] === 'one') {
                        $this->_getWorkerById();
                    }

                    /**
                     * /api/admin/worker/get/all-limited
                     */
                    if($this->url[4] === 'all-limited') {
                        $this->_getWorkers();
                    }

                    /**
                     * /api/admin/worker/get/all-by-department
                     */
                    if($this->url[4] === 'all-by-department') {
                        $this->_getWorkersByDepartment();
                    }

                    /**
                     * /api/admin/worker/get/all-by-service
                     */
                    if($this->url[4] === 'all-by-service') {
                        $this->_getWorkersByService();
                    }

                    /**
                     * /api/admin/worker/get/all
                     */
                    if($this->url[4] === 'all') {
                        $this->_getWorkersAll();
                    }
                }
            }

            /**
             * url = /api/admin/worker/register
             */
            if($this->url[3] === 'register') {
                $this->_registerWorker();
            }

            /**
             * url = /api/admin/worker/edit
             */
            if($this->url[3] === 'edit') {
                $this->_editWorker();
            }

            /**
             * url = /api/admin/worker/delete
             */
            if($this->url[3] === 'delete') {
                $this->_deleteWorker();
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/position/
     */
    public function position() {
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/position/add
             */
            if($this->url[3] === 'add') {
                $this->_addPosition();
            }

            /**
             * url = /api/admin/position/edit
             */
            if($this->url[3] === 'edit') {
                $this->_editPosition();
            }

            /**
             * url = /api/admin/position/delete
             */
            if($this->url[3] === 'delete') {
                $this->_deletePosition();
            }

            /**
             * url = /api/admin/position/get
             */
            if($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * /api/admin/position/get/one
                     */
                    if($this->url[4] === 'one') {
                        $this->_getPositionById();
                    }

                    /**
                     * /api/admin/position/get/all
                     */
                    if($this->url[4] === 'all') {
                        $this->_getAllPositions();
                    }

                    /**
                     * /api/admin/position/get/all-with-departments
                     */
                    if($this->url[4] === 'all-with-departments') {
                        $this->_getAllPositionsWithDepartments();
                    }
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/role/
     */
    public function role() {
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/role/get
             */
            if($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * /api/admin/role/get/all
                     */
                    if($this->url[4] === 'all') {
                        $this->_getAllRoles();
                    }
                }
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/service/
     */
    public function service() {
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/service/get
             */
            if($this->url[3] === 'get') {
                if(isset($this->url[4])) {
                    /**
                     * /api/admin/service/get/all-with-departments
                     */
                    if($this->url[4] === 'all-with-departments') {
                        $this->_getServicesAllWithDepartments();
                    }
                    /**
                     * url = /api/admin/service/get/one
                     */
                    if($this->url[4] === 'one') {
                        $this->_getServiceById();
                    }
                }
            }
            /**
             * url = /api/admin/service/add
             */
            if($this->url[3] === 'add') {
                $this->_addService();
            }

            /**
             * url = /api/worker/service/edit
             */
            if($this->url[3] === 'edit') {
                $this->_editService();
            }

            /**
             * url = /api/worker/service/delete
             */
            if($this->url[3] === 'delete') {
                $this->_deleteService();
            }
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/department/
     */
    public function department() {
        if (isset($this->url[3])) {
            /**
             * url = /api/admin/department/add
             */
            if ($this->url[3] === 'add') {
                $this->_addDepartment();
            }

            /**
             * url = /api/admin/department/edit
             */
            if ($this->url[3] === 'edit') {
                $this->_editDepartment();
            }

            /**
             * url = /api/admin/department/delete
             */
            if ($this->url[3] === 'delete') {
                $this->_deleteDepartment();
            }

            /**
             * url = /api/admin/department/get
             */
            if ($this->url[3] === 'get') {
                if (isset($this->url[4])) {
                    /**
                     * url = /api/admin/department/get/all
                     */
                    if ($this->url[4] === 'all') {
                        $this->_getDepartmentsAll();
                    }

                    /**
                     * url = /api/admin/department/get/one
                     */
                    if ($this->url[4] === 'one') {
                        $this->_getDepartmentById();
                    }

                    /**
                     * url = /api/admin/department/get/all-limited
                     */
                    if ($this->url[4] === 'all-limited') {
                        $this->_getDepartmentsAllForTable();
                    }

                    /**
                     * url = /api/admin/department/get/all-services
                     */
                    if ($this->url[4] === 'all-services') {
                        $this->_getServicesAllByDepartment();
                    }
                }
            }
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/affiliate/
     */
    public function affiliate()
    {
        if(!empty($this->url[3]))
        {
            /**
             * url = /api/admin/affiliate/add/
             */
            if($this->url[3] === 'add') {
                $this->_addAffiliate();
            }

            /**
             * url = /api/admin/affiliate/edit/
             */
            if($this->url[3] === 'edit') {
                $this->_editAffiliate();
            }

            /**
             * url = /api/admin/affiliate/delete/
             */
            if($this->url[3] === 'delete') {
                $this->_deleteAffiliate();
            }

            /**
             * url = /api/admin/affiliate/get/
             */
            if($this->url[3] === 'get')
            {
                if(!empty($this->url[4]))
                {
                    /**
                     * url = /api/admin/affiliate/get/one
                     */
                    if($this->url[4] === 'one') {
                        $this->_getAffiliateById();
                    }

                    /**
                     * url = /api/admin/affiliate/get/all-limited
                     */
                    if($this->url[4] === 'all-limited') {
                        $this->_getAllAffiliatesForAdminTable();
                    }
                }
            }
        }
    }


    private function _getAdminId($request)
    {
        return SessionHelper::getAdminSession() ?? $request->get('admin_id');
    }

    /**
     * @return void
     *
     * url = /api/admin/profile/get
     */
    protected function _getAdminInfo(): void
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());

            $adminId = $this->_getAdminId($request);

            if(!$adminId) {
                $this->_missingRequestFields();
            }

            /**
             *  [
             *      'name' =>
             *      'surname' =>
             *      'email' =>
             * ]
             */
            $result = $this->dataMapper->selectAdminInfoById($adminId);

            if ($result) {
                $this->returnJson([
                    'success' => true,
                    'data'    => $result
                ]);
            } else {
                $this->returnJson([
                    'error' => "The error occurred while getting admin's info"
                ], HttpCode::notFound());
            }
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/get/one
     */
    protected function _getWorkerById()
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }
            $id = $request->get('id');

            $result = $this->dataMapper->selectWorkerByIdForEdit($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the worker!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/get/all-limited
     */
    protected function _getWorkers(): void
    {
        if(HttpRequest::method() === 'GET')
        {
            $param = $this->_getLimitPageFieldOrderOffset();
            /**
             *  * [
             *      0 => [
             *          id =>
             *          name =>
             *          surname =>
             *          email =>
             *          position =>
             *          salary =>
             *          experience =>
             *      ]
             *      ....................
             * ]
             */
            $result = $this->dataMapper->selectAllWorkersForAdmin(
                $param['limit'],
                $param['offset'],
                $param['order_field'],
                $param['order_direction']
            );
            if($result === false) {
                $this->returnJson([
                    'error' => "The error occurred while getting data about all workers!"
                ], HttpCode::notFound());
            }
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     *  url = /api/admin/worker/get/all-by-department
     */
    protected function _getWorkersByDepartment()
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['department_id'])
            || empty($DATA['limit'])
            || empty($DATA['page'])) {
                $this->_missingRequestFields();
            }

            $items = [
                'department_id' => $request->get('department_id'),
                'limit' => $request->get('limit'),
                'page' => $request->get('page'),
            ];
            $offset = ($items['page'] - 1) * $items['limit'];

            $result = $this->dataMapper->selectWorkersByDepartmentId(
                $items['department_id'], $items['limit'], $offset
            );
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting workers for the department!'
                ], HttpCode::notFound());
            }

            $result += [
                'department_id' => $items['department_id']
            ];
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     *  url = /api/admin/worker/get/all-by-service
     */
    protected function _getWorkersByService()
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['service_id'])
                || empty($DATA['limit'])
                || empty($DATA['page']))
            {
                $this->_missingRequestFields();
            }

            $items = [
                'service_id' => $request->get('service_id'),
                'limit' => $request->get('limit'),
                'page' => $request->get('page'),
            ];
            $offset = ($items['page'] - 1) * $items['limit'];

            $result = $this->dataMapper->selectWorkersByServiceId(
                $items['service_id'], $items['limit'], $offset
            );
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting workers for the service!'
                ], HttpCode::notFound());
            }

            $result += [
                'service_id' => $items['service_id']
            ];
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    protected function _validatePositionName(array $items)
    {
        /**
         * Validate the name
         */
        $validator = new NameValidator(3, 50, true);
        if (!$validator->validate($items['position_name']) < 3) {
            $this->returnJson([
                'error' => 'Position name should be between 3-50 characters and contain only letters with whitespaces!'
            ], HttpCode::unprocessableEntity());
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/position/add
     *
     * position_name
     * department_id
     */
    protected function _addPosition()
    {
        if(HttpRequest::method() === 'POST')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['department_id']) || empty($DATA['position_name'])) {
                $this->_missingRequestFields();
            }
            $items = [
                'position_name' => $request->get('position_name'),
                'department_id' => $request->get('department_id')
            ];

            $this->_validatePositionName($items);

            /**
             * Check if there is such position already in the db
             */
            $exists = $this->dataMapper->selectPositionIdByNameAndDepartment(
                $items['position_name'], $items['department_id']
            );
            if($exists) {
                $this->returnJson([
                    'error' => 'The position with provided name already exists in the selected department!'
                ], HttpCode::forbidden());
            }

            $insertedId = $this->dataMapper->insertPosition(
                $items['position_name'], $items['department_id']
            );
            if($insertedId === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting the position!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => "You successfully added new position with name '{$items['position_name']}'",
                'data' => [
                    'id' => $insertedId
                ]
            ], HttpCode::created());
        }
        else {
            $this->_methodNotAllowed(['POST']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/position/edit
     *
     * id
     * position_name
     * department_id
     */
    protected function _editPosition()
    {
        if(HttpRequest::method() === 'PUT')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['department_id'])
                || empty($DATA['position_name'])
                || empty($DATA['id'])
            ) {
                $this->_missingRequestFields();
            }
            $items = [
                'id' => $request->get('id'),
                'position_name' => $request->get('position_name'),
                'department_id' => $request->get('department_id')
            ];

            $this->_validatePositionName($items);

            /**
             * Check if there is such position already in the db
             */
            $exists = $this->dataMapper->selectPositionIdByNameAndDepartment(
                $items['position_name'], $items['department_id']
            );
            if($exists) {
                $this->returnJson([
                    'error' => 'The position with provided name already exists in the selected department!'
                ], HttpCode::unprocessableEntity());
            }

            $updated = $this->dataMapper->updatePositionById(
                $items['id'], $items['position_name'], $items['department_id']
            );
            if($updated === false) {
                $this->returnJson([
                    'error' => 'An error occurred while updating the position!'
                ], HttpCode::notFound());
            }

            $updatedPositionRow = $this->dataMapper->selectPositionWithDepartmentById($items['id']);
            if($updatedPositionRow === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the updated position!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => "You successfully updated the position!",
                'data' => $updatedPositionRow
            ]);
        }
        else {
            $this->_methodNotAllowed(['PUT']);
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/position/edit
     *
     * id
     */
    protected function _deletePosition()
    {
        if(HttpRequest::method() === 'DELETE')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }

            $id = $request->get('id');

            /**
             * Check if there is no incomplete upcoming orders
             * for the worker with the position we would like to delete
             */
            $futureOrders = $this->dataMapper->selectFutureOrdersByPositionId($id);
            if($futureOrders === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the upcoming orders for workers with the position you would like to delete!'
                ], HttpCode::notFound());
            }
            if($futureOrders) {
                $this->returnJson([
                    'error' => 'You can not delete the position, of workers with incomplete upcoming orders!'
                ], HttpCode::notFound());
            }

            $deleted = $this->dataMapper->deletePositionById($id);
            if($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deleting the position!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => 'You successfully deleted the position!',
                'data' => [
                    'id' => $id
                ]
            ]);
        }
        else {
            $this->_methodNotAllowed(['DELETE']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/position/get/one
     */
    protected function _getPositionById(): void
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }
            $id = $request->get('id');

            $result = $this->dataMapper->selectPositionById($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the position!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/position/get/all
     */
    protected function _getAllPositions(): void
    {
        if(HttpRequest::method() === 'GET')
        {
            /**
             * Get positions
             */
            $positions = $this->dataMapper->selectAllPositions();
            if($positions === false) {
                $this->returnJson([
                    'error' => "An error occurred while getting all positions"
                ], HttpCode::notFound());
            }
            if($positions === null) {
                $this->returnJson([
                    'error' => "There are no positions found!"
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => true,
                'data' => $positions
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    protected function _getAllPositionsWithDepartments()
    {
        if(HttpRequest::method() === 'GET')
        {
            $param = $this->_getLimitPageFieldOrderOffset();
            /**
             *  * [
             *      0 => [
             *          id =>
             *          name =>
             *          department_id =>
             *          department_name =>
             *      ]
             *      ....................
             * ]
             */
            $result = $this->dataMapper->selectPositionsAllWithDepartments(
                $param['limit'],
                $param['offset'],
                $param['order_field'],
                $param['order_direction']
            );
            if($result === false) {
                $this->returnJson([
                    'error' =>  "The error occurred while getting data about all positions!"
                ], HttpCode::notFound());
            }
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/role/get/all
     */
    protected function _getAllRoles(): void
    {
        if(HttpRequest::method() === 'GET')
        {
            /**
             * Get roles
             */
            $roles = $this->dataMapper->selectAllRoles();
            if($roles === false) {
                $this->returnJson([
                    'error' => "An error occurred while getting all roles"
                ], HttpCode::notFound());
            }
            if($roles === null) {
                $this->returnJson([
                    'error' => "There are no roles found!"
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => true,
                'data' => $roles
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/register
     */
    protected function _registerWorker(): void
    {
        $this->returnJson(
            $this->authService->registerWorker()
        );
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/edit
     */
    protected function _editWorker()
    {
        if(HttpRequest::method() === 'PUT')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(!isset($DATA['id']) || !isset($DATA['name'])
                || !isset($DATA['surname']) || !isset($DATA['email'])
                || !isset($DATA['position_id']) || !isset($DATA['role_id'])
                || !isset($DATA['gender']) || !isset($DATA['age'])
                || !isset($DATA['experience']) || !isset($DATA['salary'])
            ) {
                $this->_missingRequestFields();
            }

            $items = [
                'id' => $request->get('id'),
                'name' => $request->get('name'),
                'surname' => $request->get('surname'),
                'email' => $request->get('email'),
                'position_id' => $request->get('position_id'),
                'role_id' => $request->get('role_id'),
                'gender' => $request->get('gender'),
                'age' => $request->get('age'),
                'experience' => $request->get('experience'),
                'salary' => $request->get('salary'),
            ];

            $valid = $this->authService->validateWorkerAddForm($items);
            if($valid !== true) {
                $this->returnJson($valid, HttpCode::unprocessableEntity());
            }

            /**
             * Check if there is already registered worker with such email (except the current id)
             */
            $existId = $this->dataMapper->selectWorkerByEmailAndNotId($items['id'], $items['email']);
            if($existId) {
                $this->returnJson([
                    'error' => 'The worker with such email already exists!'
                ], HttpCode::forbidden());
            }

            /**
             * Update the worker's info
             */
            $updated = $this->dataMapper->updateWorkerById(
                $items['id'], $items['name'], $items['surname'],
                $items['email'], $items['position_id'], $items['role_id'],
                $items['gender'], $items['age'], $items['experience'],
                $items['salary']
            );
            if($updated === false) {
                $this->returnJson([
                    'error' => 'An error occurred while updating the worker!'
                ], HttpCode::notFound());
            }

            $updatedWorker = $this->dataMapper->selectWorkerRowById($items['id']);
            if($updatedWorker === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the updated worker!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => 'You successfully updated the worker!',
                'data' => $updatedWorker
            ]);

        }
        else {
            $this->_methodNotAllowed(['PUT']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/delete
     */
    public function _deleteWorker()
    {
        if(HttpRequest::method() === 'DELETE')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }

            $id = $request->get('id');

            /**
             * Check if there is no incomplete upcoming orders
             * for the worker we would like to delete
             */
            $futureOrders = $this->dataMapper->selectFutureOrdersByWorkerId($id);
            if($futureOrders === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting incomplete upcoming orders for the worker!'
                ], HttpCode::notFound());
            }
            if($futureOrders) {
                $this->returnJson([
                    'error' => 'Tou can not delete the worker which has incomplete upcoming orders!'
                ], HttpCode::notFound());
            }

            $deleted = $this->dataMapper->deleteWorkerById($id);
            if($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deletion of the worker!'
                ], HttpCode::notFound());
            }

            /**
             * Delete the folder with worker's images
             */
            $folderPath = FolderProvider::workerUploadsImg($id);
            FileUploader::deleteFolder($folderPath);

            $this->returnJson([
                'success' => 'You successfully deleted the worker!',
                'data' => [
                    'id' => $id
                ]
            ]);
        }
        else {
            $this->_methodNotAllowed(['DELETE']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/service/get/all-with-departments
     */
    protected function _getServicesAllWithDepartments()
    {
        if(HttpRequest::method() === 'GET')
        {
            $param = $this->_getLimitPageFieldOrderOffset();

            $services = $this->dataMapper->selectAllServicesWithDepartmentsInfo(
                $param['limit'],
                $param['offset'],
                $param['order_field'],
                $param['order_direction']
            );
            if ($services === false) {
                $this->returnJson([
                    'error' => 'The error occurred while getting all services'
                ], HttpCode::notFound());
            }
            $this->returnJson([
                'success' => true,
                'data'    => $services
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/service/add
     */
    protected function _addService() {
        parent::_addService();
    }

    protected function _validateDepartmentForm(array $items)
    {
        /**
         * Validate name format
         */
        $validator = new NameValidator(3, 50, true);
        if(!$validator->validate($items['name'])) {
            $this->returnJson([
                'error' => 'Department name should be between 3-50 characters long and contain only letters with whitespaces!'
            ], HttpCode::unprocessableEntity());
        }

        /**
         * Validate description format
         */
        if(strlen($items['description']) < 10) {
            $this->returnJson([
                'error' => 'Department description should be longer than 10 characters!'
            ], HttpCode::unprocessableEntity());
        }
        if(strlen($items['description']) > 255) {
            $this->returnJson([
                'error' => 'Department description should not exceed 255 characters!'
            ], HttpCode::unprocessableEntity());
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/department/add
     */
    public function _addDepartment()
    {
        if(HttpRequest::method() === 'POST')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['name']) || empty($DATA['description'])
            || empty($_FILES['photo'])) {
                $this->_missingRequestFields();
            }

            $items = [
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ];

            $this->_validateDepartmentForm($items);

            /**
             * Validate the photo and set 'random_name'
             */
            $validPhoto = PhotoValidator::validateImageAndSetRandomName($_FILES['photo']);
            if($validPhoto !== true) {
                $this->returnJson($validPhoto, HttpCode::unprocessableEntity());
            }

            /**
             * Check if there is no department with the same name
             */
            $exists = $this->dataMapper->selectDepartmentByName($items['name']);
            if($exists !== false) {
                $this->returnJson([
                    'error' => 'The department with such name already exists!'
                ], HttpCode::forbidden());
            }


            /**
             * Insert new department
             */
            $this->dataMapper->beginTransaction();

            $insertedId = $this->dataMapper->insertDepartment(
                $items['name'], $items['description'], $_FILES['photo']['random_name']
            );
            if($insertedId === false) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'An error occurred while inserting the department!'
                ], HttpCode::notFound());
            }

            /**
             * Upload photo into department_{id} folder
             */
            $uploader = new FileUploader();
            $folderPath = FolderProvider::adminUploadsDepartmentImg($insertedId);

            $uploaded = $uploader->upload(
                $_FILES['photo'], $_FILES['photo']['random_name'], $folderPath
            );
            if(!$uploaded) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'An error occurred while uploading department photo into appropriate folder!'
                ], HttpCode::notFound());
            }


            $this->dataMapper->commitTransaction();
            $this->returnJson([
                'success' => "You successfully added the department with name <b>'{$items['name']}'</b>",
                'data' => [
                    'id' => $insertedId
                ]
            ], HttpCode::created());
        }
        else {
            $this->_methodNotAllowed(['POST']);
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/department/edit
     */
    public function _editDepartment()
    {
        if(HttpRequest::method() === 'POST')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id']) || empty($DATA['name'])
                || empty($DATA['description']))
            {
                $this->_missingRequestFields();
            }

            $items = [
                'id' => $request->get('id'),
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ];

            $this->_validateDepartmentForm($items);

            /**
             * Check if there is no department with the same name
             */
            $existsId = $this->dataMapper->selectDepartmentByName($items['name']);
            if($existsId !== false && $existsId != $items['id']) {
                $this->returnJson([
                    'error' => 'The department with such name already exists!'
                ], HttpCode::forbidden());
            }


            /**
             * Validate the photo and set 'random_name'
             */
            $photoChanged = true;
            if(empty($_FILES['photo'])) {
                $photoChanged = false;
            } else {
                $validPhoto = PhotoValidator::validateImageAndSetRandomName($_FILES['photo']);
                if($validPhoto !== true) {
                    $this->returnJson($validPhoto, HttpCode::unprocessableEntity());
                }
            }

            $this->dataMapper->beginTransaction();

            /**
             * Update text data
             */
            $updated = $this->dataMapper->updateDepartment(
                $items['id'], $items['name'], $items['description']
            );

            /**
             * Update photo
             */
            $updatedPhoto = true;
            $newImage = $photoChanged === true
                ? $_FILES['photo']['random_name']
                : null;

            if($newImage) {
                /**
                 * Check the old main photo
                 */
                $oldPhotoFilename = $this->dataMapper->selectDepartmentPhotoById($items['id']);
                if($oldPhotoFilename === false) {
                    $this->dataMapper->rollBackTransaction();
                    $this->returnJson([
                        'error' => "An error occurred while getting the current photo of the department"
                    ], HttpCode::notFound());
                }

                /**
                 * Check if new photo differ from the old one
                 */
                if(!$oldPhotoFilename || $newImage !== $oldPhotoFilename) {
                    /**
                     * If yes -> update photo in db to the new one
                     */
                    $updatedPhoto = $this->dataMapper->updateDepartmentPhotoById(
                        $items['id'], $newImage
                    );
                    if($updatedPhoto === false) {
                        $this->dataMapper->rollBackTransaction();
                        $this->returnJson([
                            'error' => 'An error occurred while updating department photo!'
                        ], HttpCode::notFound());
                    }

                    /**
                     * Upload the new image into the folder
                     */
                    $uploader = new FileUploader();
                    $folderPath = FolderProvider::adminUploadsDepartmentImg($items['id']);

                    $uploaded = $uploader->upload(
                        $_FILES['photo'], $_FILES['photo']['random_name'], $folderPath
                    );
                    if(!$uploaded) {
                        $this->dataMapper->rollBackTransaction();
                        $this->returnJson([
                            'error' => 'An error occurred while uploading department photo into appropriate folder!'
                        ], HttpCode::notFound());
                    }

                    /**
                     * Remove the old photo from the folder
                     */
                    $deleted = FileUploader::deleteFileFromFolder($folderPath, $oldPhotoFilename);
                    if($deleted === false) {
                        $this->dataMapper->rollBackTransaction();
                        $this->returnJson([
                            'error' => "An error occurred while deleting the old department photo "
                        ], HttpCode::notFound());
                    }
                }
            }
            if($updated === false
                && (
                    ($updatedPhoto === false && $newImage)
                    || ($updatedPhoto && !$newImage)
                )
            ) {
                $this->dataMapper->rollBackTransaction();
                $this->returnJson([
                    'error' => 'An error occurred while updating department info!'
                ], HttpCode::notFound());
            }

            $this->dataMapper->commitTransaction();
            $this->returnJson([
                'success' => "You successfully updated the department!",
                'data' => [
                    'id' => $items['id'],
                    'name' => $items['name']
                ]
            ]);
        }
        else {
            $this->_methodNotAllowed(['POST']);
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/department/delete
     */
    public function _deleteDepartment()
    {
        if(HttpRequest::method() === 'DELETE')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }

            $id = $request->get('id');

            /**
             * Check if there is no ordered schedules
             * in the future for the services from this department
             */
            $futureOrders = $this->dataMapper->selectFutureOrdersByDepartmentId($id);
            if($futureOrders === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting upcoming orders for the department!'
                ], HttpCode::notFound());
            }
            if($futureOrders) {
                $this->returnJson([
                    'error' => 'The department can not be deleted because there are left upcoming incomplete orders for its services!'
                ], HttpCode::notFound());
            }

            $deleted = $this->dataMapper->deleteDepartmentById($id);
            if($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deleting the department!'
                ], HttpCode::notFound());
            }

            /**
             * Delete the department folder
             */
            $folderPath = FolderProvider::adminUploadsDepartmentImg($id);
            FileUploader::deleteFolder($folderPath);

            $this->returnJson([
                'success' => 'You successfully deleted the department!',
                'data' => [
                    'id' => $id
                ]
            ]);
        }
        else {
            $this->_methodNotAllowed(['DELETE']);
        }
    }

    /**
     * url = /api/admin/department/get/one
     */
    protected function _getDepartmentById()
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }
            $id = $request->get('id');

            $result = $this->dataMapper->selectDepartmentFullById($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the department info!'
                ]);
            }
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/department/get/all-limited
     */
    public function _getDepartmentsAllForTable()
    {
        if(HttpRequest::method() === 'GET')
        {
            $param = $this->_getLimitPageFieldOrderOffset();
            /**
             *  * [
             *      0 => [
             *          id =>
             *          name =>
             *      ]
             *      ....................
             * ]
             */
            $result = $this->dataMapper->selectDepartmentsAllForAdmin(
                $param['limit'],
                $param['offset'],
                $param['order_field'],
                $param['order_direction']
            );
            if($result === false) {
                $this->returnJson([
                    'error' => "The error occurred while getting data about all departments!"
                ], HttpCode::notFound());
            }
            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/department/get/all-services
     */
    public function _getServicesAllByDepartment()
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['department_id'])) {
                $this->_missingRequestFields();
            }

            $id = $request->get('department_id');

            /**
             * services = {
             *     0: {
             *         id:
             *         name:
             *     }
             * ...............
             * }
             */
            $result = $this->dataMapper->selectServicesAllByDepartmentId($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting services for the department'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/affiliate/add
     */
    protected function _addAffiliate()
    {
        if(HttpRequest::method() === 'POST')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(empty($DATA['name']) || empty($DATA['country'])
            || empty($DATA['city']) || empty($DATA['address']))
            {
                $this->_missingRequestFields();
            }

            $items = [
                'name' => $request->get('name'),
                'manager_id' => $DATA['manager_id']
                                ? $trimmer->in($DATA['manager_id'])
                                : null,
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'address' => $request->get('address'),
            ];

            $this->_validateAffiliateForm($items);

            /**
             * Check if there is no affiliate with the same address
             */
            $existsByAddress = $this->dataMapper->selectAffiliateByCountryCityAndAddress(
                $items['country'], $items['city'], $items['address']
            );
            if($existsByAddress) {
                $this->returnJson([
                    'error' => 'The affiliate with the same address already exists!'
                ], HttpCode::forbidden());
            }

            /**
             * Insert affiliate
             */
            $insertedId = $this->dataMapper->insertAffiliate(
                $items['name'], $items['country'], $items['city'],
                $items['address'], $items['manager_id']
            );
            if($insertedId === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting the affiliate!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => "You successfully added the affiliate with name '{$items['name']}'",
                'data' => [
                    'id' => $insertedId
                ]
            ], HttpCode::created());
        }
        else {
            $this->_methodNotAllowed(['POST']);
        }
    }

    protected function _validateAffiliateForm($items)
    {
        /**
         * Validate name
         */
        $validator = new NameValidator(3, 100, true);
        if(!$validator->validate($items['name'])) {
            $this->returnJson([
                'error' => 'Affiliate name should be equal length between 3-100 characters and contain only letters and whitespaces!'
            ], HttpCode::unprocessableEntity());
        }

        /**
         * Validate country
         */
        $validator = new NameValidator(3, 50);
        if(!$validator->validate($items['country'])) {
            $this->returnJson([
                'error' => 'Invalid country name format! It must not contain any digits or special chars and has length between 3-50 characters'
            ], HttpCode::unprocessableEntity());
        }

        /**
         * Validate city
         */
        if(!$validator->validate($items['city'])) {
            $this->returnJson([
                'error' => 'Invalid city name format! It must not contain any digits or special chars and has length between 3-50 characters'
            ], HttpCode::unprocessableEntity());
        }

        /**
         * Validate street address
         */
        $streetValidator = new StreetAddressValidator();
        if(!$streetValidator->validate($items['address'])) {
            $this->returnJson([
                'error' => "Invalid format for the street address! Please, follow the example like 'str. Street, 3' or 'вул. Назва Вулиці, 1'"
            ], HttpCode::unprocessableEntity());
        }
        if(strlen($items['address']) > 255) {
            $this->returnJson([
                'error' => 'Street address should not exceed 255 characters!'
            ], HttpCode::unprocessableEntity());
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/affiliate/edit
     */
    protected function _editAffiliate()
    {
        if(HttpRequest::method() === 'PUT')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(empty($DATA['name']) || empty($DATA['country'])
                || empty($DATA['city']) || empty($DATA['address'])
                || empty($DATA['id']))
            {
                $this->_missingRequestFields();
            }

            $items = [
                'id' => $request->get('id'),
                'name' => $request->get('name'),
                'manager_id' => $DATA['manager_id']
                    ? $trimmer->in($DATA['manager_id'])
                    : null,
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'address' => $request->get('address'),
            ];

           $this->_validateAffiliateForm($items);

            /**
             * Check if there is no affiliate with the same address
             */
            $existsByAddress = $this->dataMapper->selectAffiliateByAddressAndNotId(
                $items['id'], $items['country'], $items['city'], $items['address']
            );
            if($existsByAddress) {
                $this->returnJson([
                    'error' => 'The affiliate with the same address already exists!'
                ], HttpCode::forbidden());
            }

            /**
             * Insert affiliate
             */
            $insertedId = $this->dataMapper->updateAffiliateById(
                $items['id'], $items['name'], $items['country'],
                $items['city'], $items['address'], $items['manager_id']
            );
            if($insertedId === false) {
                $this->returnJson([
                    'error' => 'An error occurred while updating the affiliate!'
                ], HttpCode::notFound());
            }

            $updatedAffiliate = $this->dataMapper->selectAffiliateByIdForTable($items['id']);
            if($updatedAffiliate === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting updated affiliate!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => "You successfully updated the affiliate!",
                'data' => $updatedAffiliate
            ]);
        }
        else {
            $this->_methodNotAllowed(['PUT']);
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/affiliate/delete
     */
    protected function _deleteAffiliate()
    {
        if(HttpRequest::method() === 'DELETE')
        {
            $trimmer = new RequestTrimmer();
            $request = new HttpRequest($trimmer);
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }

            $id = $request->get('id');

            /**
             * Check if there is no upcoming incomplete orders
             * for the affiliate we would like to delete
             */
            $futureOrders = $this->dataMapper->selectFutureOrdersByAffiliateId($id);
            if($futureOrders) {
                $this->returnJson([
                    'error' => 'You can not delete the affiliate which has incomplete upcoming orders!'
                ], HttpCode::notFound());
            }

            $deleted = $this->dataMapper->deleteAffiliateById($id);
            if($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deleting the affiliate!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => 'You successfully deleted the affiliate!',
                'data' => [
                    'id' => $id
                ]
            ]);
        }
        else {
            $this->_methodNotAllowed(['DELETE']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/affiliate/get/one
     */
    protected function _getAffiliateById()
    {
        if(HttpRequest::method() === 'GET')
        {
            $request = new HttpRequest(new RequestTrimmer());
            $DATA = $request->getData();

            if(empty($DATA['id'])) {
                $this->_missingRequestFields();
            }

            $id = $request->get('id');

            /**
             * 'id':
             * 'name':
             * 'country':
             * 'city':
             * 'address':
             * 'manager_id':
             * 'created_date':
             */
            $result = $this->dataMapper->selectAffiliateById($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the affiliate!'
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/affiliate/get/all-limited
     */
    protected function _getAllAffiliatesForAdminTable()
    {
        if(HttpRequest::method() === 'GET')
        {
            $param = $this->_getLimitPageFieldOrderOffset();

            /**
             *  * [
             *      0 => [
             *          id =>
             *          name =>
             *          country =>
             *          city =>
             *          address =>
             *          manager_id =>
             *          manager_name =>
             *          manager_surname =>
             *          created_date =>
             *      ]
             *      ....................
             * ]
             */
            $result = $this->dataMapper->selectAllAffiliatesForAdminTable(
                $param['limit'],
                $param['offset'],
                $param['order_field'],
                $param['order_direction']
            );
            if($result === false) {
                $this->returnJson([
                    'error' => "The error occurred while getting data about all affiliates!"
                ], HttpCode::notFound());
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
        else {
            $this->_methodNotAllowed(['GET']);
        }
    }
}