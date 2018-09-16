<?php
namespace Edvardas\Commands;

use Edvardas\Commands\HelpCommand;
use Edvardas\Commands\AddCommand;
use Edvardas\Commands\Parameters\Argument;
use Edvardas\Commands\Parameters\Option;
use Edvardas\Output\CliOutput;
use Edvardas\Clients\ClientBuilder;
use Edvardas\Clients\SerializableClientsStorage;

class CommandParser{

    private $storage;
    
    public function __construct(SerializableClientsStorage $storage){
        $this->storage = $storage;
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
            case 'end':
                $cmd = $this->getEndCommand();
                break;
            default:
                throw new \InvalidArgumentException("Unknown command: $mainCommand");
        }
        return $cmd;
    }

    private function parseOptionsAndArguments(): array {
        global $argv;
        $optionsAndArguments = [];
        for($i=2; $i<count($argv); $i++) {
            if (substr($argv[$i], 0, 2)==='--') {
                $field = explode('=', $argv[$i], 2);
                $fieldName = substr($field[0], 2);
                if (count($field)!=2) throw new \InvalidArgumentException("Option $fieldName has no value");
                array_push($optionsAndArguments, new Option($fieldName, $field[1]));
            } else {
                array_push($optionsAndArguments, new Argument($argv[$i]));
            }
        }
        return $optionsAndArguments;
    }

    private function getAddCommand() {
        global $argv;
        $optionsAndArguments = $this->parseOptionsAndArguments();
        if (count($optionsAndArguments)==0) throw new \LengthException('Add commands must get parameters');
        if (count($optionsAndArguments)==1) return $this->getAddCsvCommand($optionsAndArguments);
        foreach($optionsAndArguments as $param){
            if ($param instanceof Option) throw new \InvalidArgumentException("Add commands must get only arguments (without --)");
        }
        if (count($optionsAndArguments)!==6) throw new \LengthException('Add commands must get 6 arguments');
        $builder = (new ClientBuilder())->setFirstname($optionsAndArguments[0]->getValue())
            ->setLastname($optionsAndArguments[1]->getValue())->setEmail($optionsAndArguments[2]->getValue())
            ->setPhonenumber1($optionsAndArguments[3]->getValue())->setPhonenumber2($optionsAndArguments[4]->getValue())
            ->setComment($optionsAndArguments[5]->getValue());
        return new AddCommand($builder, $this->storage, CliOutput::get());
    }

    private function getAddCsvCommand($optionsAndArguments) {
        if (count($optionsAndArguments)!==1) throw new \Exception('Add Csv commands must get one option --csv');
        if (!($optionsAndArguments[0] instanceof Option)) throw new \Exception('Add Csv commands must get one option --csv');
        if ($optionsAndArguments[0]->getName()!=='csv') throw new \Exception('Add Csv commands must get one option --csv');
        $csvFileName = $optionsAndArguments[0]->getValue();
        return new AddCsvCommand($csvFileName, $this->storage, CliOutput::get());
    }

    private function getDeleteCommand() {
        global $argv;
        if (count($argv)!==3) throw new \LengthException('Delete command must get 1 email argument');
        return new DeleteCommand($argv[2], $this->storage, CliOutput::get());
    }

    private function getHelpCommand() {
        return new HelpCommand(CliOutput::get());
    }

    private function getListCommand() {
        return new ListCommand($this->storage, CliOutput::get());
    }

    private function getEndCommand() {
        return new EndCommand(CliOutput::get());
    }

    private function getEditCommand() {
        global $argv;
        $optionsAndArguments = $this->parseOptionsAndArguments();

        $this->throwExceptionIfEditParamsBad($optionsAndArguments);

        $builder = $this->parseEditCommandOptions($optionsAndArguments);

        return new EditCommand($optionsAndArguments[count($optionsAndArguments)-1]->getValue(), $builder
            , $this->storage, CliOutput::get());
    }

    private function throwExceptionIfEditParamsBad(array $optionsAndArguments): void {
        $exception = new \UnexpectedValueException('Edit command expects a few options (with --) followed by one email argument');
        if (count($optionsAndArguments)<1) throw $exception;
        $argumentFound = false;
        foreach($optionsAndArguments as $key=>$param) {
            if ($param instanceof Argument){
                $argumentFound = true;
                if (count($optionsAndArguments)>$key+1) throw $exception;
            }
        }
        if(!$argumentFound) throw $exception;
        if(count($optionsAndArguments) == 1) throw new \LengthException('Enter at least one option (with --)');
    }

    private function parseEditCommandOptions($optionsAndArguments): ClientBuilder {
        global $argv;
        $builder = new ClientBuilder();
        for($j=0; $j<count($optionsAndArguments)-1; $j++) {
            $fieldName = $optionsAndArguments[$j]->getName();
            $fieldValue = $optionsAndArguments[$j]->getValue();
            switch ($fieldName) {
                case 'firstname':
                    $builder->setFirstname($fieldValue);
                    break;
                case 'lastname':
                    $builder->setLastname($fieldValue);
                    break;
                case 'email':
                    $builder->setEmail($fieldValue);
                    break;
                case 'phonenumber1':
                    $builder->setPhonenumber1($fieldValue);
                    break;
                case 'phonenumber2':
                    $builder->setPhonenumber2($fieldValue);
                    break;
                case 'comment':
                    $builder->setComment($fieldValue);
                    break;
                default:
                    throw new \InvalidArgumentException("Option $fieldName is not valid");
            }
        }
        return $builder;
    }
    
}