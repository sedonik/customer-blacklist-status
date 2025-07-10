<?php
declare(strict_types=1);

namespace Greentube\Brevo\Api\Data;

interface ContactInterface
{
    /**
     * Get Brevo ID
     *
     * @return int|null
     */
    public function getBrevoId();

    /**
     * Set Brevo ID
     *
     * @param int $brevoId
     * @return $this
     */
    public function setBrevoId($brevoId);

    /**
     * Get Black List Status
     *
     * @return int|null
     */
    public function getBlackListStatus();

    /**
     * Set Black List Status
     *
     * @param int $status
     * @return $this
     */
    public function setBlackListStatus($status);
} 