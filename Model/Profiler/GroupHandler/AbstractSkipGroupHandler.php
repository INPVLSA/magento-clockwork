<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

/**
 * This class used to skip handling profiler records if separate DataSource used to collect it.
 */
abstract class AbstractSkipGroupHandler extends AbstractGroupHandler
{
    protected function prepareDataOnStart(): void
    {
    }

    protected function handleDataOnStop(): void
    {
    }
}
