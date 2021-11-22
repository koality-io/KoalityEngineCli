<?php

namespace KoalityEngine\Cli\Command\Project;

use KoalityEngine\Cli\Command\KoalityEngineListCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\ProjectRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 * List all users of the given project.
 *
 * @package KoalityEngine\Cli\Command\Project
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-11-21
 */
class UsersCommand extends KoalityEngineListCommand
{
    protected static $defaultName = 'project:users';

    protected function configure()
    {
        parent::configure();
        $this->setHelp('The list all users of the project.');
        $this->addArgument('project', InputArgument::REQUIRED, 'The project id');
    }

    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var ProjectRepository $repo */
        $repo = $client->getRepository('project');

        $project = $input->getArgument('project');

        $result = $repo->getUsers($project, []);

        $rows = [];

        foreach ($result['users'] as $user) {
            $rows[] = [
                $user['id'],
                strtolower($user['email']),
                $user['role']['name']
            ];
        }

        $this->renderList($input, $output, ['ID', 'Email', 'Role'], $rows);
    }
}
