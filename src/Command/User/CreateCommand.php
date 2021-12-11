<?php

namespace KoalityEngine\Cli\Command\User;

use KoalityEngine\Cli\Command\KoalityEngineCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\UserRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteCommand
 *
 * Delete the given user.
 *
 * @package KoalityEngine\Cli\Command\User
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-12-09
 */
class CreateCommand extends KoalityEngineCommand
{
    protected static $defaultName = 'user:create';

    const ARGUMENT_APPLICATION_ID = 'applicationId';
    const ARGUMENT_USER_NAME = 'userName';
    const ARGUMENT_USER_PASSWORD = 'userPassword';
    const ARGUMENT_USER_EMAIL = 'userEmail';

    protected function configure()
    {
        parent::configure();

        $this->setHelp('Delete a user.')
            ->addArgument(self::ARGUMENT_APPLICATION_ID, InputArgument::REQUIRED, 'The application identifier (koality, threeSixty).')
            ->addArgument(self::ARGUMENT_USER_NAME, InputArgument::REQUIRED, 'The user name.')
            ->addArgument(self::ARGUMENT_USER_EMAIL, InputArgument::REQUIRED, 'The email address.')
            ->addArgument(self::ARGUMENT_USER_PASSWORD, InputArgument::REQUIRED, 'The password.');
    }

    /**
     * @inheritDoc
     */
    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var UserRepository $repo */
        $repo = $client->getRepository('user');

        $applicationId = $input->getArgument(self::ARGUMENT_APPLICATION_ID);

        $arguments = [
            'userName' => $input->getArgument(self::ARGUMENT_USER_NAME),
            'email' => $input->getArgument(self::ARGUMENT_USER_EMAIL),
            'password' => $input->getArgument(self::ARGUMENT_USER_PASSWORD),
        ];

        $result = $repo->createUser($applicationId, $arguments);

        var_dump($result);

        $output->writeln("\n <info>Successfully created user with id " . $userId . ".</info>");
    }
}
