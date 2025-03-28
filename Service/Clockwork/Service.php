<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Service\Clockwork;

use Clockwork\Support\Vanilla\Clockwork;
use Inpvlsa\Clockwork\Model\Clockwork\DataSource\MagentoRequestDataSource;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;

class Service
{
    public static bool $enabled = true;
    protected Authenticator $authenticator;
    protected array $dataSources = [];
    protected ConfigBuilder $clockworkConfigBuilder;

    public function __construct(
        Authenticator $authenticator,
        ConfigBuilder $clockworkConfigBuilder,
        array $dataSources = []
    ) {
        $this->dataSources = $dataSources;
        $this->authenticator = $authenticator;
        $this->clockworkConfigBuilder = $clockworkConfigBuilder;
    }

    public function initializeForTracking(RequestInterface $request): void
    {
        $this->validateRequest($request);

        if (!Service::$enabled || !$request instanceof Http || !$this->authenticator->attemptWrite()) {
            return;
        }
        $this->initialize();

        foreach ($this->dataSources as $dataSource) {
            $this->getInstance()->getClockwork()->addDataSource($dataSource);

            if ($dataSource instanceof MagentoRequestDataSource) {
                $dataSource->earlyRegister($request);
            }
        }
    }

    public function initialize(): void
    {
        $config = $this->clockworkConfigBuilder->getClockworkConfig();
        Clockwork::init($config);
    }

    public function getInstance(): Clockwork
    {
        return Clockwork::instance();
    }

    /**
     * @param Http $request
     *
     * @return void
     */
    protected function validateRequest(RequestInterface $request): void
    {
        foreach (['/clockwork_static', '/clockwork', '/__clockwork'] as $beginsWith) {
            if (strpos($request->getPathInfo(), $beginsWith) === 0) {
                $this->disable();

                return;
            }
        }

        if (strpos($request->getPathInfo(), 'favicon.ico') !== false) {
            $this->disable();
        }
    }

    public function finish(): void
    {
        if (!Service::$enabled) {
            return;
        }
        $this->getInstance()->requestProcessed();
        $this->getInstance()->sendHeaders();
    }

    public function disable(): void
    {
        Service::$enabled = false;
    }

    public function getStatus(): bool
    {
        return Service::$enabled && Clockwork::instance() && $this->authenticator->attemptWrite();
    }
}
