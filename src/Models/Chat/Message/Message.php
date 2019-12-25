<?php


namespace App\Models\Chat\Message;


use Colors\Color;

class Message
{
    /** @var string */
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function getColorMessage(): Color
    {
        return (new Color($this->message  . "\n"));
    }
}