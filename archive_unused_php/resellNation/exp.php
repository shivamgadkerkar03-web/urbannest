<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form</title>
    <style>
        h1 {
            font-size: 50px;
            line-height: 64px;
            color: #222;
        }

        /*h2 {
    font-size: 46px;
    line-height: 54px;
    color: white;
}
*/

        h4 {
            font-size: 20px;
            color: #222;
        }

        h6 {
            font-weight: 700;
            font-size: 12px;
        }


        section-p1 {
            padding: 40px 80px;
        }

        section-m1 {
            margin: 40px 0;
        }



        #header {
            display: block;
            align-items: center;
            justify-content: space-between;
            padding: 20px 80px;
            background-color: #F5CE76;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
            padding-top: 0px;
            padding-left: 0px;
            padding-bottom: 0px;
            z-index: 999;
            position: sticky;
            top: 0;
            left: 0;



        }

        body {
            background-color: #FFF5C3;
        }

        .home {
            margin-left: 55%;
        }

        #resale-logo {
            height: 100px;
        }

        #navbar {
            position: relative;
            display: flex;
            align-items: left;
            justify-content: left;
            background-color: #F5CE76;
            margin-bottom: 0%;
        }

        #navbar li {
            list-style: none;
            padding: 30px;
            padding-top: 4%;
            background-color: #F5CE76;
            padding-right: 0%;
        }

        #navbar li a {
            text-decoration: none;
            font-size: 20px;
            font-weight: 608;
            color: #000000;
            transition: 0.3s ease;
            margin-top: 30%;
            padding-top: 50%;
            width: 60px;


        }

        #navbar li a:hover,
        #navbar li a.active {
            color: #108178;
        }

        /*---------------------------------------*/
        .seller-header {
            position: absolute;
            width: 75%;
            height: 7%;
            background: #F5CE76;
            border-radius: 25px;
            top: 27%;
            left: 13%;
        }

        #header-2 {
            margin-top: 1%;
            text-align: center;
        }

        /* footer */
        #urbannest-logo {
            height: 100px;
            margin-top: 30%;

        }

        #sellform {
            display: block;
            position: absolute;
            left: 6%;
            top: 40%;
            width: 50%;
            text-align: center;
            line-height: 2em;
            font-size: 18px;
        }



        .form-label {
            position: absolute;
            background-color: #F5CE76;
            border-radius: 20px;
            min-width: 25%;
            text-align: center;
            margin-right: 40px;
            height: 5%;
            font-weight: bold;
            margin-top: 0.5%;

        }

        .form-input {
            position: absolute;
            left: 80%;
            height: 5%;
            border-radius: 20px;
            width: 50%;
            border: solid #FFF5C3;
        }

        .upload-btn {
            position: absolute;
            left: 90%;
            height: 5%;
            margin-top: 3%;
        }

        #submit-btn {
            position: absolute;
            width: 155px;
            height: 40px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 20px;
            text-align: center;
            left: 80%;
            top: 98%;
            background-color: #F5CE76;
            cursor: pointer;
            border: solid wheat;
        }

        #submit-btn:hover {
            background-color: #FFF5C3;
        }

        #submit-btn:active {
            background-color: #FFC86F;
        }

        footer {
            width: 100%;
            height: 40%;
            position: absolute;
            bottom: 0;
            background: linear-gradient(to right, #F5CE76, #F5CE76);
            color: black;
            font-size: 15px;
            line-height: 20px;
            top: 235%;
        }

        .row {
            width: 99%;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
        }

        .col {
            flex-basis: 15%;
            padding: 1%;
            height: 50%;
        }

        .col h3 {
            width: fit-content;
            margin-bottom: 5%;
            position: relative;
            left: 25%;
        }


        #footer-mail {
            width: fit-content;
            border-bottom: 1px solid #ccc;
        }

        .col ul li {
            list-style: none;
            margin-bottom: 10px;
        }

        .col ul li a {
            text-decoration: none;
            color: black;
        }
    </style>

</head>

