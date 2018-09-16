<?php
declare(strict_types=1);

use Edvardas\Commands\CommandParser;
use PHPUnit\Framework\TestCase;
use Edvardas\Commands\AddCommand;
use Edvardas\Commands\DeleteCommand;
use Edvardas\Commands\EditCommand;
use Edvardas\Commands\ListCommand;
use Edvardas\Commands\HelpCommand;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Output\CliOutput;
use Edvardas\Clients\ClientBuilder;
use Edvardas\Clients\Client;

final class CommandsTest extends TestCase {

    protected $storage;
    protected $out;

    protected function setUp() {
        $this->storage = $this->getMockBuilder(SerializableClientsStorage::class)
                ->setMethods(['add', 'delete', 'get', 'getAll', 'replace'])
                ->getMock();
        $this->out = $this->getMockBuilder(CliOutput::class)
                ->setMethods(['printSuccess', 'printClients', 'printHelpMessage'])
                ->disableOriginalConstructor()
                ->getMock();
    }

    public function testAddCommandAddsClientAndInforms(): void {
        $builder = (new ClientBuilder())->setFirstname('a')->setLastname('b')->setEmail('e@e.e')
            ->setPhonenumber1('8')->setPhonenumber2('8')->setComment('f');
        $client = new Client('a', 'b', 'e@e.e', '8', '8', 'f');
        $cmd = new AddCommand($builder, $this->storage, $this->out);
        $this->storage->expects($this->once())
                 ->method('add')
                 ->with($client);
        $this->out->expects($this->once())
                 ->method('printSuccess')
                 ->with("Client e@e.e added");
        $cmd->execute();
    }
    public function testDeleteCommandDeletsClientAndInforms(): void {
        $cmd = new DeleteCommand("email", $this->storage, $this->out);
        $this->storage->expects($this->once())
                 ->method('delete')
                 ->with("email");
        $this->out->expects($this->once())
                 ->method('printSuccess')
                 ->with("Client email deleted");
        $cmd->execute();
    }
    public function testEditCommandReplacesClientAndInforms(): void {
        $builder = (new ClientBuilder())->setFirstname('newname')->setEmail('new@g.g')
            ->setComment('newcomment');
        $email = 'g@g.g';
        $clientOld = new Client('oldname', 'b', 'g@g.g', '8', '8', 'oldcomment');
        $clientNew = new Client('newname', 'b', 'new@g.g', '8', '8', 'newcomment');
        $cmd = new EditCommand($email, $builder, $this->storage, $this->out);

        $this->storage->expects($this->once())
                ->method('get')
                ->with($email)
                ->will($this->returnValue($clientOld));
        $this->storage->expects($this->once())
                 ->method('replace')
                 ->with($email, $clientNew);
        $this->out->expects($this->once())
                 ->method('printSuccess')
                 ->with("Client g@g.g modified. New email new@g.g");
        $cmd->execute();
    }
    public function testListCommandPrintsAllClients(): void {
        $clients = [new Client('a', 'b', 'g@g.g', '8', '8', 'c'),
                    new Client('a', 'b', 'gg@g.g', '8', '8', 'c')];
        $cmd = new ListCommand($this->storage, $this->out);
        $this->storage->expects($this->once())
                 ->method('getAll')
                 ->will($this->returnValue($clients));
        $this->out->expects($this->once())
                 ->method('printClients')
                 ->with($clients);
        $cmd->execute();
    }
    public function testHelpCommandPrintsHelpMessage(): void {
        $cmd = new HelpCommand($this->out);
        $this->out->expects($this->once())
                 ->method('printHelpMessage');
        $cmd->execute();
    }
}