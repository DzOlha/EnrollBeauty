<?php

namespace Src\Model\Repository\Pool;

use Src\Model\Repository\Instance\IRepository;

 interface IRepositoryPool
{
    public function worker(): IRepository;
    public function position(): IRepository;
    public function service(): IRepository;
    public function orderService(): IRepository;
    public function affiliate(): IRepository;
    public function admin(): IRepository;
    public function user(): IRepository;
    public function department(): IRepository;
    public function servicePricing(): IRepository;
    public function role(): IRepository;
    public function schedule(): IRepository;
}