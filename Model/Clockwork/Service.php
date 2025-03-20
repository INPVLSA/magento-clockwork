<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork;

use Clockwork\Support\Vanilla\Clockwork;
use Magento\Framework\App\RequestInterface;

class Service
{
    public static bool $enabled = true;

    public function initialize(RequestInterface $request): void
    {
        $this->validateRequest($request);

        if (!Service::$enabled) {
            return;
        }
        Clockwork::init(['storage_files_path' => BP . '/var/clockwork', 'enable' => true]);

        $authenticator = $this->getInstance()->getClockwork()->authenticator();
        $authHeader = $request->getHeader('X-Clockwork-Auth');
        $authenticated = $authenticator->check($authHeader);

        if ($authenticated !== true) {

            return;
        }
    }

    public function getInstance(): Clockwork
    {
        return Clockwork::instance();
    }

    protected function validateRequest(RequestInterface $request): void
    {
        if (str_starts_with($request->getPathInfo(), '/clockwork_static')
            || str_starts_with($request->getPathInfo(), '/clockwork')
            || str_starts_with($request->getPathInfo(), '/__clockwork')
        ) {
            $this->disable();
        }
    }

    public function sendHeaders(): void
    {
        if (!Service::$enabled) {
            return;
        }
        $this->getInstance()->sendHeaders();
    }

    public function disable(): void
    {
        Service::$enabled = false;
    }

    public function getStatus(): bool
    {
        return Service::$enabled && Clockwork::instance();
    }
}
