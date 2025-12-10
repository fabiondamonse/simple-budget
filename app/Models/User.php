<?php

namespace App\Models;

use App\baseHelper;
use App\Db;
use Exception;

class User
{
    protected int $_sessionTimeout = 3600;
    protected int $_id;

    protected string $_username;

    protected string $_password;

    protected string $_email;

    protected string $_dateCreated;

    protected string $_dateUpdated;

    protected string $_lastLogin;

    /**
     * @var Db
     */
    protected $_connection;

    public function __construct(Db $connection)
    {
        $this->_connection = $connection;
    }

    // GET METHODS

    public function getId()
    {
        return $this->_id;
    }

    public function setId(int $id)
    {
        $this->_id = $id;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function setUsername(string $username)
    {
        $this->_username = $username;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setPassword(string $password)
    {
        $this->_password = $password;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    // SET METHODS

    public function setEmail(string $email)
    {
        $this->_email = $email;
    }

    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    public function setDateCreated(string $dateCreated)
    {
        $this->_dateCreated = $dateCreated;
    }

    public function getDateUpdated()
    {
        return $this->_dateUpdated;
    }

    public function setDateUpdated(string $dateUpdated)
    {
        $this->_dateUpdated = $dateUpdated;
    }

    public function getLastLogin()
    {
        return $this->_lastLogin;
    }

    public function setLastLogin(string $lastLogin)
    {
        $this->_lastLogin = $lastLogin;
    }

    // CRUD OPERATIONS
    public function createUser(array $userData)
    {

    }

    public function updateUser(array $userData)
    {

    }

    public function deleteUser()
    {

    }

    public function loadUser(int $userId)
    {
        $query = "SELECT * FROM `users` WHERE `user_id` = ?";
        $this->_connection->query($query, [$userId]);
        $result = $this->_connection->fetchArray();

        if ($result) {
            $this->_id          = $result["user_id"];
            $this->_username    = $result["username"];
            $this->_password    = $result["password_hash"];
            $this->_email       = $result["email"];
            $this->_dateCreated = $result["date_created"];
            $this->_dateUpdated = $result["date_updated"];
            $this->_lastLogin   = $result["last_login"];
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    public function login(string $username, string $password): array
    {
        $result        = [];
        $passwordHash  = "";
        $userNameQuery = "SELECT * FROM `users` WHERE `username` = ?";

        try {
            $this->_connection->query($userNameQuery, [$username]);
            $result = $this->_connection->fetchArray();

            // if username found go on to check password hash
            if (!empty($result) && count($result) > 0) {
                $passwordHash = $result["password_hash"];
                $verify       = password_verify($password, $passwordHash);
                if ($verify) {
                    $this->_id          = $result["user_id"];
                    $this->_username    = $result["username"];
                    $this->_password    = $result["password_hash"];
                    $this->_email       = $result["email"];
                    $this->_dateCreated = (!empty($result["date_created"]) ? $result["date_created"] : date("Y-m-d H:i:s"));
                    $this->_dateUpdated = (!empty($result["date_updated"]) ? $result["date_updated"] : date("Y-m-d H:i:s"));
                    $this->_lastLogin   = (!empty($result["last_login"]) ? $result["last_login"] : date("Y-m-d H:i:s"));

                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION["user_id"]  = $result["user_id"];
                    $_SESSION["username"] = $result["username"];
                    $_SESSION["loggedIn"] = true;

                    $updateQuery = "UPDATE `users` SET `last_login` = ? WHERE `user_id` = ?";
                    $this->_connection->query($updateQuery, [date("Y-m-d H:i:s"), $result["user_id"]]);
                    $result = ['success' => true];
                } else {
                    $result = ['error' => 'Invalid password', 'success' => false];
                }
            } else {
                $result = ['error' => 'Invalid username or password', 'success' => false];
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $redirectUrl       = (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : baseHelper::getBaseUrl());
            header("Location: $redirectUrl");
            die();
        }

        return $result;
    }

}