<?php
declare(strict_types=1);
require './vendor/autoload.php';

use Edvardas\Clients\Client;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Validation\Validator;
use Edvardas\Commands\CommandParser;
use Edvardas\Output\CliOutput;
use Edvardas\ClientRegistrationAppCli;

new Client();
new SerializableClientsStorage();
new Validator();
new CommandParser();
new CliOutput();
new ClientRegistrationAppCli();