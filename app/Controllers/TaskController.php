<?php
namespace App\Controllers;

use App\Models\Tasks;
use App\Models\Task;
use App\Models\Users;
use Symfony\Component\Routing\RouteCollection;

class TaskController
{
    protected $tasks;
    protected $_db;
    /**
     * @param int $id
     * @param RouteCollection $routes
     * @return void
     */

    public function __construct(){
        global $db;
        $this->_db = $db;
    }

    public function showAction(int $id, RouteCollection $routes)
    {
        $task = new Task($this->_db);
        $task->loadTask($id);

        if(!empty($_POST)){

            try {
                $task->updateTask($_POST, $task->getId());
                $_SESSION['error'] = "Task updated successfully";
            }
            catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        $usersModel = new Users($this->_db);
        $users = $usersModel->loadAllUsers();

        require_once APP_ROOT . '/views/tasks.php';
    }

    public function listAction(RouteCollection $routes)
    {
        $tasks = new Tasks($this->_db);
        $taskList = $tasks->loadTaskList();
        require_once APP_ROOT . '/views/tasksList.php';

    }

    public function addAction(RouteCollection $routes){

        if(!empty($_POST)){
            try {
                //var_dump($_POST);
                $task = new Task($this->_db);
                $lastInsertID = $task->createTask($_POST);
                var_dump($lastInsertID);
                $_SESSION['error'] = "Task added successfully";
                if(!empty($lastInsertID)){
                    echo "got here";
                    header("Location: /task/$lastInsertID");
                    die();
                }
                exit();
            }
            catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }

            exit();
        }

        $usersModel = new Users($this->_db);
        $users = $usersModel->loadAllUsers();
        require_once APP_ROOT . '/views/addTask.php';
    }
}