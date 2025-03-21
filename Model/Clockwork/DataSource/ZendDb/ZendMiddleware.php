<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource\ZendDb;

class ZendMiddleware
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

    public function process(callable $wrappedFn, $sql, $bind = [])
    {
        $startTime = microtime(true);
        $result = $wrappedFn($sql, $bind);
        ($this->onQuery)([
            'time' => $startTime,
            'duration' => (microtime(true) - $startTime) * 1000,
            'query' => (string)$sql,
        ]);

        return $result;
    }
}
