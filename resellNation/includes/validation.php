<script>
    <?php
    if (!isset($_SESSION['pgasuid'])) {
        ?>
            alert("login to continue");
            window.location.href = '../signin.php';
        <?php
        // header("Location: ../signin.php");
    }
    ?>
</script>