<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource\Magento;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\AbstractMiddleware;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\AbstractDb;

class CollectionMiddleware extends AbstractMiddleware
{
    public function process(Collection $collection, callable $wrappedFn, $printQuery = false, $logQuery = false)
    {
        $startTime = microtime(true);
        $result = $wrappedFn($printQuery, $logQuery);

        $data = [
            'start' => $startTime,
            'end' => microtime(true),
            'collection_class' => get_class($collection),
            'getPageSize' => $collection->getPageSize(),
        ];

        // This check in necessary, might cause infinite Interception loop
        if ($collection->isLoaded()) {
            $data['getSize'] = $collection->getSize();
        }

        if ($collection instanceof AbstractDb) {
            $data['sql'] = $collection->getSelect()->__toString();
        }

        if ($collection instanceof AbstractCollection) {
            try {
                $data['entity'] = $collection->getEntity()->getEntityType()->getEntityTypeCode();
            } catch (\Exception $e) {
                // skip
            }
            $data['getLoadedIds'] = $collection->getLoadedIds();
            $data['count(getLoadedIds)'] = count($collection->getLoadedIds());
        }

        ($this->onQuery)($data);

        return $result;
    }
}
