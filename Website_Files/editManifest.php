<!DOCTYPE html>

<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if(!isset($_POST['title'])) {
      $_SESSION["message"] = '-1';
    } else {
      //Connect to database and select manifests collection
      $m = new MongoClient();
      $db = $m->collections;
      $collection = $db->manifests;

      $foundManifest = $collection->findOne(array("title" => $_POST['title']));
      $manifestString = json_encode($foundManifest, JSON_PRETTY_PRINT);
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
              switch ($_SESSION["message"])
              {
                case '-1':
                  ?>
                  <div class="alert alert-danger">Failed.</div>
                  <?php
                break;
                default:
                  break;
              }
              unset($_SESSION["message"]);
        ?>
        <h1>Edit Manifest</h1>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="col-md-4 col-md-offset-5">
            <div class="input-group">
                <div class="form-group">
                    <textarea rows="100" cols="75" name="textarea"><?=$manifestString?></textarea>
                </div>
            </div>
            <input class="btn btn-info" type="submit" name="submit" value="Submit">
        </form>

        <?php
          if(isset($_POST['submit'])) {
            //Connect to database and select manifests collection
            $m = new MongoClient();
            $db = $m->collections;
            $collection = $db->manifests;

          }
          ?>
    </body>
</html>
