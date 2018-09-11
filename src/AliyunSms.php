<?php

use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;

class AliyunSms
{
    protected static $acsClient;

    protected $accessKeyId = '';
    protected $accessKeySecret = '';

    public function __construct($config = null)
    {
        if ($config) {
            $this->accessKeyId = $config['access_key_id'];
            $this->accessKeySecret = $config['access_key_secret'];
        } elseif (function_exists('config')) {
            $this->accessKeyId = config('aliyun-sms.access_key_id');
            $this->accessKeySecret = config('aliyun-sms.access_key_secret');
        }
    }

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public function getAcsClient()
    {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";
        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";
        // 暂时不支持多Region
        $region = "cn-hangzhou";
        // 服务结点
        $endPointName = "cn-hangzhou";

        if (null === static::$acsClient) {
            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $this->accessKeyId, $this->accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }
}