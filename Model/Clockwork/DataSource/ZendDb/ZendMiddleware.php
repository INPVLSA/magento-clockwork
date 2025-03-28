<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource\ZendDb;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\AbstractMiddleware;

class ZendMiddleware extends AbstractMiddleware
{
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
