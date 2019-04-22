<?php

declare(strict_types=1);

namespace corbomite\user\actions;

use corbomite\cli\services\CliQuestionService;
use corbomite\user\interfaces\UserApiInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserAction
{
    /** @var UserApiInterface */
    private $userApi;
    /** @var OutputInterface */
    private $consoleOutput;
    /** @var CliQuestionService */
    private $cliQuestionService;

    public function __construct(
        UserApiInterface $userApi,
        OutputInterface $consoleOutput,
        CliQuestionService $cliQuestionService
    ) {
        $this->userApi            = $userApi;
        $this->consoleOutput      = $consoleOutput;
        $this->cliQuestionService = $cliQuestionService;
    }

    public function __invoke() : void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->userApi->registerUser(
            $this->cliQuestionService->ask(
                '<fg=cyan>Email address: </>'
            ),
            $this->cliQuestionService->ask(
                '<fg=cyan>Password: </>',
                true,
                true
            )
        );

        $this->consoleOutput->writeln(
            '<fg=green>User registered successfully!</>'
        );
    }
}
