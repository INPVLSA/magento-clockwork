<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller\Clockwork;

use Clockwork\Support\Symfony\ClockworkSupport;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\HttpFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

class Web implements HttpGetActionInterface
{
    protected ClockworkSupport $clockworkSupport;
    protected HttpFactory $responseFactory;
    protected Repository $assetRepository;
    protected LoggerInterface $logger;
    protected File $fileDriver;

    public function __construct(
        ClockworkSupport $clockworkSupport,
        HttpFactory $responseFactory,
        Repository $assetRepository,
        LoggerInterface $logger,
        File $fileDriver
    ) {
        $this->fileDriver = $fileDriver;
        $this->logger = $logger;
        $this->assetRepository = $assetRepository;
        $this->responseFactory = $responseFactory;
        $this->clockworkSupport = $clockworkSupport;
    }

    /**
     * @throws FileSystemException|\Exception
     */
    public function execute(): Http
    {
        $htmlPath = __DIR__ . '/../../view/frontend/web/clockwork-app/index.html';
        try {
            $html = $this->fileDriver->fileGetContents($htmlPath);
        } catch (\Exception $e) {
            $this->logger->warning('Clockwork: Could not load Clockwork app HTML file');
            $this->logger->warning($e->getMessage());

            throw $e;
        }
        $html = $this->prepareHtml($html);

        $response = $this->responseFactory->create();
        $response->setBody($html);

        return $response;
    }

    protected function prepareHtml(string $html): string
    {
        $assetUrl = $this->assetRepository->getUrl('Inpvlsa_Clockwork::clockwork-app/assets');
        $html = str_replace('src="assets', 'src="' . $assetUrl, $html);

        return str_replace('href="assets', 'href="' . $assetUrl, $html);
    }
}
