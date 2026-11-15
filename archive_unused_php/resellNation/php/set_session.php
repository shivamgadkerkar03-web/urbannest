<?php
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['variableValue']) && isset($data['type'])) {

    session_start();
    $type = $data['type'];
    function show($search, $filter, $type)
    {
        $html = "";
        include ('../connect.php');
        $ret = mysqli_query($con, "select * from product");
        while ($row = mysqli_fetch_array($ret)) {
            if ($type == "onlyName") {
                if ($search) {
                    if (stripos($row['productName'], $search) !== false) {
                        $html .= '<div class="col-3 my-2">
                        <div
                            class="card d-flex flex-column justify-content-center align-items-center removeBord parent p-1">
                            <img src="upload/' . $row['images'] . '" class="card-img-top imgCard mt-1">
                            <h5 class="card-title mt-1"><strong>' . $row['productName'] . '</strong>

                            </h5>
                            <h6 class="card-title">' . $row['price'] . '₹</h6>
                            <divT class="overlay" id="' . $row['productId'] . '"></divT>
                        </div>
                    </div>';
                    }
                }
            } elseif ($type == "onlyFilter") {
                if ($row['category'] == $filter) {
                    $html .= '<div class="col-3 my-2">
                    <div
                        class="card d-flex flex-column justify-content-center align-items-center removeBord parent p-1">
                        <img src="upload/' . $row['images'] . '" class="card-img-top imgCard mt-1">
                        <h5 class="card-title mt-1"><strong>' . $row['productName'] . '</strong>

                        </h5>
                        <h6 class="card-title">' . $row['price'] . '₹</h6>
                        <divT class="overlay" id="' . $row['productId'] . '"></divT>
                    </div>
                </div>';
                }
            } elseif ($type == "both") {
                if (stripos($row['productName'], $search) !== false && $row['category'] == $filter) {
                    $html .= '<div class="col-3 my-2">
                    <div
                        class="card d-flex flex-column justify-content-center align-items-center removeBord parent p-1">
                        <img src="upload/' . $row['images'] . '" class="card-img-top imgCard mt-1">
                        <h5 class="card-title mt-1"><strong>' . $row['productName'] . '</strong>

                        </h5>
                        <h6 class="card-title">' . $row['price'] . '₹</h6>
                        <divT class="overlay" id="' . $row['productId'] . '"></divT>
                    </div>
                </div>';
                }
            }
        }
        return $html;
    }

    // Call the show function here and encode its output
    // $search = $_SESSION[$data['variableName']]; // Assuming variableName is the key for the search string
    // $html = show($search);
    echo json_encode(array("success" => true, "data" => show($data['variableValue'], $data['filter'], $data['type'])));

} else {
    echo json_encode(array("success" => false, "data" => "Error: Missing required data"));
}
?>