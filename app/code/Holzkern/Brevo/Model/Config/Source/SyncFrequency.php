<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class SyncFrequency implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Not sync')],
            ['value' => '1', 'label' => __('Every hour')],
            ['value' => '12', 'label' => __('Twice a day')],
            ['value' => '24', 'label' => __('Once a day')]
        ];
    }
} 