<?php
namespace WsClientServer;

class EmitterMessage
{
    public $resourceId;
    public $msg;
    public $type;

    public function __construct(string $type, int $resourceId, ?string $msg = null)
    {
        $this->type = $type;
        $this->resourceId = $resourceId;
        $this->msg = $msg;
    }

    /**
     * @param string $json
     * @return static
     */
    public static function createFromJson(string $json) : self
    {
        $jsonObject = \json_decode($json);
        return new static(
            $jsonObject->type,
            $jsonObject->resourceId,
            $jsonObject->msg
        );
    }
}
