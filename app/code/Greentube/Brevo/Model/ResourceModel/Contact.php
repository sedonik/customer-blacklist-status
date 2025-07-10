<?php
declare(strict_types=1);

namespace Greentube\Brevo\Model\ResourceModel;

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
        $this->_init('gr_brevo_contact', 'entity_id');
    }
}
