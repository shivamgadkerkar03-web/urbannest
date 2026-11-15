<?php
session_start();
error_reporting(0);
include ('includes/dbconnection.php');

// Retrieve product data from the database
$query = "SELECT * FROM products";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>


<head>

    <title>UrbanNest </title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <!--
        CSS
        ============================================= -->
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/ion.rangeSlider.css" />
    <link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/main.css">

    <style>
        @media (max-width: 768px) {
            .content {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <?php include_once ('includes/header.php'); ?>
    <!-- start banner Area -->
    <div
        style="margin-top: 5.5rem; position: relative; padding: 10rem 6rem; background: url('https://images.pexels.com/photos/3958960/pexels-photo-3958960.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');">
        <div class="overlay"
            style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: linear-gradient(to right, #000, #0000);">
        </div>
        <div class="content" style="width: 50%; margin: 0; padding:1rem 0; color: white;">
            <h1 style="color: white; font-size: 3rem;">Transform Your Space with <span class="gradient-text"
                    style="background: linear-gradient(45deg, #007bff, #00bfff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Exceptional
                    Secondhand Finds</span></h1>
            <p style="color: #aaa; padding: 0.5rem 0;">Enhance your space with great secondhand finds! Discover unique
                items that add charm to your PG. Explore now!</p>
        </div>
    </div>


    <!-- Product Grid Section -->
    <section class="section-gap">
        <div class="container">
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow" style="border-radius: 10px;">
                            <!-- <img class="card-img-top" src="images/<?php // echo $row['image']; 
                                ?>" alt="Room Image"> -->
                            <img class="card-img-top" style="width: 100%; overflow: hidden;border-radius: 10px 10px 0 0;"
                                src="<?php echo $row['image']; ?>" alt="Room Image" />
                            <div class="card-body"
                                style="display: flex; flex-direction: column; justify-content: space-between; gap: 10px;">
                                <div>
                                    <h4 class="card-title text-primary">
                                        <?php echo $row['title']; ?>
                                    </h4>
                                    <p class="card-title " style="font-size: 1.4rem; font-weight: 700;color: black;">₹
                                        <?php echo $row['price']; ?>/-
                                    </p>
                                    <div class="ratings">
                                        <!-- Your rating display logic here -->
                                    </div>
                                    <p class="card-text">
                                        <?php echo $row['excerpt']; ?>
                                    </p>
                                </div>
                                <a href="https://wa.me/+919325431581?text=Hello, I want to buy product no.<?= $row['id'] ?>"
                                    class="btn btn-primary" style="width: 100%; ">Buy Now</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- End Product Grid Section -->

    <!-- start footer Area -->
    <?php include_once ('includes/footer.php'); ?>
    <!-- End footer Area -->


    <script src="js/vendor/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/ion.rangeSlider.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>