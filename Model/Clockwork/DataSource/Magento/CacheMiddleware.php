<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource\Magento;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\AbstractMiddleware;

class CacheMiddleware extends AbstractMiddleware
{
    public function processLoad(callable $wrappedFn, string $identifier)
    {
        $startTime = microtime(true);
        $result = $wrappedFn($identifier);

        $data = [
            'start' => $startTime,
            'end' => microtime(true),
            'type' => 'load',
            'identifier' => $identifier,
            'data' => [
                'time' => $startTime
            ]
        ];

        ($this->onQuery)($data);

        return $result;
    }

    public function processSave(callable $wrappedFn, $data, $identifier, array $tags = [], $lifeTime = null)
    {
        $startTime = microtime(true);
        $result = $wrappedFn($data, $identifier, $tags, $lifeTime);

        $data = [
            'start' => $startTime,
            'end' => microtime(true),
            'type' => 'save',
            'identifier' => $identifier,
            'value' => $data,
            'data' => [
                'tags' => $tags,
                'lifeTime' => $lifeTime,
                'time' => $startTime
            ]
        ];

        ($this->onQuery)($data);

        return $result;
    }

    public function processRemove(callable $wrappedFn, string $identifier)
    {
        $startTime = microtime(true);
        $result = $wrappedFn($identifier);

        $data = [
            'start' => $startTime,
            'end' => microtime(true),
            'type' => 'delete',
            'identifier' => $identifier,
            'data' => [
                'time' => $startTime
            ]
        ];

        ($this->onQuery)($data);

        return $result;
    }

    public function processClean(callable $wrappedFn, array $tags = [])
    {
        $startTime = microtime(true);
        $result = $wrappedFn($tags);

        $data = [
            'start' => $startTime,
            'end' => microtime(true),
            'type' => 'delete',
            'identifier' => empty($tags) ? '*' : implode(',', $tags),
            'data' => [
                'tags' => empty($tags) ? '*' : $tags,
                'time' => $startTime
            ]
        ];

        ($this->onQuery)($data);

        return $result;
    }
}
