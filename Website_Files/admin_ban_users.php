<!DOCTYPE html>
<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
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
        <div class="container">
            <br>
            <div class="row align-center">
                <h1>Ban Users</h1>
                <form action='ban_users.php' method='POST'>
                    <input type="test" name="search">
                    <input class="btn btn-info" type="submit" name="ban" value="Ban">
                </form>
                <?php
                if (isset($_POST["ban"])){
                    ?>
                        <h4>User: Matthew Romero Moore was banned</h4>
                        <?php
                }
                ?>
            </div>
        </div>
    </body>
</html>
