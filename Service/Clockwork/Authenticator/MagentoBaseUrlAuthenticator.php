<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Service\Clockwork\Authenticator;

class MagentoBaseUrlAuthenticator implements ClockworkAuthenticatorInterface
{
    private const ALLOWED_HOSTS = [
        'host_suffix' => [
            '.local',
            '.test',
            '.wip',
            '.loc',
            '.docker'
        ]
    ];

    public function attempt(): bool
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
}
