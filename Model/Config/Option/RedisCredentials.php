<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Config\Option;

use Magento\Framework\Data\OptionSourceInterface;

class RedisCredentials implements OptionSourceInterface
{

    public function toOptionArray(): array
    {
        return [
            ['value' => 'session', 'label' => __('Session')],
            ['value' => 'cache', 'label' => __('Cache')],
            ['value' => 'custom', 'label' => __('Custom')],
        ];
    }
}
