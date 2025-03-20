<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork;

use Clockwork\Request\UserDataItem;
use Clockwork\Support\Vanilla\Clockwork;
use Magento\Framework\App\RequestInterface;

class Service
{
    public static bool $enabled = true;

    protected ?array $requestDetails = null;

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
        $this->requestDetails = [
            'PathInfo' => $request->getPathInfo(),
            'IsSecure' => $request->isSecure(),
            'RouteName' => $request->getRouteName(),
            'Method' => $request->getMethod(),
            'RequestUri' => $request->getRequestUri()
        ];
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

    public function finish(): void
    {
        if (!Service::$enabled) {
            return;
        }
        $this->collectAdditionalTabsData();
        $this->getInstance()->requestProcessed();
        $this->getInstance()->sendHeaders();
    }

    protected function collectAdditionalTabsData(): void
    {
        $data = [];

        foreach ($this->requestDetails as $key => $value) {
            $data[] = ['Type' => $key, 'Value' => $value];
        }
        Clockwork::instance()->userData('request')->title('Request Details')->table('Request Details', $data);
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
