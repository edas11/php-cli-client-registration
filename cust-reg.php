<?php
declare(strict_types=1);
require './vendor/autoload.php';

use Edvardas\ClientRegistrationAppCli;

$app = new ClientRegistrationAppCli();
$app->executeCommand();