<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Cron;

use Holzkern\Brevo\Api\ContactServiceInterface;
use Psr\Log\LoggerInterface;

class SyncContacts
{
    /**
     * @var ContactServiceInterface
     */
    private $contactService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ContactServiceInterface $contactService
     * @param LoggerInterface $logger
     */
    public function __construct(
        ContactServiceInterface $contactService,
        LoggerInterface $logger
    ) {
        $this->contactService = $contactService;
        $this->logger = $logger;
    }

    /**
     * Execute the cron job
     *
     * @return void
     */
    public function execute()
    {
        try {
            $this->logger->info('Starting Brevo contacts sync cron job');

            $result = $this->contactService->syncAll();

            if ($result['success']) {
                $this->logger->info(
                    sprintf(
                        'Brevo contacts sync completed successfully. Synced %d contacts out of %d total contacts.',
                        $result['total_synced'],
                        $result['total_contacts']
                    )
                );
            } else {
                $this->logger->warning(
                    sprintf(
                        'Brevo contacts sync completed with errors. Synced %d contacts out of %d total contacts. Errors: %d',
                        $result['total_synced'],
                        $result['total_contacts'],
                        count($result['errors'])
                    )
                );
            }
        } catch (\Exception $e) {
            $this->logger->error(
                sprintf('Brevo contacts sync cron job failed: %s', $e->getMessage())
            );
        }
    }
}
