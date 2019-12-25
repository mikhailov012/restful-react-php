<?php


namespace App\Models\Chat\Message;


class NotifyMessage extends Message implements MessageInterface
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function getMessage()
    {
        return $this->getColorMessage()->fg('light_gray');
    }
}