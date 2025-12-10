<?php

namespace App\Models;

use App\BaseCollection;
use App\Db;
use App\Models\Message;

class Messages
{
    /**
     * @var Db
     */
    protected Db $_connection;

    protected BaseCollection $_messageCollection;

    protected BaseCollection $_unreadMessageCollection;

    public function __construct(Db $db)
    {
        $this->_connection = $db;
    }

    /**
     * Retrieves messages for a specified user.
     *
     * @param int $userId The ID of the user for whom messages are to be retrieved.
     * @return BaseCollection A collection of Message objects associated with the specified user.
     */
    public function getMessages(int $userId): BaseCollection
    {
        $query = "SELECT `id` FROM messages where `toUserId` = ?";
        $this->_connection->query($query, [$userId]);
        $results = $this->_connection->fetchAll();

        if ($results) {
            $tmpMessages = [];
            foreach ($results as $result) {
                $tmpMessage = new Message($this->_connection);
                $tmpMessage->loadMessage($result['id']);
                $tmpMessages[] = $tmpMessage;
            }
            $this->_messageCollection = new BaseCollection($tmpMessages);
        } else {
            $this->_messageCollection = new BaseCollection();
        }

        return $this->_messageCollection;
    }

    public function getUserUnreadMessages(int $userId): BaseCollection
    {
        $query = "SELECT `id` FROM messages where `toUserId` = ? AND `status` = 'UNREAD'";
        $this->_connection->query($query, [$userId]);
        $results = $this->_connection->fetchAll();

        if (!empty($results)) {
            $tmpUnreadMessages = [];
            foreach ($results as $result) {
                $tmpMessage = new Message($this->_connection);
                $tmpMessage->loadMessage($result['id']);
                $tmpUnreadMessages[] = $tmpMessage;
            }
            $this->_unreadMessageCollection = new BaseCollection($tmpUnreadMessages);
        } else {
            $this->_unreadMessageCollection = new BaseCollection();
        }
        return $this->_unreadMessageCollection;
    }

}