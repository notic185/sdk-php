<?php

namespace Notic185\PHP_SDK;

readonly class Mangrove
{
    public MangroveMerchantOrder $merchantOrder;
    public MangroveOrder $order;
    public MangroveUser $user;
    public MangroveUserOrder $userOrder;

    public function __construct(protected string $endpoint, protected array $credential)
    {
        // 初始化子类
        $_ = new Mangrove0($this->endpoint, $this->credential);
        // 初始化操作类
        // > -
        $this->merchantOrder = new MangroveMerchantOrder($_);
        // > -
        $this->order = new MangroveOrder($_);
        // > -
        $this->user = new MangroveUser($_);
        // > -
        $this->userOrder = new MangroveUserOrder($_);
    }
}
