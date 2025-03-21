<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;
use Inpvlsa\Clockwork\Model\Clockwork\DataSource\Elasticsearch\ElasticsearchMiddleware;

class ElasticsearchDataSource extends DataSource
{
    protected array $searchData = [];

    public function resolve(Request $request): Request
    {
        if (!empty($this->searchData)) {
            $dataTab = $request->userData('ElasticSearch');

            foreach ($this->searchData as $search) {
                $dataTab->table('ElasticSearch Query', [$search['query']]);
                $dataTab->table('ElasticSearch Result', [$search['result']]);
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
