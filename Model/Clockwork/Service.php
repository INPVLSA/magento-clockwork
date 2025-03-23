<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork;

use Clockwork\Support\Vanilla\Clockwork;
use Inpvlsa\Clockwork\Model\Clockwork\DataSource\MagentoRequestDataSource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

class Service
{
    public static bool $enabled = true;
    protected ClockworkAuthenticator $authenticator;
    protected ScopeConfigInterface $scopeConfig;
    protected DeploymentConfig $deploymentConfig;
    protected LoggerInterface $logger;
    protected Filesystem\Proxy $filesystem;
    protected File\Proxy $fileDriver;
    protected array $dataSources = [];

    public function __construct(
        ClockworkAuthenticator $authenticator,
        ScopeConfigInterface $scopeConfig,
        DeploymentConfig $deploymentConfig,
        LoggerInterface $logger,
        Filesystem\Proxy $filesystem,
        File\Proxy $fileDriver,
        array $dataSources = []
    ) {
        $this->dataSources = $dataSources;
        $this->fileDriver = $fileDriver;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->deploymentConfig = $deploymentConfig;
        $this->scopeConfig = $scopeConfig;
        $this->authenticator = $authenticator;
    }

    public function initializeForTracking(RequestInterface $request): void
    {
        $this->validateRequest($request);

        if (!Service::$enabled || !$request instanceof Http || !$this->authenticator->attempt([])) {
            return;
        }
        $this->initClockwork();

        foreach ($this->dataSources as $dataSource) {
            $this->getInstance()->getClockwork()->addDataSource($dataSource);

            if ($dataSource instanceof MagentoRequestDataSource) {
                $dataSource->earlyRegister($request);
            }
        }
    }

    public function initClockwork(): void
    {
        $defaultConfig = [
            'storage' => 'files',
            'storage_files_path' => BP . '/var/clockwork'
        ];

        try {
            $storageConfigValue = $this->scopeConfig->getValue('dev/clockwork/data_storage');

            if ($storageConfigValue === 'redis') {
                $config = [
                    'storage' => 'redis',
                    'storage_redis' => $this->getRedisConfig()
                ];
            } else {
                $config = $defaultConfig;
            }
        } catch (\Throwable $e) {
            $config = $defaultConfig;
        }

        if ($config['storage'] !== 'files' && $config['storage_' . $config['storage']] === null) {
            $this->logger->warning('Clockwork: Redis configuration is missing, falling back to files storage');
            $config = $defaultConfig;
        }

        if ($config['storage'] === 'files') {
            $this->checkDirExistsOrCreate();
        }
        $config['enable'] = true;

        Clockwork::init($config);
    }

    protected function checkDirExistsOrCreate(): void
    {
        $varDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $clockworkPath = $varDirectory->getAbsolutePath('clockwork');

        if (!$this->fileDriver->isDirectory($clockworkPath)) {
            $varDirectory->create('clockwork');
        }
    }

    protected function getRedisConfig(): ?array
    {
        $host = $this->deploymentConfig->get('session/redis/host');

        if ($host === null) {
            return null;
        }

        return [
            'host' => $host,
            'port' => $this->deploymentConfig->get('session/redis/port'),
            'database' => $this->deploymentConfig->get('session/redis/database'),
            'pass' => $this->deploymentConfig->get('session/redis/password'),
        ];
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
        $this->getInstance()->requestProcessed();
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
