<?php

namespace App\Controllers;

use App\Models\Message;
use App\Models\Messages;
use App\Models\Users;
use Exception;
use Symfony\Component\Routing\RouteCollection;

class MessageController
{
    protected $_db;
    public function __construct(){
        global $db;
        $this->_db = $db;
    }

    /**
     * Retrieves and displays a list of user messages.
     *
     * @param RouteCollection $routes The route collection object used for application routing.
     * @return void
     */
    public function listAction(RouteCollection $routes): void
    {
        $messages = new Messages($this->_db, (int) $_SESSION['user_id']);
        $listMessages = $messages->getMessages();
        require_once APP_ROOT . '/views/messagesList.php';
    }

    /**
     * Displays and processes the update for a specific notice while loading associated users.
     *
     * @param int             $id The unique identifier of the notice to be displayed and updated.
     * @param RouteCollection $routes The route collection object used for application routing.
     * @return void
     */
    public function showAction(int $id, RouteCollection $routes): void
    {
        $message = new Message($this->_db);
        $usersModel = new Users($this->_db);

        $message->loadMessage($id);
        $users = $usersModel->loadAllUsers();
        $message->setStatus("READ");
        $message->updateMessage();

        if(!empty($_POST)){

            try {
                $message->setSubject($_POST['subject']);
                $message->setMessage($_POST['message']);
                $message->setStatus($_POST['status']);
                $message->setToUserId($_POST['toUserId']);
                $message->updateMessage();
                $_SESSION['error'] = "Message updated successfully";
            }
            catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        require_once APP_ROOT . '/views/message.php';
    }

    public function addAction(RouteCollection $routes): void
    {
        if(!empty($_POST)){
            try {
                $message = new Message($this->_db);
                $message->setToUserId($_POST['toUserId']);
                $message->setSubject($_POST['subject']);
                $message->setMessage($_POST['message']);
                $message->setStatus("UNREAD");
                $message->createMessage();

                if(!empty($lastInsertID)){
                    header("Location: /message/$lastInsertID");
                    die();
                }
                $_SESSION['error'] = "Messages sent successfully";
            }
            catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        $usersModel = new Users($this->_db);
        $users = $usersModel->loadAllUsers();

        require_once APP_ROOT . '/views/addMessaage.php';
    }

    /**
     * Marks a notice as read by updating its status in the database and returns a JSON response.
     *
     * @param int             $id The unique identifier of the notice to be marked as read.
     * @param RouteCollection $routes The route collection object used for application routing.
     * @return void
     */
    public function markAsRead(int $id, RouteCollection $routes): void
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