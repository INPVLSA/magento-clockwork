<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;
use Inpvlsa\Clockwork\Model\Clockwork\DataSource\ZendDb\ZendMiddleware;

class ZendDbDataSource extends DataSource
{
    protected array $queries = [];

    protected ZendMiddleware $middleware;

    public function resolve(Request $request): Request
    {
        $request->databaseQueries = array_merge($request->databaseQueries, $this->queries);

        return $request;
    }

    public function extend(Request $request): Request
    {
        return $request;
    }

    public function reset(): void
    {
        $this->queries = [];
    }

    public function middleware(): ZendMiddleware
    {
        return $this->middleware = new ZendMiddleware(function ($query) {
            $this->registerQuery($query);
        });
    }

    protected function registerQuery($query): void
    {
        $query['duration'] = (microtime(true) - $query['time']) * 1000;
        $query['connection'] = $query['connection'] ?? 'Default';

        if ($this->passesFilters([$query])) {
            $this->queries[] = $query;
        }
    }
}
