<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller\Clockwork;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\FileFactory;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Asset\Repository;

class StaticContent implements HttpGetActionInterface
{
    public function __construct(
        protected RequestInterface $request,
        protected ResponseFactory $responseFactory,
        protected FileFactory $fileFactory,
        protected Repository $assetRepository
    ) {}

    public function execute(): ResponseInterface
    {
        $response = $this->responseFactory->create();

        $path = $this->request->getPathInfo();
        $path = str_replace('clockwork_static/', '', $path);
        $path = trim($path, '/');

        $filename = basename($path);

        $url = $this->assetRepository->getUrl('Inpvlsa_Clockwork::clockwork-app/assets');

//        $file = $this->packagePath . $path;

//        if (!file_exists($file)) {
//
//            return $this->responseFactory->create()->setHttpResponseCode(404);
//        }

//        return $this->fileFactory->create(['options' => ['filePath' => $file]]);

        return $response;
    }
}
