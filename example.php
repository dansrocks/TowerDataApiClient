<?php

require_once 'vendor/autoload.php';


use \TowerDataApiClient\TowerDataApiClient;
use GuzzleHttp\Exception\RequestException;

$apiKey = '4e64a84e4b49d55cc054498a359c6ec2';

try {
    $api = new TowerDataApiClient($apiKey);
    $service = $api->getEmailValidationService();
    $response = $service->validate('root@mailinator.com');
} catch (\Exception $exception) {
    print_r($exception->getMessage());
}
print_r($response);