<?php

namespace Notic185\PHP_SDK;

use Exception;

readonly class MangroveOrder
{
    public function __construct(private Mangrove0 $mangrove)
    {
    }

    /**
     * @throws Exception
     */
    public function describe(string $uuid): array
    {
        return $this->mangrove->request('GET', "/v1.2/order/$uuid");
    }

    /**
     * @throws Exception
     */
    public function handleCallback(?array $request = null): array
    {
        // 储存请求体
        $requestBody = null;

        // 填充请求
        if ($request) {
            $requestBody = $request['body'];
        } else {
            // -
            $requestBody = json_decode(
                file_get_contents('php://input'), true
            );
            // -
            $request = [
                "method" => $_SERVER['REQUEST_METHOD'],
                "path" => $_SERVER['REQUEST_URI'],
                "headers" => getallheaders(),
                "body" => $requestBody
            ];
        }

        // 计算签名
        $requestSignature = $this->mangrove->signRequest($request);

        // 验证签名
        if (
            $requestSignature === explode(
                " ",
                $request['headers']['Authorization'] ?? $request['headers']['authorization']
            )[1]
        ) {
            return $requestBody;
        } else {
            throw new Exception("Invalid signature");
        }
    }

    /**
     * @throws Exception
     */
    public function update(array $order): array
    {
        return $this->mangrove->request(
            'PATCH', "/v1.2/order/{$order['uuid']}", $order
        );
    }

    /**
     * @throws Exception
     */
    public function delete(string $uuid): array
    {
        return $this->mangrove->request('DELETE', "/v1.2/order/$uuid");
    }
}
