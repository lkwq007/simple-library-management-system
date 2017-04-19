<script src="/static/js/dropzone.js"></script>
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
                Manage cards:
            </p>
            <div class="panel-block">
                <form name="cards">
                    <div class="column">

                    </div>
                    <div class="columns">
                        <div class="field is-horizontal column">
                            <label class="label field-label">CNO</label>
                            <p class="control has-icon has-icon-right">
                                <input class="input id-input" name="cno" type="text" placeholder="CNO">
                                <span class="icon user">
                      <i class="fa fa-id-card" aria-hidden="true"></i>
                    </span>
                            </p>
                        </div>
                        <div class="field is-horizontal column">
                            <label class="label field-label">Name</label>
                            <p class="control has-icon has-icon-right">
                                <input class="input password-input" name="name" type="text" placeholder="Name">
                                <span class="icon user">
                      <i class="fa fa-user"></i>
                    </span>
                            </p>
                        </div>
                        <div class="field is-horizontal column">
                            <label class="label field-label">Department</label>
                            <p class="control has-icon has-icon-right">
                                <input class="input password-input" name="department" type="text" placeholder="Department">
                                <span class="icon user">
                      <i class="fa fa-building" aria-hidden="true"></i>
                    </span>
                            </p>
                        </div>
                        <div class="field is-horizontal column">
                            <label class="label field-label">Type</label>
                            <p class="control">
                                <div class="select is-fullwidth">
                                    <select name="type">
                                        <option value="S">Student</option>
                                        <option value="T">Teacher</option>
                                    </select>
                                </div>
                            </p>
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
                Cards:
            </p>
            <div id="jsontotable" class="jsontotable panel-block">

            </div>
        </div>

    </div>
</section>

<script>
    function get_card() {
        $("#jsontotable").empty();
        $.get("/card/info", function (result) {
            result.unshift({
                "cno": "Card no",
                "name": "Name",
                "department": "Department",
                "type": "Type"
            });
            var i;
            for(i=0;i<result.length;i++)
            {
                if(result[i].type=='T')
                {
                    result[i].type='Teacher';
                }
                else if(result[i].type=='S')
                {
                    result[i].type='Student';
                }
            }
            $.jsontotable(result, {id: '#jsontotable', header: true, className: 'table is-striped'});
            $("table").tablesorter();

        });
    }
    function delete_card(cno) {
        $.get("/card/delete/" + cno, function (result) {
            notice(result.status);
        });
    }
    $(document).ready(function () {
        get_card();
        $(".notification").hide();
    });
    function notice(status) {
        if (status == 0) {
            $("#success").show(500);
            get_card();
        }
        else {
            $("#fail").show(500);
        }
    }
    $("#delete").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='cards']").serializeJSON();
        var temp;
        temp = json.cno;
        delete_card(temp);
    });
    $("#add").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='cards']").serializeJSON();
        var data = JSON.stringify(json);
        $.ajax({
            url: "/card/add/" + json.cno,
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