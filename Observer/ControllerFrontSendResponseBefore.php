<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Observer;

use Inpvlsa\Clockwork\Model\Clockwork\Service;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ControllerFrontSendResponseBefore implements ObserverInterface
{
    public function __construct(
        protected Service $clockworkService
    ) {}

    public function execute(Observer $observer): void
    {
        if ($this->clockworkService->getStatus()) {
            $this->clockworkService->finish();
        }
    }
}
