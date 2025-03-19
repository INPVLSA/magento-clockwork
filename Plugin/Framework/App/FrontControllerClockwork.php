<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Plugin\Framework\App;

use Inpvlsa\Clockwork\Model\Clockwork\Service;
use Magento\Framework\App\FrontController;
use Magento\Framework\App\RequestInterface;

class FrontControllerClockwork
{
    public function __construct(
        protected Service $clockworkService
    ) {}

    public function beforeDispatch(FrontController $subject, RequestInterface $request): void
    {
        $this->clockworkService->initialize($request);
    }

    public function afterDispatch(FrontController $subject, $result)
    {
        $this->clockworkService->sendHeaders();

        return $result;
    }
}
