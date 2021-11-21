<?php

namespace KoalityEngine\Cli\Command\Project;

use KoalityEngine\Cli\Command\KoalityEngineCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\ProjectRepository;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UsersCommand
 *
 * List all users in the given project of the given user.
 *
 * @package KoalityEngine\Cli\Command\Project
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-11-21
 */
class UsersCommand extends KoalityEngineCommand
{
    protected static $defaultName = 'project:users';

    protected function configure()
    {
        parent::configure();
        $this->setHelp('The users command will show all users in the given project for the given user.')
            ->addArgument('projectId', InputArgument::REQUIRED, 'The numeric projects identifier.');

    }

    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var ProjectRepository $repo */
        $repo = $client->getRepository('project');

        $currentUser = $client->getClusterUser();

        $result = $repo->getUsers($input->getArgument('projectId'), ['user' => $currentUser['id']]);

        $rows = [];

        foreach ($result['users'] as $user) {
            $rows[] = [
                $user['id'],
                trim($user['first_name'].' '.$user['last_name']),
                $user['email']
            ];
        }

        $table = new Table($output);

        $table->setHeaders(['ID', 'name', 'email'])
            ->setRows($rows);

        $table->render();
    }
}
