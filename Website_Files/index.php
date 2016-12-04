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

        <?php
              switch ($_SESSION["message"])//Checks for flags
              {
                case 'created':
                  ?>
                  <div class="alert alert-success">Successfully created user!</div>
                  <?php
                break;
                case 'loggedin':
                  ?>
                  <div class="alert alert-success">Successfully logged in!</div>
                  <?php
                break;
                default:
                  break;
              }
              unset($_SESSION["message"]);
        ?>
        <div>
            <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Manifest</th>
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
                                <td><input type="hidden" name="Manifest" value="Blackboard">Blackboard</td>
                                <td>11/12/2016</td>
                            </tr>
                        </form>
                    </tbody>
                </table>
        </div>
    </body>
</html>
