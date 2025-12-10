<?php

namespace App\Models;

use App\BaseCollection;
use App\Db;
use App\Models\Task;

class Tasks
{

    /**
     * @var Db
     */
    protected Db $_connection;

    protected BaseCollection $_taskCollection;

    public function __construct(Db $db)
    {
        $this->_connection = $db;
    }
    public function loadTaskList(): BaseCollection
    {
        $currentDate = date("Y-m-d H:i:s");
        $query       = 'SELECT * FROM tasks';
        $this->_connection->query($query);
        $results = $this->_connection->fetchAll();
        if($results){
            $tmpTasks = [];
            foreach ($results as $result){
                $tmpTask = new Task($this->_connection);
                $tmpTask->loadTask($result['id']);
                $tmpTasks[] = $tmpTask;
            }

            $this->_taskCollection = new BaseCollection($tmpTasks);
        }

        return $this->_taskCollection;
    }

    public function loadUpComingTasks(): BaseCollection
    {
        $currentDate = date("Y-m-d H:i:s");
        $query       = 'SELECT `id` FROM tasks where `dueDate` >= ?';
        $this->_connection->query($query, [$currentDate]);
        $results = $this->_connection->fetchAll();


        if($results){
            $tmpTasks = [];
            foreach ($results as $result){
                $tmpTask = new Task($this->_connection);
                $tmpTask->loadTask($result['id']);
                $tmpTasks[] = $tmpTask;
            }
            $this->_taskCollection = new BaseCollection($tmpTasks);
        }
        else{
            $this->_taskCollection = new BaseCollection();
        }

        return $this->_taskCollection;
    }

}