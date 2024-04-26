<?php

namespace Src\Model\Repository\Pool\impl;

use Src\DB\IDatabase;
use Src\Helper\Builder\IBuilder;
use Src\Model\Repository\Instance\impl\extend\AdminRepository;
use Src\Model\Repository\Instance\impl\extend\AffiliateRepository;
use Src\Model\Repository\Instance\impl\extend\DepartmentRepository;
use Src\Model\Repository\Instance\impl\extend\OrderServiceRepository;
use Src\Model\Repository\Instance\impl\extend\PositionRepository;
use Src\Model\Repository\Instance\impl\extend\RoleRepository;
use Src\Model\Repository\Instance\impl\extend\ScheduleRepository;
use Src\Model\Repository\Instance\impl\extend\ServicePricingRepository;
use Src\Model\Repository\Instance\impl\extend\ServiceRepository;
use Src\Model\Repository\Instance\impl\extend\UserRepository;
use Src\Model\Repository\Instance\impl\extend\WorkerRepository;
use Src\Model\Repository\Pool\IRepositoryPool;

class RepositoryPool implements IRepositoryPool
{
    private IDatabase $db;
    private IBuilder $builder;

    public function __construct(
        IDatabase $db, IBuilder $builder
    ){
        $this->db = $db;
        $this->builder = $builder;
    }

    public function worker(): WorkerRepository
    {
        return WorkerRepository::getInstance($this->db, $this->builder);
    }

    public function position(): PositionRepository
    {
        return PositionRepository::getInstance($this->db, $this->builder);
    }

    public function service(): ServiceRepository
    {
        return ServiceRepository::getInstance($this->db, $this->builder);
    }

    public function orderService(): OrderServiceRepository
    {
        return OrderServiceRepository::getInstance($this->db, $this->builder);
    }

    public function affiliate(): AffiliateRepository
    {
        return AffiliateRepository::getInstance($this->db, $this->builder);
    }

    public function admin(): AdminRepository
    {
        return AdminRepository::getInstance($this->db, $this->builder);
    }

    public function user(): UserRepository
    {
        return UserRepository::getInstance($this->db, $this->builder);
    }

    public function department(): DepartmentRepository
    {
        return DepartmentRepository::getInstance($this->db, $this->builder);
    }

    public function servicePricing(): ServicePricingRepository
    {
        return ServicePricingRepository::getInstance($this->db, $this->builder);
    }

    public function role(): RoleRepository
    {
        return RoleRepository::getInstance($this->db, $this->builder);
    }

    public function schedule(): ScheduleRepository
    {
        return ScheduleRepository::getInstance($this->db, $this->builder);
    }
}