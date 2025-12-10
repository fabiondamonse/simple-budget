<?php

namespace App\Models;

use App\BaseCollection;
use App\Models\User;
use App\Db;

class Users
{
    /**
     * @var Db
     */
    protected Db $_connection;

    protected BaseCollection $_userCollection;

    public function __construct(Db $db)
    {
        $this->_connection = $db;
    }

    public function loadAllUsers() : BaseCollection
    {
        $query = "SELECT `user_id` FROM `users`";
        $this->_connection->query($query);
        $results = $this->_connection->fetchAll();

        if ($results) {
            $tmpUsers = [];
            foreach ($results as $result) {
                $tmpUser = new User($this->_connection);
                $tmpUser->loadUser($result['user_id']);
                $tmpUsers[] = $tmpUser;
            }
            $this->_userCollection = new BaseCollection($tmpUsers);
        }

        return $this->_userCollection;
    }

}