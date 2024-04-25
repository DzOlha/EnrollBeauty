<?php

namespace Src\Model\Repository\Pool;
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

 interface IRepositoryPool
{
    public function worker(): WorkerRepository;
    public function position(): PositionRepository;
    public function service(): ServiceRepository;
    public function orderService(): OrderServiceRepository;
    public function affiliate(): AffiliateRepository;
    public function admin(): AdminRepository;
    public function user(): UserRepository;
    public function department(): DepartmentRepository;
    public function servicePricing(): ServicePricingRepository;
    public function role(): RoleRepository;
    public function schedule(): ScheduleRepository;
}