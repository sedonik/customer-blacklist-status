<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Model;

use Holzkern\Brevo\Api\Data\ContactInterface;
use Magento\Framework\Model\AbstractModel;

class Contact extends AbstractModel implements ContactInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Holzkern\Brevo\Model\ResourceModel\Contact::class);
    }
}
