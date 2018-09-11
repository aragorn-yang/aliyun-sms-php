<?php

namespace AragornYang\AliyunSms;

use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
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
            Config::load();

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $this->accessKeyId, $this->accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }


    /**
     * 发送短信
     * @param string $phoneNumbers
     * @param string $signName
     * @param string $templateCode
     * @param array $param
     * @return \stdClass
     */
    public function sendSms($phoneNumbers, $signName, $templateCode, $param)
    {
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        $request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phoneNumbers);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($signName);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        //$templateCode = "SMS_0000001";
        $request->setTemplateCode($templateCode);

        // 短信模板中字段的值
        //$param = [
        //    "code" => "12345",
        //    "product" => "dsd"
        //];

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode($param, JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        //$request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        //$request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    /**
     * 短信发送记录查询
     * @param string $phoneNumber
     * @param string $sendDate
     * @param int $pageSize
     * @param int $currentPage
     * @return \stdClass
     */
    public function querySendDetails($phoneNumber, $sendDate, $pageSize, $currentPage)
    {
        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        //可选-启用https协议
        $request->setProtocol("https");

        // 必填，短信接收号码
        $request->setPhoneNumber($phoneNumber);

        // 必填，短信发送日期，格式Ymd，支持近30天记录查询
        //$sendDate = "20170718";
        $request->setSendDate($sendDate);

        // 必填，分页大小
        //$pageSize = 10;
        $request->setPageSize($pageSize);

        // 必填，当前页码
        //$currentPage = 1;
        $request->setCurrentPage($currentPage);

        // 选填，短信发送流水号
        //$request->setBizId("yourBizId");

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }
}