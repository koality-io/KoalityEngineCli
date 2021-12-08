<?php

namespace KoalityEngine\Cli\Command\Crawl;

use KoalityEngine\Cli\Command\KoalityEngineListCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\CrawlerRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CompanyListCommand
 *
 * List all crawls of the given company.
 *
 * @package KoalityEngine\Cli\Command\Incident
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-12-08
 */
class CompanyListCommand extends KoalityEngineListCommand
{
    protected static $defaultName = 'crawl:company:list';

    protected function configure()
    {
        parent::configure();

        $this->setHelp('List all crawls of the given company.');

        $this->addArgument('company', InputArgument::OPTIONAL, 'The company id.');
    }

    /**
     * @inheritDoc
     */
    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var CrawlerRepository $repo */
        $repo = $client->getRepository('crawler');

        if ($input->getArgument('company')) {
            $companyId = $input->getArgument('company');
        } else {
            $user = $client->getClusterUser();
            $companyId = $user['company']['id'];
        }

        $result = $repo->listCompanyCrawls($companyId, []);

        $rows = [];

        foreach ($result['crawls'] as $crawl) {
            $rows[] = [
                $crawl['id'],
                $crawl['name'],
                $crawl['crawl_depth'],
                $crawl['success'],
                $crawl['uncertain'],
                $crawl['failure'],
                date('Y-m-d H:i:s', $crawl['start_date']),
                $crawl['status'],
            ];
        }

        $this->renderList($input, $output, ['ID', 'Name', 'Depth', 'Successful', 'Uncertain', 'Failed', 'Start Date', 'Status'], $rows);
    }
}
