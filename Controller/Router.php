<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller;

use Inpvlsa\Clockwork\Controller\Clockwork\Rest;
use Inpvlsa\Clockwork\Controller\Clockwork\Web;
use Inpvlsa\Clockwork\Service\Clockwork\Authenticator;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;

class Router implements RouterInterface
{
    protected ActionFactory $actionFactory;
    protected Authenticator $clockworkAuthenticator;

    public function __construct(
        ActionFactory $actionFactory,
        Authenticator $clockworkAuthenticator
    ) {
        $this->clockworkAuthenticator = $clockworkAuthenticator;
        $this->actionFactory = $actionFactory;
    }

    public function match(RequestInterface $request): ?ActionInterface
    {
        $result = null;

        if ($request instanceof Http && $this->clockworkAuthenticator->attemptRead()) {
            $path = trim($request->getPathInfo(), '/');

            if (str_starts_with($path, 'clockwork')) {
                $result = $this->actionFactory->create(Web::class);
            } elseif (str_starts_with($path, '__clockwork')) {
                $result = $this->actionFactory->create(Rest::class);
            }
        }

        return $result;
    }
}
