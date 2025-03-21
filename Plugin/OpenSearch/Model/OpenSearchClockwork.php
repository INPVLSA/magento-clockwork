<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Plugin\OpenSearch\Model;

use Inpvlsa\Clockwork\Model\Clockwork\DataSource\OpenSearchDataSource;
use Inpvlsa\Clockwork\Plugin\Elasticsearch7\Model\Client\ElasticsearchClockwork;

class OpenSearchClockwork extends ElasticsearchClockwork
{
    public function __construct(OpenSearchDataSource $dataSource)
    {
        parent::__construct($dataSource);
    }
}
