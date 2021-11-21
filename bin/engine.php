<?php

use Symfony\Component\Console\Application;

include_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$application = new Application();

$application->add(new \KoalityEngine\Cli\Command\User\InviteCommand());
$application->add(new \KoalityEngine\Cli\Command\Project\ListCommand());

$application->run();
