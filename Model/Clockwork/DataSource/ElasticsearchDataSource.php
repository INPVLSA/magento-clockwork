<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;
use Inpvlsa\Clockwork\Model\Clockwork\DataSource\Elasticsearch\ElasticsearchMiddleware;

class ElasticsearchDataSource extends DataSource
{
    protected array $searchData = [];

    protected const NAME = 'ElasticSearch';

    public function resolve(Request $request): Request
    {
        if (!empty($this->searchData)) {
            $dataTab = $request->userData(static::NAME);

            foreach ($this->searchData as $search) {
                $queryData = [];

                foreach ($search['query'] as $key => $value) {
                    $queryData[] = ['Key' => $key, 'Value' => $value];
                }

                $dataTab->table(static::NAME . ' Query', $queryData);

                $queryData = [];

                foreach ($search['result'] as $key => $value) {
                    $queryData[] = ['Key' => $key, 'Value' => $value];
                }
                $dataTab->table(static::NAME . ' Result', $queryData);
            }
        }

        return $request;
    }

    public function middleware(): ElasticsearchMiddleware
    {
        return new ElasticsearchMiddleware(function ($query, $result) {
            $this->registerQuery($query, $result);
        });
    }

    protected function registerQuery(array $query, array $result): void
    {
        $this->searchData[] = [
            'query' => $query,
            'result' => $result
        ];
    }
}
