<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Service\Clockwork;

use Clockwork\Authentication\AuthenticatorInterface;
use Inpvlsa\Clockwork\Service\Clockwork\Authenticator\ClockworkAuthenticatorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\MaintenanceMode;
use Magento\Store\Model\StoreManagerInterface;

class Authenticator implements AuthenticatorInterface
{
    protected StoreManagerInterface $storeManager;
    protected MaintenanceMode $maintenanceMode;
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var ClockworkAuthenticatorInterface[]
     */
    private array $authenticators;

    public function __construct(
        StoreManagerInterface $storeManager,
        MaintenanceMode $maintenanceMode,
        ScopeConfigInterface $scopeConfig,
        array $authenticators = []
    ) {
        $this->maintenanceMode = $maintenanceMode;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->authenticators = $authenticators;
    }

    public function attemptWrite(): bool
    {
        return $this->scopeConfig->getValue('dev/clockwork/enabled') && $this->attemptRead();
    }

    public function attemptRead(): bool
    {
        foreach ($this->authenticators as $authenticator) {
            if ($authenticator->attempt()) {

                return true;
            }
        }

        return false;
    }

    public function attempt(array $credentials): bool
    {
        return $this->attemptWrite();
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
