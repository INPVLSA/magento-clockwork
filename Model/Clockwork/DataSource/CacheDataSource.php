<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;
use Inpvlsa\Clockwork\Model\Clockwork\DataSource\Magento\CacheMiddleware;

class CacheDataSource extends DataSource
{
    protected array $cacheQueries = [];

    public function resolve(Request $request): Request
    {
        foreach ($this->cacheQueries as $query) {
            if ($query['type'] === 'save') {
                $request->addCacheQuery(
                    'write',
                    $query['identifier'],
                    $query['value'],
                    $query['end'] - $query['start'],
                    $query['data']
                );
            } else {
                $request->addCacheQuery(
                    $query['type'],
                    $query['identifier'],
                    null,
                    $query['end'] - $query['start'],
                    ['time' => $query['start']]
                );
            }
        }

        return $request;
    }

    public function middleware(): CacheMiddleware
    {
        return new CacheMiddleware(function ($data) {
            $this->registerCacheQuery($data);
        });
    }

    protected function registerCacheQuery(array $data): void
    {
        $this->cacheQueries[] = $data;
    }
}
