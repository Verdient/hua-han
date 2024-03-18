<?php

declare(strict_types=1);

namespace Verdient\HuaHan;

use stdClass;

/**
 * 响应
 * @author Verdient。
 */
class Response
{
    /**
     * 请求是否成功
     * @author Verdient。
     */
    protected bool $isOK = false;

    /**
     * 错误码
     * @author Verdient。
     */
    protected int|string|null $errorCode = null;

    /**
     * 错误信息
     * @author Verdient。
     */
    protected ?string $errorMessage = null;

    /**
     * 响应信息
     * @author Verdient。
     */
    protected ?stdClass $response = null;

    /**
     * 数据
     * @author Verdient。
     */
    protected mixed $data = null;

    /**
     * @param stdClass 响应数据
     * @author Verdient。
     */
    public function __construct(stdClass $response)
    {
        $this->response = $response;
        $response = $this->toArray(json_decode($response->response));
        $this->isOK = $response['ask'] === 'Success';
        if ($this->isOK) {
            $this->data = $response['Data'];
        } else {
            $this->errorCode = 0;
            $this->errorMessage = $response['Error']['cnMessage'];
        }
    }

    /**
     * 将对象转换为数组
     * @param stdClass|array 待转换的数据
     * @return array
     * @author Verdient。
     */
    protected function toArray(stdClass|array $data): array
    {
        $result = [];
        foreach ((array) $data as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $result[$key] = $this->toArray($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * 获取响应
     * @return array
     * @author Verdient。
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * 获取是否成功
     * @return bool
     * @author Verdient。
     */
    public function getIsOK()
    {
        return $this->isOK;
    }

    /**
     * 获取错误码
     * @return int
     * @author Verdient。
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * 获取错误信息
     * @return string
     * @author Verdient。
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * 获取返回的数据
     * @return array
     * @author Verdient。
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     * @return mixed
     * @author Verdient。
     */
    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}
