# aliyun-sms-php

## 安装
` composer require aragorn-yang/aliyun-sms-php`

## 使用方法

### 基于 Laravel 框架

`php artisan vendor:publish --provider="AragornYang\AliyunSms\ServiceProvider"`

根据新增的`aliyun-sms.php` 文件，在 `.env` 文件中添加环境变量：
``` 
ALIYUN_SMS_ACCESS_KEY_ID=your_access_key_id
ALIYUN_SMS_ACCESS_KEY_SECRET=your_access_key_secret
```

```php
$aliSms = new AliyunSms();
$response = $aliSms->sendSms('phone_number', 'sign_name', 'template_code', ['name'=> 'value in your template']);
$response = $aliSms->querySendDetails('phone_number', '20180911', 10, 1);
```