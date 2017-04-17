<?php

/**
 * Created by PhpStorm.
 * User: lnyan
 * Date: 2017/4/16
 * Time: 22:56
 */
class Borrow
{
    public $cno;
    public $bno;
    public $admin;
    public $borrow_date;
    public $return_date;
    public $uuid;

    public function __construct($cno, $bno, $admin)
    {
        date_default_timezone_set('Asia/Shanghai');
        if(Book::borrow($bno))
        {
            $this->cno = $cno;
            $this->bno = $bno;
            $this->admin = $admin;
            $now = time();
            $this->borrow_date = date("Y-m-d H:i:s", $now);
            $this->return_date = date("Y-m-d H:i:s", $now + 40 * 24 * 3600);
            $this->uuid=self::get_id();
            if (!$this->insert()) {
                $this->uuid = null;
                Book::ret($bno);
            }
        }
        else
        {
            $this->uuid=null;
        }

    }

    public function insert()
    {
        global $db;
        $statement = $db->prepare("INSERT INTO borrow VALUES (?,?,?,?,?,?)");
        $statement->execute(array($this->cno, $this->bno, $this->admin, $this->borrow_date,$this->return_date,$this->uuid));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_id()
    {
        global $db;
        $statement = $db->prepare("SELECT UUID()");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }

    public static function fetch($cno)
    {
        global $db;
        $statement = $db->prepare("SELECT * FROM borrow WHERE cno=?");
        $statement->execute(array($cno));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function delete_id($uuid,$bno)
    {
        global $db;
        $statement = $db->prepare("DELETE FROM borrow WHERE uuid=?");
        $statement->execute(array($uuid));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            Book::ret($bno);
            return true;
        } else {
            return false;
        }
    }

    public static function delete($cno,$bno)
    {
        global $db;
        $statement = $db->prepare("DELETE FROM borrow WHERE cno=? AND bno=?");
        $statement->execute(array($cno,$bno));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            Book::ret($bno,$rows);
            return true;
        } else {
            return false;
        }
    }
}