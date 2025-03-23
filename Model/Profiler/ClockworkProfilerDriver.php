<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler;

use Clockwork\Clockwork;
use Clockwork\Request\Timeline\Event;
use Inpvlsa\Clockwork\Model\Clockwork\Service;
use Inpvlsa\Clockwork\Model\Profiler\GroupHandler\AbstractGroupHandler;
use Magento\Framework\Profiler\Driver\Standard\OutputInterface;
use Magento\Framework\Profiler\Driver\Standard\Stat;
use Magento\Framework\Profiler\DriverInterface;

class ClockworkProfilerDriver implements DriverInterface, OutputInterface
{
    /**
     * @param AbstractGroupHandler[] $resolvers
     */
    public function __construct(
        protected array $resolvers = []
    ) {}

    protected array $nameCache = [];

    /**
     * @var array<string, AbstractGroupHandler>
     */
    protected array $activeHandlers = [];

    protected function clock(): Clockwork
    {
        return \Clockwork\Support\Vanilla\Clockwork::instance()->getClockwork();
    }

    public function start($timerId, array $tags = null): void
    {
        if (Service::$enabled) {
            if ($this->tryResolveForDefinedEntity($timerId, $tags)) {

                return;
            }
            /** @var Event $event */
            $event = $this->clock()->event($timerId, ['data' => $tags]);
            $event->color($this->getColor($tags));
            $event->begin();
        }
    }

    /**
     * @return bool isResolved
     */
    protected function tryResolveForDefinedEntity(string $timerId, ?array $tags): bool
    {
        if ($tags === null) {
            $tags = [];
        }

        foreach ($this->resolvers as $resolver) {
            if ($resolver::canHandle($timerId, $tags)) {
                $resolverInstance = new $resolver($timerId, $tags);
                $this->activeHandlers[$timerId] = $resolverInstance;
                $resolverInstance->start();

                return true;
            }
        }

        return false;
    }

    public function stop($timerId): void
    {
        if (Service::$enabled) {
            if (isset($this->activeHandlers[$timerId])) {
                $this->activeHandlers[$timerId]->stop();
                unset($this->activeHandlers[$timerId]);

                return;
            }
            $this->clock()->event($timerId)->end();

            unset($this->nameCache[$timerId]);
        }
    }

    public function clear($timerId = null): void
    {
    }

    public function display(Stat $stat): void
    {
    }

    protected function getColor(?array $tags): string
    {
        $color = 'blue';

        if ($tags) {
            if (isset($tags['group'])) {
                $color = match ($tags['group']) {
                    'EVENT' => 'green',
                    'TEMPLATE' => 'purple',
                    default => 'blue',
                };
            }
        }

        return $color;
    }
}
