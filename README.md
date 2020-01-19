<div>
  <p align="center">
    <image src="https://mc.qcloudimg.com/static/img/f16f1ac8e60723fec56675eec2a74f1b/image.svg" width="250" height="250">
  </p>
  <p align="center">Tencent Cloud API Gateway PHP SDK</p>
  <p align="center">
    <a href="LICENSE">
      <image src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License">
    </a>
    <!--<a href="https://travis-ci.org/freyo/qcloud-apigateway-http">
      <image src="https://img.shields.io/travis/freyo/qcloud-apigateway-http/master.svg?style=flat-square" alt="Build Status">
    </a>
    <a href="https://scrutinizer-ci.com/g/freyo/qcloud-apigateway-http">
      <image src="https://img.shields.io/scrutinizer/coverage/g/freyo/qcloud-apigateway-http.svg?style=flat-square" alt="Coverage Status">
    </a>-->
    <a href="https://scrutinizer-ci.com/g/freyo/qcloud-apigateway-http">
      <image src="https://img.shields.io/scrutinizer/g/freyo/qcloud-apigateway-http.svg?style=flat-square" alt="Quality Score">
    </a>
    <a href="https://packagist.org/packages/freyo/qcloud-apigateway-http">
      <image src="https://img.shields.io/packagist/v/freyo/qcloud-apigateway-http.svg?style=flat-square" alt="Packagist Version">
    </a>
    <a href="https://packagist.org/packages/freyo/qcloud-apigateway-http">
      <image src="https://img.shields.io/packagist/dt/freyo/qcloud-apigateway-http.svg?style=flat-square" alt="Total Downloads">
    </a>
  </p>
  <p align="center">
    <a href="https://app.fossa.io/projects/git%2Bgithub.com%2Ffreyo%2Fqcloud-apigateway-http?ref=badge_small">
      <img src="https://app.fossa.io/api/projects/git%2Bgithub.com%2Ffreyo%2Fqcloud-apigateway-http.svg?type=small"  alt="FOSSA Status">
    </a>
  </p>
</div>

## Requirement

1. PHP >= 7.1.3
2. **[Composer](https://getcomposer.org/)**
3. openssl 拓展

## Installation

```shell
$ composer require freyo/qcloud-apigateway-http -vvv
```

## Usage

```php
<?php

include 'vendor/autoload.php';

use Freyo\ApiGateway\Application;

$app = new Application([
    'response_type' => 'collection',
    'secret_key'    => 'your-secret-key',
    'secret_id'     => 'your-secret-id',
    'region'        => 'your-gateway-region', // e.g., ap-guangzhou
    'log'           => [
        'file'  => __DIR__ . DIRECTORY_SEPARATOR . 'apigateway.log',
        'level' => 'debug',
    ],
    'http' => [
        'base_uri' => 'http://{service-id}.{region}.apigw.tencentcs.com',
        // 'base_uri' => 'http://{service-id}.{region}.apigateway.myqcloud.com',
    ],
]);

$response = $app->http_client->request('path/to');

var_dump($response);
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Ffreyo%2Fqcloud-apigateway-http.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Ffreyo%2Fqcloud-apigateway-http?ref=badge_large)
