<?php

namespace KoalityEngine\Cli\Command\User;

use KoalityEngine\Cli\Command\KoalityEngineCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\InvitationRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InviteCommand
 *
 * Invite a user to an existing project.
 *
 * @package KoalityEngine\Cli\Command\User
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-11-21
 */
class InviteCommand extends KoalityEngineCommand
{
    protected static $defaultName = 'user:invite';

    protected function configure()
    {
        parent::configure();

        $this->setHelp('The invite command will add a user to an existing project. The inviter must be at least administrator of the project.')
            ->addArgument('projectId', InputArgument::REQUIRED, 'The numeric projects identifier.')
            ->addArgument('email', InputArgument::REQUIRED, 'The email address of the invitee.')
            ->addArgument('role', InputArgument::REQUIRED, 'The role of the invitee.')
            ->addOption('user_name', 'name', InputOption::VALUE_OPTIONAL, 'The user name of the invitee.');
    }

    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var InvitationRepository $repo */
        $repo = $client->getRepository('invitation');

        $email = $input->getArgument('email');
        $user_role = $input->getArgument('role');
        $user_name = $input->getOption('user_name');
        if (null == $user_name) {
            $user_name = explode('@', $email);
            $user_name = reset($user_name);
        }

        $currentUser = $client->getClusterUser();

        $args = [
            'email' => $email,
            'user_role' => $user_role,
            'inviter' => $currentUser['id'],
            'user_name' => $user_name,
        ];
        $repo->invite($input->getArgument('projectId'), $args);

        $output->writeln("\n <info>Successfully invited user with email address " . $email . " to the project.</info>");
        $output->writeln(" If the user already had an account the project is immediately added otherwise an invitation email was send.\n");
    }
}
