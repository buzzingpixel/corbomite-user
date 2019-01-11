<?php
declare(strict_types=1);

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2019 BuzzingPixel, LLC
 * @license Apache-2.0
 */

namespace corbomite\user\actions;

use corbomite\user\UserApi;
use corbomite\cli\services\CliQuestionService;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserAction
{
    private $userApi;
    private $consoleOutput;
    private $cliQuestionService;

    public function __construct(
        UserApi $userApi,
        OutputInterface $consoleOutput,
        CliQuestionService $cliQuestionService
    ) {
        $this->userApi = $userApi;
        $this->consoleOutput = $consoleOutput;
        $this->cliQuestionService = $cliQuestionService;
    }

    public function __invoke()
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
