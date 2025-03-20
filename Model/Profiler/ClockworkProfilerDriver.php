<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler;

use Clockwork\Clockwork;
use Inpvlsa\Clockwork\Model\Clockwork\Service;
use Magento\Framework\Profiler\Driver\Standard\OutputInterface;
use Magento\Framework\Profiler\Driver\Standard\Stat;
use Magento\Framework\Profiler\DriverInterface;

class ClockworkProfilerDriver implements DriverInterface, OutputInterface
{
    protected array $nameCache = [];

    protected function clock(): Clockwork
    {
        return \Clockwork\Support\Vanilla\Clockwork::instance()->getClockwork();
    }

    public function start($timerId, array $tags = null): void
    {
        if (Service::$enabled) {
            $name = $this->getName($timerId);
            $this->clock()->event($this->getName($timerId))->color($this->getColor($timerId, $name))->begin();
        }
    }

    public function stop($timerId): void
    {
        if (Service::$enabled) {
            $name = $this->getName($timerId);
            $this->clock()->event($name)->end();

            unset($this->nameCache[$timerId]);
        }
    }

    public function clear($timerId = null): void
    {
    }

    public function display(Stat $stat): void
    {
    }

    protected function getName(string $timerId): string
    {
        if (!isset($this->nameCache[$timerId])) {
            if (str_starts_with($timerId, 'magento->routers_match->CONTROLLER_ACTION:')) {
                $this->nameCache[$timerId] = 'Controller: ' . substr($timerId, 42);
            } elseif (str_contains($timerId, '->')) {
                $parts = explode('->', $timerId);
                $lastPart = end($parts);

                if (str_starts_with($lastPart, 'OBSERVER:')) {
                    $this->nameCache[$timerId] = 'Observer: ' . substr($lastPart, 9);
                }
            }

            if (!isset($this->nameCache[$timerId])) {
                $this->nameCache[$timerId] = 'Profiler: ' . $timerId;
            }
        }

        return $this->nameCache[$timerId];
    }

    protected function getColor(string $timerId, string $name): string
    {
        if (str_starts_with($name, 'Observer:')) {
            return 'green';
        }

        if (str_starts_with($name, 'Controller:')) {
            return 'green';
        }

        if (str_contains($timerId, 'LAYOUT->layout_generate_blocks')) {
            return 'red';
        }

        if (str_starts_with($timerId, 'magento->routers_match')) {
            return 'blue';
        }

        return 'purple';
    }
}
