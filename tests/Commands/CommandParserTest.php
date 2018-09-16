<?php
declare(strict_types=1);

use Edvardas\Commands\CommandParser;
use PHPUnit\Framework\TestCase;
use Edvardas\Commands\AddCommand;
use Edvardas\Commands\AddCsvCommand;
use Edvardas\Commands\DeleteCommand;
use Edvardas\Commands\EditCommand;
use Edvardas\Commands\ListCommand;
use Edvardas\Commands\HelpCommand;
use Edvardas\Output\CliOutput;
use Edvardas\Clients\ClientBuilder;
use Edvardas\Clients\SerializableClientsStorage;

final class CommandParserTest extends TestCase {
    protected $cmdParser;
    protected $storage;

    protected function setUp() {
        $this->storage = new SerializableClientsStorage(false);
        $this->cmdParser = new CommandParser($this->storage);
    }

    public function testThrowsExceptionIfUnrecognisedCommand(): void {   
        global $argv;
        $argv = ['', 'bbb'];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown command: bbb");
        $this->cmdParser->getCommand();
    }
    public function testThrowsExceptionNotEnoughArguments(): void {   
        global $argv;
        $argv = [''];

        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage("Not enough arguments");
        $this->cmdParser->getCommand();
    }
    public function testParsesHelpCommand(): void {   
        global $argv;

        $argv = ['', 'help'];
        $this->assertEquals($this->cmdParser->getCommand(), new HelpCommand(CliOutput::get()));

        $argv = ['', 'help', 'a', '-b', '--c=2'];
        $this->assertEquals($this->cmdParser->getCommand(), new HelpCommand(CliOutput::get()));
    }
    public function testParsesAddCommand(): void {   
        global $argv;

        $argv = ['', 'add', 'a', 'b', 'c', 'd', 'e', 'f'];
        $builder = (new ClientBuilder())->setFirstname('a')->setLastname('b')->setEmail('c')
            ->setPhonenumber1('d')->setPhonenumber2('e')->setComment('f');
        $this->assertEquals($this->cmdParser->getCommand(), new AddCommand($builder, $this->storage, CliOutput::get()));
    }
    public function testParsesAddCsvCommand(): void {   
        global $argv;

        $argv = ['', 'add', '--csv=abc'];
        $this->assertEquals($this->cmdParser->getCommand(), new AddCsvCommand('abc', $this->storage, CliOutput::get()));
    }
    public function testParsingAddCsvCommandThrowsErrorIfNotOptionGiven(): void {   
        global $argv;
        $argv = ['', 'add', 'fdhdg'];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Add Csv commands must get one option --csv');
        $this->cmdParser->getCommand();
    }
    public function testParsingAddCsvCommandThrowsErrorIfMoreThanOneParamGiven(): void {   
        global $argv;
        $argv = ['', 'add', 'fdhdg --csv=ll'];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Add Csv commands must get one option --csv');
        $this->cmdParser->getCommand();
    }
    public function testParsingAddCsvCommandThrowsErrorIfWrongOptionGiven(): void {   
        global $argv;
        $argv = ['', 'add', '--asfas=l'];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Add Csv commands must get one option --csv');
        $this->cmdParser->getCommand();
    }
    public function testParsingAddCommandThrowsExceptionIfGetsOptions(): void {   
        global $argv;
        $argv = ['', 'add', '--asd=a', 'b', 'c', 'd', 'e', 'f', 'g'];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Add commands must get only arguments (without --)');
        $this->cmdParser->getCommand();
    }
    public function testParsingAddCommandThrowsExceptionIfTooManyCmdArguments(): void {   
        global $argv;
        $argv = ['', 'add', 'a', 'b', 'c', 'd', 'e', 'f', 'g'];

        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Add commands must get 6 arguments');
        $this->cmdParser->getCommand();
    }
    public function testParsingAddCommandThrowsExceptionIfNotEnoughCmdArguments(): void {   
        global $argv;
        $argv = ['', 'add', 'a', 'b', 'c', 'd', 'e'];

        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Add commands must get 6 arguments');
        $this->cmdParser->getCommand();
    }
    public function testParsingAddCommandThrowsExceptionIfNoParameters(): void {   
        global $argv;
        $argv = ['', 'add'];

        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Add commands must get parameters');
        $this->cmdParser->getCommand();
    }
    public function testParsesDeleteCommand(): void {   
        global $argv;

        $argv = ['', 'delete', 'email'];
        $this->assertEquals($this->cmdParser->getCommand(), new DeleteCommand('email', $this->storage, CliOutput::get()));
    }
    public function testParsingDeleteCommandThrowsErrorIfNoEmail(): void {   
        global $argv;

        $argv = ['', 'delete'];
        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Delete command must get 1 email argument');
        $this->cmdParser->getCommand();
    }
    public function testParsesListCommand(): void {   
        global $argv;

        $argv = ['', 'list'];
        $this->assertEquals($this->cmdParser->getCommand(), new ListCommand($this->storage, CliOutput::get()));

        $argv = ['', 'list', 'a', '-b', '--c=2'];
        $this->assertEquals($this->cmdParser->getCommand(), new ListCommand($this->storage, CliOutput::get()));
    }
    public function testParsesEditCommand(): void {   
        global $argv;

        $argv = ['', 'edit', '--firstname=bob', 'email'];
        $builder = (new ClientBuilder())->setFirstname('bob');
        $this->assertEquals($this->cmdParser->getCommand(), new EditCommand('email', $builder, $this->storage, CliOutput::get()));

        $argv = ['', 'edit', '--firstname=bob', '--lastname=bob', '--email=g@g.g', '--phonenumber1=8',
            '--phonenumber2=8', '--comment=aa', 'email'];
        $builder = (new ClientBuilder())->setFirstname('bob')->setLastname('bob')->setEmail('g@g.g')
            ->setPhonenumber1('8')->setPhonenumber2('8')->setComment('aa');
        $this->assertEquals($this->cmdParser->getCommand(), new EditCommand('email', $builder, $this->storage, CliOutput::get()));

        $argv = ['', 'edit', '--firstname=bob', '--comment=bob=good', 'email'];
        $builder = (new ClientBuilder())->setFirstname('bob')->setComment('bob=good');
        $this->assertEquals($this->cmdParser->getCommand(), new EditCommand('email', $builder, $this->storage, CliOutput::get()));

        $argv = ['', 'edit', '--firstname=', '--comment=bob=good', 'email'];
        $builder = (new ClientBuilder())->setFirstname('')->setComment('bob=good');
        $this->assertEquals($this->cmdParser->getCommand(), new EditCommand('email', $builder, $this->storage, CliOutput::get()));
    }
    public function testParsingEditThrowsExceptionIfEmailParameterIsBetweenOptions(): void {
        global $argv;
        $argv = ['', 'edit', '--firstname=bob', '--lastname=bob', '--email=g@g.g', '--phonenumber1=8',
            '--phonenumber2=8', 'email', '--comment=aa'];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Edit command expects a few options (with --) followed by one email argument');
        $this->cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfNoEmailArgument(): void {
        global $argv;
        $argv = ['', 'edit', '--firstname=bob', '--lastname=bob', '--email=g@g.g', '--phonenumber1=8',
            '--phonenumber2=8', '--comment=aa'];
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage('Edit command expects a few options (with --) followed by one email argument');
            $this->cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfMoreThanOneEmailArgument(): void {
        global $argv;
        $argv = ['', 'edit', '--firstname=bob', '--lastname=bob', '--email=g@g.g', '--phonenumber1=8',
            'fsaf', 'gsdgsd'];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Edit command expects a few options (with --) followed by one email argument');
        $this->cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfOptionIsWrong(): void {
        global $argv;
        $argv = ['', 'edit', '--asqw=bob', '--phonenumber1=8',
            '--phonenumber2=8', 'email'];
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Option asqw is not valid");
        $this->cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfNoOptions(): void {
        global $argv;
        $argv = ['', 'edit', 'email'];
        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage("Enter at least one option (with --)");
        $this->cmdParser->getCommand();
    }
    public function testParsingEditThrowsExceptionIfNoParameters(): void {
        global $argv;
        $argv = ['', 'edit'];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Edit command expects a few options (with --) followed by one email argument');
        $this->cmdParser->getCommand();
    }
    
}