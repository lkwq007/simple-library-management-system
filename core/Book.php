<?php

/**
 * Created by PhpStorm.
 * User: lnyan
 * Date: 2017/4/16
 * Time: 19:37
 */
class Book
{
    public $bno;
    public $category;
    public $title;
    public $press;
    public $year;
    public $author;
    public $price;
    public $total;
    public $stock;
    public $num;

    public function __construct($bno, $category = null, $title = null, $press = null, $year = null, $author = null, $price = null, $num = null)
    {
        $result = $this->fetch($bno);
        if (is_null($category)) {
            if (count($result) == 0) {
                $this->id = null;
            } else {
                $this->category = $result[0]['category'];
                $this->title = $result[0]['title'];
                $this->press = $result[0]['press'];
                $this->year = $result[0]['year'];
                $this->author = $result[0]['author'];
                $this->price = $result[0]['price'];
                $this->total = $result[0]['total'];
                $this->stock = $result[0]['stock'];
                $this->num = $result[0]['stock'];
            }
        } else {
            if (count($result == 0)) {
                $this->bno = $bno;
                $this->category = $category;
                $this->title = $title;
                $this->press = $press;
                $this->year = $year;
                $this->author = $author;
                $this->price = $price;
                $this->stock = $num;
                $this->total = $num;
                if (!$this->insert()) {
                    $this->bno = null;
                }
            } else {
                $this->add_total($bno, $num);
            }
        }
    }

    public static function fetch($bno)
    {
        global $db;
        $statement = $db->prepare("SELECT * FROM book WHERE bno=?");
        $statement->execute(array($bno));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert()
    {
        global $db;
        $statement = $db->prepare("INSERT INTO book VALUES (?,?,?,?,?,?,?,?,?)");
        $statement->execute(array($this->bno, $this->category, $this->title, $this->press, $this->year, $this->author, $this->price, $this->total, $this->stock));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function add_total($bno, $num)
    {
        global $db;
        $statement = $db->prepare("UPDATE book SET stock=stock+?,total=total+? WHERE bno=?");
        $statement->execute(array($num, $num, $bno));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function patch($str)
    {
        $data = str_getcsv($str);
        $success=0;
        $fail=0;
        $fail_log=array();
        foreach ($data as $item) {
            $result = self::fetch($item[0]);
            if(count($result)==0)
            {
                $book=new Book($item[0],$item[1],$item[2],$item[3],$item[4],$item[5],$item[6],$item[7]);
                if(is_null($book->bno))
                {
                    array_push($fail_log,$item[0]);
                    $fail++;
                }
                else
                {
                    $success++;
                }
                unset($book);
            }
            else
            {
                if(self::add_total($item[0],$item[7]))
                {
                    $success++;
                }
                else
                {
                    array_push($fail_log,$item[0]);
                    $fail++;
                }
            }
        }
        return array("success"=>$success,"fail"=>$fail,"log"=>$fail_log);
    }

    public static function borrow($bno)
    {
        global $db;
        $statement = $db->prepare("UPDATE book SET stock=stock-1 WHERE bno=?");
        $statement->execute(array($bno));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function ret($bno)
    {
        global $db;
        $statement = $db->prepare("UPDATE book SET stock=stock+1 WHERE bno=?");
        $statement->execute(array($bno));
        $rows = $statement->rowCount();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function search($category = "", $title = "", $press = "", $year_start = 0, $year_end = 99999, $author = "", $price_start = "0", $price_end = "99999.99", $page = 0)
    {
        global $db;
        $statement = $db->prepare("SELECT * FROM book WHERE category LIKE :category AND title LIKE :title AND press LIKE :press AND author LIKE :author AND year BETWEEN :yearstart AND :yearend AND price BETWEEN :pricestart AND :priceend");
        $statement->execute(array(":category" => "%" . $category . "%", ":title" => "%" . $title . "%", ":press" => "%" . $press . "%", ":author" => "%" . $author . "%", ":yearstart" => $year_start, ":yearend" => $year_end, ":pricestart" => $price_start, ":priceend" => $price_end));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}