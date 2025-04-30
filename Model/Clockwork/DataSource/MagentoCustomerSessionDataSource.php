<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;
use Magento\Customer\Model\Session;

class MagentoCustomerSessionDataSource extends DataSource
{
    protected ?array $customerData = null;

    public function resolve(Request $request): Request
    {
        if ($this->customerData !== null) {
            $request->authenticatedUser = $this->customerData;
        }

        return $request;
    }

    public function handle(Session $session): void
    {
        if ($session->isLoggedIn()) {
            $customer = $session->getCustomer();

            $this->customerData = [
                'id' => $customer->getId(),
                'username' => $customer->getEmail(),
                'email' => $customer->getEmail(),
                'name' => $customer->getName(),
            ];
        }
    }
}
