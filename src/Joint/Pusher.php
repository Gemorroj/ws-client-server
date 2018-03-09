<?php
namespace WsClientServer\Joint;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsConnection;
use WsClientServer\EmitterMessage;

class Pusher implements MessageComponentInterface
{
    /** @var ConnectionInterface[]|WsConnection[] */
    protected $clients = [];
    /** @var \Amp\Mysql\Pool */
    protected $pool;
    /** @var \Amp\Mysql\Statement $stmtUserData */
    private $stmtUserData;
    /** @var int */
    private $interval;

    public function __construct(int $interval = 5000)
    {
        $this->interval = $interval;
        $this->pool = \Amp\Mysql\pool("host=127.0.0.1 user=root password= db=test");
    }

    /**
     * @param ConnectionInterface|WsConnection $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        echo 'New client connection ' . $conn->resourceId . "\n";
        $this->clients[$conn->resourceId]['connection'] = $conn;

        \Amp\asyncCall(function () {
            $this->stmtUserData = yield $this->pool->prepare("SELECT * FROM user WHERE id = ?");
        });
    }


    /**
     * @param ConnectionInterface|WsConnection $conn
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $conn, $msg)
    {
        echo 'New message from client ' . $conn->resourceId . ': ' . $msg . "\n";

        $userMsg = \json_decode($msg);

        if ($userMsg->type === 'connect') {
            $this->clients[$conn->resourceId]['timer'] = \Amp\Loop::repeat($this->interval, function () use ($userMsg, $conn) {
                /** @var \Amp\Mysql\ResultSet $result */
                $result = yield $this->stmtUserData->execute([$userMsg->userId]);

                while (yield $result->advance()) {
                    $row = $result->getCurrent();

                    $msg = \json_encode(new EmitterMessage(
                        'message',
                        $conn->resourceId,
                        \json_encode($row)
                    ));
                    echo 'Send message to client : ' . $msg . "\n";
                    $conn->send($msg);
                }
            });
        }
    }

    /**
     * @param ConnectionInterface|WsConnection $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        echo 'Close connection client ' . $conn->resourceId . "\n";

        \Amp\Loop::cancel($this->clients[$conn->resourceId]['timer']);
        unset($this->clients[$conn->resourceId]);
    }

    /**
     * @param ConnectionInterface|WsConnection $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo 'Error connection ' . $conn->resourceId . "\n";

        $conn->close();
    }
}
