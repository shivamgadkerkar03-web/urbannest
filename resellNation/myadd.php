<head>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- <link rel="stylesheet" href="styles.css"> -->
</head>
<?php
include ('connect.php');
?>
<html>

<?php
session_start();
include_once ('includes/validation.php');
include_once ('includes/header2.php');
?>


<body>
    <a href="./index.php"
        class="bg-primary text-white rounded-pill text-center p-2 m-5 d-flex justify-content-center align-items-center"
        style="max-width: 20em;"><ion-icon size="large" name="arrow-back-outline" class="me-4"></ion-icon>
        <h4 class="m-0">Back</h4>
    </a>

    <div class="container-fluid">
        <h1 class="text-center mt-5">My Ads</h1>
    </div>
    <div class="container my-5" id="allPro">
        <div class="row">

            <?php
            $ret = mysqli_query($con, "select * from product where userId =" . $_SESSION['pgasuid'] . "");

            while ($row = mysqli_fetch_array($ret)) {
                ?>
                <div class="col-3 my-2">
                    <div class="card d-flex flex-column justify-content-center align-items-center removeBord parent p-1">
                        <img src="upload/<?php echo $row['images']; ?>" class="card-img-top imgCard mt-1">
                        <h5 class="card-title mt-1"><strong>
                                <?php echo $row['productName']; ?>
                            </strong>

                        </h5>
                        <h6 class="card-title">
                            <?php echo $row['price']; ?>₹
                        </h6>
                        <div class="mb-2">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop" id="<?php echo $row['productId']; ?>" action="edit"
                                edData='<?php echo json_encode($row); ?>'>Edit</button>
                            <button type="button" class="btn btn-danger" id="<?php echo $row['productId']; ?>"
                                action="del">Delete</button>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>

    <script>
        let pId = 0;
        document.getElementById("allPro").addEventListener('click', (event) => {
            if (event.target.tagName == "BUTTON") {
                if (event.target.getAttribute("action") == "del") {

                    $("#confirmationModal").modal("show");

                    $("#confirmDeleteButton").click(function () {
                        console.log(event.target.id);
                        var deleteData = {
                            productId: event.target.id
                        };
                        sendData("delete", deleteData);
                    });

                } else if (event.target.getAttribute("action") == "edit") {
                    pId = event.target.id;
                    // event.preventDefault();
                    console.log(JSON.parse(event.target.getAttribute("edData")));
                    document.getElementById("imgProd").setAttribute("src", `upload/${JSON.parse(event.target.getAttribute("edData")).images}`)
                    document.getElementById("productName").value = JSON.parse(event.target.getAttribute("edData")).productName;
                    document.getElementById("category").value = JSON.parse(event.target.getAttribute("edData")).category;
                    document.getElementById("brand").value = JSON.parse(event.target.getAttribute("edData")).brand;
                    document.getElementById("size").value = JSON.parse(event.target.getAttribute("edData")).size;
                    // document.getElementById("color").value = JSON.parse(event.target.getAttribute("edData")).color;
                    document.getElementById("description").value = JSON.parse(event.target.getAttribute("edData")).description;
                    // document.getElementById("usedFor").value = JSON.parse(event.target.getAttribute("edData")).usedFor;
                    document.getElementById("price").value = JSON.parse(event.target.getAttribute("edData")).price;
                    // document.getElementById("overallCondition").value = JSON.parse(event.target.getAttribute("edData")).overallCondition;
                    document.getElementById("sellersName").value = JSON.parse(event.target.getAttribute("edData")).sellersName;
                    document.getElementById("location").value = JSON.parse(event.target.getAttribute("edData")).location;
                    // document.getElementById("meetUpPreference").value = JSON.parse(event.target.getAttribute("edData")).meetUpPreference;
                }
            }
        })

        function sendData(action, data) {
            var url = "./php/edDel.php";
            var jsonData = {
                action: action,
                data: data
            };
            var options = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonData)
            };
            fetch(url, options)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    if (action == "edit") {
                        console.log(data);
                        location.reload();
                    } else {
                        console.log(data);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    </script>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Product Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="modInp">
                            <img id="imgProd" src=""
                                style="width: 200px; height: 200px; overflow: hidden; object-fit: cover; object-position: center;"
                                class="img-thumbnail">
                            <input class="mt-3 modInp" type="file" id="imageNew">

                        </div>
                        <div class="mx-3 container-fluid">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">productName</span>
                                <input type="text" class="form-control" id="productName" aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text formIt" for="inputGroupSelect01">Category</label>
                                <select class="form-select" id="category">
                                    <option value="F">Furniture</option>
                                    <option value="E">Electronics</option>
                                    <option value="L">Lifestyle</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Brand</span>
                                <input type="text" class="form-control" id="brand" aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Size</span>
                                <input type="text" class="form-control" id="size" aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div>
                            <!-- <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Color</span>
                                <input type="text" class="form-control" id="color" aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div> -->
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Price</span>
                                <input type="text" class="form-control" id="price" aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Description</span>
                        <input type="text" class="form-control" id="description" aria-label="Username"
                            aria-describedby="basic-addon1">
                    </div>
                    <!-- <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Used For</span>
                        <input type="text" class="form-control" id="usedFor" aria-label="Username"
                            aria-describedby="basic-addon1">
                    </div> -->
                    <!-- <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Overall Condition</span>
                        <input type="text" class="form-control" id="overallCondition" aria-label="Username"
                            aria-describedby="basic-addon1">
                    </div> -->
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Seller's Name</span>
                        <input type="text" class="form-control" id="sellersName" aria-label="Username"
                            aria-describedby="basic-addon1" readonly>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Location</span>
                        <input type="text" class="form-control" id="location" aria-label="Username"
                            aria-describedby="basic-addon1">
                    </div>
                    <!-- <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Meet up preference</span>
                        <input type="text" class="form-control" id="meetUpPreference" aria-label="Username"
                            aria-describedby="basic-addon1">
                    </div> -->

                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="button" style="width: 100%;" class="btn btn-primary" id="editConf">Confirm</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="confirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>
                <div class="modal-body">
                    Are you sure you want to delete?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button id="confirmDeleteButton" type="button" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("editConf").addEventListener('click', (event) => {
            const fileInput = document.getElementById('imageNew');
            const file = fileInput.files[0];
            readImageFile(file, function (imageData) {
                // Create an object containing the image data if an image is selected
                let imageObject = null;
                if (file) {
                    imageObject = {
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        data: imageData
                    };
                    console.log(imageObject);
                }

                // Extract other data for editing
                let newData = {
                    productName: document.getElementById("productName").value,
                    category: document.getElementById("category").value,
                    brand: document.getElementById("brand").value,
                    size: document.getElementById("size").value,
                    // color: document.getElementById("color").value,
                    description: document.getElementById("description").value,
                    // usedFor: document.getElementById("usedFor").value,
                    price: document.getElementById("price").value,
                    // overallCondition: document.getElementById("overallCondition").value,
                    sellersName: document.getElementById("sellersName").value,
                    location: document.getElementById("location").value,
                    // meetUpPreference: document.getElementById("meetUpPreference").value,
                    imageObj: imageObject // Include the image object if selected
                };

                // Construct the edit data object
                const editData = {
                    action: 'edit',
                    productId: pId,
                    newData: newData
                };

                // Send the edit data to the server
                sendData("edit", editData);
            });

            function readImageFile(file, callback) {
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function () {
                        const imageData = reader.result;
                        callback(imageData);
                    };
                    reader.readAsDataURL(file);
                } else {
                    // If no image is selected, call the callback function with null
                    callback(null);
                }
            }
        });

    </script>

    <style>
        .modInp {
            width: 210px;
        }

        .imgCard {
            height: 200px;
            width: 200px;
            overflow: hidden;
            object-fit: cover;
            border-radius: 1rem;
        }

        .removeBord {
            border: none !important;
            background-color: rgba(245, 206, 118, 0.5);
            border-radius: 1rem;
            width: 220px;
        }

        .removeBord:hover {
            cursor: pointer;
        }

        .parent {
            position: relative;
            /* Other styles for the parent div */
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0);
        }

        span,
        .formIt {
            width: 200px !important;
        }
    </style>
</body>
<?php include_once ('includes/footer2.php'); ?>

</html>