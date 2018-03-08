<?php
namespace WsClientServer;

use Amp\Websocket\Connection;
use Amp\Mysql\Pool;
use Amp\Loop;

class EmitterTimer
{
    protected $connection;
    protected $pool;
    /** @var \Amp\Mysql\Statement $stmtUserData */
    private $stmtUserData;
    private $timers = [];

    public function __construct(Connection $connection, Pool $pool)
    {
        $this->connection = $connection;
        $this->pool = $pool;
    }

    /**
     * @param int $interval
     * @throws \Amp\Mysql\ConnectionException
     * @throws \Amp\Mysql\FailureException
     */
    public function run(int $interval)
    {
        /** @var \Amp\Mysql\Statement $stmtUserData */
        $this->stmtUserData = yield $this->pool->prepare("SELECT * FROM user WHERE id = ?");

        while ($message = yield $this->connection->receive()) {
            /** @var string $payload */
            $payload = yield $message->buffer();
            $emitterMessage = EmitterMessage::createFromJson($payload);

            echo 'New message from client ' . $emitterMessage->resourceId . ': ' . $payload . "\n";

            if ($emitterMessage->type === 'message') {
                $this->timers[$emitterMessage->resourceId] = Loop::repeat($interval, function () use ($emitterMessage) {
                    yield from $this->messageHandler($emitterMessage);
                });
                continue;
            }

            if ($emitterMessage->type === 'close') {
                echo 'Close timer for client ' . $emitterMessage->resourceId . "\n";
                if (isset($this->timers[$emitterMessage->resourceId])) {
                    Loop::cancel($this->timers[$emitterMessage->resourceId]);
                }
                continue;
            }

        }
    }


    /**
     * @param EmitterMessage $emitterMessage
     * @return \Generator
     * @throws \Throwable
     */
    protected function messageHandler(EmitterMessage $emitterMessage)
    {
        $userMsg = \json_decode($emitterMessage->msg);

        /** @var \Amp\Mysql\ResultSet $result */
        $result = yield $this->stmtUserData->execute([$userMsg->userId]);

        while (yield $result->advance()) {
            $row = $result->getCurrent();

            $msg = \json_encode(new EmitterMessage(
                'message',
                $emitterMessage->resourceId,
                \json_encode($row)
            ));
            echo 'Send message to client: ' . $msg . "\n";
            yield $this->connection->send($msg);
        }
    }
}
