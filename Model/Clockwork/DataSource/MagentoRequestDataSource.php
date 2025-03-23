<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Model\Clockwork\DataSource;

use Clockwork\DataSource\DataSource;
use Clockwork\Request\Request;
use Clockwork\Support\Vanilla\Clockwork;
use Magento\Framework\App\Request\Http;

class MagentoRequestDataSource extends DataSource
{
    protected ?array $requestDetails = null;

    public function resolve(Request $request): Request
    {
        $data = [];

        foreach ($this->requestDetails as $key => $value) {
            $data[] = ['Type' => $key, 'Value' => $value];
        }
        Clockwork::instance()->userData('request')->title('Request Details')->table('Request Details', $data);

        return $request;
    }

    public function earlyRegister(Http $request): void
    {
        $this->requestDetails = [
            'PathInfo' => $request->getPathInfo(),
            'IsSecure' => $request->isSecure(),
            'RouteName' => $request->getRouteName(),
            'Method' => $request->getMethod(),
            'RequestUri' => $request->getRequestUri()
        ];
    }

    public function addData(string $key, $value): void
    {
        $this->requestDetails[$key] = $value;
    }
}
