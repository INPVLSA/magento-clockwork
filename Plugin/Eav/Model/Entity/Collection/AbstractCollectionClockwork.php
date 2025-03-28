<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Plugin\Eav\Model\Entity\Collection;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\MagentoCollectionDataSource;
use Magento\Framework\Data\Collection;

class AbstractCollectionClockwork
{
    protected MagentoCollectionDataSource $magentoCollectionDataSource;

    public function __construct(
        MagentoCollectionDataSource $magentoCollectionDataSource
    ) {
        $this->magentoCollectionDataSource = $magentoCollectionDataSource;
    }

    public function aroundLoad(Collection $subject, callable $proceed, $printQuery = false, $logQuery = false)
    {
        if ($subject->isLoaded()) {
            return $proceed($printQuery, $logQuery);
        }

        return $this->magentoCollectionDataSource->middleware()->process($subject, $proceed, $printQuery, $logQuery);
    }
}
