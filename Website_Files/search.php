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

        <?php
              switch ($_SESSION["message"])//Checks for flags
              {
                case 'deleted':
                  ?>
                  <div class="alert alert-success">Successfully deleted manifest.</div>
                  <?php
                break;
                case 'deletefailed':
                  ?>
                  <div class="alert alert-warning">Could not delete manifest.</div>
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
                <h1>Search Manifests</h1>
                <form action='search.php' method='POST'>
                    <label class="radio-inline">
                        <input type="radio" name="radios" value="title" checked>Title
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radios" value="author">Author
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="radios" value="id">Dataset URL
                    </label>
                    <input type="text" name="search">
                    <input class="btn btn-info" type="submit" name="submit" value="Search">
                </form>
                <?php
                if (isset($_POST["submit"])){
                    $connection = new MongoClient();
                    $db = $connection->collections;
                    $collection = $db->manifests;
                    $radio = $_POST["radios"];
                    $search = $_POST["search"];
                    $query = array($radio => $search);
                    $cursor = $collection->find($query);
                    echo '<h4>Total Number of Results: '.$cursor->count().'</h4>';
                ?>
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
                                echo '<td><input type="hidden" name="id" value="'.$manifest["id"].'">'.$manifest["id"].'</td>';
                                ?>
                            </tr>
                        </form>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
