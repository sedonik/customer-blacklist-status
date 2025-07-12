<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Model\ResourceModel\Contact;

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
            \Holzkern\Brevo\Model\Contact::class,
            \Holzkern\Brevo\Model\ResourceModel\Contact::class
        );
    }
} 