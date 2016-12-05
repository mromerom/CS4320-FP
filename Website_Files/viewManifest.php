<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if(!isset($_POST['title'])) {
      $_SESSION["message"] = '-1';
      header("Location: search.php");
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
        <script>
            function Download(){
            }

            function Edit(){
                document.View.action = "editManifest.php";
                document.View.submit();
                return true;
            }

            function Delete(){
                var r = confirm("Are you sure you want to delete the manifest?");
                if(r == true){
                    document.View.action = "deleteManifest.php";
                    document.View.submit();
                    return true;
                }else{
                    return false
                }
            }
        </script>
    </head>
    <body>
        <?php
        include_once("navbar.php");
        ?>

        <?php
        $m = new MongoClient();
        $db = $m->collections;
        $collection = $db->manifests;

        $foundManifest = $collection->findOne(array("datasetURL" => $_POST['datasetURL']));
        $manifestString = json_encode($foundManifest, JSON_PRETTY_PRINT);
        echo '<pre>'.$manifestString.'</pre>';
        ?>
        <form name="View" method="POST">
            <div class="form-group">
                <input type="hidden" name="datasetURL" value="<?=$_POST['datasetURL']?>" />
                <input type="hidden" name="delete" value="Delete" />
                <button class="btn btn-info" type="button" name="download" onclick="Download">Download</button>
                <button class="btn btn-info" type="button" name="edit" onclick="Edit()">Edit</button>
                <button class="btn btn-warning" type="button" name="delete" onclick="Delete()">Delete</button>
            </div>
        </form>
    </body>
</html>
