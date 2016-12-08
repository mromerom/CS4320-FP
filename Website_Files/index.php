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
        <div class="container">
            <br>
            <div class="row align-center">
                <?php
                    $connection = new MongoClient();
                    $db = $connection->collections;
                    $collection = $db->manifests;

                    $cursor = $collection->find();
                    $cursor = $cursor->sort(array("dateCreated" => -1));
                ?>
                <h1>Recent Uploads</h1>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Dataset URL</th>
                        </tr>
                    </thead>
                    <br>
                    <tbody>
                        <?php
                        foreach ($cursor as $manifest){
                        ?>
                        <form action="viewManifest.php" method="POST">
                            <tr>
                                <td>
                                    <input class="btn btn-info" type="submit" name="view" value="View">
                                </td>
                                <?php
                                echo '<td><input type="hidden" name="title" value="'.$manifest["title"].'">'.$manifest["title"].'</td>';
                                echo '<td><input type="hidden" name="author" value="'.$manifest["author"].'">'.$manifest["author"].'</td>';
                                echo '<td><input type="hidden" name="datasetURL" value="'.$manifest["datasetURL"].'">'.$manifest["datasetURL"].'</td>';
                                ?>
                            </tr>
                        </form>
                        <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
