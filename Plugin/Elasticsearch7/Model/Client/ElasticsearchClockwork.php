<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Plugin\Elasticsearch7\Model\Client;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\ElasticsearchDataSource;

class ElasticsearchClockwork
{
    protected ElasticsearchDataSource $dataSource;

    public function __construct(
        ElasticsearchDataSource $dataSource
    ) {
        $this->dataSource = $dataSource;
    }

    public function aroundQuery(
        $subject,
        callable $proceed,
        array $query
    ): array {
        return $this->dataSource->middleware()->process($proceed, $query);
    }
}
