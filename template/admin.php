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
                Operation succeeded!
            </div>
            <div class="notification is-danger" id="fail">
                <button class="delete close"></button>
                Operation failed!
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
                Manage admin:
            </p>
            <div class="panel-block">
                <form name="user">
                    <div class="column">

                    </div>

                    <div class="columns">
                        <div class="column field is-horizontal">
                            <label class="label field-label">Id</label>
                            <p class="control has-icon has-icon-right">
                                <input class="input field-body" name="id" type="text" placeholder="Id">
                                <span class="icon user">
                      <i class="fa fa-id-card" aria-hidden="true"></i>
                    </span>
                            </p>
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Password</label>
                            <p class="control has-icon has-icon-right">
                                <input class="input field-body" name="pwd" type="password" placeholder="●●●●●●●">
                                <span class="icon user">
                      <i class="fa fa-lock"></i>
                    </span>
                            </p>
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Name</label>
                            <p class="control has-icon has-icon-right">
                                <input class="input field-body" name="name" type="text" placeholder="Name">
                                <span class="icon user">
                      <i class="fa fa-user"></i>
                    </span>
                            </p>
                        </div>
                        <div class="column field is-horizontal">
                            <label class="label field-label">Contact</label>
                            <p class="control has-icon has-icon-right">
                                <input class="input field-body" name="contact" type="contact" placeholder="Contact">
                                <span class="icon user">
                      <i class="fa fa-address-book" aria-hidden="true"></i>
                    </span>
                            </p>
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
                Admins:
            </p>
            <div id="jsontotable" class="jsontotable panel-block">

            </div>
        </div>

    </div>
</section>

<script>
    function get_admin() {
        $("#jsontotable").empty();
        $.get("/admin/info", function (result) {
            result.unshift({
                "id": "Id",
                //"pwd": "Password",
                "name": "Name",
                "contact": "Contact"
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
        if (status == 0) {
            $("#success").show(500);
            get_admin();
        }
        else {
            $("#fail").show(500);
        }
    }
    $("#delete").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='user']").serializeJSON();
        var temp;
        temp = json.id;
        $.get("/admin/delete/" + temp, function (result) {
            notice(result.status);
        });
    });
    $("#add").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='user']").serializeJSON();
        json.pwd=md5(json.pwd);
        var data = JSON.stringify(json);
        $.ajax({
            url: "/admin/add/" + json.id,
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