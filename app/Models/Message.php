<?php

/**
 * Class Message
 *
 * Represents a message entity in the application, providing methods for retrieving and manipulating message data.
 * Includes functionality for database operations such as loading, creating, and updating message records.
 */

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

    public function createMessage(array $messageData = []): string|int
    {
        if(empty($messageData)){
            $messageData['fromUserId'] = $_SESSION['user_id'];
            $messageData['toUserId'] = $this->_toUserId;
            $messageData['subject'] = $this->_subject;
            $messageData['message'] = $this->_message;
            $messageData['status'] = $this->_status;
        }

        $query = "INSERT INTO `messages`";
        $query .= "(`fromUserId`, `toUserId`, `subject`, `message`, `dateCreated`, `status`)";
        $query .= "VALUES (?, ?, ?, ?, NOW(), ?)";
        $this->_connection->query($query, $messageData);
        return $this->_connection->lastInsertID();
    }

    /**
     * Updates an existing message record in the database with the current object properties.
     *
     * The method updates the `fromUserId`, `toUserId`, `message`, `subject`, and `status` fields
     * in the `messages` table for the message identified by its `id`.
     * It uses the database connection to execute the update query.
     * If an error occurs during the update, it captures the exception and stores the error message in the session.
     *
     * @return void
     */
    public function updateMessage(): Void
    {
        $sql = "UPDATE `messages`
            SET
                    `fromUserId` = $this->_fromUserId,
                    `toUserId` = $this->_toUserId,
                    `message` = \"$this->_message\",
                    `subject` = \"$this->_subject\",
                    `status` = \"$this->_status\"
            WHERE `id` = $this->_id;";

        try {
            $this->_connection->query($sql);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }

    public function deleteMessage(int $messageId)
    {
        $query = "DELETE FROM `messages` WHERE `id` = ?";
        $this->_connection->query($query, [$messageId]);
    }
}