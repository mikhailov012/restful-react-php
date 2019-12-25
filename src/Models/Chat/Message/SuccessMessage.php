<?php

namespace App\Models\Chat\Message;

use Colors\Color;

class SuccessMessage extends Message implements MessageInterface
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function getMessage()
    {
        return $this->getColorMessage()->fg('blue');
    }
}