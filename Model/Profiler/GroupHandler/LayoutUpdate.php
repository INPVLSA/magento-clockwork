<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Profiler\GroupHandler;

class LayoutUpdate extends Layout
{
    protected const REGEX = '/.*CONTROLLER_ACTION:([\w_]*).*layout_package_update:(..*)$/';
}
