<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Observer;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\MagentoCustomerSessionDataSource;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerSessionInit implements ObserverInterface
{
    protected MagentoCustomerSessionDataSource $customerSessionDataSource;

    public function __construct(
        MagentoCustomerSessionDataSource $customerSessionDataSource
    ) {
        $this->customerSessionDataSource = $customerSessionDataSource;
    }

    public function execute(Observer $observer): void
    {
        /** @var Session $session */
        $session = $observer->getData('customer_session');
        $this->customerSessionDataSource->handle($session);
    }
}
