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
        return $conn->remoteAddress === '127.0.0.1' && $conn->httpRequest->getHeader('User-Agent') === 'Ws-Emitter/0.1';
    }

    /**
     * @param ConnectionInterface|WsConnection $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        echo 'New connection ' . $conn->resourceId . "\n";

        // if connect emitter
        if ($this->isEmitter($conn)) {
            $this->emitter = $conn;
        } else {
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
        echo 'New message from ' . $conn->resourceId . ': ' . $msg . "\n";

        // if message from emitter
        if ($this->isEmitter($conn)) {
            /** @var EmitterMessage $emitterMessage */
            $emitterMessage = \json_decode($msg); // emitter sends EmitterMessage object
            foreach ($this->clients as $client) {
                if ($client->resourceId === $emitterMessage->resourceId) { // send emitter message to client. identifying the client by resourceId
                    $client->send($msg);
                    break;
                }
            }
        } else {
            // if message from client, forward the message to emitter
            $this->emitter->send(\json_encode(new EmitterMessage('message', $conn->resourceId), $msg));
        }
    }

    /**
     * @param ConnectionInterface|WsConnection $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        echo 'Close connection ' . $conn->resourceId . "\n";

        // if emitter close connection, drop all clients... this should not happen
        if ($this->isEmitter($conn)) {
            $this->clients->removeAll($this->clients);
        } else {
            $this->emitter->send(\json_encode(new EmitterMessage('close', $conn->resourceId)));
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
