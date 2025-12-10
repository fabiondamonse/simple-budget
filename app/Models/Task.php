<?php

namespace App\Models;

use App\Db;
use App\Models\User;

class Task
{
    /**
     * @var int
     */
    protected int $_id;

    /**
     * @var string
     */
    protected string $_name;

    /**
     * @var string
     */
    protected string $_description;

    /**
     * @var string
     */
    protected string $_dateCreated;
    protected string $_dateUpdated;

    /**
     * @var string
     */
    protected string $_dueDate;

    /**
     * @var string
     */
    protected string $_status;

    /**
     * @var Db
     */
    protected Db $_connection;

    /**
     * @var User
     */
    protected User $_createdByUser;

    /**
     * @var int
     */
    protected int $_createdByUserId;

    /**
     * @var User
     */
    protected User $_assignedToUser;

    /**
     * @var int
     */
    protected int $_assignedToUserId;

    /**
     * @param Db $connection
     */
    public function __construct(Db $connection)
    {
        $this->_connection = $connection;
    }

    // GET METHODS

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getDateCreated(): string
    {

        return date('Y-m-d', strtotime($this->_dateCreated));
    }

    public function getDateUpdated(): string
    {
        return date('Y-m-d', strtotime($this->_dateUpdated));
    }

    public function getDescription(): string
    {
        return $this->_description;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getCreatedByUserId(): int
    {
        return $this->_createdByUserId;
    }

    // SET METHODS

    public function getAssignedToUserId(): int
    {
        return $this->_assignedToUserId;
    }

    public function getDueDate()
    {
        return date('Y-m-d', strtotime($this->_dueDate));
    }

    public function setDueDate($dueDate)
    {
        $this->_dueDate = $dueDate;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    // CRUD OPERATIONS
    public function createTask(array $taskData)
    {
        $name        = $taskData['name'];
        $description = $taskData['description'];
        $status      = $taskData['status'];
        $createdBy   = $taskData['created_by'];
        $assignedTo  = $taskData['assigned_to'];
        $dueDate     = $taskData['dueDate'];
        $query       = "INSERT INTO `budget_app`.`tasks`";
        $query       .= "(`name`, `description`, `dueDate`, `status`, `created_by`, `assigned_to`)";
        $query       .= "VALUES ('$name', '$description', '$dueDate', '$status', '$createdBy', '$assignedTo')";
        $this->_connection->query($query);
        return $this->_connection->lastInsertID();
    }

    public function updateTask(array $taskData, int $taskId): void
    {
        $taskData['dateUpdated'] = date('Y-m-d H:i:s');
        $query                   = "UPDATE `budget_app`.`tasks` SET ";
        foreach ($taskData as $key => $value) {
            $query .= "`$key`='$value',";
        }
        $query = trim($query, ",");
        $query .= " WHERE `id` = $taskId";

        $queryResult = $this->_connection->query($query);
    }

    public function deleteTask(int $taskId)
    {

    }

    /**
     * @param int $taskId
     * @return void
     */
    public function loadTask(int $taskId): void
    {
        $query = "SELECT * FROM `tasks` WHERE `id` = ?";
        $this->_connection->query($query, [$taskId]);
        $result = $this->_connection->fetchArray();

        if ($result && count($result)) {

            $this->_id               = $result['id'];
            $this->_name             = $result['name'];
            $this->_description      = $result['description'];
            $this->_dateCreated      = $result['dateCreated'];
            $this->_dateUpdated      = $result['dateUpdated'];
            $this->_dueDate          = $result['dueDate'];
            $this->_status           = $result['status'];
            $this->_createdByUserId  = $result['created_by'];
            $this->_assignedToUserId = $result['assigned_to'];
            $this->loadCreatedByUser();
            $this->loadAssignedToUser();
        }
    }

    public function loadCreatedByUser(): User
    {
        if (empty($this->_createdByUser)) {
            $this->_createdByUser = new User($this->_connection);
            if (!empty($this->_createdByUserId)) {
                $this->_createdByUser->loadUser($this->_createdByUserId);
            }
        }
        return $this->_createdByUser;
    }

    public function loadAssignedToUser(): User
    {
        if (empty($this->_assignedToUser)) {
            $this->_assignedToUser = new User($this->_connection);
            if (!empty($this->_assignedToUserId)) {
                $this->_assignedToUser->loadUser($this->_assignedToUserId);
            }
        }
        return $this->_assignedToUser;
    }
}