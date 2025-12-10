<?php

namespace App\Controllers;
use App\Models\Notice;
use Symfony\Component\Routing\RouteCollection;

class NoticeController
{
    public function markAsRead(RouteCollection $routes)
    {
        global $db;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!empty($_POST['noticeId'])) {
            $noticeId = intval($_POST['noticeId']);
            $notice = new Notice($db);
            $notice->loadNotice($noticeId);

            $notice->setStatus("READ");
            $notice->save();
        }


    }

}