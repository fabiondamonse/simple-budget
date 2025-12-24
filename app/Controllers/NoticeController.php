<?php

namespace App\Controllers;

use App\Models\Notice;
use App\Models\Notices;
use App\Models\Users;
use Exception;
use Symfony\Component\Routing\RouteCollection;

class NoticeController
{
    protected $_db;
    public function __construct(){
        global $db;
        $this->_db = $db;
    }

    public function listAction(RouteCollection $routes){
        $notices = new Notices($this->_db, (int) $_SESSION['user_id']);
        $listNotices = $notices->getNotices();
        require_once APP_ROOT . '/views/noticesList.php';
    }

    public function showAction(int $id, RouteCollection $routes){
        $notice = new Notice($this->_db);
        $usersModel = new Users($this->_db);

        $notice->loadNotice($id);
        $users = $usersModel->loadAllUsers();

        if(!empty($_POST)){

            try {
                $notice->setName($_POST['name']);
                $notice->setStatus($_POST['status']);
                $notice->setUser($_POST['assigned_to']);
                $notice->setMessage($_POST['message']);
                $notice->updateNotice();
                $_SESSION['error'] = "Notice updated successfully";
            }
            catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        require_once APP_ROOT . '/views/notice.php';
    }

    public function addAction(RouteCollection $routes){
        if(!empty($_POST)){
            try {
                $notice = new Notice($this->_db);
                $notice->setName($_POST['name']);
                $notice->setStatus($_POST['status']);
                $notice->setUser($_POST['assigned_to']);
                $notice->setMessage($_POST['message']);
                $lastInsertID = $notice->createNotice();

                if(!empty($lastInsertID)){
                    header("Location: /notice/$lastInsertID");
                    die();
                }
                $_SESSION['error'] = "Notice created successfully";
            }
            catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        $usersModel = new Users($this->_db);
        $users = $usersModel->loadAllUsers();

        require_once APP_ROOT . '/views/addNotice.php';
    }

    public function markAsRead(int $id, RouteCollection $routes)
    {
        $data = ["message" => "", "status" => ""];

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            if (!empty($id)) {
                $noticeId = intval($id);
                $notice   = new Notice($this->_db);
                $notice->loadNotice($noticeId);
                $notice->setStatus("READ");
                $notice->updateNotice();
            }
            $data["status"] = "success";
        } catch (Exception $e) {
            $data["message"] = $e->getMessage();
            $data["status"]  = "error";
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }


}