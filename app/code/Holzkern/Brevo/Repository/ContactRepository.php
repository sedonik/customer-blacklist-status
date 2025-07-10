<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Repository;

use Holzkern\Brevo\Service\ContactService;
use Holzkern\Brevo\Model\ResourceModel\Contact as ContactResource;

class ContactRepository
{
    /**
     * @var ContactService
     */
    private $contactService;

    /**
     * @var ContactResource
     */
    private $contactResource;

    /**
     * @param ContactService $contactService
     * @param ContactResource $contactResource
     */
    public function __construct(
        ContactService $contactService,
        ContactResource $contactResource
    ) {
        $this->contactService = $contactService;
        $this->contactResource = $contactResource;
    }

    /**
     * Sync contact blacklist status by Brevo ID
     *
     * @param int $brevoId
     * @return void
     */
    public function syncById($brevoId)
    {
        $blacklistStatus = $this->contactService->getById($brevoId);
        
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
} 