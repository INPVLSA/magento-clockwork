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
            if (!empty($tags)) {
                if ($this->tryResolveForDefinedEntity($timerId, $tags)) {

                    return;
                }
            }

            $name = $this->getName($timerId, $tags);
            /** @var Event $event */
            $event = $this->clock()->event($name, ['data' => $tags]);
            $event->color($this->getColor($timerId, $tags));
            $event->begin();
        }
    }

    /**
     * @return bool isResolved
     */
    protected function tryResolveForDefinedEntity(string $timerId, array $tags): bool
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver::canHandle($tags)) {
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

    protected function getData(string $timerId): array
    {
        $data = [];

        if (preg_match('/.*->OBSERVER:(.*?\w)$/', $timerId, $matches)) {
            $data['type'] = 'observer';
        } elseif (preg_match('/.*->EVENT:(.*?\w)$/', $timerId, $matches)) {
            $data['type'] = 'event';
        }

        return $data;
    }

    protected function getName(string $timerId, ?array $tags = []): string
    {
        if (isset($tags['group'])) {
            $this->nameCache[$timerId] = match ($tags['group']) {
                'EVENT' => 'Event: ' . $tags['name'],
                'TEMPLATE' => 'Template: ' . $tags['file_name'],
                'cache' => 'Cache: ' . $tags['operation'],
                'EAV' => 'EAV: ' . $tags['method'],
                default => false,
            };
        }

        if (!isset($this->nameCache[$timerId]) || !$this->nameCache[$timerId]) {
            // Removing `magento->`
            $name = preg_replace('/^magento->/', '', $timerId);
            // Replacing profiler separator
            $name = str_replace('->', '/', $name);

            if (preg_match('/.*\/OBSERVER:(.*?\w)$/', $name, $matches)) {
                $name = 'Observer: ' . $matches[1];
            } elseif (preg_match('/.*\/EVENT:(.*?\w)$/', $name, $matches)) {
                $name = 'Event dispatch: ' . $matches[1];
            } elseif (preg_match('/.*\/BLOCK_ACTION:(.*?)>(.*?)$/', $name, $matches)) {
                $name = "Block($matches[1])->$matches[2]";
            } else {
                $name = str_replace('action_body/LAYOUT/layout_generate_blocks/Magento\Framework\View\Layout::Magento\Framework\View\Layout::generateElements/generate_elements', '<layout_generate_blocks:generate_elements>', $name);
                $name = preg_replace('~routers_match/CONTROLLER_ACTION:(\w+?)/~', '<c:$1>/', $name);
            }

            $this->nameCache[$timerId] = $name;
        }

        return $this->nameCache[$timerId];
    }

    protected function getColor(string $timerId, ?array $tags): string
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
