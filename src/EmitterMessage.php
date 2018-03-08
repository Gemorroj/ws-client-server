<?php
namespace WsClientServer;

class EmitterMessage
{
    public $resourceId;
    public $msg;
    public $type;

    public function __construct(string $type, int $resourceId, string $msg = null)
    {
        $this->type = $type;
        $this->resourceId = $resourceId;
        $this->msg = $msg;
    }
}
