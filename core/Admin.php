<?php

/**
 * Created by PhpStorm.
 * User: lnyan
 * Date: 2017/4/16
 * Time: 19:21
 */
class Admin
{
    public $id;
    private $pwd;
    public $name;
    public $contact;

    public function __construct($id, $pwd = null, $name = null, $contact = null)
    {
        if (is_null($pwd)) {
            $result = $this->fetch($id);
            if (count($result) == 0) {
                $this->id = null;
            } else {
                $this->id = $id;
                $this->pwd = $result[0]['pwd'];
                $this->name = $result[0]['name'];
                $this->contact = $result[0]['contact'];
            }
        } else {
            $this->id = $id;
            $this->pwd = $pwd;
            $this->name = $name;
            $this->contact = $contact;
            if(!$this->insert())
            {
                $this->id=null;
            }
        }
    }

    public function insert()
    {
        global $db;
        $statement = $db->prepare("INSERT INTO admin VALUES (?,?,?,?)");
        $statement->execute(array($this->id, $this->pwd, $this->name, $this->contact));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function fetch($id)
    {
        global $db;
        $statement = $db->prepare("SELECT * FROM admin WHERE id=?");
        $statement->execute(array($id));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function verify($pwd)
    {
        if(is_null($this->id))
        {
            return false;
        }
        if ($pwd == $this->pwd) {
            return true;
        }
        return false;
    }

}
