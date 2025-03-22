<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Config\Option;

use Magento\Framework\Data\OptionSourceInterface;

class ClockworkDataStorage implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'file', 'label' => __('File')],
            ['value' => 'redis', 'label' => __('Redis')],
        ];
    }
}
