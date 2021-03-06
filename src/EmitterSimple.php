<?php
namespace WsClientServer;

use Amp\Websocket\Connection;

class EmitterSimple
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return \Generator
     */
    public function run() : \Generator
    {
        while ($message = yield $this->connection->receive()) {
            $payload = yield $message->buffer();
            echo 'New message from client: ' . $payload . "\n";

            /** @var EmitterMessage $emitterMessage */
            $emitterMessage = \json_decode($payload);

            if ($emitterMessage->type === 'close') {
                //todo: drop timer
                continue;
            }

            $msg = \json_encode(new EmitterMessage(
                'message',
                $emitterMessage->resourceId,
                'ping'
            ));
            echo 'Send message to client: ' . $msg . "\n";
            yield $this->connection->send($msg);
        }
    }
}
