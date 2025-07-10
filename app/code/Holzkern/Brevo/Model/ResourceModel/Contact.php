<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Model\ResourceModel;

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
        $this->_init('holzkern_brevo_contact', 'entity_id');
    }
}
