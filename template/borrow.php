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
                Operation failed! <span id="infos"></span>
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
                Manage:
            </p>
            <div class="panel-block">
                <form name="borrows">
                    <div class="column">

                    </div>
                    <div class="columns">
                        <div class="field is-horizontal column">
                            <label class="label field-label">CNO</label>
                            <p class="control">
                                <input class="input cno-input has-icon has-icon-right" name="cno" type="text" placeholder="CNO">
                            </p>
                        </div>
                        <div class="field is-horizontal column">
                            <label class="label field-label">BNO</label>
                            <p class="control">
                                <input class="input bno-input" name="bno" type="text" placeholder="BNO">

                            </p>
                        </div>
                        <div class="field is-horizontal column">
                            <label class="label field-label">UUID</label>
                            <p class="control is-expanded">
                                <input class="input uid-input is-fullwidth" name="uuid" type="text" placeholder="UUID">

                            </p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-block">
                <div class="field is-grouped">
                    <p class="control">
                        <a class="button is-success" id="get">
                            Query
                        </a>
                    </p>
                    <p class="control">
                        <a class="button" id="reset">
                            Reset
                        </a>
                    </p>
                    <p class="control">
                        <a class="button is-primary" id="borrow">
                            Borrow
                        </a>
                    </p>
                    <p class="control">
                        <a class="button is-danger" id="return">
                            Return
                        </a>
                    </p>
                    <p class="control">
                        <a class="button is-danger" id="return-id">
                            Return by UUID
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="panel">
            <p class="panel-heading">
                Loans:
            </p>
            <div id="jsontotable" class="jsontotable panel-block">

            </div>
        </div>

    </div>
</section>

<script>
    var current;
    $(document).ready(function () {
        $(".notification").hide();
    });
    function get_loans(cno) {
        $("#jsontotable").empty();
        $.get("/borrow/" + cno, function (result) {
            loans = result;
            result.unshift({
                "uuid": "UUID",
                "cno": "Card no",
                "bno": "Book no",
                "title": "Title",
                "admin_id": "By",
                "borrow_date": "Borrow Date",
                "return_date": "Due Date"
            });

            $.jsontotable(result, {id: '#jsontotable', header: true, className: 'table is-striped'});
            $("table").tablesorter();
        });
    }
    function delete_loan(cno, bno) {
        $.get("/return/" + cno + "/" + bno, function (result) {
            notice(result);
        });
    }
    function delete_loan_id(uuid) {
        $.get("/return-id/" + uuid, function (result) {
            notice(result);
        });
    }
    function notice(result) {
        $("#infos").empty();
        switch (result.status) {
            case 0:
                $("#success").show(500);
                var json = $("form[name='borrows']").serializeJSON();
                get_loans(json.cno);
                break;
            case 1:
            case 2:
                $("#fail").show(500);
                break;
            case 3:
                $("#fail").show(500);
                console.log(result.info);
                $("#infos").html("Latest due date: "+result.info);
                break;
            default:
                $("#fail").show(500);
        }
    }
    $("#get").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='borrows']").serializeJSON();
        get_loans(json.cno);
    });
    $("#return").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='borrows']").serializeJSON();
        delete_loan(json.cno, json.bno);
    });
    $("#return-id").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='borrows']").serializeJSON();
        delete_loan_id(json.uuid);
    });
    $("#borrow").click(function () {
        $(".notification").hide(500);
        var json = $("form[name='borrows']").serializeJSON();
        $.get("/borrow/" + json.cno + "/" + json.bno, function (result) {
            console.log(result);
            notice(result);
        });

    });
    $("#reset").click(function () {
        $('form').find("input[type=text], textarea").val("");
    });
    $(".close").click(function () {
        $(".close").parent().hide(500);
    })
</script>