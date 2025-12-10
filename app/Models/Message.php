<?php

namespace App\Models;

use App\Db;
use App\Models\User;
use App\BaseCollection;

class Message
{
    protected Db $_connection;
    protected int $_fromUserId;

    protected int $_toUserId;

    protected int $_id;

    protected string $_subject;
    protected string $_message;
    protected string $_dateCreated;
    protected string $_status;
    protected User $_fromUser;
    protected User $_toUser;

    public function __construct(Db $db)
    {
        $this->_connection = $db;
    }

    // GET METHODS
    public function getId(): int
    {
        return $this->_id;
    }

    public function getFromUserId(): int
    {
        return $this->_fromUserId;
    }

    public function setFromUserId(int $fromUserId): void
    {
        $this->_fromUserId = $fromUserId;
    }

    public function getToUserId(): int
    {
        return $this->_toUserId;
    }

    public function setToUserId(int $toUserId): void
    {
        $this->_toUserId = $toUserId;
    }

    public function getSubject(): string
    {
        return $this->_subject;
    }

    public function setSubject(string $subject): void
    {
        $this->_subject = $subject;
    }

    public function getFromUser(): User
    {
        if (empty($this->_fromUser)) {
            $this->_fromUser = new User($this->_connection);
            $this->_fromUser->loadUser($this->_fromUserId);
        }
        return $this->_fromUser;
    }

    public function getToUser(): User
    {
        if (empty($this->_toUser)) {
            $this->_toUser = new User($this->_connection);
            $this->_toUser->loadUser($this->_toUserId);
        }
        return $this->_toUser;
    }

    public function getMessage(): string
    {
        return $this->_message;
    }

    // SET METHODS
    public function setMessage(string $message): void
    {
        $this->_message = $message;
    }

    public function getDateCreated(): string
    {
        return $this->_dateCreated;
    }

    public function setDateCreated(string $dateCreated): void
    {
        $this->_dateCreated = $dateCreated;
    }

    public function getStatus(): string
    {
        return $this->_status;
    }

    public function setStatus(string $status): void
    {
        $this->_status = $status;
    }

    // CRUD OPERATIONS

    /**
     * Loads a message from the database by its ID and populates the object's properties with the retrieved data.
     *
     * @param int $messageId The ID of the message to retrieve from the database.
     * @return void This method does not return any value.
     */
    public function loadMessage(int $messageId): void
    {
        $query = "SELECT * FROM `messages` WHERE `id` = ?";
        $this->_connection->query($query, [$messageId]);
        $result = $this->_connection->fetchArray();

        if ($result && count($result)) {

            $this->_id          = (int)$result['id'];
            $this->_fromUserId  = (int)$result['fromUserId'];
            $this->_toUserId    = (int)$result['toUserId'];
            $this->_subject     = $result['subject'];
            $this->_message     = $result['message'];
            $this->_dateCreated = $result['dateCreated'];
            $this->_status      = $result['status'];
        }
    }

    /**
     * Creates a new message record in the database with the given data.
     *
     * @param array $messageData An associative array containing the values for the message record. It should
     *                            include keys for `fromUserId`, `toUserId`, `subject`, `message`, `dateCreated`, and `status`.
     * @return string|int The ID of the newly created message record.
     */
    public function createMessage(array $messageData): string|int
    {
        $query = "INSERT INTO `messages`";
        $query .= "(`fromUserId`, `toUserId`, `subject`, `message`, `dateCreated`, `status`)";
        $query .= "VALUES (?, ?, ?, ?, ?, ?)";
        $this->_connection->query($query, $messageData);
        return $this->_connection->lastInsertID();
    }

    /**
     * Updates a message record in the database with the provided data for a specific message ID.
     *
     * @param array $messageData An associative array where the keys represent the column names and
     *                            the values represent the new values to update for the message record.
     * @param int $messageId The ID of the message to be updated in the database.
     * @return Db The result of the database query execution.
     */
    public function updateMessage(array $messageData, int $messageId): Db
    {
        $query = "UPDATE `messages` SET ";
        foreach ($messageData as $key => $value) {
            $query .= "`$key`='$value',";
        }
        $query = trim($query, ",");
        $query .= " WHERE `id` = $messageId";

        $queryResult = $this->_connection->query($query);
        return $queryResult;
    }

}