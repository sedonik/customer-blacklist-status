<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_PATH_API_URL = 'holzkern/brevo/api_url';
    const XML_PATH_API_KEY = 'holzkern/brevo/api_key';
    const XML_PATH_SYNC_FREQUENCY = 'holzkern/brevo/sync_frequency';

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Get API URL
     *
     * @param string|null $storeId
     * @return string
     */
    public function getApiUrl($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_API_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get API Key
     *
     * @param string|null $storeId
     * @return string
     */
    public function getApiKey($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * TODO: add ability to change Frequency sync by the config self::XML_PATH_SYNC_FREQUENCY
     * Get sync frequency
     *
     * @param string|null $storeId
     * @return string
     */
    public function getSyncFrequency($storeId = null)
    {
        $frequency = $this->scopeConfig->getValue(
            self::XML_PATH_SYNC_FREQUENCY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        // Default to every hour if not configured
        return $frequency ?: '0 * * * *';
    }
}
