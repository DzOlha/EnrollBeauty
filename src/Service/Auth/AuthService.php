<?php

namespace Src\Service\Auth;

use Src\Helper\Http\HttpCode;
use Src\Model\DataMapper\DataMapper;

abstract class AuthService
{
    protected DataMapper $dataMapper;

    /**
     * @param DataMapper $dataMapper
     */
    public function __construct(DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    protected function _methodNotAllowed(array $allowedMethods): array
    {
        $methods = implode(', ', $allowedMethods);
        return [
            'error' => "Method not allowed! Allowed ones: $methods",
            'code' => HttpCode::methodNotAllowed()
        ];
    }

    protected function _missingRequestFields(): array
    {
        return [
            'error' => 'Missing request fields!',
            'code' => HttpCode::badRequest()
        ];
    }
}