<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

class EavIgnore extends AbstractSkipGroupHandler
{
    protected const string REGEX = '/.*[(EAV)(REWRITE)]:(.*)$/';

    public static function canHandle(string $timerId, array $tags): bool
    {
        return preg_match(static::REGEX, $timerId) > 0;
    }
}
