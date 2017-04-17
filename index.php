<?php
require_once 'config.php';
$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8', $db_user, $db_password);
//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

require_once 'core/Admin.php';
require_once 'core/Book.php';
require_once 'core/Card.php';
require_once 'core/Borrow.php';
require 'flight/Flight.php';

session_start();
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = random_bytes(32);
}

Flight::set('flight.views.path', 'template');

Flight::route('/', function () {
    echo 'hello world!';
});

Flight::route('/panel',function (){
    echo 'panel';
});

Flight::route('/login', function () {
    if (isset($_SESSION['id'])) {
        Flight::redirect('/panel');
    } else {
        //echo 'need login';
        Flight::render('login', array('test' => '233'));
    }
});

Flight::route('/logout', function () {
    unset($_SESSION['id']);
    unset($_SESSION['name']);
    unset($_SESSION['contact']);
});

Flight::route('POST /auth', function () {
    $id = $_POST['id'];
    $pwd = $_POST['pwd'];
    $admin = new Admin($id);
    if ($admin->verify($pwd)) {
        $_SESSION['id'] = $id;
        $_SESSION['name'] = $admin->name;
        $_SESSION['contact'] = $admin->contact;
        Flight::json(array('status' => 0));
    } else {
        Flight::json(array('status' => 1));
    }
});

Flight::route('POST /admin/add/@id', function ($id) {
    if (is_null($_POST['pwd']) || is_null($_POST['name']) || is_null($_POST['contact'])) {
        Flight::json(array('status' => 2));
        return false;
    }
    $user = new Admin($id);
    if (is_null($user->id)) {
        unset($user);
        $user = new Admin($id, $_POST['pwd'], $_POST['name'], $_POST['contact']);
        if (is_null($user->id)) {
            Flight::json(array('status' => 3));
        } else {
            Flight::json(array('status' => 0));
        }
    } else {
        Flight::json(array('status' => 1));
    }
});

Flight::route('/admin/delete/@id', function ($id) {
    if (isset($_SESSION['id'])) {
        if ($_SESSION['id'] == $id) {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
            return false;
        }
        if (Admin::delete($id)) {
            Flight::json(array('status' => 0, 'info' => 'Success'));
        } else {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
        }
    } else {
        Flight::json(array('status' => 2, 'info' => 'Not permitted'));
    }
});

Flight::route('/admin/info', function () {
    if (isset($_SESSION['id'])) {
        $result = Admin::fetch_all();
        Flight::json($result);
    } else {
        Flight::json(array('info' => 'Not permitted'));
    }
});git 

Flight::route('/admin/info/@id', function ($id) {
    if (isset($_SESSION['id'])) {
        $result = Admin::fetch($id);
        Flight::json($result);
    } else {
        Flight::json(array('info' => 'Not permitted'));
    }
});

Flight::route('POST /book/add/@bno', function ($bno) {
    if (isset($_SESSION['id'])) {
        $json = Flight::request()->data;
        $info = json_decode($json);
        $book = new Book($bno, $info->category, $info->title, $info->press, $info->year, $info->author, $info->price, $info->num);
        if (is_null($book->bno)) {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
        } else {
            Flight::json(array('status' => 0, 'info' => 'Success'));
        }
    } else {
        Flight::json(array('status' => 2, 'info' => 'Not permitted'));
    }
});

Flight::route('POST /book/add', function () {
    if (isset($_SESSION['id'])) {
        $files = Flight::request()->files;
        var_dump($files);
        //$info=json_decode($json);
        //$book=new Book($bno,$info->category,$info->title,$info->press,$info->year,$info->author,$info->price,$info->num);
        if (false) {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
        } else {
            Flight::json(array('status' => 0, 'info' => 'All Success'));
        }
    } else {
        Flight::json(array('status' => 2, 'info' => 'Not permitted'));
    }
});

Flight::route('/book/info', function () {
    $result = Book::search();
    Flight::json($result);
});

Flight::route('POST /book/search', function () {
    $json = Flight::request()->data;
    $data = json_decode($json, true);
    $temp = array('category' => '', 'title' => '', 'press' => '', 'author' => '', 'year_start' => 0, 'year_end' => 99998, 'price_start' => 0, 'price_end' => 99999.98);
    foreach ($temp as $key => $value) {
        if (!isset($data[$key])) {
            $data[$key] = $value;
        }
    }
    $data['year_end']++;
    $data['price_end'] += 0.01;
    $result = Book::search($data['category'], $data['title'], $data['press'], $data['year_start'], $data['year_end'], $data['author'], $data['price_start'], $data['price_end']);
    Flight::json($result);
});

Flight::route('/book/info/@bno', function ($bno) {
    $result = Book::fetch($bno);
    Flight::json($result);
});

Flight::route('POST /card/add/@cno', function ($cno) {
    if (isset($_SESSION['id'])) {
        $json = Flight::request()->data;
        $info = json_decode($json);
        $card = new Card($cno, $info->name, $info->department, $info->type);
        if (is_null($card->cno)) {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
        } else {
            Flight::json(array('status' => 0, 'info' => 'Success'));
        }
    } else {
        Flight::json(array('status' => 2, 'info' => 'Not permitted'));
    }
});

Flight::route('/card/delete/@cno', function ($cno) {
    if (isset($_SESSION['id'])) {
        if (Card::delete($cno)) {
            Flight::json(array('status' => 0, 'info' => 'Success'));
        } else {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
        }
    } else {
        Flight::json(array('status' => 2, 'info' => 'Not permitted'));
    }
});

Flight::route('/card/info', function () {
    if (isset($_SESSION['id'])) {
        $result = Card::fetch_all();
        Flight::json($result);
    } else {
        Flight::json(array('info' => 'Not permitted'));
    }
});

Flight::route('/card/info/@cno', function ($cno) {
    if (isset($_SESSION['id'])) {
        $result = Card::fetch($cno);
        Flight::json($result);
    } else {
        Flight::json(array('info' => 'Not permitted'));
    }
});

Flight::route('/borrow/@cno', function ($cno) {
    if (isset($_SESSION['id'])) {
        $result = Borrow::fetch_cno($cno);
        Flight::json($result);
    } else {
        Flight::json(array('info' => 'Not permitted'));
    }
});

Flight::route('/borrow/@cno/@bno', function ($cno, $bno) {
    if (isset($_SESSION['id'])) {
        $borrow = new Borrow($cno, $bno,$_SESSION['id']);
        if (is_null($borrow->uuid)) {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
        } elseif ($borrow->uuid==0)
        {
            Flight::json(array('status'=>3,'info'=>$borrow->return_date));
        }
        else {
            Flight::json(array('status' => 0, 'info' => 'Success'));
        }
    } else {
        Flight::json(array('status' => 2, 'info' => 'Not permitted'));
    }
});

Flight::route('/return/@cno/@bno',function ($cno,$bno){
    if (isset($_SESSION['id'])) {
        if (Borrow::delete($cno,$bno)) {
            Flight::json(array('status' => 0, 'info' => 'Success'));
        } else {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
        }
    } else {
        Flight::json(array('info' => 'Not permitted'));
    }
});

Flight::route('/return-id/@uuid',function ($uuid){
    if (isset($_SESSION['id'])) {
        if (Borrow::delete_id($uuid)) {
            Flight::json(array('status' => 0, 'info' => 'Success'));
        } else {
            Flight::json(array('status' => 1, 'info' => 'Fail'));
        }
    } else {
        Flight::json(array('info' => 'Not permitted'));
    }
});

Flight::start();
?>
