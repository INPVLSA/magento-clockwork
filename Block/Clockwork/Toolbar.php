<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Block\Clockwork;

use Inpvlsa\Clockwork\Service\Clockwork\Authenticator;
use Magento\Framework\View\Element\Template;

class Toolbar extends Template
{
    protected Authenticator $clockworkAuthenticator;

    public function __construct(
        Template\Context $context,
        Authenticator $clockworkAuthenticator,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->clockworkAuthenticator = $clockworkAuthenticator;
    }

    public function canRender(): bool
    {
        // Using write attempt, because if writing disabled no data will be collected
        return $this->clockworkAuthenticator->attemptWrite() && $this->_scopeConfig->getValue('dev/clockwork/toolbar');
    }
}
