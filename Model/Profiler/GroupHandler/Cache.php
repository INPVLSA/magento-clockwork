<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

class Cache extends AbstractSkipGroupHandler
{
    public static function canHandle(string $timerId, array $tags): bool
    {
        return isset($tags['group']) && $tags['group'] === 'cache';
    }
}
