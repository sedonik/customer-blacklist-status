<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Service;

use GuzzleHttp\Client;
use Holzkern\Brevo\Api\ContactServiceInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ContactService implements ContactServiceInterface
{
    const API_URL_CONFIG_PATH = 'holzkern/brevo/api_url';
    const API_KEY_CONFIG_PATH = 'holzkern/brevo/api_key';

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param Client $httpClient
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Client $httpClient,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->httpClient = $httpClient;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get contact blacklist status by Brevo ID
     *
     * @param int $brevoId
     * @return array
     */
    public function getById($brevoId)
    {
        $apiUrl = $this->scopeConfig->getValue(self::API_URL_CONFIG_PATH);
        $apiKey = $this->scopeConfig->getValue(self::API_KEY_CONFIG_PATH);

        $response = $this->httpClient->request('GET', $apiUrl . '/v3/contacts/' . $brevoId, [
            'headers' => [
                'api-key' => $apiKey,
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return [
            'emailBlacklisted' => $data['emailBlacklisted'] ?? null,
            'smsBlacklisted' => $data['smsBlacklisted'] ?? null,
        ];
    }
} 