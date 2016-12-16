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
        <style>
            pre {
                white-space: pre-wrap;
            }
        </style>
        <script>
            function Download(){
                document.View.action = "downloadFiles.php";
                document.View.submit();
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
                <input type="hidden" name="manifestUsername" value="<?=$foundManifest['username']?>" />
                <input type="hidden" name="manifestTitle" value="<?=$foundManifest['title']?>" />
                <input type="hidden" name="datasetURL" value="<?=$_POST['datasetURL']?>" />
                <input type="hidden" name="delete" value="Delete" />
                <button class="btn btn-info" type="button" name="download" onclick="Download()">Download</button>
                <button class="btn btn-info" type="button" name="edit" onclick="Edit()">Edit</button>
                <button class="btn btn-warning" type="button" name="delete" onclick="Delete()">Delete</button>
            </div>
        </form>
    </body>
</html>

<!--Copyright (c) 2016 Kevin Free, Matthew Romero Moore, Ryan Tray, Jonathon Israel, Ryan Jones, Matthew Schwarz

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.-->
