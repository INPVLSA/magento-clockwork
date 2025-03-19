<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork;

use Clockwork\Support\Vanilla\Clockwork;
use Magento\Framework\App\RequestInterface;

class Service
{
    protected Clockwork $instance;
    protected bool $enabled = true;

    public function initialize(RequestInterface $request): void
    {
        $this->validateRequest($request);

        if (!$this->enabled) {
            return;
        }
        $this->instance = new Clockwork(['storage_files_path' => BP . '/var/clockwork', 'enable' => true]);

        $authenticator = $this->instance->getClockwork()->authenticator();
        $authHeader = $request->getHeader('X-Clockwork-Auth');
        $authenticated = $authenticator->check($authHeader);

        if ($authenticated !== true) {

            return;
        }

        $this->instance->requestProcessed();
    }

    protected function validateRequest(RequestInterface $request): void
    {
        if (str_starts_with($request->getPathInfo(), '/clockwork_static')
            || str_starts_with($request->getPathInfo(), '/clockwork')
        ) {
            $this->disable();
        }
    }

    public function sendHeaders(): void
    {
        if (!$this->enabled) {
            return;
        }
        $this->instance->sendHeaders();
    }

    public function disable(): void
    {
        $this->enabled = false;
    }
}
