<?php
namespace App\Models;

use App\Db;
use App\Models\User;
use App\BaseCollection;
use Exception;

/**
 * Class Notice
 *
 * Represents a notice entity with properties and methods to manage its data
 * and interaction with a database.
 */
class Notice
{
    protected Db $_connection;
    protected int $_userId;

    protected string $_id;

    protected string $_name;
    protected string $_message;
    protected string $_dateCreated;
    protected string $_status;

    public function __construct(Db $connection)
    {
        $this->_connection = $connection;
    }

    // GET METHODS
    public function getId()
    {
        return $this->_id;
    }

    public function setId(string $id): void
    {
        $this->_id = $id;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getDateCreated(string $format = "Y/m/d")
    {
        $date = date_create($this->_dateCreated);
        return date_format($date, $format);
    }

    // SET METHODS

    public function setDateCreated(string $dateCreated): void
    {
        $this->_dateCreated = $dateCreated;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setStatus(string $status): void
    {
        $this->_status = $status;
    }

    public function setUser(int $userId): void
    {
        $this->_userId = $userId;
    }

    public function loadNotice(int $noticeId): void
    {
        $query = "SELECT * FROM `notices` WHERE `id` = ?";
        $this->_connection->query($query, [$noticeId]);
        $result = $this->_connection->fetchArray();

        if ($result && count($result)) {

            $this->_id          = $result['id'];
            $this->_name        = $result['name'];
            $this->_message     = $result['message'];
            $this->_dateCreated = $result['dateCreated'];
            $this->_status      = $result['status'];
            $this->_userId      = $result['user_id'];
        }
    }

    // CRUD OPERATIONS

    public function saveNotice(): void
    {
        $sql = "";

        // check if current object has id (saved in the DB)
        if (!empty($this->_id)) {
            $sql = "UPDATE `notices`
            SET
                    `id` = $this->_id,
                    `user_id` = $this->_userId,
                    `name` = \"$this->_name\",
                    `message` = \"$this->_message\",
                    `status` = \"$this->_status\"
            WHERE `id` = $this->_id;";
        }

        try {
            $this->_connection->query($sql);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }

    public function getMessage()
    {
        return $this->_message;
    }
}