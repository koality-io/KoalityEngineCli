<?php

namespace KoalityEngine\Cli\Command\Project;

use KoalityEngine\Cli\Command\KoalityEngineListCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\ProjectRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 * List all projects of the given user.
 *
 * @package KoalityEngine\Cli\Command\Project
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-11-21
 */
class ListCommand extends KoalityEngineListCommand
{
    protected static $defaultName = 'project:list';

    protected function configure()
    {
        parent::configure();
        $this->setHelp('The list command will show all projects for the given user.');
    }

    protected function doListCreation(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var ProjectRepository $repo */
        $repo = $client->getRepository('project');

        $currentUser = $client->getClusterUser();

        $result = $repo->search(['user' => $currentUser['id']]);

        $rows = [];

        foreach ($result['projects'] as $project) {

            $systems = "";
            foreach ($project['systems'] as $system) {
                $systems .= "- " . $system['name'] . " (" . $system['id'] . ") \n";
            }

            $systems = rtrim($systems, "\n");

            $rows[] = [
                $project['id'],
                $project['name'],
                $project['role']['name'],
                $systems
            ];
        }

        $this->renderList(['ID', 'Name', 'Role', 'Systems'], $rows);
    }
}
