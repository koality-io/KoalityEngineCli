<?php

namespace KoalityEngine\Cli\Command\Subscription;

use KoalityEngine\Cli\Command\KoalityEngineCommand;
use Leankoala\ApiClient\Client;
use Leankoala\ApiClient\Repository\Entity\SubscriptionRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeleteCommand
 *
 * Delete the given user.
 *
 * @package KoalityEngine\Cli\Command\User
 *
 * @author Nils Langner <nils.langner@leankoala.com>
 * created 2021-12-09
 */
class PlanSetCommand extends KoalityEngineCommand
{
    protected static $defaultName = 'subscription:plan:set';

    const ARGUMENT_USER_ID = 'userId';
    const ARGUMENT_PLAN_ID = 'planId';

    protected function configure()
    {
        parent::configure();

        $this->setHelp('Set a plan for a user.')
            ->addArgument(self::ARGUMENT_USER_ID, InputArgument::REQUIRED, 'The numeric users identifier.')
            ->addArgument(self::ARGUMENT_PLAN_ID, InputArgument::REQUIRED, 'The plan identifier.');
    }

    /**
     * @inheritDoc
     */
    protected function doExecution(Client $client, InputInterface $input, OutputInterface $output)
    {
        /** @var SubscriptionRepository $repo */
        $repo = $client->getRepository('subscription');

        $userId = $input->getArgument(self::ARGUMENT_USER_ID);
        $planIdentifier = $input->getArgument(self::ARGUMENT_PLAN_ID);

        $repo->setSubscriptionPlan($userId, ['identifier' => $planIdentifier]);

        $output->writeln("\n <info>Successfully updated subscription plan for user " . $userId . ".</info>");
    }
}
