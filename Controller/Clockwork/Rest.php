<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller\Clockwork;

use Clockwork\Storage\Search;
use Clockwork\Storage\Storage;
use Inpvlsa\Clockwork\Service\Clockwork\Authenticator;
use Inpvlsa\Clockwork\Service\Clockwork\Service;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpOptionsActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\HttpFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Rest implements HttpGetActionInterface, HttpOptionsActionInterface
{
    /**
     * @var Http
     */
    protected RequestInterface $request;
    protected HttpFactory $responseFactory;
    protected SerializerInterface $serializer;
    protected Authenticator $authenticator;
    protected Service $clockworkService;

    public function __construct(
        RequestInterface $request,
        HttpFactory $responseFactory,
        SerializerInterface $serializer,
        Authenticator $authenticator,
        Service $clockworkService
    ) {
        $this->clockworkService = $clockworkService;
        $this->authenticator = $authenticator;
        $this->serializer = $serializer;
        $this->responseFactory = $responseFactory;
        $this->request = $request;
    }

    public function execute(): ResponseInterface
    {
        $this->prepareRequest();
        $response = $this->responseFactory->create();

        $this->clockworkService->initialize();

        $clockwork = $this->clockworkService->getInstance();
        $authenticated = $this->authenticator->attemptRead();

        if ($authenticated !== true) {
            $response->setBody(
                $this->serializer->serialize(['message' => $authenticated, 'requires' => $this->authenticator->requires()])
            );
            $response->setStatusCode(403);

            return $response;
        }

        // Processing request
        $id = $this->request->getParam('id');
        $direction = $this->request->getParam('direction');
        $count = $this->request->getParam('count');

        /** @var Storage $storage */
        $storage = $clockwork->getClockwork()->storage();

        if ($direction == 'previous') {
            $data = $storage->previous($id, $count, Search::fromRequest($this->request->getParams()));
        } elseif ($direction == 'next') {
            $data = $storage->next($id, $count, Search::fromRequest($this->request->getParams()));
        } elseif ($id == 'latest') {
            $data = $storage->latest(Search::fromRequest($this->request->getParams()));
        } else {
            $data = $storage->find($id);
        }

        if (is_array($data)) {
            $data = array_map(function ($request) { return $request->toArray(); }, $data);
        } else {
            try {
                $data = $data->toArray();
            } catch (\Error $e) {
                $data = [];
            }
        }

        $response = $this->responseFactory->create();
        $response->setBody($this->serializer->serialize($data));

        // Uncomment on Client App development
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Headers', 'x-clockwork-auth');
        $response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $response;
    }

    protected function prepareRequest(): void
    {
        $path = $this->request->getPathInfo();
        $path = trim($path, '/');

        $parts = explode('/', $path);

        if (isset($parts[1])) {
            $this->request->setParam('id', $parts[1]);
        }

        if (isset($parts[2])) {
            $this->request->setParam('direction', $parts[2]);
        }

        if (isset($parts[3])) {
            $this->request->setParam('count', $parts[3]);
        }
    }
}
