<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

use Clockwork\Support\Vanilla\Clockwork;

class Event extends AbstractGroupHandler
{
    protected const array EVENT_IGNORE_LIST = [
        'core_layout_block_create_after',
        'view_block_abstract_to_html_after',
        'core_layout_render_element',
        'view_block_abstract_to_html_before',
        'core_collection_abstract_load_before',
        'core_collection_abstract_load_after'
    ];

    public static function canHandle(string $timerId, array $tags): bool
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

        if (in_array($event, self::EVENT_IGNORE_LIST)) {
            return;
        }

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
