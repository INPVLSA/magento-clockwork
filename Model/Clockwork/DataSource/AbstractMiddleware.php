<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

class AbstractMiddleware
{
    /**
     * @var callable
     */
    protected $onQuery;

    public function __construct(
        callable $onQuery
    ) {
        $this->onQuery = $onQuery;
    }
}
