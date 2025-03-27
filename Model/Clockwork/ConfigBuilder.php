<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork;

use Clockwork\Storage\RedisStorage;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

class ConfigBuilder
{
    protected const CONFIG_SRC_DEPLOY = 0;
    protected CONST CONFIG_SRC_SCOPE = 1;

    protected const REDIS_PATHS_BY_TYPE = [
        'session' => [
            'config' => self::CONFIG_SRC_DEPLOY,
            'host' => 'session/redis/host',
            'port' => 'session/redis/port',
            'database' => 'session/redis/database',
            'password' => 'session/redis/password'
        ],
        'cache' => [
            'config' => self::CONFIG_SRC_DEPLOY,
            'host' => 'cache/frontend/page_cache/backend_options/server',
            'port' => 'cache/frontend/page_cache/backend_options/port',
            'database' => 'cache/frontend/page_cache/backend_options/database',
            'password' => 'cache/frontend/page_cache/backend_options/password'
        ],
        'custom' => [
            'config' => self::CONFIG_SRC_SCOPE,
            'host' => 'dev/clockwork/redis_custom_host',
            'port' => 'dev/clockwork/redis_custom_port',
            'database' => 'dev/clockwork/redis_custom_db',
            'password' => 'dev/clockwork/redis_custom_password'
        ]
    ];

    protected ScopeConfigInterface $scopeConfig;
    private DeploymentConfig $deploymentConfig;
    private Filesystem\Proxy $filesystem;
    private File\Proxy $fileDriver;
    private LoggerInterface $logger;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        DeploymentConfig $deploymentConfig,
        Filesystem\Proxy $filesystem,
        File\Proxy $fileDriver,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->deploymentConfig = $deploymentConfig;
        $this->filesystem = $filesystem;
        $this->fileDriver = $fileDriver;
        $this->logger = $logger;
    }

    public function getClockworkConfig(): array
    {
        try {
            $storageConfigValue = $this->scopeConfig->getValue('dev/clockwork/data_storage');
            $redisConfigSource = $this->scopeConfig->getValue('dev/clockwork/redis_from');

            if ($storageConfigValue === 'redis') {
                $config = [
                    'storage' => 'redis',
                    'storage_redis' => $this->getRedisConfig($redisConfigSource)
                ];
                $this->tryConnectRedis($config['storage_redis']);
            } else {
                $config = $this->getDefaultConfig();
            }
        } catch (\Throwable $e) {
            $this->logger->warning('[Clockwork] Unable to initialize Redis storage. Message: ' . $e->getMessage());

            $config = $this->getDefaultConfig();
        }

        if ($config['storage'] !== 'files' && $config['storage_' . $config['storage']] === null) {
            $this->logger->warning('Clockwork: Redis configuration is missing, falling back to files storage');
            $config = $this->getDefaultConfig();
        }

        try {
            if ($config['storage'] === 'files') {
                $this->checkDirExistsOrCreate();
            }
            $config['enable'] = true;
        } catch (\Exception $e) {
            $this->logger->warning('[Clockwork] Unable to initialize Clockwork (file). Message: ' . $e->getMessage());
            $config['enable'] = false;
        }

        return $config;
    }

    /**
     * Just in case some classes missing in autoload we need to validate this inside try-catch first
     */
    public function tryConnectRedis(array $config): void
    {
        new RedisStorage($config);
    }

    protected function getDefaultConfig(): array
    {
        return [
            'storage' => 'files',
            'storage_files_path' => BP . '/var/clockwork'
        ];
    }

    /**
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function getRedisConfig(?string $type = null): ?array
    {
        if ($type === null) {
            $type = 'session';
        }

        if (!isset(self::REDIS_PATHS_BY_TYPE[$type])) {

            return null;
        }
        $configMap = self::REDIS_PATHS_BY_TYPE[$type];

        if ($configMap['config'] === self::CONFIG_SRC_DEPLOY) {
            $source = $this->deploymentConfig;
            $method = 'get';
        } elseif ($configMap['config'] === self::CONFIG_SRC_SCOPE) {
            $source = $this->scopeConfig;
            $method = 'getValue';
        } else {
            throw new RuntimeException(__('Invalid config source'));
        }
        $host = $source->{$method}($configMap['host']);

        if ($host === null) {
            return null;
        }

        return [
            'host'      => $host,
            'port'      => $source->{$method}($configMap['port']),
            'database'  => $source->{$method}($configMap['database']),
            'pass'      => $source->{$method}($configMap['password']),
        ];
    }

    /**
     * @throws FileSystemException
     */
    protected function checkDirExistsOrCreate(): void
    {
        $varDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $clockworkPath = $varDirectory->getAbsolutePath('clockwork');

        if (!$this->fileDriver->isDirectory($clockworkPath)) {
            $varDirectory->create('clockwork');
        }
    }
}
