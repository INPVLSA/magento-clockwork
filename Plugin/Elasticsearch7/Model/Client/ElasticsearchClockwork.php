<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Plugin\Elasticsearch7\Model\Client;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\ElasticsearchDataSource;

class ElasticsearchClockwork
{
    public function __construct(
        protected ElasticsearchDataSource $dataSource
    ) {}

    /**
     * @noinspection PhpFullyQualifiedNameUsageInspection
     * @noinspection PhpDeprecationInspection
     */
    public function aroundQuery(
        \Magento\Elasticsearch7\Model\Client\Elasticsearch $subject,
        callable $proceed,
        array $query
    ): array {
        return $this->dataSource->middleware()->process($proceed, $query);
    }
}
