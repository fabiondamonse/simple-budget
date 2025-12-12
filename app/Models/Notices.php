<?php

namespace App\Models;

use App\Db;
use App\Models\Notice;
use App\BaseCollection;
class Notices
{
    /**
     * @var Db
     */
    protected Db $_connection;

    /**
     * @var int
     */
    protected Int $_userId;

    /**
     * @var BaseCollection
     */
    protected BaseCollection $_noticeCollection;

    public function __construct(Db $db, int $userId = null)
    {
        $this->_connection = $db;
        if(!empty($userId)){
            $this->_userId = $userId;
        }
    }

    /**
     * Retrieves a collection of notices associated with the given user ID.
     *
     * @return BaseCollection Returns a collection of Notice objects based on the user's ID.
     */
    public function getNotices(): BaseCollection{

        $query = "SELECT `id` FROM notices where `user_id` = ?";
        $this->_connection->query($query, [$this->_userId]);
        $results = $this->_connection->fetchAll();

        if($results){
            $tmpNotices = [];
            foreach($results as $result){
                $tmpNotice = new Notice($this->_connection);
                $tmpNotice->loadNotice($result['id']);
                $tmpNotices[] = $tmpNotice;
            }
            $this->_noticeCollection = new BaseCollection($tmpNotices);
        }
        else{
            $this->_noticeCollection = new BaseCollection();
        }

        return $this->_noticeCollection;
    }

    public function getUnReadNotices(): BaseCollection{
        $query = "SELECT `id` FROM notices where `user_id` = ? AND `status` = 'UNREAD'";
        $this->_connection->query($query, [$this->_userId]);
        $results = $this->_connection->fetchAll();

        if($results){
            $tmpNotices = [];
            foreach($results as $result){
                $tmpNotice = new Notice($this->_connection);
                $tmpNotice->loadNotice($result['id']);
                $tmpNotices[] = $tmpNotice;
            }
            $this->_noticeCollection = new BaseCollection($tmpNotices);
        }
        else{
            $this->_noticeCollection = new BaseCollection();
        }

        return $this->_noticeCollection;
    }
}