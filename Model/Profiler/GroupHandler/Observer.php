<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

use Clockwork\Request\Timeline\Event as ClockworkEvent;
use Clockwork\Support\Vanilla\Clockwork;
use Inpvlsa\Clockwork\Model\Clockwork\Model\FlexEvent;

class Observer extends AbstractGroupHandler
{
    protected const REGEX = '/.*->OBSERVER:(.*?)$/';

    public static function canHandle(string $timerId, array $tags): bool
    {
        return preg_match(static::REGEX, $timerId) > 0;
    }

    protected function prepareDataOnStart(): void
    {
        preg_match(static::REGEX, $this->timerId, $matches);
        $observerName = $matches[1];

        $this->data['data'] = [
            'name' => $observerName,
            'tags' => ['observer']
        ];
        $this->data['start'] = microtime(true);
    }

    protected function handleDataOnStop(): void
    {
        /** @var \Clockwork\Clockwork $instance */
        $instance = Clockwork::instance();

        $this->data['end'] = microtime(true);

        $timelineEvent = new ClockworkEvent($this->data['data']['name'], $this->data);
        $event = FlexEvent::fromEvent($timelineEvent);
        $event->setData('tags', $this->data['data']['tags']);

        $instance->request()->timeline()->events[] = $event;
    }
}
