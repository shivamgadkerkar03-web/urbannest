<?php
session_start();
function addUi($fil)
{
    require 'dbconnection.php';
    $ret = mysqli_query($con, "select * from adRoomie");
    $cnt = 1;
    while ($row = mysqli_fetch_array($ret)) {
        if (!$row['hide']) {
            if (isset($_GET['arg'])) {
                if ($_GET['arg'] == $row['choosePG']) {
                    ?>
                    <div class="col-lg-4 mt-3">
                        <div class="single-property">
                            <div class="images">
                                <img class="mx-auto d-block imageEdit" src="img/<?php echo $row["image"]; ?>"
                                    title="<?php echo $row['image']; ?>" style="object-fit: cover;">
                                <span>ForRoomie</span>
                            </div>

                            <div class="desc">
                                <div class="top d-flex justify-content-between">
                                    <h4><a href="pg-details.php?pid=<?php echo $row['ID']; ?>">PG Name:
                                            <?php echo $row['choosePG']; ?>
                                        </a></h4>

                                </div>


                                <div class="middle">
                                    <div class="d-flex justify-content-start">
                                        <p>Name <span class="gr">
                                                <?php echo $row['name']; ?>
                                            </span></p>
                                        <p>Gender: <span class="rd">
                                                <?php echo $row['Gender']; ?>
                                            </span></p>
                                        <!-- <p>Addiction: <span class="rd">
                                                <?php //echo $row['addiction']; ?>
                                            </span></p> -->
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <p>Contactno: <span class="gr">
                                                <?php echo $row['contactNo']; ?>
                                            </span></p>
                                        <!-- <p>Hoobies: <span class="rd">
                                                <?php //echo $row['hobbies']; ?>
                                            </span></p><br> -->
                                        <p>Location: <span class="rd">
                                                <?php echo $row['location']; ?>
                                            </span></p>
                                    </div>
                                    <!-- <div class="d-flex justify-content-start">
                                        <p>Addictioninfo: <span class="rd">
                                                <?php// echo $row['knowledgeAboutAddiction']; ?>
                                            </span></p>
                                    </div> -->
                                </div>
                                <div class="bottom d-flex justify-content-start">
                                    <p><span class="lnr lnr-heart"></span>
                                        <?php echo $row['StateName']; ?>
                                    </p>
                                    <p><span class="lnr lnr-bubble"></span>
                                        <?php echo $row['CityName']; ?>
                                    </p>



                                </div>
                                <div class="mt-1 d-flex justify-content-between align-items-center">


                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-lg-4 mt-3">
                    <div class="single-property">
                        <div class="images">
                            <img class="mx-auto d-block imageEdit" src="img/<?php echo $row["image"]; ?>" width="100"
                                title="<?php echo $row['image']; ?>">
                            <span>ForRoomie</span>
                        </div>

                        <div class="desc container d-flex flex-column justify-content-between">
                            <div class="w-100">
                                <div class="top d-flex justify-content-between">
                                    <!-- <h4><a href="pg-details.php?pid=<?php echo $row['ID']; ?>">PG Name:
                                                            <?php //echo $row['choosePG']; ?>
                                                        </a></h4> -->
                                    <h4>PG Name: <?php echo $row['choosePG']; ?></h4>

                                </div>


                                <div class="middle">
                                    <div class="d-flex justify-content-start">
                                        <p>Name <span class="gr">
                                                <?php echo $row['name']; ?>
                                            </span></p>
                                        <p>Gender: <span class="rd">
                                                <?php echo $row['Gender']; ?>
                                            </span></p>
                                        <p>Addiction: <span class="rd">
                                                <?php echo $row['addiction']; ?>
                                            </span></p>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <p>Contactno: <span class="gr">
                                                <?php echo $row['contactNo']; ?>
                                            </span></p>
                                        <p>Hoobies: <span class="rd">
                                                <?php echo $row['hobbies']; ?>
                                            </span></p><br>
                                        <p>Location: <span class="rd">
                                                <?php echo $row['location']; ?>
                                            </span></p>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <p>Addictioninfo: <span class="rd">
                                                <?php echo $row['knowledgeAboutAddiction']; ?>
                                            </span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="bottom d-flex justify-content-start">
                                <p><span class="lnr lnr-heart"></span>
                                    <?php echo $row['StateName']; ?>
                                </p>
                                <p><span class="lnr lnr-bubble"></span>
                                    <?php echo $row['CityName']; ?>
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}

function loggedOut()
{
    echo '
               		 <h4 class="container-fluid mt-5"><span
                        class="badge text-bg-warning container-fluid mb-2 d-flex align-items-center"
                        style="width: 100% !important;"><ion-icon size="large" class="mx-2"
                            name="alert-circle-outline"></ion-icon>Warning :
                        Login / Register to add Roomie</span></h4>';
}


if (!isset($_SESSION['pgasuid'])) {
    loggedOut();
} else {
    addUi("");
}
?>