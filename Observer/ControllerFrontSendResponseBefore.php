<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Observer;

use Inpvlsa\Clockwork\Service\Clockwork\Service;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ControllerFrontSendResponseBefore implements ObserverInterface
{
    protected Service $clockworkService;

    public function __construct(
        Service $clockworkService
    ) {
        $this->clockworkService = $clockworkService;
    }

    public function execute(Observer $observer): void
    {
        if ($this->clockworkService->getStatus()) {
            $this->clockworkService->finish();
        }
    }
}
