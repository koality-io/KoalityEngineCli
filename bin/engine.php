<?php

use Symfony\Component\Console\Application;

include_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$application = new Application();

# User
$application->add(new \KoalityEngine\Cli\Command\User\InviteCommand());
$application->add(new \KoalityEngine\Cli\Command\User\CreateCommand());
$application->add(new \KoalityEngine\Cli\Command\User\DeleteCommand());

# Subscription
$application->add(new \KoalityEngine\Cli\Command\Subscription\PlanSetCommand());

# Incident
$application->add(new \KoalityEngine\Cli\Command\Incident\ListCommand());

# Project
$application->add(new \KoalityEngine\Cli\Command\Project\ListCommand());
$application->add(new \KoalityEngine\Cli\Command\Project\UsersCommand());

# Crawler
$application->add(new \KoalityEngine\Cli\Command\Crawl\CrawlShowCommand());

$application->add(new \KoalityEngine\Cli\Command\Crawl\CollectionsListCommand());

$application->add(new \KoalityEngine\Cli\Command\Crawl\ProjectListCommand());
$application->add(new \KoalityEngine\Cli\Command\Crawl\CompanyListCommand());
$application->add(new \KoalityEngine\Cli\Command\Crawl\CompanyRunCommand());

$application->run();
