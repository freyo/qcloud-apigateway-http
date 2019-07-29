# qcloud-apigateway-http
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Ffreyo%2Fqcloud-apigateway-http.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Ffreyo%2Fqcloud-apigateway-http?ref=badge_shield)


## Requirement

1. PHP >= 5.6
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
    'log'           => [
        'file'  => __DIR__ . DIRECTORY_SEPARATOR . 'apigateway.log',
        'level' => 'debug',
    ],
    'http' => [
        'base_uri' => 'http://{service-id}.{region}.apigw.tencentcs.com',
        // 'base_uri' => 'http://{service-id}.{region}.apigateway.myqcloud.com',
    ],
]);

$response = $app->base_client->request('path/to');

var_dump($response);
```

## License

MIT


[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Ffreyo%2Fqcloud-apigateway-http.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Ffreyo%2Fqcloud-apigateway-http?ref=badge_large)