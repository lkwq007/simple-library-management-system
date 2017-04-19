<script src="/static/js/dropzone.js"></script>
<script>
    Dropzone.options.myAwesomeDropzone = {
        init: function () {
            this.on("success", function (data) {
                console.log(data);
                var temp=JSON.parse(data.xhr.response);
                notice_info(temp);
            });
        }
    };
</script>
<link rel="stylesheet" href="/static/css/dropzone.css">
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
</section>
<section class="section">
    <div class="container">
        <div id="notify">
            <div class="notification is-primary" id="success">
                <button class="delete close"></button>
                Operation succeeded! <span class="infos"></span>
            </div>
            <div class="notification is-danger" id="fail">
                <button class="delete close"></button>
                Operation failed! <span class="infos"></span>
            </div>
            <div style="display: none;">
                <button class="delete"></button>
            </div>
        </div>
    </div>
    <div class="columns"></div>
    <div class="container">
        <div class="panel">
            <p class="panel-heading">
                Manage books:
            </p>
            <div class="panel-block is-expanded">
                <form name="books">
                    <div class="column"></div>
                    <div class="columns">
                        <div class="column field is-horizontal">
                            <label class="label field-label">BNO</label><input class="input field-body"
                                                                               type="text"
                                                                               placeholder="BNO" name="bno">
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Title</label><input class="input field-body"
                                                                                 type="text"
                                                                                 placeholder="Title"
                                                                                 name="title">
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Category</label><input class="input field-body"
                                                                                    type="text"
                                                                                    placeholder="Category"
                                                                                    name="category">
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Press</label><input class="input field-body"
                                                                                 type="text"
                                                                                 placeholder="Press"
                                                                                 name="press">
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column field is-horizontal">
                            <label class="label field-label">Author</label><input class="input field-body"
                                                                                  type="text"
                                                                                  placeholder="Author"
                                                                                  name="author">
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Price</label><input class="input field-body"
                                                                                 type="text"
                                                                                 placeholder="Price"
                                                                                 name="price">
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Year</label><input class="input field-body"
                                                                                type="text"
                                                                                placeholder="Year"
                                                                                name="year">
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Amount</label><input class="input field-body"
                                                                                  type="text"
                                                                                  placeholder="Amount"
                                                                                  name="amount">
                        </div>
                    </div>

                </form>
            </div>
            <div class="panel-block">
                <div class="field is-grouped">
                    <p class="control">
                        <a class="button is-primary" id="add">
                            Add
                        </a>
                    </p>
                    <p class="control">
                        <a class="button" id="reset">
                            Reset
                        </a>
                    </p>
                    <p class="control">
                        <a class="button is-danger" id="delete">
                            Delete
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="panel">
            <p class="panel-heading">
                Patch books:
            </p>
            <div class="panel-block">
                <div class="message-body">
                    <div id="dropzone">
                        <form action="/book/add" method="post" enctype="multipart/form-data"
                              class="dropzone needsclick dz-clickable" id="myAwesomeDropzone">
                            <div class="dz-message needsclick">
                                Drop files here or click to upload.<br>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel">
            <p class="panel-heading">
                Books:
            </p>
            <div id="jsontotable" class="jsontotable panel-block">

            </div>
        </div>

    </div>
</section>

<script>
    function get_admin() {
        $("#jsontotable").empty();
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
    $(document).ready(function () {
        get_admin();
        $(".notification").hide();
    });
    function notice(status) {
        $(".infos").empty();
        console.log(status);
        if (status == 0) {
            $("#success").show(500);
            get_admin();
        }
        else {
            $("#fail").show(500);
        }
    }
    function notice_info(data) {
        console.log(data);
        $(".infos").empty();
        var temp=data.info.success+data.info.fail;
        $(".infos").html(" Patched "+data.info.success+"/"+temp);
        if (data.status == 0) {
            $("#success").show(500);
            get_admin();
        }
        else {
            $("#fail").show(500);
        }
    }
    $("#delete").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='books']").serializeJSON();
        var temp;
        temp = json.bno;
        $.get("/book/delete/" + temp, function (result) {
            notice(result.status);
        });
    });
    $("#add").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='books']").serializeJSON();
        var data = JSON.stringify(json);
        $.ajax({
            url: "/book/add/" + json.bno,
            type: "POST",
            data: data,
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log(result);
                notice(result.status);
            }
        })
    });
    $("#reset").click(function () {
        $('form').find("input[type=text], textarea").val("");
    });
    $(".close").click(function () {
        $(".close").parent().hide(500);
    })
</script>