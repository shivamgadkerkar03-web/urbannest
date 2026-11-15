<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(0);
include ('includes/dbconnection.php');

// Retrieve product data from the database
// $query = "SELECT * FROM products";
// $result = mysqli_query($con, $query);

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
  <!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
  <link rel="stylesheet" href="css/owl.carousel.css">
  <link rel="stylesheet" href="css/main.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

  <style>
    @media (max-width: 768px) {
      .content {
        width: 100% !important;
      }
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .overlay {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to right, #000, #0000);
    }

    .content {
      width: 50%;
      margin: 0 auto;
      padding: 1rem 0;
      color: white;
      text-align: center;
    }

    .content h1 {
      font-size: 3rem;
    }

    .bg-primary {
      background-color: #4caf50;
      color: #fff;
      text-decoration: none;
      padding: 0.8rem 2.4rem;
      border-radius: 5px;
    }

    @media (max-width: 768px) {
      .content {
        width: 100%;
      }
    }

    form {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      /* max-width: 500px; */
      width: 80%;
      /* Adjusted width to 80% */
      margin: 20px auto;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    input[type="file"] {
      border: none;
      padding: 0;
    }

    button {
      background-color: #4caf50;
      color: #fff;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      margin-bottom: 10px;
    }

    button:hover {
      background-color: #45a049;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      color: #333;
      text-decoration: none;
      font-weight: bold;
    }

    .radio-group {
      display: flex;
      justify-content: space-around;
      margin-bottom: 15px;
    }

    #choosePG {
      font-size: 16px;
    }

    span {
      font-size: 14px;
      color: #777;
    }
  </style>
</head>

<body>
  <!-- start banner Area -->
  <?php include_once ('includes/header.php'); ?>
  <!-- start banner Area -->
  <div style="margin-top: 5.5rem; position: relative; padding: 8rem 6rem; background: url('roomie.jpg');">
    <div class="overlay"
      style="position: absolute; left: 0; top: 0; width: 80%; height: 100%; background: linear-gradient(to right, #000, #0000);">
    </div>
    <div class="content" style="width: 50%; margin: 0; padding:1rem 0; color: white;">
      <h1 style="color: white; font-size: 3rem;">Discover Your Perfect Roommate: Advertise Now!<span
          class="gradient-text"
          style="background: linear-gradie -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Exceptional
          Furniture</span></h1>

      <p style="color: #aaa; padding: 0.5rem 0;">Connect with the ideal roommate to elevate your living experience. Post
        your ad now and find the perfect match for your living space.Take the first step towards creating a harmonious
        home environment by posting your ad today. Find a roommate who not only shares your space but enhances your
        lifestyle."</p>
      <!-- <a class="bg-primary" style="text-decoration: none; padding: 0.8rem 2.4rem;  border-radius: 5px;">Explore
        Our Collection</a>-->
    </div>
  </div>


  <?php
  require 'dbconnection.php';

  if (isset($_POST["submit"])) {
    $choosePG = $_POST["choosePG"];
    $name = $_POST["name"];
    // $name = $_SESSION["FullName"];
    $contactNo = $_POST["contactNo"];
    $roomDetails = $_POST["roomDetails"];
    // $hobbies = $_POST["hobbies"];
    $location = $_POST["location"];
    // $addiction = isset($_POST["addiction"]) ? $_POST["addiction"] : "";
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    // $knowledgeAboutAddiction = isset($_POST["knowledgeAboutAddiction"]) ? $_POST["knowledgeAboutAddiction"] : "";
  
    echo "Choose PG: " . $choosePG . "<br>";
    echo "Name: " . $name . "<br>";
    echo "Contact Number: " . $contactNo . "<br>";
    echo "Room Details: " . $roomDetails . "<br>";
    // echo "Hobbies: " . $hobbies . "<br>";
    echo "Location: " . $location . "<br>";
    // echo "Addiction: " . $addiction . "<br>";
    echo "Gender: " . $gender . "<br>";
    // echo "Knowledge about Addiction: " . $knowledgeAboutAddiction . "<br>";
  

    if (empty($choosePG) || empty($name) || empty($contactNo) || empty($roomDetails) || empty($location) || empty($gender)) {
      echo "<script>alert('All fields (except Knowledge About) are compulsory.');</script>";
    } elseif (!ctype_digit($contactNo) || strlen($contactNo) !== 10) {
      echo "<script>alert('Contact No should be a 10-digit integer.');</script>";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
      echo "<script>alert('Only letters and white space allowed in Name');</script>";
    } else {
      // $checkContactNoQuery = "SELECT * FROM adRoomie WHERE ContactNo = '$contactNo'";
      // $result = mysqli_query($con, $checkContactNoQuery);
  
      // if (mysqli_num_rows($result) > 0) {
      //   echo "<script>alert('Contact No already exists.');</script>";
      // } else {
      if ($_FILES["image"]["error"] == 4) {
        echo "<script> alert('Image Does Not Exist'); </script>";
      } else {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtension)) {
          echo "<script> alert('Invalid Image Extension'); </script>";
        } else if ($fileSize > 1000000) {
          echo "<script> alert('Image Size Is Too Large'); </script>";
        } else {
          $newImageName = uniqid();
          $newImageName .= '.' . $imageExtension;
          $userId = $_SESSION['pgasuid'];

          move_uploaded_file($tmpName, 'img/' . $newImageName);
          $query = "INSERT INTO adRoomie (userId, choosePG, Name, ContactNo, RoomDetails, Location, Gender, image) VALUES ('$userId', '$choosePG', '$name', '$contactNo', '$roomDetails', '$location', '$gender', '$newImageName')";

          mysqli_query($con, $query);
          echo "<script> alert('Successfully Added'); document.location.href = 'View_details.php'; </script>";
        }
        // }
      }
    }
    exit;
  }
  ?>
  <style>
    .mainForm {
      max-width: 100% !important;
    }

    label {
      width: 300px;
    }

    .spInput {
      width: 100px !important;
    }
  </style>
  <div>
    <?php
    // if (isset($_SESSION['pgasuid'])) {
    //   // Escape the input to prevent SQL injection
    //   $userid = mysqli_real_escape_string($con, $_SESSION['pgasuid']);
    
    //   // Select query with JOIN operation
    //   $query = "SELECT tblpg.PGTitle FROM tblbookpg 
    //           INNER JOIN tblpg ON tblbookpg.Pgid = tblpg.ID
    //           WHERE tblbookpg.Userid='$userid' AND tblbookpg.Status='Confirmed'";
    
    //   $result = mysqli_query($con, $query);
    
    //   if ($result) {
    //     $numRows = mysqli_num_rows($result);
    
    //     if ($numRows == 1) {
    //       $row = mysqli_fetch_assoc($result);
    //       echo "Name: " . $row['PGTitle'] . "<br>";
    //     } else {
    //       echo 'No Entry';
    //     }
    //   } else {
    //     echo "Query failed: " . mysqli_error($con);
    //   }
    // }
    ?>

  </div>

  <form action="" method="post" autocomplete="off" enctype="multipart/form-data" class="mainForm" id="addRomie">

    <?php
    if (!$_SESSION['pgasuid']) { ?>
      <h4><span class="badge text-bg-warning container-fluid mb-2 d-flex align-items-center"><ion-icon size="large"
            class="mx-2" name="alert-circle-outline"></ion-icon>Warning :
          Login / Register to add Roomie</span></h4>
    <?php } ?>



    <div class="form-group">
      <!-- <select class="form-control m-bot15" name="choosePG" id="choosePG" required="true"> -->
      <!-- <option value="">Choose PG</option> -->
      <?php
      // $query = mysqli_query($con, "SELECT * FROM tblpg");
      // while ($row = mysqli_fetch_array($query)) {
      ?>
      <!-- <option value="<?php //echo $row['PGTitle'] . ' - ' . $row['CityName']; ?>">
            <?php //echo $row['PGTitle'] . ' - ' . $row['CityName']; ?>
          </option> -->
      <?php //} ?>
      <!-- </select> -->

      <?php
      if (isset($_SESSION['pgasuid'])) {
        // Escape the input to prevent SQL injection
        $userid = mysqli_real_escape_string($con, $_SESSION['pgasuid']);
        $query = "SELECT tblpg.PGTitle, tblpg.CityName, tblpg.Address FROM tblbookpg 
              INNER JOIN tblpg ON tblbookpg.Pgid = tblpg.ID
              WHERE tblbookpg.Userid='$userid' AND tblbookpg.Status='Confirmed'";

        $result = mysqli_query($con, $query);
        $location = "";
        if ($result) {
          $numRows = mysqli_num_rows($result);

          if ($numRows == 1) {
            $row = mysqli_fetch_assoc($result);
            echo '<input type="text" name="choosePG" id="choosePG" value="' . $row['PGTitle'] . '" required readonly>';
            // echo "<option value=".$row['PGTitle'] . ' - ' . $row['CityName'].">".$row['PGTitle'] . ' - ' . $row['CityName']".</option>";
            $location = $row['Address'];
          } else {
            echo '<input type="text" ame="choosePG" id="choosePG" value="No Pg Booked" required readonly>';
            // echo "<option value=".$row['PGTitle'] . ' - ' . $row['CityName'].">".$row['PGTitle'] . ' - ' . $row['CityName']".</option>";
            $location = "No Pg Booked";
          }
        } else {
          echo "Query failed: " . mysqli_error($con);
        }
      }
      ?>


    </div>

    <div class="d-flex align-items-center">
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" value="<?php echo $_SESSION['FullName']; ?>" required readonly><br>
    </div>

    <div class="d-flex align-items-center">
      <label for="contactNo">Contact No:</label>
      <input type="tel" name="contactNo" id="contactNo" value="<?php echo $_SESSION['cno']; ?>" required readonly><br>
    </div>

    <div class="d-flex align-items-center">
      <label for="roomDetails">Room Details:</label>
      <input type="text" name="roomDetails" id="roomDetails" required><br>
    </div>

    <!-- <div class="d-flex align-items-center">
      <label for="hobbies">Hobbies:</label>
      <input type="text" name="hobbies" id="hobbies" required><br>
    </div> -->

    <div class="d-flex align-items-center">
      <label>Prefered Gender to stay with:</label>
      <div class="radio-group d-flex align-items-center">
        <input type="radio" name="gender" value="Male" id="male" class="spInput">
        <label for="male">Male</label>
        <input type="radio" name="gender" value="Female" id="female" class="spInput">
        <label for="female">Female</label>
      </div>
    </div>

    <div class="d-flex align-items-center">
      <label for="location">Location:</label>
      <input type="text" name="location" id="location" value="<?php echo $location; ?>" required readonly><br>
    </div>


    <!-- <div class="d-flex align-items-center">
      <label for="addiction">Addiction:</label>
      <div class="radio-group d-flex align-items-center">
        <input type="radio" name="addiction" value="Yes" id="adyes" class="spInput">
        <label for="adyes"> Yes</label>
        <input type="radio" name="addiction" value="No" id="adno" class="spInput">
        <label for="adno"> No</label>
      </div>
    </div> -->

    <!-- <div class="d-flex align-items-center">
      <label for="knowledgeAboutAddiction">Knowledge About Addiction:</label>
      <textarea name="knowledgeAboutAddiction" id="knowledgeAboutAddiction" rows="4" cols="50"
        style="color: #999;"></textarea><br>
    </div> -->

    <div class="d-flex align-items-center">
      <label for="image">Image:</label>
      <input class="m-0" type="file" name="image" id="image" accept=".jpg, .jpeg, .png" style="width: 250px" required>
      <span class="badge text-bg-secondary">Make sure that you and your room both are visible</span><br><br>
    </div>

    <!-- <div style="text-align: center;">
      <button type="submit" style="width: 100%;">Submit</button>
    </div> -->

    <?php
    if (!$_SESSION['pgasuid']) { ?>
      <div style="text-align: center;" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <button style="width: 100%;">Submit</button>
      </div>
    <?php } else { 
      $userid = mysqli_real_escape_string($con, $_SESSION['pgasuid']);
      $query = "SELECT * FROM adroomie WHERE userId='$userid'";

      $result = mysqli_query($con, $query);
      if ($result) {
        $numRows = mysqli_num_rows($result);

        if ($numRows == 1) { 
          echo '<div style="text-align: center;">
            <button style="width: 100%;" class="btn btn-warning" onclick="return alreadyAdded();">Already Ad Registered</button>
          </div>';
        } else {
          echo '<div style="text-align: center;">
            <button type="submit" name="submit" style="width: 100%;" onclick="return valid();">Submit</button>
          </div>
          ';
        }
      }
      ?>
    <?php } ?>
  </form>

  <script>
    function valid() {
      if (document.getElementById("choosePG").value == "No Pg Booked") {
        alert("Book Pg");
        return false;
      }
      return true; 
    }

    function alreadyAdded() {
        alert("Roomie request Already Registered !!");
        return false;
    }
  </script>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div> -->
        <div class="modal-body">
          Need to login/register to add roomie !
        </div>
        <div class="modal-footer d-flex align-items-center justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a type="button" href="./signin.php" class="btn btn-primary">Login / Register</a>
        </div>
      </div>
    </div>
  </div>

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