<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

use Clockwork\Request\Timeline\Event as ClockworkEvent;
use Clockwork\Support\Vanilla\Clockwork;
use Inpvlsa\Clockwork\Model\Clockwork\Model\FlexEvent;

class Template extends AbstractGroupHandler
{
    public static function canHandle(string $timerId, array $tags): bool
    {
        return isset($tags['group']) && $tags['group'] === 'TEMPLATE';
    }

    protected function prepareDataOnStart(): void
    {
        $this->data['file'] = $this->data['tags']['file_name'] ?? 'Inline-rendered template';
        $this->data['data']['tags'][] = 'template';
        $this->data['start'] = microtime(true);
    }

    protected function handleDataOnStop(): void
    {
        $this->data['end'] = microtime(true);
        $event = FlexEvent::fromEvent(new ClockworkEvent($this->data['file'], $this->data));
        $event->setData('tags', $this->data['data']['tags']);

        Clockwork::instance()->request()->timeline()->events[] = $event;
    }
}
