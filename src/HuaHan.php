<?php

namespace Verdient\HuaHan;

use SoapClient;

/**
 * 华翰物流
 * @author Verdient。
 */
class HuaHan
{
    /**
     * @param string $wsdl WSDL地址
     * @param string $appKey App标识
     * @param string $appToken App令牌
     * @param string|null $proxyHost 代理主机
     * @param string|null $proxyPort 代理端口
     */
    public function __construct(
        protected string $wsdl,
        protected string $appKey,
        protected string $appToken,
        protected string|null $proxyHost = null,
        protected string|null $proxyPort = null
    ) {
    }

    /**
     * 发起请求
     * @param string $action 请求的动作
     * @param array $data 数据
     * @return Response
     * @throws Throwable
     * @author Verdient。
     */
    public function request(string $action, array $data = []): Response
    {
        $options = [];
        if ($this->proxyHost) {
            $options['proxy_host'] = $this->proxyHost;
            $options['proxy_port'] = $this->proxyPort;
        }
        return new Response(
            (new SoapClient($this->wsdl, $options))
                ->__soapCall('callService', [
                    'parameters' => [
                        'appKey' => $this->appKey,
                        'appToken' => $this->appToken,
                        'service' => $action,
                        'paramsJson' => json_encode($data)
                    ]
                ])
        );
    }
}
