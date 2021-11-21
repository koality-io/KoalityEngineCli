<?php

namespace KoalityEngine\Cli\Command;

use GuzzleHttp\Exception\GuzzleException;
use Leankoala\ApiClient\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ApiCommand
 *
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-11-21
 */
abstract class KoalityEngineCommand extends Command
{
    const OPTION_USERNAME = 'username';
    const OPTION_PASSWORD = 'password';
    const OPTION_ENVIRONMENT = 'environment';

    protected function configure()
    {
        $this->addOption(self::OPTION_USERNAME, 'u', InputOption::VALUE_OPTIONAL, 'The username to connect with the API.');
        $this->addOption(self::OPTION_PASSWORD, 'p', InputOption::VALUE_OPTIONAL, 'The password to connect with the API.');
        $this->addOption(self::OPTION_ENVIRONMENT, 'e', InputOption::VALUE_OPTIONAL, 'The environment to connect with (prod, stage). Default is prod.');
    }

    /**
     * Handle execution errors in a standardized way.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $client = $this->getConnectedClient($input);
            $this->doExecution($client, $input, $output);
        } catch (\Exception $exception) {
            $output->writeln("\n<error>Error running API command (" . static::$defaultName . ")</error> \n" . $exception->getMessage() . "\n");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @param Client $client
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    abstract protected function doExecution(Client $client, InputInterface $input, OutputInterface $output);

    /**
     * Get a ready to use connected client.
     *
     * @param InputInterface $input
     *
     * @return Client
     *
     * @throws GuzzleException
     */
    protected function getConnectedClient(InputInterface $input): Client
    {
        $credentials = $this->getCredentials($input);

        $client = new Client($credentials['environment']);
        $client->connect($credentials['username'], $credentials['password'], true);

        return $client;
    }

    /**
     * Return the API credentials.
     *
     * Can be defined via .env file or command options. Command options overwrite the env variables.
     *
     * @param InputInterface $input
     *
     * @return array
     */
    private function getCredentials(InputInterface $input): array
    {
        if ($input->getOption(self::OPTION_USERNAME)) {
            $username = $input->getOption(self::OPTION_USERNAME);
        } elseif (array_key_exists('USER_NAME', $_ENV)) {
            $username = $_ENV['USER_NAME'];
        } else {
            throw new \RuntimeException('No username set. It is recommended to use the .env file. It is also possible to use the -u option.');
        }

        if ($input->getOption(self::OPTION_PASSWORD)) {
            $password = $input->getOption(self::OPTION_PASSWORD);
        } elseif (array_key_exists('USER_PASSWORD', $_ENV)) {
            $password = $_ENV['USER_PASSWORD'];
        } else {
            throw new \RuntimeException('No password set. It is recommended to use the .env file. It is also possible to use the -p option.');
        }

        if ($input->getOption(self::OPTION_ENVIRONMENT)) {
            $environment = $input->getOption(self::OPTION_ENVIRONMENT);
        } elseif (array_key_exists('ENVIRONMENT', $_ENV)) {
            $environment = $_ENV['ENVIRONMENT'];
        } else {
            $environment = 'prod';
        }

        return [
            'username' => $username,
            'password' => $password,
            'environment' => $environment
        ];
    }

}
