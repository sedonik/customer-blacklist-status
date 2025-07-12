<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Service;

use GuzzleHttp\Client;
use Holzkern\Brevo\Api\ContactServiceInterface;
use Holzkern\Brevo\Model\ResourceModel\Contact as ContactResource;
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
     * @var ContactResource
     */
    private $contactResource;

    /**
     * @param Client $httpClient
     * @param ScopeConfigInterface $scopeConfig
     * @param ContactResource $contactResource
     */
    public function __construct(
        Client $httpClient,
        ScopeConfigInterface $scopeConfig,
        ContactResource $contactResource
    ) {
        $this->httpClient = $httpClient;
        $this->scopeConfig = $scopeConfig;
        $this->contactResource = $contactResource;
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

    /**
     * Get all contacts with pagination
     *
     * @param int $limit
     * @param int $offset
     * @param string $sort
     * @return array
     */
    public function getAll($limit = 50, $offset = 0, $sort = 'asc')
    {
        $apiUrl = $this->scopeConfig->getValue(self::API_URL_CONFIG_PATH);
        $apiKey = $this->scopeConfig->getValue(self::API_KEY_CONFIG_PATH);

        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'sort' => $sort
        ];

        $response = $this->httpClient->request('GET', $apiUrl . '/v3/contacts', [
            'headers' => [
                'api-key' => $apiKey,
                'Accept' => 'application/json',
            ],
            'query' => $queryParams
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return [
            'contacts' => $data['contacts'] ?? [],
            'count' => $data['count'] ?? 0,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    /**
     * Sync contact blacklist status by Brevo ID
     *
     * @param int $brevoId
     * @return void
     */
    public function syncById($brevoId)
    {
        $blacklistStatus = $this->getById($brevoId);
        
        // Update the black_list_status field based on emailBlacklisted status
        $blackListStatus = $blacklistStatus['emailBlacklisted'] ? 1 : 0;
        
        // Update the database record
        $connection = $this->contactResource->getConnection();
        $connection->update(
            $this->contactResource->getMainTable(),
            ['black_list_status' => $blackListStatus],
            ['brevo_id = ?' => $brevoId]
        );
    }

    /**
     * Sync all contacts in batches
     *
     * @return array
     */
    public function syncAll()
    {
        $limit = 10;
        $offset = 0;
        $totalSynced = 0;
        $totalContacts = 0;
        $errors = [];

        do {
            try {
                $result = $this->getAll($limit, $offset, 'asc');
                $contacts = $result['contacts'] ?? [];
                $totalContacts = $result['count'] ?? 0;

                foreach ($contacts as $contact) {
                    try {
                        $brevoId = $contact['id'] ?? null;
                        if ($brevoId) {
                            $this->syncById($brevoId);
                            $totalSynced++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = [
                            'brevo_id' => $brevoId,
                            'error' => $e->getMessage()
                        ];
                    }
                }

                $offset += $limit;
            } catch (\Exception $e) {
                $errors[] = [
                    'offset' => $offset,
                    'error' => $e->getMessage()
                ];
                break;
            }
        } while (count($contacts) === $limit);

        return [
            'total_contacts' => $totalContacts,
            'total_synced' => $totalSynced,
            'errors' => $errors,
            'success' => empty($errors)
        ];
    }
}
