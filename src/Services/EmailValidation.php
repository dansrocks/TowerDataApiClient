<?php

namespace TowerDataApiClient\Services;

use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\ResponseInterface;
use TowerDataApiClient\Exceptions\ApiResponseFailureException;
use TowerDataApiClient\Exceptions\EmailMalformedException;

/**
 * Class EmailValidation
 *
 * @package TowerDataApiClient\Services
 */
class EmailValidation extends AbstractApiService
{
    const EMAIL_MALFORMED = 'malformed';
    const EMAIL_VALID = 'valid';
    const EMAIL_INVALID = 'invalid';
    const EMAIL_RISKY = 'risky';
    const EMAIL_UNVERIFIABLE = 'unverifiable';
    const EMAIL_UNKNOWN = 'unknown';


    /** @var string */
    protected $endpoint = 'v5/ev';

    /**
     * @param string $email
     *
     * @return mixed
     *
     * @throws ConnectException
     * @throws ApiResponseFailureException
     * @throws EmailMalformedException
     */
    public function validate(string $email)
    {
        $this->checkValidEmail($email);

        $response = $this->execute([ 'email' => $email, 'timeout' => $this->timeout ]);
        if (! $response || $response->getStatusCode() !== 200) {
            throw new ApiResponseFailureException();
        }

        return $this->processResponse($response);
    }

    /**
     * @param string $email
     *
     * @throws EmailMalformedException
     */
    protected function checkValidEmail(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw  new EmailMalformedException();
        }
    }

    /**
     * @param ResponseInterface $response
     *
     * @return mixed
     *
     * @throws ApiResponseFailureException
     */
    protected function processResponse(ResponseInterface $response)
    {
        $json = json_decode($response->getBody()->getContents());
        if (! $json instanceof \stdClass || ! property_exists($json, 'email_validation')) {
            throw new ApiResponseFailureException();
        }

        return $json->email_validation;
    }
}