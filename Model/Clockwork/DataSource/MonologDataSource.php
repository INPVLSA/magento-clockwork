<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

use Clockwork\DataSource\MonologDataSource as ClockworkMonologDataSource;
use Magento\Framework\Logger\Monolog;

/**
 * This class is a workaround resolves issue #5 with missing $name argument while using injection using di
 */
class MonologDataSource extends ClockworkMonologDataSource
{
    public function __construct(Monolog $monolog)
    {
        parent::__construct($monolog);
    }
}
