<?php


namespace App\Models\Chat;

use App\Models\Chat\Message\InfoMessage;
use App\Models\Chat\Message\NotifyMessage;
use App\Models\Chat\Message\SuccessMessage;
use App\Models\Chat\Message\WarningMessage;
use Colors\Color;
use React\Socket\ConnectionInterface;
use SplObjectStorage;

/**
 * Created by PhpStorm.
 * User: amikhailov
 * Date: 23.12.2019
 * Time: 13:05
 */

class ConnectionPool
{
    /** @var SplObjectStorage */
    protected $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }

    public function add(ConnectionInterface $connection)
    {
        $connection->write($this->infoMessage("Welcome to chat"));
        $connection->write($this->notifyMessage("Enter your name:"));

        $this->setConnectionName($connection, '');
        $this->initEvents($connection);
    }

    private function successMessage($message)
    {
        return (new SuccessMessage($message))->getMessage();
    }

    private function infoMessage($message)
    {
        return (new InfoMessage($message))->getMessage();
    }

    private function warningMessage($message)
    {
        return (new WarningMessage($message))->getMessage();
    }

    private function notifyMessage($message)
    {
        return(new NotifyMessage($message))->getMessage();
    }

    private function initEvents(ConnectionInterface $connection)
    {
        $connection->on('data', function ($data) use ($connection) {

            $name = $this->getConnectionName($connection);

            if (empty($name)) {
                $this->addNewMember($connection, $data);
                return;
            }

            $this->sendAll($this->successMessage("$name: $data"), $connection);
        });

        $connection->on('close', function () use ($connection) {
            $name = $this->getConnectionName($connection);

            $this->connections->offsetUnset($connection);
            $this->sendAll($this->warningMessage("User $name leaves the chat"), $connection);
        });
    }

    private function addNewMember(ConnectionInterface $connection, $name)
    {
        $name = str_replace(["\n", "\r"], '', $name);
        $this->setConnectionName($connection, $name);
        $this->sendAll($this->infoMessage("User $name join the chat"), $connection);
    }

    private function getConnectionName(ConnectionInterface $connection)
    {
        return $this->connections->offsetGet($connection);
    }

    private function setConnectionName(ConnectionInterface $connection, $name)
    {
        $this->connections->offsetSet($connection, $name);
    }

    private function sendAll($message, ConnectionInterface $except)
    {
        foreach ($this->connections as $connection) {
            if ($connection != $except) {
                $connection->write($this->notifyMessage($message));
            }
        }
    }
}