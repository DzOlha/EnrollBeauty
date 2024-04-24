<?php

namespace Src\Model\Repository\impl\extend;

use Src\DB\IDatabase;
use Src\Helper\Builder\impl\SqlBuilder;
use Src\Model\Repository\impl\Repository;
use Src\Model\Table\Departments;
use Src\Model\Table\Services;
use Src\Model\Table\WorkersServicePricing;

class ServicePricingRepository extends Repository
{
    protected static ?Repository $instance = null;

    public static function getInstance(
        IDatabase $db = null, SqlBuilder $builder = null
    ){
        if (self::$instance === null) {
            self::$instance = new self($db, $builder);
        }
        return self::$instance;
    }

    /**
     * @return array|false
     *
     * [
     *      0 => [
     *          id =>,
                name =>,
                services => [
     *              id =>
     *              name =>
     *              min_price =>
     *              currency =>
                ]
     *      ]
     * ]
     */
    public function selectAllMinPricelist(): array | false
    {
        $workers_service_pricing = WorkersServicePricing::$table;
        $services = Services::$table;
        $departments = Departments::$table;

        $this->db->query("
            SELECT 
                d.id,
                d.name,
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', s.id,
                        'name', s.name,
                        'min_price', min_price.min_price,
                        'currency', min_price.currency
                    )
                ) AS services
            FROM 
                $departments d
            INNER JOIN 
                $services s ON d.id = s.department_id
            INNER JOIN 
            (
                SELECT
                    service_id,
                    MIN(price) AS min_price,
                    currency
                FROM 
                    $workers_service_pricing
                GROUP BY 
                    service_id, currency
            ) min_price ON s.id = min_price.service_id
            GROUP BY 
                d.id

        ");

        return $this->db->manyRows();
    }
}