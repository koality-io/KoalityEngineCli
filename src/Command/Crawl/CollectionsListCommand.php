<?php

namespace KoalityEngine\Cli\Command\Crawl;

use KoalityEngine\Cli\Command\KoalityEngineListCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\CrawlerRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CollectionsListCommand
 *
 * List all collections that can be used in a crawl.
 *
 * @package KoalityEngine\Cli\Command\Incident
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-12-08
 */
class CollectionsListCommand extends KoalityEngineListCommand
{
    protected static $defaultName = 'crawl:collection:list';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();
        $this->setHelp('List all collections that can be used in a crawl.');
    }

    /**
     * @inheritDoc
     */
    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var CrawlerRepository $repo */
        $repo = $client->getRepository('crawler');

        $result = $repo->getCrawlableCollections([]);

        $rows = [];

        foreach ($result['collections'] as $collection) {
            $rows[] = [
                $collection['id'],
                $collection['name'],
                $collection['description']
            ];
        }

        $this->renderList($input, $output, ['ID', 'Name', 'Description'], $rows);
    }
}
