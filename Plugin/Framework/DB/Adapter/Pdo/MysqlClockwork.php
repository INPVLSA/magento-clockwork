<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Plugin\Framework\DB\Adapter\Pdo;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\ZendDbDataSource;
use Magento\Framework\DB\Adapter\Pdo\Mysql;

class MysqlClockwork
{
    public function __construct(
        protected ZendDbDataSource $driver
    ) {}

    public function aroundQuery(Mysql $subject, callable $proceed, $sql, $bind = [])
    {
        return $this->driver->middleware()->process($proceed, $sql, $bind);
    }
}
