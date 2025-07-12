<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Api;

interface ContactServiceInterface
{
    /**
     * Get contact blacklist status by Brevo ID
     *
     * @param int $brevoId
     * @return array
     */
    public function getById($brevoId);

    /**
     * Get all contacts with pagination
     *
     * @param int $limit
     * @param int $offset
     * @param string $sort
     * @return array
     */
    public function getAll($limit = 50, $offset = 0, $sort = 'asc');

    /**
     * Sync contact blacklist status by Brevo ID
     *
     * @param int $brevoId
     * @return void
     */
    public function syncById($brevoId);

    /**
     * Sync all contacts in batches
     *
     * @return array
     */
    public function syncAll();
}
