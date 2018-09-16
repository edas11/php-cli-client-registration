<?php
declare(strict_types=1);

use Edvardas\Commands\CommandParser;
use PHPUnit\Framework\TestCase;
use Edvardas\Commands\AddCommand;
use Edvardas\Commands\DeleteCommand;
use Edvardas\Commands\EditCommand;
use Edvardas\Commands\ListCommand;
use Edvardas\Commands\HelpCommand;
use Edvardas\Output\CliOutput;
use Edvardas\Clients\ClientBuilder;
use Edvardas\Clients\SerializableClientsStorage;

final class CommandParserTest extends TestCase {
    public function testThrowsExceptionIfUnrecognisedCommand(): void {   
        global $argv;
        $cmdParser = new CommandParser();
        $argv = ['', 'bbb'];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown command: bbb");
        $cmdParser->getCommand();
    }
    public function testThrowsExceptionNotEnoughArguments(): void {   
        global $argv;
        $cmdParser = new CommandParser();
        $argv = [''];

        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage("Not enough arguments");
        $cmdParser->getCommand();
    }
    public function testParsesHelpCommand(): void {   
        global $argv;
        $cmdParser = new CommandParser();

        $argv = ['', 'help'];
        $this->assertEquals($cmdParser->getCommand(), new HelpCommand(CliOutput::get()));

        $argv = ['', 'help', 'a', '-b', '--c=2'];
        $this->assertEquals($cmdParser->getCommand(), new HelpCommand(CliOutput::get()));
    }
    public function testParsesAddCommand(): void {   
        global $argv;
        $cmdParser = new CommandParser();

        $argv = ['', 'add', 'a', 'b', 'c', 'd', 'e', 'f'];
        $builder = (new ClientBuilder())->setFirstname('a')->setLastname('b')->setEmail('c')
            ->setPhonenumber1('d')->setPhonenumber2('e')->setComment('f');
        $this->assertEquals($cmdParser->getCommand(), new AddCommand($builder, new SerializableClientsStorage(), CliOutput::get()));
    }
    public function testParsingAddCommandThrowsExceptionIfGetsOptions(): void {   
        global $argv;
        $cmdParser = new CommandParser();
        $argv = ['', 'add', '--asd=a', 'b', 'c', 'd', 'e', 'f', 'g'];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Add commands must get only arguments (without --)');
        $cmdParser->getCommand();
    }
    public function testParsingAddCommandThrowsExceptionIfTooManyCmdArguments(): void {   
        global $argv;
        $cmdParser = new CommandParser();
        $argv = ['', 'add', 'a', 'b', 'c', 'd', 'e', 'f', 'g'];

        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Add commands must get 6 arguments');
        $cmdParser->getCommand();
    }
    public function testParsingAddCommandThrowsExceptionIfNotEnoughCmdArguments(): void {   
        global $argv;
        $cmdParser = new CommandParser();
        $argv = ['', 'add', 'a', 'b', 'c', 'd', 'e'];

        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Add commands must get 6 arguments');
        $cmdParser->getCommand();
    }
    public function testParsesDeleteCommand(): void {   
        global $argv;
        $cmdParser = new CommandParser();

        $argv = ['', 'delete', 'email'];
        $this->assertEquals($cmdParser->getCommand(), new DeleteCommand('email', new SerializableClientsStorage(), CliOutput::get()));
    }
    public function testParsingDeleteCommandThrowsErrorIfNoEmail(): void {   
        global $argv;
        $cmdParser = new CommandParser();

        $argv = ['', 'delete'];
        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Delete command must get 1 email argument');
        $cmdParser->getCommand();
    }
    public function testParsesListCommand(): void {   
        global $argv;
        $cmdParser = new CommandParser();

        $argv = ['', 'list'];
        $this->assertEquals($cmdParser->getCommand(), new ListCommand(new SerializableClientsStorage(), CliOutput::get()));

        $argv = ['', 'list', 'a', '-b', '--c=2'];
        $this->assertEquals($cmdParser->getCommand(), new ListCommand(new SerializableClientsStorage(), CliOutput::get()));
    }
    public function testParsesEditCommand(): void {   
        global $argv;
        $cmdParser = new CommandParser();

        $argv = ['', 'edit', '--firstname=bob', 'email'];
        $builder = (new ClientBuilder())->setFirstname('bob');
        $this->assertEquals($cmdParser->getCommand(), new EditCommand('email', $builder, new SerializableClientsStorage(), CliOutput::get()));

        $argv = ['', 'edit', '--firstname=bob', '--lastname=bob', '--email=g@g.g', '--phonenumber1=8',
            '--phonenumber2=8', '--comment=aa', 'email'];
        $builder = (new ClientBuilder())->setFirstname('bob')->setLastname('bob')->setEmail('g@g.g')
            ->setPhonenumber1('8')->setPhonenumber2('8')->setComment('aa');
        $this->assertEquals($cmdParser->getCommand(), new EditCommand('email', $builder, new SerializableClientsStorage(), CliOutput::get()));

        $argv = ['', 'edit', '--firstname=bob', '--comment=bob=good', 'email'];
        $builder = (new ClientBuilder())->setFirstname('bob')->setComment('bob=good');
        $this->assertEquals($cmdParser->getCommand(), new EditCommand('email', $builder, new SerializableClientsStorage(), CliOutput::get()));

        $argv = ['', 'edit', '--firstname=', '--comment=bob=good', 'email'];
        $builder = (new ClientBuilder())->setFirstname('')->setComment('bob=good');
        $this->assertEquals($cmdParser->getCommand(), new EditCommand('email', $builder, new SerializableClientsStorage(), CliOutput::get()));
    }
    public function testParsingEditThrowsExceptionIfEmailParameterIsBetweenOptions(): void {
        global $argv;
        $cmdParser = new CommandParser(); 
        $argv = ['', 'edit', '--firstname=bob', '--lastname=bob', '--email=g@g.g', '--phonenumber1=8',
            '--phonenumber2=8', 'email', '--comment=aa'];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Edit command expects a few options (with --) followed by one email argument');
        $cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfNoEmailArgument(): void {
        global $argv;
        $cmdParser = new CommandParser(); 
        $argv = ['', 'edit', '--firstname=bob', '--lastname=bob', '--email=g@g.g', '--phonenumber1=8',
            '--phonenumber2=8', '--comment=aa'];
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage('Edit command expects a few options (with --) followed by one email argument');
        $cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfMoreThanOneEmailArgument(): void {
        global $argv;
        $cmdParser = new CommandParser(); 
        $argv = ['', 'edit', '--firstname=bob', '--lastname=bob', '--email=g@g.g', '--phonenumber1=8',
            'fsaf', 'gsdgsd'];
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage('Edit command expects a few options (with --) followed by one email argument');
        $cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfOptionIsWrong(): void {
        global $argv;
        $cmdParser = new CommandParser(); 
        $argv = ['', 'edit', '--asqw=bob', '--phonenumber1=8',
            '--phonenumber2=8', 'email'];
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage("Option asqw is not valid");
        $cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfNoOptions(): void {
        global $argv;
        $cmdParser = new CommandParser(); 
        $argv = ['', 'edit', 'email'];
        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage("Enter at least one option (with --)");
        $cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfNoParameters(): void {
        global $argv;
        $cmdParser = new CommandParser(); 
        $argv = ['', 'edit'];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Edit command expects a few options (with --) followed by one email argument');
        $cmdParser->getCommand();
    }
    
}