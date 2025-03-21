<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller;

use Inpvlsa\Clockwork\Controller\Clockwork\Rest;
use Inpvlsa\Clockwork\Controller\Clockwork\StaticContent;
use Inpvlsa\Clockwork\Controller\Clockwork\Web;
use Inpvlsa\Clockwork\Model\Clockwork\ClockworkAuthenticator;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;

class Router implements RouterInterface
{
    public function __construct(
        protected ActionFactory $actionFactory,
        protected ClockworkAuthenticator $clockworkAuthenticator
    ) {}

    public function match(RequestInterface $request): ?ActionInterface
    {
        $result = null;

        if ($request instanceof Http && $this->clockworkAuthenticator->attempt([])) {
            $path = trim($request->getPathInfo(), '/');

            if (str_starts_with($path, 'clockwork_static')) {
                $result = $this->actionFactory->create(StaticContent::class);
            } elseif (str_starts_with($path, 'clockwork')) {
                $result = $this->actionFactory->create(Web::class);
            } elseif (str_starts_with($path, '__clockwork')) {
                $result = $this->actionFactory->create(Rest::class);
            }
        }

        return $result;
    }
}
