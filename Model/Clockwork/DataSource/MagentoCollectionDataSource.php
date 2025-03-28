<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;
use Clockwork\Request\Timeline\Event;
use Inpvlsa\Clockwork\Model\Clockwork\DataSource\Magento\CollectionMiddleware;
use Inpvlsa\Clockwork\Model\Clockwork\FlexEvent;

class MagentoCollectionDataSource extends DataSource
{
    protected array $data = [];

    public function resolve(Request $request): Request
    {
        foreach ($this->data as $item) {
            $eventData = [
                'start' => $item['start'],
                'end' => $item['end'],
            ];
            $event = FlexEvent::fromEvent(new Event($item['name'], $eventData));
            $event->setData('data', ['tags' => ['collection']]);

            $request->timeline()->events[] = $event;

            $tableData = [];

            foreach ($item as $key => $value) {
                if ($key === 'start' || $key === 'end' || $key === 'name') {
                    continue;
                }

                $tableData[] = ['Key/Method' => $key, 'Value' => $value];
            }
            $tableData[] = ['Key/Method' => 'Time, ms', 'Value' => ($eventData['end'] - $eventData['start']) * 1000];
            $request->userData('Collections')->table($item['name'], $tableData);
        }

        return $request;
    }

    public function middleware(): CollectionMiddleware
    {
        return new CollectionMiddleware(function ($result) {
            $this->registerLoad($result);
        });
    }

    protected function registerLoad(array $data): void
    {
        $data['name'] = ($data['entity'] ?? $data['collection_class']) . ' (UID: ' . uniqid() . ')';
        $this->data[] = $data;
    }
}
