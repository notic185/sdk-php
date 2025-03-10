<?php

namespace Notic185\PHP_SDK;

use Exception;

readonly class MangroveUserOrder
{
    public function __construct(private Mangrove0 $mangrove) {}

    /**
     * @throws Exception
     */
    public function create(array $userOrders): array
    {
        return $this->mangrove->request(
            'PUT', '/v1.2/user-order', $userOrders
        );
    }
}
