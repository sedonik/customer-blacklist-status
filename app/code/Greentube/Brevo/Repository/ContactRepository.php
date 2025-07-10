<?php
namespace Greentube\Brevo\Repository;

use Greentube\Brevo\Model\EmailBlackListStatus;
use Greentube\Brevo\Model\ResourceModel\Contact as ContactResource;

class ContactRepository
{
    /**
     * @var EmailBlackListStatus
     */
    private $emailBlackListStatus;

    /**
     * @var ContactResource
     */
    private $contactResource;

    /**
     * @param EmailBlackListStatus $emailBlackListStatus
     * @param ContactResource $contactResource
     */
    public function __construct(
        EmailBlackListStatus $emailBlackListStatus,
        ContactResource $contactResource
    ) {
        $this->emailBlackListStatus = $emailBlackListStatus;
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
        $blacklistStatus = $this->emailBlackListStatus->getById($brevoId);
        
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