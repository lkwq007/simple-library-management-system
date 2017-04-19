<footer class="footer">
    <div class="container">
        <div class="content has-text-centered">
            <p>
                <strong>Simple Library Management System</strong>. Powered by <a href="http://bulma.io">Bulma</a> and <a
                    href="http://flightphp.com/">Flight</a>. Fork me on GitHub!
            </p>
            <p>
                <a class="icon" href="https://github.com/lkwq007/simple-library-management-system">
                    <i class="fa fa-github"></i>
                </a>
            </p>
        </div>
    </div>
</footer>
<script>
    var toggle=true;
    $("#toggle").click(function () {
        if(toggle)
        {
            $(".mobile").addClass("is-active");
        }
        else
        {
            $(".mobile").removeClass("is-active");
        }
        toggle=!toggle;
    });
</script>
</body>
</html>