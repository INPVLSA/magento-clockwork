<?php declare(strict_types=1);

namespace Inpvlsa\Clockwork\Controller\Clockwork;

use Clockwork\Storage\Search;
use Clockwork\Support\Vanilla\Clockwork;
use Inpvlsa\Clockwork\Model\Clockwork\ClockworkAuthenticator;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\HttpFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Rest implements HttpGetActionInterface
{
    public function __construct(
        protected RequestInterface $request,
        protected HttpFactory $responseFactory,
        protected SerializerInterface $serializer,
        protected ClockworkAuthenticator $authenticator
    ) {}

    public function execute(): ResponseInterface
    {
        $this->prepareRequest();
        $response = $this->responseFactory->create();

        $clockwork = new Clockwork(['storage_files_path' => BP . '/var/clockwork']);

        $authenticated = $this->authenticator->attempt([]);

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

        // TODO:: On file storage check if the file & dir exists (and create)
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

        $data = is_array($data)
            ? array_map(function ($request) { return $request->toArray(); }, $data)
            : $data->toArray();

        $response = $this->responseFactory->create();
        $response->setBody($this->serializer->serialize($data));

        return $response;
    }

    protected function prepareRequest(): void
    {
        /** @param $this->request */
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
