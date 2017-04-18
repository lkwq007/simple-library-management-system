<!doctype html>
<html>
<head>
    <title>Simple Library Management System</title>
    <link rel="stylesheet" href="/static/css/bulma.css">
    <link rel="stylesheet" href="/static/css/font-awesome.min.css">
    <script src="/static/js/jquery-3.2.1.js"></script>
    <script src="/static/js/jquery.jsontotable.js"></script>
    <script src="/static/js/jquery.serializejson.js"></script>
    <script src="/static/js/jquery.tablesorter.js"></script>
    <script src="/static/js/md5.js"></script>
</head>

<body>
<nav class="nav has-shadow">
    <div class="container">
        <div class="nav-left">
            <a class="nav-item">
                <img src="/static/img/logo.png" alt="logo">
            </a>
            <?php
            if (isset($_SESSION['id'])) {
                $nav = array('/' => 'Home', '/admin' => 'Admin', '/book' => 'Book', '/card' => 'Card', '/borrow' => 'Borrow');
                foreach ($nav as $key => $value) {
                    if ($key == $page) {
                        echo '<a class="nav-item is-tab is-hidden-mobile is-active" href="' . $key . '">' . $value . '</a>';
                    } else {
                        echo '<a class="nav-item is-tab is-hidden-mobile" href="' . $key . '">' . $value . '</a>';
                    }
                }
            } else {
                echo '<a class="nav-item is-tab is-hidden-mobile is-active" href="/">Home</a>';
            }
            ?>
            <!--            <a class="nav-item is-tab is-hidden-mobile">Home</a>
                        <a class="nav-item is-tab is-hidden-mobile">Admin</a>
                        <a class="nav-item is-tab is-hidden-mobile">Book</a>
                        <a class="nav-item is-tab is-hidden-mobile">Card</a>
                        <a class="nav-item is-tab is-hidden-mobile">Borrow</a>-->
        </div>
        <span class="nav-toggle">
      <span></span>
      <span></span>
      <span></span>
    </span>
        <div class="nav-right nav-menu">
            <?php
            if (isset($_SESSION['id'])) {
                $nav = array('/' => 'Home', '/admin' => 'Admin', '/book' => 'Book', '/card' => 'Card', '/borrow' => 'Borrow');
                foreach ($nav as $key => $value) {
                    if ($key == $page) {
                        echo '<a class="nav-item is-tab is-hidden-tablet is-active" href="' . $key . '">' . $value . '</a>';
                    } else {
                        echo '<a class="nav-item is-tab is-hidden-tablet" href="' . $key . '">' . $value . '</a>';
                    }
                }
            } else {
                echo '<a class="nav-item is-tab is-hidden-tablet is-active" href="/">Home</a>';
            }
            ?>
            <!--            <a class="nav-item is-tab is-hidden-tablet is-active">Home</a>
                        <a class="nav-item is-tab is-hidden-tablet">Admins</a>
                        <a class="nav-item is-tab is-hidden-tablet">Book</a>
                        <a class="nav-item is-tab is-hidden-tablet">Card</a>
                        <a class="nav-item is-tab is-hidden-tablet">Borrow</a>-->
            <a class="nav-item is-tab">
                <?php if (isset($_SESSION['id'])) {
                    echo "Hi, " . $_SESSION['name'];
                }
                ?>
            </a>
            <?php
            if (isset($_SESSION['id'])) {
                ?>
                <a class="nav-item is-tab" href="/logout">Log out</a>
                <?php
            } else {
                ?>
                <a class="nav-item is-tab" href="/#login" id="login">Log in</a>
                <?php
            }
            ?>
        </div>
    </div>
</nav>