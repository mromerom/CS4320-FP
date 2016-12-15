<!DOCTYPE html>

<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if(!isset($_POST['datasetURL'])) {
      $_SESSION["message"] = '-1';
    } else {
      //Connect to database and select manifests collection
      $m = new MongoClient();
      $db = $m->collections;
      $collection = $db->manifests;

      $foundManifest = $collection->findOne(array("datasetURL" => $_POST['datasetURL']));
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
        <script>
            function auto_grow(element) {
                element.style.height = "5px";
                element.style.height = (element.scrollHeight+20)+"px";
            }
        </script>
        <style>
            textarea {
                resize: none;
                overflow: hidden;
                min-height: 50px;
                width: 700px;
            }
            
            #fbootstrap {
                margin-left: 0%;
            }
            
            #nobootstrap {
                margin-bottom: 8px;
            }
        </style>
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
        <form id="fbootstrap" action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="col-md-4 col-md-offset-5">
            <div class="input-group">
                <div class="form-group">
                    <textarea autofocus onfocus="auto_grow(this)" name="textarea"><?=$manifestString?></textarea>
                </div>
            </div>
            <input type="hidden" name="datasetURL" value="<?=$_POST['datasetURL']?>"/>
            <input id="nobootstrap" class="btn btn-info" type="submit" name="edit" value="Submit">
        </form>

        <?php
          if(isset($_POST['edit'])) {
            //Connect to database and select manifests collection
            $m = new MongoClient();
            $db = $m->collections;
            $collection = $db->manifests;

            if($collection -> findOne(array('datasetURL' => $_POST['datasetURL'], 'username' => $_SESSION['username'])) == NULL){
                $collection -> update(array('datasetURL' => $_POST['datasetURL']), array('$addToSet' => array("contributors" => $_SESSION['username'])));
            }
          }
          ?>
    </body>
</html>
