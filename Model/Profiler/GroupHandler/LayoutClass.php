<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

class LayoutClass extends Layout
{
    protected const REGEX = '/.*CONTROLLER_ACTION:([\w_]*).*LAYOUT->(..*)$/';
}
