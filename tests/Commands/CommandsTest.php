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
use Edvardas\Clients\ClientInputData;
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
                ->getMock();
    }

    public function testAddCommandAddsClientAndInforms(): void {
        $input = ClientInputData::create('a', 'b', 'e@e.e', '8', '8', 'f');
        $client = new Client($input->firstname, $input->lastname, $input->email, $input->phonenumber1,
            $input->phonenumber2, $input->comment);
        $cmd = new AddCommand($input, $this->storage, $this->out);
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
        $input = ClientInputData::createEmpty();
        $input->firstname = 'newname';
        $input->comment = 'newcomment';
        $input->email = 'new@g.g';
        $email = 'g@g.g';
        $clientOld = new Client('oldname', 'b', 'g@g.g', '8', '8', 'oldcomment');
        $clientNew = new Client('newname', 'b', 'new@g.g', '8', '8', 'newcomment');
        $cmd = new EditCommand($email, $input, $this->storage, $this->out);

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