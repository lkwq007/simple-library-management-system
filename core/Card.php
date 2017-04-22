<?php

/**
 * Created by PhpStorm.
 * User: lnyan
 * Date: 2017/4/16
 * Time: 21:56
 */
class Card
{
    public $cno;
    public $name;
    public $department;
    public $type;

    //public $bound;

    public function __construct($cno, $name = null, $department = null, $type = null)
    {
        if (is_null($name)) {
            $result = $this->fetch($cno);
            if (count($result) == 0) {
                $this->cno = null;
            } else {
                $this->cno = $cno;
                $this->department = $result[0]['department'];
                $this->name = $result[0]['name'];
                $this->type = $result[0]['type'];
            }
        } else {
            $this->cno = $cno;
            $this->department = $department;
            $this->name = $name;
            $this->type = $type;
            if (!$this->insert()) {
                $this->cno = null;
            }
        }
    }

    public function insert()
    {
        global $db;
        $statement = $db->prepare("INSERT INTO card VALUES (?,?,?,?)");
        $statement->execute(array($this->cno, $this->name, $this->department, $this->type));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function fetch($cno)
    {
        global $db;
        $statement = $db->prepare("SELECT * FROM card WHERE cno=?");
        $statement->execute(array($cno));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function fetch_all()
    {
        global $db;
        $statement = $db->prepare("SELECT * FROM card");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function delete($cno)
    {
        global $db;
        $statement = $db->prepare("DELETE FROM card WHERE cno=?");
        $statement->execute(array($cno));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function update($cno, $name, $department, $type)
    {
        global $db;
        $statement = $db->prepare("UPDATE card SET name=?,department=?,type=? WHERE cno=?");
        $statement->execute(array($name, $department, $type, $cno));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}