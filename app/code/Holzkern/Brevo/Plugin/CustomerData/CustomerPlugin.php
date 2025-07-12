<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Plugin\CustomerData;

use Magento\Customer\CustomerData\Customer;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomerPlugin
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerSession $customerSession
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerSession $customerSession
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * Add blacklist_status to customer section data
     *
     * @param Customer $subject
     * @param array $result
     * @return array
     */
    public function afterGetSectionData(Customer $subject, array $result)
    {
        if (empty($result)) {
            return $result;
        }

        if (!$this->customerSession->isLoggedIn()) {
            return $result;
        }

        try {
            $customerId = $this->customerSession->getCustomerId();

            $customer = $this->customerRepository->getById($customerId);
            $extensionAttributes = $customer->getExtensionAttributes();

            if ($extensionAttributes && $extensionAttributes->getBrevoContact()) {
                $blacklistStatus = $extensionAttributes->getBrevoContact()->getData('black_list_status');
                $result['blacklist_status'] = (int) $blacklistStatus;
            } else {
                $result['blacklist_status'] = null;
            }
        } catch (NoSuchEntityException $e) {
            $result['blacklist_status'] = null;
        }

        return $result;
    }
}
