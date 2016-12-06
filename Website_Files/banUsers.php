<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }

    if($_SESSION['usertype'] != 'admin') {
        header("Location: index.php");
        exit();
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php
        include_once("navbar.php");
        ?>
        <?php
        switch ($_SESSION["message"])//Checks for flags
        {
          case 'unfound':
            ?>
            <div class="alert alert-warning">No user exists with this username.</div>
            <?php
          break;
          case 'success':
            ?>
            <div class="alert alert-success">Successfully banned user.</div>
            <?php
          break;
          case 'failed':
            ?>
            <div class="alert alert-danger">Ban user failed.</div>
            <?php
          break;
          case 'admin':
            ?>
            <div class="alert alert-warning">You can't delete an admin user.</div>
            <?php
          break;
          default:
            break;
        }
        unset($_SESSION["message"]);
        ?>

        <div class="container">
            <br>
            <div class="row align-center">
                <h1>Ban Users</h1>
                <form action='banUsers.php' method='POST'>
                    <input type="text" name="search">
                    <input class="btn btn-info" type="submit" name="submit" value="Search">
                </form>
                <?php
                if (isset($_POST["submit"])){
                    $connection = new MongoClient();
                    $db = $connection->collections;
                    $collection = $db->users;

                    $foundUser = $collection->findOne(array("username" => $_POST['search']));

                    if($foundUser == NULL) {
                        $_SESSION['message'] = 'unfound';
                        header("Location: banUsers.php");
                        exit();
                    }

                    if($foundUser['usertype'] == "admin") {
                        $_SESSION['message'] = 'admin';
                        header("Location: banUsers.php");
                        exit();
                    }

                    if($collection->remove(array("username" => $_POST['search'])) == TRUE) {
                        $_SESSION['message'] = "success";
                        header("Location: banUsers.php");
                        exit();
                    } else {
                        $_SESSION['message'] = "failed";
                        header("Location: banUsers.php");
                        exit();
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>
