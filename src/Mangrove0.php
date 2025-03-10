<?php

namespace Notic185\PHP_SDK;

use Exception;
use Notic185\PHP_SDK\Utilities\Simplifier;

readonly class Mangrove0
{
    public function __construct(protected readonly string $endpoint, protected readonly array $credential)
    {}

    /**
     * @throws Exception
     */
    public function request(string $requestMethod, string $requestPath, ?array $requestPayload = null): array
    {
        // 构建请求
        $request = curl_init($this->endpoint . $requestPath);

        // 初始化请求
        // > 请求方法
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, $requestMethod);
        // > 请求头
        // > > 构建
        // > > > -
        $requestHeaders = [
            "content-type" => "application/json",
            "host" => substr($this->endpoint, strrpos($this->endpoint, '/') + 1),
            "x-guarder-id" => $this->credential['externalId'],
            "x-guarder-signed-at" => time() * 1000,
            "x-guarder-uuid" => uniqid(),
        ];
        // > > > -
        $requestHeaders['authorization'] = 'Signature ' . $this->signRequest([
                "method" => $requestMethod,
                "path" => $requestPath,
                "headers" => $requestHeaders,
                "body" => $requestPayload,
            ]);
        // > > 写入
        curl_setopt(
            $request,
            CURLOPT_HTTPHEADER,
            array_map(
                fn($key, $value): string => "$key: $value",
                array_keys($requestHeaders), array_values($requestHeaders)
            )
        );
        // > 请求体
        if ($requestPayload) {
            curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($requestPayload));
        }
        // > 其它参数
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        // 发起请求
        // > -
        $response = curl_exec($request);
        // > 处理错误
        // > > 获取错误信息
        $responseWrong = curl_error($request);
        // > > 如果有错误则抛出
        if ($responseWrong) {
            throw new Exception($responseWrong);
        }
        // 解析响应
        // > -
        $responsePayload = json_decode($response, true);
        // > 返回结果或抛出错误
        if ($responsePayload['code'] == 200) {
            return $responsePayload['data'];
        } else {
            throw new Exception($responsePayload['message']);
        }
    }

    /**
     * @param array $request <p>
     * ["method"->?,"path"->?,"headers"->[?->?],"body"->?]
     * </p>
     * @return string
     */
    public function signRequest(array $request): string
    {
        // 转换请求体
        // > -
        $requestBody = '';
        // > -
        if ($request['body']) {
            // 数据打平
            // > -
            $flattenedRequestBody = [];
            // > -
            Simplifier::pileDown($request['body'], $flattenedRequestBody);
            // 数据排序
            ksort($flattenedRequestBody);
            // 数据转换 & 数据拼接
            $requestBody = join(
                '&',
                array_map(
                    fn($key, $value): string => "$key=$value",
                    array_keys($flattenedRequestBody), array_values($flattenedRequestBody),
                )
            );
        }

        // 构建签名载荷
        $requestSignature = [
            "{$request['method']} {$request['path']}",
            ...array_map(
                fn($key): string => "$key: {$request['headers'][$key]}",
                ["content-type", "host", "x-guarder-id", "x-guarder-signed-at", "x-guarder-uuid"]
            ),
            '',
            $requestBody,
        ];

        // 签名并返回
        return hash_hmac(
            'SHA512',
            join("\r\n", $requestSignature),
            $this->credential['secret']
        );
    }
}
