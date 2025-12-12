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

    /**
     * @var BaseCollection
     */
    protected BaseCollection $_messageCollection;

    /**
     * @var BaseCollection
     */
    protected BaseCollection $_unreadMessageCollection;

    /**
     * @var int
     */
    protected int $_userId;

    public function __construct(Db $db, $userId = null)
    {
        $this->_connection = $db;
        $this->_userId     = $userId;
    }

    /**
     * Retrieves messages for a specified user.
     *
     * @return BaseCollection A collection of Message objects associated with the specified user.
     */
    public function getMessages(): BaseCollection
    {
        $query = "SELECT `id` FROM messages where `toUserId` = ?";
        $this->_connection->query($query, [$this->_userId]);
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

    /**
     * Retrieves the collection of unread messages for the user.
     *
     * This method queries the database to fetch messages that are marked as
     * unread and belong to the currently authenticated user. It initializes
     * each unread message and stores them in a collection, which is then returned.
     *
     * @return BaseCollection A collection of unread messages for the user.
     *                         Returns an empty collection if no unread messages are found.
     */
    public function getUserUnreadMessages(): BaseCollection
    {
        $query = "SELECT `id` FROM messages where `toUserId` = ? AND `status` = 'UNREAD'";
        $this->_connection->query($query, [$this->_userId]);
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