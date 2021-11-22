<?php

namespace KoalityEngine\Cli\Command\Incident;

use KoalityEngine\Cli\Command\KoalityEngineListCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\IncidentRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 * List all incidents of the given project.
 *
 * @package KoalityEngine\Cli\Command\Incident
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-11-21
 */
class ListCommand extends KoalityEngineListCommand
{
    protected static $defaultName = 'incident:list';

    protected function configure()
    {
        parent::configure();

        $this->setHelp('List all incidents of the given project.');
        $this->addArgument('project', InputArgument::REQUIRED, 'The project id.');
    }

    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var IncidentRepository $repo */
        $repo = $client->getRepository('incident');

        $result = $repo->search($input->getArgument('project'), []);

        $rows = [];

        foreach ($result['incidents'] as $incident) {
            $rows[] = [
                $incident['component']['name'] . "\n" . $incident['component']['url'],
                $incident['tool']['name'],
                $incident['message'],
                date('Y-m-d H:i:s', strtotime($incident['start_date']['date']))
            ];
        }

        $this->renderList($input, $output, ['Component', 'Tool', 'Message', 'Start date'], $rows);
    }
}
