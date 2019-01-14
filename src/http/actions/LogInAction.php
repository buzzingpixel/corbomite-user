<?php
declare(strict_types=1);

namespace corbomite\user\http\actions;

use Throwable;
use LogicException;
use corbomite\user\UserApi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use corbomite\requestdatastore\DataStoreInterface;
use corbomite\flashdata\interfaces\FlashDataApiInterface;

class LogInAction
{
    private $userApi;
    private $response;
    private $flashDataApi;
    private $requestDataStore;

    public function __construct(
        UserApi $userApi,
        ResponseInterface $response,
        FlashDataApiInterface $flashDataApi,
        DataStoreInterface $requestDataStore
    ) {
        $this->userApi = $userApi;
        $this->response = $response;
        $this->flashDataApi = $flashDataApi;
        $this->requestDataStore = $requestDataStore;
    }

    public function __invoke(ServerRequestInterface $request): ?ResponseInterface
    {
        $requestMethod = strtolower(
            $request->getServerParams()['REQUEST_METHOD'] ?? 'get'
        );

        if ($requestMethod !== 'post') {
            throw new LogicException('Log In Action requires post request');
        }

        $email = $request->getParsedBody()['email'] ?? '';
        $password = $request->getParsedBody()['password'] ?? '';

        try {
            $this->userApi->logUserIn($email, $password);

            $flashDataModel =$this->flashDataApi->makeFlashDataModel([
                'name' => 'LogInAction'
            ]);

            $flashDataModel->dataItem('success', true);

            $this->flashDataApi->setFlashData($flashDataModel);

            $response = $this->response->withHeader(
                'Location',
                $request->getParsedBody()['redirect'] ?? $request->getUri()->getPath()
            );

            $response = $response->withStatus(303);

            return $response;
        } catch (Throwable $e) {
            $this->requestDataStore->storeItem('LogInAction.hasError', true);
        }

        return null;
    }
}
