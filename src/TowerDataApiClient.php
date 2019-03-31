<?php

namespace TowerDataApiClient;

use TowerDataApiClient\Exceptions\InvalidApiService;
use TowerDataApiClient\Services\EmailValidation;

/**
 * Class TowerDataApiClient
 *
 * @package TowerDataApiClient
 */
class TowerDataApiClient
{
    const SERVICE_EMAIL_VALIDATION = EmailValidation::class;
//    const SERVICE_EMAIL_INTELLIGENCE = 'EmailIntelligence';
//    const SERVICE_IDENTITY_MATCHING = 'IdentityMatching';

    /** @var string */
    private $apiKey;

    /** @var array */
    private $availableServices = [
        self::SERVICE_EMAIL_VALIDATION => true,
//        self::SERVICE_EMAIL_INTELLIGENCE => false,
//        self::SERVICE_IDENTITY_MATCHING => false,
    ];

    /** @var string */
    private $apiBaseUrl = 'https://api.towerdata.com';


    /**
     * TowerDataApiClient constructor.
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return EmailValidation
     *
     * @throws InvalidApiService
     */
    public function getEmailValidationService()
    {
        return $this->getService(self::SERVICE_EMAIL_VALIDATION);
    }

    /**
     * @param string $service
     *
     * @return EmailValidation
     *
     * @throws InvalidApiService
     */
    public function getService(string $service)
    {
        if (! $this->isAvailableService($service)) {
            throw new InvalidApiService();
        }

        return $this->buildService($service);
    }

    /**
     * @param string $service
     *
     * @return bool
     */
    private function isAvailableService(string $service)
    {
        return array_key_exists($service, $this->availableServices)
            && $this->availableServices[$service];
    }

    /**
     * @param string $service
     *
     * @return EmailValidation
     */
    private function buildService(string $service)
    {
        $service = new $service($this->apiBaseUrl, $this->apiKey);

        return $service;
    }
}