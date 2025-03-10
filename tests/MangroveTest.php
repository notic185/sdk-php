<?php

namespace Notic185\PHP_SDK;

use PHPUnit\Framework\TestCase;

class MangroveTest extends TestCase
{
    private readonly Mangrove $mangrove;

    /** @noinspection PhpDocFinalChecksInspection */
    public function __construct(string $name)
    {
        // -
        parent::__construct($name);
        // -
        $this->mangrove = new Mangrove(
            "http://10.0.0.254:4003",
            [
                "externalId" => "sbSO9eCvjQkE38hVnrVy4TiD",
                "secret" => "ER3wT3UP05TVEyh8CdMnZsCz5I1j0z",
            ],
        );
    }

    public function test_createMerchantOrder(): void
    {
        // -
        $merchantOrders = $this->mangrove->merchantOrder->create([
            [
                "order" => [
                    "amount" => 1,
                    "orderCallback" => [
                        "endpoint" => "http://127.0.0.1:8888"
                    ]
                ]
            ]
        ]);
        // -
        $this->assertIsList($merchantOrders);
        // -
        print("Message: The code for this order is {$merchantOrders[0]["order"]["code"]}");
    }

    public function test_describeOrder(): void
    {
        // -
        $order = $this->mangrove->order->describe("05bf25dd-103a-4580-96b2-4e30c9736822");
        // -
        $this->assertEquals("SkAoLS", $order["code"]);
    }

    public function test_updateOrder(): void
    {
        // -
        $orderName = uniqid();
        // -
        $order = $this->mangrove->order->update(
            [
                "uuid" => "05bf25dd-103a-4580-96b2-4e30c9736822",
                "name" => $orderName,
            ]
        );
        // -
        $this->assertEquals($orderName, $order[0]["name"]);
    }

    public function test_deleteOrder(): void
    {
        $this->mangrove->order->delete("05bf25dd-103a-4580-96b2-4e30c9736822");
    }

    public function test_summarizeUserIntegralAmount(): void
    {
        foreach ($this->mangrove->user->summarizeIntegralAmount() as $key => $value) {
            print("Message: $key → $value\r\n");
        }
    }

    public function test_createsUserOrder(): void
    {
        // -
        $userOrders = $this->mangrove->userOrder->create([
            [
                "currency" => "1",
                "userOrderTransaction" => [
                    "amount" => 100000,
                ]
            ]
        ]);
        // -
        $this->assertIsList($userOrders);
        // -
        print("Message: The code for this order is {$userOrders[0]["order"]["code"]}\r\n");
    }
}
