<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource\Elasticsearch;

class ElasticsearchMiddleware
{
    /**
     * @var callable
     */
    private $onQuery;

    public function __construct(
        callable $onQuery
    ) {
        $this->onQuery = $onQuery;
    }

    public function process(callable $wrappedFn, array $query): array
    {
        $result = $wrappedFn($query);

        ($this->onQuery)($query, $result);

        return $result;
    }
}
