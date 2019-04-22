<?php

declare(strict_types=1);

namespace corbomite\user\http\actions;

use corbomite\flashdata\interfaces\FlashDataApiInterface;
use corbomite\requestdatastore\DataStoreInterface;
use corbomite\user\interfaces\UserApiInterface;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use function mb_strtolower;

class LogInAction
{
    /** @var UserApiInterface */
    private $userApi;
    /** @var ResponseInterface */
    private $response;
    /** @var FlashDataApiInterface */
    private $flashDataApi;
    /** @var DataStoreInterface */
    private $requestDataStore;

    public function __construct(
        UserApiInterface $userApi,
        ResponseInterface $response,
        FlashDataApiInterface $flashDataApi,
        DataStoreInterface $requestDataStore
    ) {
        $this->userApi          = $userApi;
        $this->response         = $response;
        $this->flashDataApi     = $flashDataApi;
        $this->requestDataStore = $requestDataStore;
    }

    public function __invoke(ServerRequestInterface $request) : ?ResponseInterface
    {
        $requestMethod = mb_strtolower(
            $request->getServerParams()['REQUEST_METHOD'] ?? 'get'
        );

        if ($requestMethod !== 'post') {
            throw new LogicException('Log In Action requires post request');
        }

        $email    = $request->getParsedBody()['email'] ?? '';
        $password = $request->getParsedBody()['password'] ?? '';

        try {
            $this->userApi->logUserIn($email, $password);

            $flashDataModel =$this->flashDataApi->makeFlashDataModel(['name' => 'LogInAction']);

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
