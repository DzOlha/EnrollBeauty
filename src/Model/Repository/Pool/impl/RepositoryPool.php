<?php

namespace Src\Model\Repository\Pool\impl;

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
    public function worker(): WorkerRepository
    {
        return WorkerRepository::getInstance();
    }

    public function position(): PositionRepository
    {
        return PositionRepository::getInstance();
    }

    public function service(): ServiceRepository
    {
        return ServiceRepository::getInstance();
    }

    public function orderService(): OrderServiceRepository
    {
        return OrderServiceRepository::getInstance();
    }

    public function affiliate(): AffiliateRepository
    {
        return AffiliateRepository::getInstance();
    }

    public function admin(): AdminRepository
    {
        return AdminRepository::getInstance();
    }

    public function user(): UserRepository
    {
        return UserRepository::getInstance();
    }

    public function department(): DepartmentRepository
    {
        return DepartmentRepository::getInstance();
    }

    public function servicePricing(): ServicePricingRepository
    {
        return ServicePricingRepository::getInstance();
    }

    public function role(): RoleRepository
    {
        return RoleRepository::getInstance();
    }

    public function schedule(): ScheduleRepository
    {
        return ScheduleRepository::getInstance();
    }
}