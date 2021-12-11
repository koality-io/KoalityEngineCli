<?php

namespace KoalityEngine\Cli\Command\Crawl;

use KoalityEngine\Cli\Command\KoalityEngineListCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\CrawlerRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CompanyRunCommand
 *
 * List all crawls of the given company.
 *
 * @package KoalityEngine\Cli\Command\Incident
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-12-08
 */
class CrawlShowCommand extends KoalityEngineListCommand
{
    protected static $defaultName = 'crawl:show';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();
        $this->setHelp('List all crawls of the given company.');
        $this->addArgument('crawlId', InputOption::VALUE_REQUIRED, 'The crawls id.');
    }

    /**
     * @inheritDoc
     */
    protected function doListCreation(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var CrawlerRepository $repo */
        $repo = $client->getRepository('crawler');

        $crawlId = $input->getArgument('crawlId');

        $result = $repo->getCrawl($crawlId, []);

        $results = $result['results'];

        $table = [];

        foreach ($results as $singleResult) {

            if ($singleResult['status'] == 'success') {
                continue;
            }

            $tableRow = [
                'status' => $singleResult['status'],
                'url' => $singleResult['url'],
            ];

            $findings = '';

            if (array_key_exists('findings', $singleResult)) {
                foreach ($singleResult['findings'] as $tool => $toolFindings) {
                    $findings .= $tool . "\n";
                    foreach ($toolFindings as $toolFinding) {
                        $findings .= "- " . $toolFinding['message'] . " (" . $toolFinding['label'] . ") \n";
                    };
                    $findings .= "\n";
                }
            }

            $tableRow['findings'] = rtrim($findings, "\n");

            $table[] = $tableRow;
        }

        $this->renderList(['Status', 'Url', 'Findings'], $table);
    }
}
