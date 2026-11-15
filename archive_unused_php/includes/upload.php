<?php
// Check if form is submitted
if (isset($_POST['submit'])) {
    $targetDirectory = 'uploads/';
    $targetFile = $targetDirectory . basename($_FILES['image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo 'File is not an image.';
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo 'Sorry, file already exists.';
        $uploadOk = 0;
    }

    // Check file size (you can set your own size limit)
    if ($_FILES['image']['size'] > 500000) {
        echo 'Sorry, your file is too large.';
        $uploadOk = 0;
    }

    // Allow certain file formats (you can add more formats as needed)
    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedFormats)) {
        echo 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo 'Sorry, your file was not uploaded.';
    } else {
        // Upload the file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            echo 'The file ' . htmlspecialchars(basename($_FILES['image']['name'])) . ' has been uploaded.';
        } else {
            echo 'Sorry, there was an error uploading your file.';
        }
    }
}
?>
