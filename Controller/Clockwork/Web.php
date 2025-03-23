<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller\Clockwork;

use Clockwork\Support\Symfony\ClockworkSupport;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\HttpFactory;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\Filesystem\Driver\File;

class Web implements HttpGetActionInterface
{
    public function __construct(
        protected ClockworkSupport $clockworkSupport,
        protected HttpFactory $responseFactory,
        protected Repository $assetRepository,
        protected File $fileDriver
    ) {}

    public function execute(): Http
    {
        $htmlPath = __DIR__ . '/../../view/frontend/web/clockwork-app/index.html';
        $html = $this->fileDriver->fileGetContents($htmlPath);
        $html = $this->prepareHtml($html);

        $response = $this->responseFactory->create();
        $response->setBody($html);

        return $response;
    }

    protected function prepareHtml(string $html): string
    {
        $assetUrl = $this->assetRepository->getUrl('Inpvlsa_Clockwork::clockwork-app/assets');

        $html = str_replace('src="assets', 'src="' . $assetUrl, $html);
        $html = str_replace('href="assets', 'href="' . $assetUrl, $html);

        return str_replace('href="img', 'href="clockwork_static/img', $html);
    }
}
