<?php

namespace Src\Controller\Api;

use Src\DB\Database\MySql;
use Src\Helper\Session\SessionHelper;
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

    public function checkPermission(): void {
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


    private function _getAdminId()
    {
        $userId = 0;
        if (isset($_GET['admin_id']) && $_GET['admin_id'] !== '') {
            $userId = htmlspecialchars(trim($_GET['admin_id']));
        } else {
            $sessionUserId = SessionHelper::getAdminSession();
            if ($sessionUserId) {
                $userId = $sessionUserId;
            }
        }
        return $userId;
    }

    /**
     * @return void
     *
     * url = /api/admin/profile/get
     */
    protected function _getAdminInfo(): void
    {
        $adminId = $this->_getAdminId();
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
                'data' => $result
            ]);
        } else {
            $this->returnJson([
                'error' => "The error occurred while getting admin's info"
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/get/one
     */
    protected function _getWorkerById()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['id'])) {
                $this->returnJson([
                    'error' => 'Missing get fields!'
                ]);
            }
            $id = htmlspecialchars(trim($_GET['id']));

            $result = $this->dataMapper->selectWorkerByIdForEdit($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the worker!'
                ]);
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/get/all-limited
     */
    protected function _getWorkers(): void
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
            $this->returnJsonError(
                "The error occurred while getting data about all workers!"
            );
        }
        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
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
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['department_id']) || empty($_POST['position_name'])) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }
            $items = [
                'position_name' => htmlspecialchars(trim($_POST['position_name'])),
                'department_id' => htmlspecialchars(trim($_POST['department_id']))
            ];

            /**
             * Validate the name
             */
            if (strlen($items['position_name']) < 3) {
                $this->returnJson([
                    'error' => 'Position name should be equal to or longer than 3 characters!'
                ]);
            }

            /**
             * Check if there is such position already in the db
             */
            $exists = $this->dataMapper->selectPositionIdByNameAndDepartment(
                $items['position_name'], $items['department_id']
            );
            if($exists) {
                $this->returnJson([
                    'error' => 'The position with provided name already exists in the selected department!'
                ]);
            }

            $insertedId = $this->dataMapper->insertPosition(
                $items['position_name'], $items['department_id']
            );
            if($insertedId === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting the position!'
                ]);
            }

            $this->returnJson([
                'success' => "You successfully added new position with name '{$items['position_name']}'",
                'data' => [
                    'id' => $insertedId
                ]
            ]);
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
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['department_id'])
                || empty($_POST['position_name'])
                || empty($_POST['id'])
            ) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }
            $items = [
                'id' => htmlspecialchars(trim($_POST['id'])),
                'position_name' => htmlspecialchars(trim($_POST['position_name'])),
                'department_id' => htmlspecialchars(trim($_POST['department_id']))
            ];

            /**
             * Validate the name
             */
            if (strlen($items['position_name']) < 3) {
                $this->returnJson([
                    'error' => 'Position name should be equal to or longer than 3 characters!'
                ]);
            }

            /**
             * Check if there is such position already in the db
             */
            $exists = $this->dataMapper->selectPositionIdByNameAndDepartment(
                $items['position_name'], $items['department_id']
            );
            if($exists) {
                $this->returnJson([
                    'error' => 'The position with provided name already exists in the selected department!'
                ]);
            }

            $updated = $this->dataMapper->updatePositionById(
                $items['id'], $items['position_name'], $items['department_id']
            );
            if($updated === false) {
                $this->returnJson([
                    'error' => 'An error occurred while updating the position!'
                ]);
            }

            $updatedPositionRow = $this->dataMapper->selectPositionWithDepartmentById($items['id']);
            if($updatedPositionRow === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the updated position!'
                ]);
            }

            $this->returnJson([
                'success' => "You successfully updated the position!",
                'data' => $updatedPositionRow
            ]);
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
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['id'])) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }

            $id = htmlspecialchars(trim($_POST['id']));

            /**
             * Check if there is no incomplete upcoming orders
             * for the worker with the position we would like to delete
             */
            $futureOrders = $this->dataMapper->selectFutureOrdersByPositionId($id);
            if($futureOrders === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the upcoming orders for workers with the position you would like to delete!'
                ]);
            }
            if($futureOrders) {
                $this->returnJson([
                    'error' => 'You can not delete the position, of workers with incomplete upcoming orders!'
                ]);
            }

            $deleted = $this->dataMapper->deletePositionById($id);
            if($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deleting the position!'
                ]);
            }

            $this->returnJson([
                'success' => 'You successfully deleted the position!',
                'data' => [
                    'id' => $id
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/position/get/one
     */
    protected function _getPositionById(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['id'])) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }
            $id = htmlspecialchars(trim($_GET['id']));
            $result = $this->dataMapper->selectPositionById($id);
            if($result === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the position!'
                ]);
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/position/get/all
     */
    protected function _getAllPositions(): void
    {
        /**
         * Get positions
         */
        $positions = $this->dataMapper->selectAllPositions();
        if($positions === false) {
            $this->returnJson(['error' => "An error occurred while getting all positions"]);
        }
        if($positions === null) {
            $this->returnJson(['error' => "There are no positions found!"]);
        }

        $this->returnJson([
            'success' => true,
            'data' => $positions
        ]);
    }

    protected function _getAllPositionsWithDepartments()
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
            $this->returnJsonError(
                "The error occurred while getting data about all positions!"
            );
        }
        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * @return void
     *
     * url = /api/admin/role/get/all
     */
    protected function _getAllRoles(): void
    {
        /**
         * Get roles
         */
        $roles = $this->dataMapper->selectAllRoles();
        if($roles === false) {
            $this->returnJson(['error' => "An error occurred while getting all roles"]);
        }
        if($roles === null) {
            $this->returnJson(['error' => "There are no roles found!"]);
        }

        $this->returnJson([
            'success' => true,
            'data' => $roles
        ]);
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
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [
                'id' => htmlspecialchars(trim($_POST['id'])),
                'name' => htmlspecialchars(trim($_POST['name'])),
                'surname' => htmlspecialchars(trim($_POST['surname'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'position_id' => htmlspecialchars(trim($_POST['position_id'])),
                'role_id' => htmlspecialchars(trim($_POST['role_id'])),
                'gender' => htmlspecialchars(trim($_POST['gender'])),
                'age' => htmlspecialchars(trim($_POST['age'])),
                'experience' => htmlspecialchars(trim($_POST['experience'])),
                'salary' => htmlspecialchars(trim($_POST['salary'])),
            ];

            $valid = $this->_validateWorkerForm($items);
            if($valid !== true) {
                $this->returnJson($valid);
            }

            /**
             * Check if there is already registered worker with such email (except the current id)
             */
            $existId = $this->dataMapper->selectWorkerByEmailAndNotId($items['id'], $items['email']);
            if($existId) {
                $this->returnJson([
                    'error' => 'The worker with such email already exists!'
                ]);
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
                ]);
            }

            $updatedWorker = $this->dataMapper->selectWorkerRowById($items['id']);
            if($updatedWorker === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting the updated worker!'
                ]);
            }

            $this->returnJson([
                'success' => 'You successfully updated the worker!',
                'data' => $updatedWorker
            ]);

        }
    }
    private function _validateWorkerForm(array &$items)
    {
        $nameValidator = new NameValidator();
        $emailValidator = new EmailValidator();
        /**
         * Name
         */
        $validName = $nameValidator->validate($items['name']);
        if (!$validName) {
            return [
                'error' => 'Name must be at least 3 characters long and contain only letters'
            ];
        }

        /**
         * Surname
         */
        $validSurname = $nameValidator->validate($items['surname']);
        if (!$validSurname) {
            return [
                'error' => 'Surname must be at least 3 characters long and contain only letters'
            ];
        }

        /**
         * Email
         */
        $validEmail = $emailValidator->validate($items['email']);
        if (!$validEmail) {
            return [
                'error' => 'Please enter an email address in the format myemail@mailservice.domain'
            ];
        }

        /**
         * Position id
         */
        if(!$items['position_id']) {
            return [
                'error' => 'Position is required field!'
            ];
        }
        if(!is_int((int)$items['position_id'])) {
            return [
                'error' => 'Invalid position has been selected!'
            ];
        }

        /**
         * Role id
         */
        if(!$items['role_id']) {
            return [
                'error' => 'Role is required field!'
            ];
        }
        if(!is_int((int)$items['role_id'])) {
            return [
                'error' => 'Invalid role has been selected!'
            ];
        }

        /**
         * Gender
         */
        if($items['gender']) {
            if(
                $items['gender'] !== Gender::$MALE
                && $items['gender'] !== Gender::$FEMALE
                && $items['gender'] !== Gender::$OTHER
            ) {
                return [
                    'error' => 'Invalid gender selected! It should be Male, Female, or Other'
                ];
            }
        } else {
            $items['gender'] = null;
        }

        /**
         * Age
         */
        if(!$items['age']) {
            return [
                'error' => 'Age is required field!'
            ];
        }
        if($items['age'] < 14 || $items['age'] > 80) {
            return [
                'error' => "The worker's age should be from 14 to 80 years!"
            ];
        }

        /**
         * Years of experience
         */
        if(!$items['experience']) {
            return [
                'error' => "Years of worker's experience is required field!"
            ];
        }
        if($items['experience'] < 0 || $items['experience'] > 66) {
            return [
                'error' => "The years of the worker's experience should be from 0 to 66 years!"
            ];
        }

        /**
         * Salary
         */
        if($items['salary']) {
            if($items['salary'] < 0){
                return [
                    'error' => 'Salary can not be negative number!'
                ];
            }
            if(!is_int((int)$items['salary']) && !is_double((double)$items['salary'])) {
                return [
                    'error' => 'Invalid salary number is provided!'
                ];
            }
        } else {
            $items['salary'] = null;
        }
        return true;
    }

    /**
     * @return void
     *
     * url = /api/admin/worker/delete
     */
    public function _deleteWorker()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['id'])) {
                $this->returnJson([
                    'error' => 'Missing post fields!'
                ]);
            }

            $id = htmlspecialchars(trim($_POST['id']));

            /**
             * Check if there is no incomplete upcoming orders
             * for the worker we would like to delete
             */
            $futureOrders = $this->dataMapper->selectFutureOrdersByWorkerId($id);
            if($futureOrders === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting incomplete upcoming orders for the worker!'
                ]);
            }
            if($futureOrders) {
                $this->returnJson([
                    'error' => 'Tou can not delete the worker which has incomplete upcoming orders!'
                ]);
            }

            $deleted = $this->dataMapper->deleteWorkerById($id);
            if($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deletion of the worker!'
                ]);
            }

            $this->returnJson([
                'success' => 'You successfully deleted the worker!',
                'data' => [
                    'id' => $id
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/service/get/all-with-departments
     */
    protected function _getServicesAllWithDepartments() {
        parent::_getServicesAllWithDepartments();
    }

    /**
     * @return void
     *
     * url = /api/admin/service/add
     */
    protected function _addService() {
        parent::_addService();
    }

    /**
     * @return void
     *
     * url = /api/admin/department/add
     */
    public function _addDepartment()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['name'])) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }

            $name = htmlspecialchars(trim($_POST['name']));

            /**
             * Validate name format
             */
            if(strlen($name) < 3) {
                $this->returnJson([
                    'error' => 'Department name should be longer than 3 characters!!'
                ]);
            }

            /**
             * Check if there is no department with the same name
             */
            $exists = $this->dataMapper->selectDepartmentByName($name);
            if($exists === true) {
                $this->returnJson([
                    'error' => 'The department with such name already exists!'
                ]);
            }

            /**
             * Insert new department
             */
            $insertedId = $this->dataMapper->insertDepartment($name);
            if($insertedId === false) {
                $this->returnJson([
                    'error' => 'An error occurred while inserting the department!'
                ]);
            }

            $this->returnJson([
                'success' => "You successfully added the department with name <b>'$name'</b>",
                'data' => [
                    'id' => $insertedId
                ]
            ]);
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/department/edit
     */
    public function _editDepartment()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['id']) || empty($_POST['name'])) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }

            $items = [
                'id' => htmlspecialchars(trim($_POST['id'])),
                'name' => htmlspecialchars(trim($_POST['name']))
            ];

            /**
             * Validate name format
             */
            if(strlen($items['name']) < 3) {
                $this->returnJson([
                    'error' => 'Department name should be longer than 3 characters!!'
                ]);
            }

            /**
             * Check if there is no department with the same name
             */
            $exists = $this->dataMapper->selectDepartmentByName($items['name']);
            if($exists === true) {
                $this->returnJson([
                    'error' => 'The department with such name already exists!'
                ]);
            }

            /**
             * Insert new department
             */
            $updated = $this->dataMapper->updateDepartmentName(
                $items['id'], $items['name']
            );
            if($updated === false) {
                $this->returnJson([
                    'error' => 'An error occurred while updating the department name!'
                ]);
            }

            $this->returnJson([
                'success' => "You successfully updated the department!",
                'data' => [
                    'id' => $items['id'],
                    'name' => $items['name']
                ]
            ]);
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/department/delete
     */
    public function _deleteDepartment()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['id'])) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }

            $id = htmlspecialchars(trim($_POST['id']));

            /**
             * Check if there is no ordered schedules
             * in the future for the services from this department
             */
            $futureOrders = $this->dataMapper->selectFutureOrdersByDepartmentId($id);
            if($futureOrders === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting upcoming orders for the department!'
                ]);
            }
            if($futureOrders) {
                $this->returnJson([
                    'error' => 'The department can not be deleted because there are left upcoming incomplete orders for its services!'
                ]);
            }

            $deleted = $this->dataMapper->deleteDepartmentById($id);
            if($deleted === false) {
                $this->returnJson([
                    'error' => 'An error occurred while deleting the department!'
                ]);
            }

            $this->returnJson([
                'success' => 'You successfully deleted the department!',
                'data' => [
                    'id' => $id
                ]
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/department/get/all-limited
     */
    public function _getDepartmentsAllForTable()
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
            $this->returnJsonError(
                "The error occurred while getting data about all departments!"
            );
        }
        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * @return void
     *
     * url = /api/admin/department/get/all-services
     */
    public function _getServicesAllByDepartment()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['department_id'])) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }

            $id = htmlspecialchars(trim($_GET['department_id']));

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
                ]);
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
    }


    /**
     * @return void
     *
     * url = /api/admin/affiliate/add
     */
    protected function _addAffiliate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['name']) || empty($_POST['country'])
            || empty($_POST['city']) || empty($_POST['address']))
            {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }

            $items = [
                'name' => htmlspecialchars(trim($_POST['name'])),
                'manager_id' => $_POST['manager_id']
                                ? htmlspecialchars(trim($_POST['manager_id']))
                                : null,
                'country' => htmlspecialchars(trim($_POST['country'])),
                'city' => htmlspecialchars(trim($_POST['city'])),
                'address' => htmlspecialchars(trim($_POST['address'])),
            ];

            /**
             * Validate name
             */
            if(strlen($items['name']) < 3) {
                $this->returnJson([
                    'error' => 'Affiliate name should be equal to or longer than 3 characters!'
                ]);
            }

            /**
             * Validate country
             */
            $validator = new NameValidator();
            if(!$validator->validate($items['country'])) {
                $this->returnJson([
                    'error' => 'Invalid country name format! It must not contain any digits or special chars and has length equal to or longer that 3.'
                ]);
            }

            /**
             * Validate city
             */
            if(!$validator->validate($items['city'])) {
                $this->returnJson([
                    'error' => 'Invalid city name format! It must not contain any digits or special chars and has length equal to or longer that 3.'
                ]);
            }

            /**
             * Validate street address
             */
            $streetValidator = new StreetAddressValidator();
            if(!$streetValidator->validate($items['address'])) {
                $this->returnJson([
                    'error' => "Invalid format for the street address! Please, follow the example like 'str. Street, 3' or 'вул. Назва Вулиці, 1'"
                ]);
            }

            /**
             * Check if there is no affiliate with the same address
             */
            $existsByAddress = $this->dataMapper->selectAffiliateByCountryCityAndAddress(
                $items['country'], $items['city'], $items['address']
            );
            if($existsByAddress) {
                $this->returnJson([
                    'error' => 'The affiliate with the same address already exists!'
                ]);
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
                ]);
            }

            $this->returnJson([
                'success' => "You successfully added the affiliate with name '{$items['name']}'",
                'data' => [
                    'id' => $insertedId
                ]
            ]);
        }
    }

    protected function _editAffiliate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(empty($_POST['name']) || empty($_POST['country'])
                || empty($_POST['city']) || empty($_POST['address'])
                || empty($_POST['id']))
            {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }

            $items = [
                'id' => htmlspecialchars(trim($_POST['id'])),
                'name' => htmlspecialchars(trim($_POST['name'])),
                'manager_id' => $_POST['manager_id']
                    ? htmlspecialchars(trim($_POST['manager_id']))
                    : null,
                'country' => htmlspecialchars(trim($_POST['country'])),
                'city' => htmlspecialchars(trim($_POST['city'])),
                'address' => htmlspecialchars(trim($_POST['address'])),
            ];

            /**
             * Validate name
             */
            if(strlen($items['name']) < 3) {
                $this->returnJson([
                    'error' => 'Affiliate name should be equal to or longer than 3 characters!'
                ]);
            }

            /**
             * Validate country
             */
            $validator = new NameValidator();
            if(!$validator->validate($items['country'])) {
                $this->returnJson([
                    'error' => 'Invalid country name format! It must not contain any digits or special chars and has length equal to or longer that 3.'
                ]);
            }

            /**
             * Validate city
             */
            if(!$validator->validate($items['city'])) {
                $this->returnJson([
                    'error' => 'Invalid city name format! It must not contain any digits or special chars and has length equal to or longer that 3.'
                ]);
            }

            /**
             * Validate street address
             */
            $streetValidator = new StreetAddressValidator();
            if(!$streetValidator->validate($items['address'])) {
                $this->returnJson([
                    'error' => "Invalid format for the street address! Please, follow the example like 'str. Street, 3' or 'вул. Назва Вулиці, 1'"
                ]);
            }

            /**
             * Check if there is no affiliate with the same address
             */
            $existsByAddress = $this->dataMapper->selectAffiliateByAddressAndNotId(
                $items['id'], $items['country'], $items['city'], $items['address']
            );
            if($existsByAddress) {
                $this->returnJson([
                    'error' => 'The affiliate with the same address already exists!'
                ]);
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
                ]);
            }

            $updatedAffiliate = $this->dataMapper->selectAffiliateByIdForTable($items['id']);
            if($updatedAffiliate === false) {
                $this->returnJson([
                    'error' => 'An error occurred while getting updated affiliate!'
                ]);
            }

            $this->returnJson([
                'success' => "You successfully updated the affiliate!",
                'data' => $updatedAffiliate
            ]);
        }
    }

    protected function _getAffiliateById()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if(empty($_GET['id'])) {
                $this->returnJson([
                    'error' => 'Missing request fields!'
                ]);
            }

            $id = htmlspecialchars(trim($_GET['id']));

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
                ]);
            }

            $this->returnJson([
                'success' => true,
                'data' => $result
            ]);
        }
    }

    /**
     * @return void
     *
     * url = /api/admin/affiliate/get/all-limited
     */
    protected function _getAllAffiliatesForAdminTable()
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
            $this->returnJsonError(
                "The error occurred while getting data about all affiliates!"
            );
        }

        $this->returnJson([
            'success' => true,
            'data' => $result
        ]);
    }
}