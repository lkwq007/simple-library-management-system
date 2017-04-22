<section class="hero is-primary">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-vcentered">
                <div class="column">
                    <p class="title">
                        Simple Library Management System
                    </p>
                    <p class="subtitle">
                        By Yuan Liu
                    </p>
                </div>
                <div class="column is-narrow">

                </div>
            </div>
        </div>
    </div>
    <div class="hero-foot">
        <div class="container">
            <nav class="tabs is-boxed">
                <ul class="tabx">
                    <li class="is-active">
                        <a href="#tab1">Quick Search</a>
                    </li>
                    <li>
                        <a href="#tab2">Advanced Search</a>
                    </li>
                    <li>
                        <a href="#tab3">Book List</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>
<section class="section">
    <form id="tab1" name="books">
        <div class="container">
            <div class="field is-grouped">
                <p class="control is-expanded">
                    <input name="title" id="title" class="input" type="text" placeholder="Book Title">
                </p>
                <p class="control">
                    <a class="button is-primary" id="search">
                        Search
                    </a>
                </p>
            </div>
        </div>
        <div class="container">
            <div class="column">
                <p></p>
            </div>
        </div>
        <div id="tab2">
            <div class="container">
                <div class="columns">
                    <div class="column is-1"></div>
                    <div class="column field is-horizontal">
                        <label class="label field-label">Category</label><input class="input field-body" type="text"
                                                                                placeholder="Category" name="category">
                    </div>
                    <div class="column field is-horizontal">
                        <label class="label field-label">Press</label><input class="input field-body" type="text"
                                                                             placeholder="Press" name="press">
                    </div>
                    <div class="column field is-horizontal">
                        <label class="label field-label">Author</label><input class="input field-body" type="text"
                                                                              placeholder="Author" name="author">
                    </div>
                    <div class="column is-1"></div>
                </div>
            </div>

            <div class="container">
                <div class="columns">
                    <div class="column is-2"></div>
                    <div class="column field is-horizontal">
                        <label class="label field-label">Price</label><input class="input field-body" type="text"
                                                                             placeholder="Start"
                                                                             name="price_start"><input
                                class="input field-body" type="text"
                                placeholder="End" name="price_end">
                    </div>
                    <div class="column field is-horizontal">
                        <label class="label field-label">Year</label><input class="input field-body" type="text"
                                                                            placeholder="Start" name="year_start"><input
                                class="input field-body" type="text"
                                placeholder="End" name="year_end">
                    </div>
                    <div class="column is-2"></div>
                </div>
            </div>
        </div>
    </form>
    <!--</section>
    <section class="section">-->
    <div class="columns"></div>
    <div class="container">
        <div id="jsontotable" class="jsontotable">

        </div>
        <div id="pager" class="pager">

        </div>
    </div>
</section>
<div class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Login</p>
            <button class="delete" id="close"></button>
        </header>
        <section class="modal-card-body">
            <form name="user">
                <div class="login-form">
                    <p class="control has-icon has-icon-right">
                        <input class="input email-input" name="id" type="text" placeholder="id">
                        <span class="icon user">
                      <i class="fa fa-user"></i>
                    </span>
                    </p>
                    <p class="control has-icon has-icon-right">
                        <input class="input password-input" name="pwd" type="password" placeholder="●●●●●●●">
                        <span class="icon user">
                      <i class="fa fa-lock"></i>
                    </span>
                    </p>
                    <p class="help is-danger" id="error" style="display: none;">Incorrect id or password.</p>
                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <a class="button is-success" id="login-ok">Login!</a>
        </footer>
    </div>
</div>
<script>
    $("#login").click(function () {
        $(".modal").addClass('is-active');
    });
    $("#close").click(function () {
        $(".modal").removeClass('is-active');
    });
    $("#login-ok").click(function () {
        $("#error").hide();
        var json=$("form[name='user']").serializeJSON();
        json.pwd=md5(json.pwd);
        var data = JSON.stringify(json);
        $.ajax({
            url: "/auth",
            type: "POST",
            data: data,
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log(result);
                if(result.status==0)
                {
                    location.reload();
                }
                else
                {
                    $("#error").show();
                }
            }
        })

    });
    $("#search").click(function () {
        $("#jsontotable").empty();
        var data = JSON.stringify($("form[name='books']").serializeJSON());
        /*        $.post("/book/search",data,function (result) {
         console.log(result);
         })*/
        $.ajax({
            url: "/book/search",
            type: "POST",
            data: data,
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                result.unshift({
                    "bno": "Book no",
                    "category": "Category",
                    "title": "Title",
                    "press": "Press",
                    "year": "Year",
                    "author": "Author",
                    "price": "Price",
                    "total": "Total",
                    "stock": "Stock"
                });
                $.jsontotable(result, {id: '#jsontotable', header: true, className: 'table is-striped'});
                console.log(result);
                $("table").tablesorter();
            }
        })
    });
    $('ul.tabx').each(function () {
        var $active, $content, $links = $(this).find('a');
        $("#jsontotable").empty();
        $active = $($links.filter('[href="' + location.hash + '"]')[0] || $links[0]);
        $active.parent().addClass('is-active');
        //console.log($active[0].hash);
        $content = $($active[0].hash);

        // Hide the remaining content
        $links.not($active).each(function () {
            $(this.hash).hide();
            if (this.hash == '#tab2') {
                $("#tab1").show();
            }
        });

        $(this).on('click', 'a', function (e) {
            var temp = $('#title').val();
            //$('form').find("input[type=text], textarea").val("");
            $('#title').val(temp);
            $("#jsontotable").empty();
            $active.parent().removeClass('is-active');
            $content.hide();
            $active = $(this);
            $content = $(this.hash);
            $active.parent().addClass('is-active');
            $content.show();
            if (this.hash != '#tab3') {
                $("#tab1").show();
            }
            if (this.hash == '#tab3') {
                $("#tab1").hide();
                $.get("/book/info", function (result) {
                    result.unshift({
                        "bno": "Book no",
                        "category": "Category",
                        "title": "Title",
                        "press": "Press",
                        "year": "Year",
                        "author": "Author",
                        "price": "Price",
                        "total": "Total",
                        "stock": "Stock"
                    });
                    $.jsontotable(result, {id: '#jsontotable', header: true, className: 'table is-striped'});
                    $("table").tablesorter();
                });
            }

            e.preventDefault();
        });
    });
</script>