<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Service\Clockwork\Authenticator;

use Magento\Framework\App\MaintenanceMode;

class IpWhitelistAuthenticator implements ClockworkAuthenticatorInterface
{
    private const ALLOWED_IPS = [
        'host' => [
            'localhost'
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

    private MaintenanceMode $maintenanceMode;

    public function __construct(
        MaintenanceMode $maintenanceMode
    ) {
        $this->maintenanceMode = $maintenanceMode;
    }

    public function attempt(): bool
    {
        $remoteAddress = $_SERVER['REMOTE_ADDR'];

        if (in_array($remoteAddress, self::ALLOWED_IPS['host'], true)) {
            return true;
        }

        foreach (self::ALLOWED_IPS['ips_prefix'] as $ips_prefix) {
            if (str_starts_with($remoteAddress, $ips_prefix)) {
                return true;
            }
        }

        if (in_array($remoteAddress, $this->maintenanceMode->getAddressInfo())) {
            return true;
        }

        return false;
    }
}
