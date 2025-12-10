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

    protected BaseCollection $_noticeCollection;

    public function __construct(Db $db)
    {
        $this->_connection = $db;
    }

    /**
     * Retrieves a collection of notices associated with the given user ID.
     *
     * @param int $userId The ID of the user for whom notices are being retrieved.
     * @return BaseCollection Returns a collection of Notice objects based on the user's ID.
     */
    public function getNotices(int $userId): BaseCollection{
        $query = "SELECT `id` FROM notices where `user_id` = ?";
        $this->_connection->query($query, [$userId]);
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