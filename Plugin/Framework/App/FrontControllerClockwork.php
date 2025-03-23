<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Plugin\Framework\App;

use Inpvlsa\Clockwork\Model\Clockwork\Service;
use Inpvlsa\Clockwork\Model\Profiler\ClockworkProfilerDriver;
use Magento\Framework\App\FrontController;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Profiler;

class FrontControllerClockwork
{
    protected Service $clockworkService;
    protected ClockworkProfilerDriver $driver;

    public function __construct(
        Service $clockworkService,
        ClockworkProfilerDriver $driver
    ) {
        $this->driver = $driver;
        $this->clockworkService = $clockworkService;
    }

    public function beforeDispatch(FrontController $subject, RequestInterface $request): void
    {
        $this->clockworkService->initializeForTracking($request);

        if ($this->clockworkService->getStatus()) {
            Profiler::add($this->driver);
        }
    }
}
