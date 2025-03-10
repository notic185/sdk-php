<?php

namespace Notic185\PHP_SDK;

use Exception;

readonly class MangroveUser
{
    public function __construct(private Mangrove0 $mangrove) {}

    /**
     * @throws Exception
     */
    public function summarizeIntegralAmount(): array
    {
        return $this->mangrove->request(
            'GET', '/v1.2/user/summarize-integral-amount'
        );
    }
}
