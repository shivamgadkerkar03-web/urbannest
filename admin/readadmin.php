<?php
require_once __DIR__ . '/../includes/security.php';
$adminid = require_admin('login.php');
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title> UrbanNation </title>
    <meta name="csrf-token" content="<?php echo h(csrf_token()); ?>">
    <script type="application/x-javascript">
        addEventListener("load", function() {
          setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
          window.scrollTo(0, 1);
        }
      </script>
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <!-- <link href="css/style-responsive.css" rel="stylesheet" /> -->
    <link
        href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic'
        rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/font.css" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery2.0.3.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/panel.css">
</head>

<body>
    <section id="container">
        <?php include_once ('includes/header.php'); ?>
        <?php include_once ('includes/sidebar.php'); ?>
        <section id="main-content">
            <section class="wrapper Csection">
                <div class="container my-5" id="allPro">
                    <div class="row">

                    </div>
                </div>

                <table width="100%" border="1" style="text-align: center; font-size:18px;" cellpadding="10"
                    id="mainTable">
                    <tr>
                        <th>Image</th>
                        <th>Seller Name</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Size</th>
                        <!-- <th>Color</th> -->
                        <th>Description</th>
                        <!-- <th>Used for</th> -->
                        <th>Price</th>
                        <!-- <th>Overlall Condition</th> -->
                        <th>Location</th>
                        <!-- <th>Meetup Preference</th> -->
                        <th></th>
                    </tr>
                    <?php
                    $categories = ['Hm' => 'Furniture', 'E' => 'Electronics', 'L' => 'Lifestyle'];
                    $ret = mysqli_query($con, "select * from product");
                    while ($row = mysqli_fetch_array($ret)) {
                        $cat = $categories[$row['category']] ?? $row['category'];
                        ?>
                        <tr class="">
                                <td scope="row"><img class="imgCard" src="../resellNation/upload/<?php echo h(rawurlencode(basename((string) $row['images']))); ?>" alt=""></td>
                                <td><?php echo h($row['sellersName']); ?></td>
                                <td><?php echo h($row['productName']); ?></td>
                                <td><?php echo h($cat); ?></td>
                                <td><?php echo h($row['brand']); ?></td>
                                <td><?php echo h($row['size']); ?></td>
                                <td><?php echo h($row['description']); ?></td>
                                <td><?php echo h($row['price']); ?></td>
                                <td><?php echo h($row['location']); ?></td>
                                <td><button type="button" class="btn btn-danger js-delete-product" data-id="<?php echo (int) $row['productId']; ?>">Delete</button></td>
                            </tr>
                        <?php
                    }
                    ?>

                </table>
            </section>
        </section>

    </section>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/jquery.scrollTo.js"></script>
    <script>
        document.getElementById("mainTable").addEventListener("click", (event) => {
            const btn = event.target.closest('.js-delete-product');
            if (!btn) {
                return;
            }
            event.preventDefault();
            if (!confirm('Do you really want to delete this product?')) {
                return;
            }
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('php/delete-product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': token },
                body: JSON.stringify({ productId: btn.dataset.id })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Could not delete the product.');
                        return;
                    }
                    const row = btn.closest('tr');
                    if (row) {
                        row.remove();
                    }
                })
                .catch(() => {
                    alert('Could not delete the product. Please try again.');
                });
        });
    </script>
</body>

<style>
    .Csection {
        background-color: white;
        min-height: 94%;
    }

    .equal-width {
        flex: 0.5;
        min-width: 80px;
        /* Adjust as needed */
    }

    .input-group {
        display: flex;
    }

    .form-control {
        border: 1px solid #dee2e6 !important;
        font-size: inherit !important;
        color: inherit !important;
    }

    .imgCard {
        height: 150px;
        width: 150px;
        overflow: hidden;
        object-fit: cover;
        border-radius: 1rem;
    }
</style>
</html>
