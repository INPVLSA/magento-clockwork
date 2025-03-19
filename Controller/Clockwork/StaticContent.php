<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller\Clockwork;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\FileFactory;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\ResponseInterface;

class StaticContent implements HttpGetActionInterface
{
    public function __construct(
        protected RequestInterface $request,
        protected ResponseFactory $responseFactory,
        protected FileFactory $fileFactory,
        protected string $packagePath = BP . '/vendor/itsgoingd/clockwork/Clockwork/Web/public/'
    ) {}

    public function execute(): ResponseInterface
    {
        $path = $this->request->getPathInfo();
        $path = str_replace('clockwork_static/', '', $path);
        $path = trim($path, '/');

        $file = $this->packagePath . $path;

        if (!file_exists($file)) {

            return $this->responseFactory->create()->setHttpResponseCode(404);
        }

        return $this->fileFactory->create(['options' => ['filePath' => $file]]);
    }
}
