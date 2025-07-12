<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Holzkern\Brevo\Model\ResourceModel\Contact\CollectionFactory as ContactCollectionFactory;

class CustomerRepositoryPlugin
{
    /**
     * @var ContactCollectionFactory
     */
    private $contactCollectionFactory;

    /**
     * @param ContactCollectionFactory $contactCollectionFactory
     */
    public function __construct(ContactCollectionFactory $contactCollectionFactory)
    {
        $this->contactCollectionFactory = $contactCollectionFactory;
    }

    /**
     * Load Brevo contact data into customer extension attributes
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $customer
     * @return CustomerInterface
     */
    public function afterGetById(CustomerRepositoryInterface $subject, CustomerInterface $customer)
    {
        $extensionAttributes = $customer->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->extensionAttributesFactory->create(CustomerInterface::class);
        }

        $contactCollection = $this->contactCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customer->getId())
            ->getFirstItem();

        if ($contactCollection->getId()) {
            $extensionAttributes->setBrevoContact($contactCollection);
        }

        $customer->setExtensionAttributes($extensionAttributes);

        return $customer;
    }
}
