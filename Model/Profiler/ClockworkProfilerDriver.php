<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler;

use Clockwork\Clockwork;
use Inpvlsa\Clockwork\Model\Clockwork\Service;
use Magento\Framework\Profiler\Driver\Standard\OutputInterface;
use Magento\Framework\Profiler\Driver\Standard\Stat;
use Magento\Framework\Profiler\DriverInterface;

class ClockworkProfilerDriver implements DriverInterface, OutputInterface
{
    protected function clock(): Clockwork
    {
        return \Clockwork\Support\Vanilla\Clockwork::instance()->getClockwork();
    }

    public function start($timerId, array $tags = null): void
    {
        if (Service::$enabled) {
            $this->clock()->event($timerId)->color('purple')->begin();
        }
    }

    public function stop($timerId): void
    {
        if (Service::$enabled) {
            $this->clock()->event($timerId)->end();
        }
    }

    public function clear($timerId = null): void
    {
    }

    public function display(Stat $stat): void
    {
    }
}
