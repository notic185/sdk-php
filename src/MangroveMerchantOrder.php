<?php

namespace Notic185\PHP_SDK;

use Exception;

readonly class MangroveMerchantOrder
{
    public function __construct(private Mangrove0 $mangrove) {}

    /**
     * @throws Exception
     */
    public function create(array $merchantOrders): array
    {
        return $this->mangrove->request(
            'PUT', '/v1.2/merchant-order', $merchantOrders
        );
    }
}
