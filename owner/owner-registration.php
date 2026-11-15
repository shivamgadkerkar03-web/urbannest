<?php
session_start();
error_reporting(0);
include ('includes/dbconnection.php');

if (isset($_POST['submit'])) {
  $fname = $_POST['name'];
  $mobno = $_POST['mobilnumber'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $uploadDir = 'uploads/';
  $uploadFile = $uploadDir . basename($_FILES['file']['name']);
  
  $ret = mysqli_query($con, "select Email from tblowner where Email='$email'");
  $result = mysqli_fetch_array($ret);
  if ($result > 0) {
    $msg = "This email or Contact Number already associated with another account";
  } else {
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
      $query = mysqli_query($con, "INSERT INTO tblowner (FullName, MobileNumber, Email,  Password, VerificationFile) VALUES ('$fname', '$mobno', '$email', '$password', '$uploadFile')");
      if ($query) {
        header('location:login.php');
        $msg = "You have successfully registered";
      } else {
        $msg = "Something Went Wrong. Please try again";
      }
    } else {
      $msg = "File upload failed";
    }
  }
}
?>



<!DOCTYPE html>

<head>
  <title>UrbanNest | Owner Registration </title>

  <script type="application/x-javascript">
    addEventListener("load", function() {
      setTimeout(hideURLbar, 0);
    }, false);

    function hideURLbar() {
      window.scrollTo(0, 1);
    }
  </script>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="css/style.css" rel='stylesheet' type='text/css' />
  <link href="css/style-responsive.css" rel="stylesheet" />

  <link
    href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic'
    rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="css/font.css" type="text/css" />
  <link href="css/font-awesome.css" rel="stylesheet">

  <script src="js/jquery2.0.3.min.js"></script>
  <script type="text/javascript">
    function checkpass() {
      if (document.signup.password.value != document.signup.repeatpassword.value) {
        alert('Password and Repeat Password field does not match');
        document.signup.repeatpassword.focus();
        return false;
      }
      return true;
    }

    function validatePhoneNumber(phone, password) {
      // Remove all characters except digits
      phone = phone.replace(/\D/g, '');
      if (phone.length == 10) {
        if (phone >= 6000000000) {
          if(isStrongPassword(password)) {
            return true;
          } else {
            document.myForm.password.focus();
            alert("Password Must Be Strong !!")
            return false;
          }
        }
      }
      alert('Contact number Invalid', phone.length);
      document.myForm.mobilnumber.focus();
      return false;
    }

    function isStrongPassword(password) {
			// Define regular expressions for each criterion
			const minLength = 8; // Minimum length
			const hasUppercase = /[A-Z]/;
			const hasLowercase = /[a-z]/;
			const hasNumber = /\d/;
			const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/; // Add or remove special characters as needed

			// Check each criterion
			const isMinLength = password.length >= minLength;
			const hasUppercaseLetter = hasUppercase.test(password);
			const hasLowercaseLetter = hasLowercase.test(password);
			const hasNumberDigit = hasNumber.test(password);
			const hasSpecialCharacter = hasSpecialChar.test(password);

			// Return true if all criteria are met, false otherwise
			return (
				isMinLength &&
				hasUppercaseLetter &&
				hasLowercaseLetter &&
				hasNumberDigit &&
				hasSpecialCharacter
			);
		}
  </script>

</head>

<body>
  <div class="reg-w3">
    <div class="w3layouts-main">
      <h2 style="color:#fff;">UrbanNest | OWNER</h2>
      <hr />
      <h3 align="center">Register Now</h3>
      <hr />
      <p style="font-size:16px; color:red" align="center">
        <?php if ($msg) {
          echo $msg;
        } ?>
      </p>
      <!-- maxlength="10" pattern="[0-9]+" -->
      <form enctype="multipart/form-data" action=" #" method="post" enctype="multipart/form-data" name="myForm">
        <input type="text" class="ggg" name="name" placeholder="NAME" required="true">
        <input type="email" class="ggg" name="email" placeholder="E-MAIL" required="true">
        <input type="text" class="ggg" name="mobilnumber" placeholder="PHONE" required="true">
        <input type="password" class="ggg" name="password" placeholder="PASSWORD" required="true">
        <input type="password" class="ggg" name="repeatpassword" placeholder="REPEAT PASSWORD" required="true"><br><br>
        <label for="file" style="font-weight: 900;color:white;">Upload Verification Document</label><br>
        <input type="file" class="ggg" name="file" required="true">
        <h4><input type="checkbox" required="true" />I agree to the Terms of Service and Privacy Policy</h4>

        <div class="clearfix"></div>
        <!-- <input type="submit" value="submit" name="submit" onclick="return validatePhoneNumber(document.myForm.mobilnumber.value, document.myForm.password.value);"> -->
        <input type="submit" value="submit" name="submit">
      </form>
      <p>Already Registered.<a href="login.php">Login</a></p>
      <hr />
      <p align="center">
        <a href="../index.php" class="btn btn-primary">Back to Home Page</a>
      </p>
    </div>
  </div>
  <script src="js/bootstrap.js"></script>
  <script src="js/jquery.dcjqaccordion.2.7.js"></script>
  <script src="js/scripts.js"></script>
  <script src="js/jquery.slimscroll.js"></script>
  <script src="js/jquery.nicescroll.js"></script>
  <script src="js/jquery.scrollTo.js"></script>
</body>

</html>