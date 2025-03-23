<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

use Clockwork\Request\Timeline\Event as ClockworkEvent;
use Clockwork\Support\Vanilla\Clockwork;
use Inpvlsa\Clockwork\Model\Clockwork\Model\FlexEvent;

class Layout extends AbstractGroupHandler
{
    protected const REGEX = '/.*CONTROLLER_ACTION:([\w_]*).*View\\\\Layout(::generateElements.*)$/';

    public static function canHandle(string $timerId, array $tags): bool
    {
        return preg_match(static::REGEX, $timerId) === 1;
    }

    protected function prepareDataOnStart(): void
    {
        preg_match(static::REGEX, $this->timerId, $matches);

        $layoutAction = $matches[1];
        $layoutMethod = $matches[2];

        $this->data['name'] = $layoutAction . $layoutMethod;
        $this->data['data'] = [
            'start' => microtime(true)
        ];
    }

    protected function handleDataOnStop(): void
    {
        $this->data['data']['end'] = microtime(true);
        $event = FlexEvent::fromEvent(new ClockworkEvent($this->data['name'], $this->data['data']));
        $event->setTags(['layout']);

        Clockwork::instance()->request()->timeline()->events[] = $event;
    }
}
