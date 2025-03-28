<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Service\Clockwork;

use Clockwork\Authentication\AuthenticatorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\MaintenanceMode;
use Magento\Store\Model\StoreManagerInterface;

class Authenticator implements AuthenticatorInterface
{
    private const ALLOWED_HOSTS = [
        'host' => [
            'localhost'
        ],
        'host_suffix' => [
            '.local',
            '.test',
            '.wip',
            '.loc',
            '.docker'
        ],
        'ips_prefix' => [
            '192.168.',
            '10.',
            '172.16',
            '172.17',
            '172.18',
            '172.19',
            '172.20',
            '172.21',
            '172.22',
            '172.23',
            '172.24',
            '172.25',
            '172.26',
            '172.27',
            '172.28',
            '172.29',
            '172.30',
            '172.31',
            '127.'
        ]
    ];
    protected StoreManagerInterface $storeManager;
    protected MaintenanceMode $maintenanceMode;
    protected ScopeConfigInterface $scopeConfig;

    public function __construct(
        StoreManagerInterface $storeManager,
        MaintenanceMode $maintenanceMode,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->maintenanceMode = $maintenanceMode;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function attemptWrite(): bool
    {
        return $this->scopeConfig->getValue('dev/clockwork/enabled') && $this->attemptRead();
    }

    public function attemptRead(): bool
    {
        return $this->isAllowByMagentoUrl() || $this->isAllowByIp();
    }

    public function attempt(array $credentials): bool
    {
        return $this->attemptWrite();
    }

    protected function isAllowByIp(): bool
    {
        $remoteAddress = $_SERVER['REMOTE_ADDR'];

        if (in_array($remoteAddress, self::ALLOWED_HOSTS['host'], true)) {
            return true;
        }

        foreach (self::ALLOWED_HOSTS['ips_prefix'] as $ips_prefix) {
            if (str_starts_with($remoteAddress, $ips_prefix)) {
                return true;
            }
        }

        if (in_array($remoteAddress, $this->maintenanceMode->getAddressInfo())) {
            return true;
        }

        return false;
    }

    protected function isAllowByMagentoUrl(): bool
    {
        $store = $this->storeManager->getStore();
        $baseUrl = $store->getBaseUrl();
        $baseUrl = parse_url($baseUrl, PHP_URL_HOST);

        if (in_array($baseUrl, self::ALLOWED_HOSTS['host'], true)) {
            return true;
        }

        foreach (self::ALLOWED_HOSTS['host_suffix'] as $host_suffix) {
            if (str_ends_with($baseUrl, $host_suffix)) {
                return true;
            }
        }

        return false;
    }

    public function check($token): bool
    {
        return false;
    }

    public function requires(): array
    {
        return [];
    }
}
