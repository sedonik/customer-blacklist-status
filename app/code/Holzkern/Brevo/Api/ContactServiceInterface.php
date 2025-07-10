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
}