<body>

    <?php

    include ('connect.php');

    if (isset($_POST["submit"])) {

        $productName = $_POST["productName"];
        $category = $_POST["category"];
        $brand = $_POST["brand"];
        $size = $_POST["size"];
        $color = $_POST["color"];
        $description = $_POST["description"];
        $usedFor = $_POST["usedFor"];
        $price = $_POST["price"];
        $overallCondition = $_POST["overallCondition"];
        $sellersName = $_POST["sellersName"];
        $location = $_POST["location"];
        $meetUpPreference = $_POST["meetUpPreference"];
        // File upload handling
        //$images = $_FILES["images"]["name"];
        //  $tempImages = $_FILES["images"]["tmp_name"];
    
        if (empty($productName) || empty($category) || empty($brand) || empty($size) || empty($color) || empty($description) || empty($usedFor) || empty($price) || empty($overallCondition) || empty($sellersName) || empty($location) || empty($meetUpPreference)) {
            echo "<script>alert('All fields (except Knowledge About description) are compulsory.');</script>";
        } //else {
        //$targetDirectory = "img/";
        //$targetFiles = [];
    
        //foreach ($images as $key => $value) {
        //$targetFile = $targetDirectory . basename($images[$key]);
        // $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
        // if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        //  echo "<script>alert('Sorry, only JPG, JPEG, PNG files are allowed.');</script>";
        // $uploadOk = 0;
        //  }
    
        //if (move_uploaded_file($tempImages[$key], $targetFile)) {
        //   $targetFiles[] = $targetFile;
        // } else {
        // echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        //  }
        //}
    
        //   $imagesJson = json_encode($targetFiles);
        $query = "INSERT INTO product ( productName, category, brand, size, color, description, usedFor, price, overallCondition, sellersName, location, meetUpPreference) VALUES ( '$productName', '$category', '$brand', '$size', '$color', '$description', '$usedFor', '$price', '$overallCondition', '$sellersName', '$location', '$meetUpPreference')";

        if (mysqli_query($con, $query)) {
            echo "<script>alert('Successfully Added');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
        }
    }

    ?>


    <form action="" method="post" autocomplete="off" enctype="multipart/form-data">


        <label for="productName" class="form-label">Product Name:</label>
        <input type="text" id="productName" name="productName" class="form-input" required><br /><br />

        <label for="category" class="form-label">Category:</label>
        <select class="form-input" name="category">
            <option>Select</option>
            <option value="Hm">Furniture</option>
            <option value="C">Clothing</option>
            <option value="L">Lifestyle</option>
        </select><br /><br />

        <label for="brand" class="form-label"> Brand:</label>
        <input type="text" id="brand" name="brand" class="form-input" required><br /><br />

        <label for="size" class="form-label">Size:</label>
        <input type="text" id="size" name="size" class="form-input" required><br /><br />

        <!-- <label for="color" class="form-label">Color:</label>
        <input type="text" id="color" name="color" class="form-input" required><br /><br /> -->

        <label for="description" class="form-label">Description:</label>
        <textarea id="description" name="description" class="form-input" required></textarea><br /><br />

        <!-- <label for="usedFor" class="form-label">Used For:</label>
        <input type="text" id="usedFor" name="usedFor" class="form-input" required><br /><br /> -->

        <label for="price" class="form-label">Price:</label>
        <input type="number" step="100" min="0" id="price" name="price" class="form-input" required><br /><br />

        <!-- <label for="overallCondition" class="form-label">Overall Condition:</label>
        <input type="text" id="overall" name="overallCondition" class="form-input" required><br /><br /> -->

        <label for="sellersName" class="form-label">Seller's Name:</label>
        <input type="text" id="sellersName" name="sellersName" class="form-input" required><br /><br />

        <label for="location" class="form-label">Location:</label>
        <input type="text" id="location" name="location" class="form-input" required><br /><br />

        <!-- <label for="meetUpPreference" class="form-label">Meetup Preference:</label>
        <input type="text" id="meetUpPreference" name="meetUpPreference" class="form-input" required><br /><br /> -->

        <!--<label for="images" class="form-label">Upload Images:</label>
   <input type="file" accept=".jpg,.jpeg,.png" name="images[]" multiple id="images" max="5" class="upload-btn"><br/><br/>-->
        <input type="submit" value="Submit" name="submit" id="submit-btn">
    </form>
    <br>
</body>

</html>