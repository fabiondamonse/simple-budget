<?php

namespace App\Controllers;

use App\Models\Notice;
use App\Models\Notices;
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
                $notice->saveNotice();
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