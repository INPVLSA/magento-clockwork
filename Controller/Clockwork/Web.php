<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller\Clockwork;

use Clockwork\Support\Symfony\ClockworkSupport;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\HttpFactory;
use Magento\Framework\View\Asset\Repository;

class Web implements HttpGetActionInterface
{
    public function __construct(
        protected ClockworkSupport $clockworkSupport,
        protected HttpFactory $responseFactory,
        protected Repository $assetRepository
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
        $html = str_replace('href="img', 'href="clockwork_static/img', $html);

        return str_replace('</body>',
            '<script src="' . $this->assetRepository->getUrlWithParams('Inpvlsa_Clockwork::js/clockwork-web.js', []) . '"></script></body>',
            $html
        );
    }
}
