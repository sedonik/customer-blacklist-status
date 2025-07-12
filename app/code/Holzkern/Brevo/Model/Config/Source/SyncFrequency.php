<?php
declare(strict_types=1);

namespace Holzkern\Brevo\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class SyncFrequency implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '0 */6 * * *', 'label' => __('Every 6 hours')],
            ['value' => '0 */4 * * *', 'label' => __('Every 4 hours')],
            ['value' => '0 */2 * * *', 'label' => __('Every 2 hours')],
            ['value' => '0 * * * *', 'label' => __('Every hour')],
            ['value' => '0 */12 * * *', 'label' => __('Every 12 hours')],
            ['value' => '0 0 * * *', 'label' => __('Daily (at midnight)')],
            ['value' => '0 0 */2 * *', 'label' => __('Every 2 days')],
            ['value' => '0 0 * * 0', 'label' => __('Weekly (Sundays)')],
        ];
    }
}
