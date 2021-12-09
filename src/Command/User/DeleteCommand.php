<?php

namespace KoalityEngine\Cli\Command\User;

use KoalityEngine\Cli\Command\KoalityEngineDangerousCommand;
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
class DeleteCommand extends KoalityEngineDangerousCommand
{
    protected static $defaultName = 'user:delete';

    const ARGUMENT_USER_ID = 'userId';

    protected function configure()
    {
        parent::configure();

        $this->setHelp('Delete a user.')
            ->addArgument(self::ARGUMENT_USER_ID, InputArgument::REQUIRED, 'The numeric users identifier.');
    }

    /**
     * @inheritDoc
     */
    protected function getQuestion(InputInterface $input, Client $client)
    {
        $userId = $input->getArgument(self::ARGUMENT_USER_ID);

        $currentUser = $client->getClusterUser();

        if ($currentUser['id'] == $userId) {
            return "Are you sure you want to delete your account? (y/n) ";
        } else {
            return "Are you sure you want to delete the given user (id: " . $userId . ")? (y/n) ";
        }

    }

    /**
     * @inheritDoc
     */
    protected function doDangerousExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var UserRepository $repo */
        $repo = $client->getRepository('user');

        $userId = $input->getArgument(self::ARGUMENT_USER_ID);

        $repo->deleteUser('cli', $userId, []);

        $output->writeln("\n <info>Successfully deleted user id " . $userId . ".</info>");
    }
}
