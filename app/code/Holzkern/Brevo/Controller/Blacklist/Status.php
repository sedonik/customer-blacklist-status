<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Controller\Blacklist;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Status implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @param JsonFactory $jsonFactory
     * @param CustomerSession $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        JsonFactory $jsonFactory,
        CustomerSession $customerSession,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Execute action
     *
     * @return Json
     */
    public function execute(): Json
    {
        $result = $this->jsonFactory->create();

        try {
            $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
            $extensionAttributes = $customer->getExtensionAttributes();

            $data = [
                'status' => 'success',
                'message' => 'Customer found in session',
                'customer_id' => $customer->getId(),
                'customer_email' => $customer->getEmail(),
                'blacklist_status' => $extensionAttributes && $extensionAttributes->getBrevoContact()
                    ? $extensionAttributes->getBrevoContact()->getData('black_list_status')
                    : null
            ];
        } catch (\Exception $e) {
            $data = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return $result->setData($data);
    }
}
