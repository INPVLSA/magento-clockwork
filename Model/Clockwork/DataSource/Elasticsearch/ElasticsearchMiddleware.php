<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource\Elasticsearch;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\AbstractMiddleware;

class ElasticsearchMiddleware extends AbstractMiddleware
{
    public function process(callable $wrappedFn, array $query): array
    {
        $result = $wrappedFn($query);

        ($this->onQuery)($query, $result);

        return $result;
    }
}
