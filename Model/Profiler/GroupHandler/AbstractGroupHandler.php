<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

abstract class AbstractGroupHandler
{
    protected string $timerId;

    /**
     * @var array{'tags': array, 'data': array}
     */
    protected array $data;

    public function __construct(string $timerId, array $tags)
    {
        $this->timerId = $timerId;
        $this->data = ['tags' => $tags, 'data' => []];
    }

    abstract public static function canHandle(array $tags): bool;

    public function start(): void
    {
        $this->prepareDataOnStart();
    }

    abstract protected function prepareDataOnStart(): void;

    public function stop(): void
    {
        $this->handleDataOnStop();
    }

    abstract protected function handleDataOnStop(): void;
}
