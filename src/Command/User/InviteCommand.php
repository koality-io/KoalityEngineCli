<?php

namespace KoalityEngine\Cli\Command\User;

use KoalityEngine\Cli\Command\KoalityEngineCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\InvitationRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
            ->addArgument('email', InputArgument::REQUIRED, 'The email address of the invitee.');
    }

    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var InvitationRepository $repo */
        $repo = $client->getRepository('invitation');

        $email = $input->getArgument('email');

        $repo->invite($input->getArgument('projectId'), ['email' => $email]);

        $output->writeln("\n <info>Successfully invited user with email address " . $email . " to the project.</info>");
        $output->writeln(" If the user already had an account the project is immediately added otherwise an invitation email was send.\n");
    }
}
