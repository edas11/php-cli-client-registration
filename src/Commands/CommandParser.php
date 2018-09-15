<?php
namespace Edvardas\Commands;

use Edvardas\Commands\HelpCommand;
use Edvardas\Commands\AddCommand;
use Edvardas\Output\CliOutput;
use Edvardas\Clients\ClientInputData;
use Edvardas\Clients\SerializableClientsStorage;

class CommandParser{
    
    public function __construct(){
    }

    public function getCommand(): Command {
        global $argv;
        if (count($argv)<2) throw new \LengthException('Not enough arguments');
        $mainCommand = $argv[1];
        switch ($mainCommand) {
            case 'add':
                $cmd = $this->getAddCommand();
                break;
            case 'delete':
                $cmd = $this->getDeleteCommand();
                break;
            case 'edit':
                $cmd = $this->getEditCommand();
                break;
            case 'help':
                $cmd = $this->getHelpCommand();
                break;
            case 'list':
                $cmd = $this->getListCommand();
                break;
            default:
                throw new \InvalidArgumentException("Unknown command: $mainCommand");
        }
        return $cmd;
    }

    private function getAddCommand() {
        global $argv;
        if (count($argv)!==8) throw new \LengthException('Add commands must get 6 arguments');
        $input = ClientInputData::create($argv[2], $argv[3], $argv[4], $argv[5], $argv[6], $argv[7]);
        return new AddCommand($input, new SerializableClientsStorage(), CliOutput::get());
    }

    private function getDeleteCommand() {
        global $argv;
        if (count($argv)!==3) throw new \LengthException('Delete command must get 1 email argument');
        return new DeleteCommand($argv[2], new SerializableClientsStorage(), CliOutput::get());
    }

    private function getHelpCommand() {
        return new HelpCommand(CliOutput::get());
    }

    private function getListCommand() {
        return new ListCommand(new SerializableClientsStorage(), CliOutput::get());
    }

    private function getEditCommand() {
        global $argv;

        $argAfterOptionsIndex = $this->getArgumentIndex();

        $input = $this->parseEditCommandOptions($argAfterOptionsIndex);

        return new EditCommand($argv[$argAfterOptionsIndex], $input, new SerializableClientsStorage(), CliOutput::get());
    }

    private function getArgumentIndex(): int {
        global $argv;
        $argAfterOptionsIndex = -1;

        for($i=2; $i<count($argv); $i++) {
            if ($argAfterOptionsIndex != -1) throw new \UnexpectedValueException('Edit command expects a few options (with --) followed by one email argument');
            if (substr($argv[$i], 0, 2)!=='--') {
                $argAfterOptionsIndex = $i;
            }
        }
        if($argAfterOptionsIndex == -1) throw new \UnexpectedValueException('Edit command expects a few options (with --) followed by one email argument');
        return $argAfterOptionsIndex;
    }

    private function parseEditCommandOptions($argAfterOptionsIndex): ClientInputData {
        global $argv;
        $input = ClientInputData::createEmpty();
        for($j=2; $j<$argAfterOptionsIndex; $j++) {
            $fieldToChange = explode('=', $argv[$j], 2);
            $fieldName = substr($fieldToChange[0], 2);
            if (count($fieldToChange)!=2) throw new \InvalidArgumentException("Option $fieldName has no value");
            $fieldValue = $fieldToChange[1];
            switch ($fieldName) {
                case 'firstname':
                    $input->firstname = $fieldValue;
                    break;
                case 'lastname':
                    $input->lastname = $fieldValue;
                    break;
                case 'email':
                    $input->email = $fieldValue;
                    break;
                case 'phonenumber1':
                    $input->phonenumber1 = $fieldValue;
                    break;
                case 'phonenumber2':
                    $input->phonenumber2 = $fieldValue;
                    break;
                case 'comment':
                    $input->comment = $fieldValue;
                    break;
            }
        }
        return $input;
    }
    
}