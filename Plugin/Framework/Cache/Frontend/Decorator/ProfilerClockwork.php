<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Plugin\Framework\Cache\Frontend\Decorator;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\CacheDataSource;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Cache\Frontend\Decorator\Profiler;

class ProfilerClockwork
{
    public function __construct(
        protected CacheDataSource $cacheDataSource
    ) {}

    public function aroundSave(CacheInterface $subject, callable $proceed, $data, $identifier, $tags = [], $lifeTime = null)
    {
        return $this->cacheDataSource->middleware()->processSave($proceed, $data, $identifier, $tags, $lifeTime);
    }

    public function aroundLoad(CacheInterface $subject, callable $proceed, $identifier)
    {
        return $this->cacheDataSource->middleware()->processLoad($proceed, $identifier);
    }
}
