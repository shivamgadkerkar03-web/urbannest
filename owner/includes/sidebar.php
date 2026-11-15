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
                        <i class="fa fa-th"></i>
                        <span>List Your PG</span>
                    </a>
                    <ul class="sub">
                        <li><a href="add-pgdetails.php">Add PG</a></li>
                        <li><a href="manage-pgdetails.php">Manage PG</a></li>
                    </ul>
                </li>
                                
                             <li>
                    <a  href="new-bookingrequest.php">
                        <i class="fa fa-files-o"></i>
                        <span>Booking Requests</span>
                    </a>
                    <ul class="sub">
                        <li><a href="new-bookingrequest.php">New Booking</a></li>
                        <li><a href="confirmed-pgbooking.php">Confirm Booking</a></li>
                        <li><a href="cancelled-pgbooking.php">Cancelled Booking</a></li>
                        <li><a href="all-bookings.php">All Booking</a></li>
                    </ul>
                </li>
                         <li>
                    <a href="search.php">
                        <i class="fa fa-search"></i>
                        <span>Search</span>
                    </a>
                </li>
              
                
            </ul>            </div>
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
