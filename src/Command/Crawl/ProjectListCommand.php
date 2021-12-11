<?php

namespace KoalityEngine\Cli\Command\Crawl;

use KoalityEngine\Cli\Command\KoalityEngineListCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Exception\NotConnectedException;
use Leankoala\ApiClient\Exception\UnknownRepositoryException;
use Leankoala\ApiClient\Repository\Entity\CrawlerRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListProjectCommand
 *
 * List all crawls of the given project and system.
 *
 * @package KoalityEngine\Cli\Command\Incident
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-11-21
 */
class ProjectListCommand extends KoalityEngineListCommand
{
    protected static $defaultName = 'crawl:project:list';

    protected function configure()
    {
        parent::configure();

        $this->setHelp('List all crawls of the given project and system.');

        $this->addArgument('project', InputArgument::REQUIRED, 'The project id.');
        $this->addArgument('system', InputArgument::REQUIRED, 'The system id.');
    }

    /**
     * @param Client $client
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws NotConnectedException
     * @throws UnknownRepositoryException
     */
    protected function doListCreation(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var CrawlerRepository $repo */
        $repo = $client->getRepository('crawler');

        $result = $repo->listCrawls($input->getArgument('project'), ['system' => $input->getArgument('system')]);

        $rows = [];

        foreach ($result['crawls'] as $crawl) {
            $rows[] = [
                $crawl['id'],
                $crawl['name'],
                $crawl['crawl_depth'],
                $crawl['success'],
                $crawl['failure'],
                date('Y-m-d H:i:s', $crawl['start_date']),
                $crawl['status'],
            ];
        }

        $this->renderList(['ID', 'Name', 'Depth', 'Successful', 'Failed', 'Start Date', 'Status'], $rows);
    }
}
