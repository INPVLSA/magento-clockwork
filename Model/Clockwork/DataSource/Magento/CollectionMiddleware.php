<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource\Magento;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\AbstractMiddleware;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Data\Collection;

class CollectionMiddleware extends AbstractMiddleware
{
    public function process(Collection $collection, callable $wrappedFn, $printQuery = false, $logQuery = false)
    {
        $startTime = microtime(true);
        $result = $wrappedFn($printQuery, $logQuery);

        $data = [
            'start' => $startTime,
            'end' => microtime(true),
            'sql' => $collection->getSelect()->__toString(),
            'collection_class' => get_class($collection),
            'getPageSize' => $collection->getPageSize(),
            'getSize' => $collection->getSize()
        ];

        if ($collection instanceof AbstractCollection) {
            $data['entity'] = $collection->getEntity()
                ? $collection->getEntity()->getEntityType()->getEntityTypeCode()
                : get_class($collection);
            $data['getLoadedIds'] = $collection->getLoadedIds();
            $data['count(loaded)'] = count($collection->getLoadedIds());
        } else {
            $data['entity'] = get_class($collection);
        }

        ($this->onQuery)($data);

        return $result;
    }
}
