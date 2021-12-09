<?php

namespace KoalityEngine\Cli\Command;

use Leankoala\ApiClient\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class KoalityEngineDangerousCommand
 *
 * All those commands will do some irreversible things.
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-12-09
 */
abstract class KoalityEngineDangerousCommand extends KoalityEngineCommand
{
    /**
     * @param Client $client
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion("<info>" . $this->getQuestion($input, $client) . "</info>", false);

        $output->writeln("");

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln("");
            return Command::SUCCESS;
        }

        return $this->doDangerousExecution($client, $input, $output);
    }

    /**
     * @param InputInterface $input
     * @param Client $client
     *
     * @return string
     */
    abstract protected function getQuestion(InputInterface $input, Client $client);

    /**
     * @param Client $client
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    abstract protected function doDangerousExecution(Client $client, InputInterface $input, OutputInterface $output);
}
