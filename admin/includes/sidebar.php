<?php
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(404);
    exit;
}
?>
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a href="dashboard.php">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>State</span>
                    </a>
                    <ul class="sub">
                        <li><a href="add-state.php">Add State</a></li>
                        <li><a href="manage-state.php">Mange State</a></li>

                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-bullhorn"></i>
                        <span>City</span>
                    </a>
                    <ul class="sub">
                        <li><a href="add-city.php">Add City</a></li>
                        <li><a href="manage-city.php">Mange City</a></li>

                    </ul>
                </li>

                <li>
                    <a href="manage-ownerdetails.php">
                        <i class="fa fa-users"></i>
                        <span>Reg Owners </span>
                    </a>
                </li>
                <li>
                    <a href="manage-pgdetails.php">
                        <i class="fa fa-tasks"></i>
                        <span>PG Details </span>
                    </a>
                </li>

                <li>
                    <a href="reg-users.php">
                        <i class="fa fa-users"></i>
                        <span>Reg Users </span>
                    </a>
                </li>

                <li>
                    <a href="readadmin.php">
                        <i class="fa fa-users"></i>
                        <span>Urban Nation</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-bullhorn"></i>
                        <span>Pages</span>
                    </a>
                    <ul class="sub">
                        <li><a href="about-us.php">About us</a></li>
                        <li><a href="contact-us.php">Contact us</a></li>
                    </ul>
                </li>


            </ul>
        </div>
        <!-- sidebar menu end-->
    </div>
</aside><script>
    (function () {
        var page = location.pathname.split('/').pop() || 'dashboard.php';
        var links = document.querySelectorAll('#sidebar a[href]');
        for (var i = 0; i < links.length; i++) {
            var href = links[i].getAttribute('href').split('?')[0];
            if (href !== page) {
                continue;
            }
            links[i].classList.add('is-current');
            var sub = links[i].closest('ul.sub');
            if (sub) {
                sub.style.display = 'block';
                var parent = sub.parentElement.querySelector(':scope > a');
                if (parent) {
                    parent.classList.add('is-current');
                }
            }
        }
    })();
</script>
