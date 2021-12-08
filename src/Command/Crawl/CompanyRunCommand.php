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
class CompanyRunCommand extends KoalityEngineListCommand
{
    protected static $defaultName = 'crawl:company:run';

    protected function configure()
    {
        parent::configure();

        $this->setHelp('List all crawls of the given company.');

        $this->addArgument('starturl', InputOption::VALUE_REQUIRED, 'The url to start the crawl at.');

        $this->addOption('company', 'c', InputOption::VALUE_OPTIONAL, 'The company id.');
        $this->addOption('name', 'i', InputOption::VALUE_OPTIONAL);
        $this->addOption('depth', 'd', InputOption::VALUE_OPTIONAL, 'The number of urls to visit.', 5);

        $this->addOption('collections', 'l', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'List of collections that should be crawled.', []);
    }

    /**
     * @inheritDoc
     */
    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var CrawlerRepository $repo */
        $repo = $client->getRepository('crawler');

        $user = $client->getClusterUser();

        if ($input->getOption('company')) {
            $companyId = $input->getOption('company');
        } else {
            $companyId = $user['company']['id'];
        }

        if ($input->getOption('name')) {
            $name = $input->getOption('name');
        } else {
            $name = date('Y-m-d H:i:s');
        }

        $path = $input->getArgument('starturl');

        $result = $repo->runCompanyCrawl($companyId, [
            'path' => $path,
            'user' => $user['id'],
            'name' => $name,
            'depth' => (int)$input->getOption('depth'),
            'collections' => $input->getOption('collections')
        ]);

        $output->writeln("\n <info>Crawl successfully started (id: " . $result['id'] . ")</info>\n");
    }
}
