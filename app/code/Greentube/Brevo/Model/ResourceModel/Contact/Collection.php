<?php
declare(strict_types=1);

namespace Greentube\Brevo\Model\ResourceModel\Contact;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Greentube\Brevo\Model\Contact::class,
            \Greentube\Brevo\Model\ResourceModel\Contact::class
        );
    }
} 