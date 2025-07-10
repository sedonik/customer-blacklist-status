<?php
namespace Greentube\Brevo\Model;

use Magento\Framework\Model\AbstractModel;

class Contact extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Greentube\Brevo\Model\ResourceModel\Contact::class);
    }
} 