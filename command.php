#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use App\Command\GenerateAdminUserCommand;
use App\Command\RemoveAdminUserCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new GenerateAdminUserCommand());
$application->add(new RemoveAdminUserCommand());

$application->run();


