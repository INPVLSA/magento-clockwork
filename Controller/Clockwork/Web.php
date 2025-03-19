<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller\Clockwork;

use Clockwork\Support\Symfony\ClockworkSupport;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\HttpFactory;

class Web implements HttpGetActionInterface
{
    public function __construct(
        protected ClockworkSupport $clockworkSupport,
        protected HttpFactory $responseFactory
    ) {}

    public function execute(): Http
    {
        $binFile = $this->clockworkSupport->getWebAsset('index.html');
        $response = $this->responseFactory->create();

        $html = $binFile->getFile()->getContent();
        $html = $this->prepareHtml($html);

        $response->setBody($html);

        return $response;
    }

    protected function prepareHtml(string $html): string
    {
        $html = str_replace('src="assets', 'src="/clockwork_static/assets', $html);
        $html = str_replace('href="assets', 'href="/clockwork_static/assets', $html);

        return str_replace('href="img', 'href="clockwork_static/img', $html);
    }
}
