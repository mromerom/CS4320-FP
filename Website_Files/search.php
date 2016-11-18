<!DOCTYPE html>
<?php
    session_start();
/*
    if(!isset($SESSION['username'])){
        header("Location: login.php");
    }
*/
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
                <h1>Search Manifests</h1>
                <form action='search.php' method='POST'>
                    <input type="test" name="search">
                    <input class="btn btn-info" type="submit" name="submit" value="Search">
                </form>
                <?php
                if (isset($_POST["submit"])){
                    ?>
                    <h4>Total Number of Results: 1</h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Manifests</th>
                            <th>Creator</th>
                            <th>Last Edited</th>
                        </tr>
                    </thead>
                    <br>
                    <tbody>
                        <form action='viewManifest.php' method='post'>
                            <tr>
                                <td>
                                    <input class="btn btn-info" type="submit" name="view" value="View">
                                </td>
                                <td><input type="hidden" name="Manifest" value="Amazon Analysis">Amazon Analysis</td>
                                <td>Matthew Romero Moore</td>
                                <td>11/12/2016</td>
                            </tr>
                        </form>
                    </tbody>
                </table>
                        <?php
                }
                ?>
            </div>
        </div>
    </body>
</html>
