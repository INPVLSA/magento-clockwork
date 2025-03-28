<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork;

use Clockwork\Request\Timeline\Event;
use Magento\Framework\DataObject;

/**
 * @see Event
 */
class FlexEvent extends DataObject
{
    public string $name = '';
    public float $start = 0;
    public float $end = 0;
    public string $description = '';

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->name = $data['name'] ?? '';
        $this->start = $data['start'] ?? 0;
        $this->end = $data['end'] ?? 0;
        $this->description = $data['name'] ?? '';
    }

    public function setTags(array $tags): void
    {
        $this->_data['data']['tags'] = $tags;
    }

    public static function fromEvent(Event $event): self
    {
        return new static($event->toArray());
    }

    public function duration(): float
    {
        return $this->end - $this->start;
    }

    public function finalize($start = null, $end = null): void
    {
        $end = $end ?: microtime(true);

        $this->start = $this->start ?: $start;
        $this->end = $this->end ?: $end;

        if ($this->start < $start) $this->start = $start;
        if ($this->end > $end) $this->end = $end;
    }
}
