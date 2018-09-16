<?php
declare(strict_types=1);
chdir(__DIR__);
require './vendor/autoload.php';

use Edvardas\ClientRegistrationAppCli;

$app = new ClientRegistrationAppCli();
$app->executeCommand();