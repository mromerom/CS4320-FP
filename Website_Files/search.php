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
                    <input type="text" name="search">
                    <input class="btn btn-info" type="submit" name="submit" value="Search">
                </form>
                <?php
                if (isset($_POST["submit"])){
                    $connection = new MongoClient();
                    $db = $connection->collections;
                    $collection = $db->manifests;
                    $radio = $_POST["radios"];
                    $string = $_POST["search"];
                    $cursor = $collection->find(
                        array('$text' => array('$search' => $string)),
                        array('score' => array('$meta' => 'textScore'))
                    );

                    $cursor = $cursor->sort(
                        array('score' => array('$meta' => 'textScore'))
                    );
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
                                echo '<td><input type="hidden" name="datasetURL" value="'.$manifest["datasetURL"].'">'.$manifest["datasetURL"].'</td>';
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

<!--Copyright (c) 2016 Kevin Free, Matthew Romero Moore, Ryan Tray, Jonathon Israel, Ryan Jones, Matthew Schwarz

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.-->
