<?php

namespace Inpvlsa\Clockwork\Service\Clockwork\Authenticator;

interface ClockworkAuthenticatorInterface
{
    public function attempt(): bool;
}
