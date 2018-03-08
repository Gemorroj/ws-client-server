<?php
namespace WsClientServer;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsConnection;

class Pusher implements MessageComponentInterface
{
    /** @var \SplObjectStorage */
    protected $clients;
    /** @var ConnectionInterface */
    protected $emitter;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * Check is emitter connection
     * @param ConnectionInterface|WsConnection $conn
     * @return bool
     */
    protected function isEmitter(ConnectionInterface $conn)
    {
        /** @var \GuzzleHttp\Psr7\Request $httpRequest */
        $httpRequest = $conn->httpRequest;

        return $conn->remoteAddress === '127.0.0.1' && $httpRequest->getHeaderLine('User-Agent') === 'Ws-EmitterSimple/0.1';
    }

    /**
     * @param ConnectionInterface|WsConnection $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        // if connect emitter
        if ($this->isEmitter($conn)) {
            echo 'New emitter connection ' . $conn->resourceId . "\n";
            $this->emitter = $conn;
        } else {
            echo 'New client connection ' . $conn->resourceId . "\n";
            // if connect all other clients
            $this->clients->attach($conn);
        }
    }

    /**
     * @param ConnectionInterface|WsConnection $conn
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $conn, $msg)
    {
        // if message from emitter
        if ($this->isEmitter($conn)) {
            echo 'New message from emitter ' . $conn->resourceId . ': ' . $msg . "\n";

            /** @var EmitterMessage $emitterMessage */
            $emitterMessage = \json_decode($msg); // emitter sends EmitterMessage object
            foreach ($this->clients as $client) {
                if ($client->resourceId === $emitterMessage->resourceId) { // send emitter message to client. identifying the client by resourceId
                    echo 'Send message to client ' . $client->resourceId . ': ' . $msg . "\n";
                    $client->send($msg);
                    break;
                }
            }
        } else {
            echo 'New message from client ' . $conn->resourceId . ': ' . $msg . "\n";
            echo 'Send message to emitter ' . $this->emitter->resourceId . ': ' . $msg . "\n";
            // if message from client, forward the message to emitter
            $this->emitter->send(\json_encode(new EmitterMessage('message', $conn->resourceId, $msg)));
        }
    }

    /**
     * @param ConnectionInterface|WsConnection $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        // if emitter close connection, drop all clients... this should not happen
        if ($this->isEmitter($conn)) {
            echo 'Close connection emitter ' . $conn->resourceId . "\n";
            $this->clients->removeAll($this->clients);
        } else {
            echo 'Close connection client ' . $conn->resourceId . "\n";

            $msg = \json_encode(new EmitterMessage('close', $conn->resourceId));

            echo 'Send message to emitter ' . $this->emitter->resourceId . ':' . $msg . "\n";
            $this->emitter->send($msg);
        }

        $this->clients->detach($conn);
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
