<?php

use KoalityEngine\Cli\Command\User\InviteCommand;
use Symfony\Component\Console\Application;

include_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$application = new Application();

$application->add(new InviteCommand());

$application->run();
