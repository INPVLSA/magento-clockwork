<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

use Clockwork\Support\Vanilla\Clockwork;

class Event extends AbstractGroupHandler
{
    public static function canHandle(array $tags): bool
    {
        return isset($tags['group']) && $tags['group'] === 'EVENT';
    }

    protected function prepareDataOnStart(): void
    {
        $this->data['data'] = [
            'time' => microtime(true),
            'event' => $this->data['tags']['name']
        ];
    }

    protected function handleDataOnStop(): void
    {
        if (!isset($this->data['data']['event'])) {
            return;
        }
        $event = $this->data['data']['event'];
        unset($this->data['data']['event']);

        $time = $this->data['data']['time'];
        unset($this->data['data']['time']);

        $this->data['data']['duration'] = ((microtime(true) - $time) * 1000);

        Clockwork::instance()->getClockwork()->request()->addEvent(
            $event,
            null,
            $time,
            $this->data['data']
        );
    }
}
