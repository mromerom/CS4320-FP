<!DOCTYPE html>

<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if($_SESSION['usertype'] != 'admin' && $_SESSION['usertype'] != 'scientist') {
        $_SESSION['message'] = 'invalidusertype';
        header("Location: index.php");
        exit();
    }
    if(!isset($_POST['datasetURL'])) {
        $_SESSION["message"] = '-1';
    } else {
        //Connect to database and select manifests collection
        $m = new MongoClient();
        $db = $m->collections;
        $collection = $db->manifests;

        $oldManifest = $collection->findOne(array("datasetURL" => $_POST['datasetURL']));

        if($_SESSION['username'] != $oldManifest['username'] && !in_array($_SESSION['username'], $oldManifest['contributors'])) {
            $_SESSION['message'] = 'invaliduser';
            header("Location: index.php");
            exit();
        } else {

            if(isset($_POST['updateEntry'])) {

                $updateManifest = json_decode($_POST['updateEntry'], true);
                unset($updateManifest["_id"]);

                if($updateManifest['datasetURL'] != $oldManifest['datasetURL']) {
                    $_SESSION["message"] = 'datasetURL';
                } else if($updateManifest['title'] != $oldManifest['title']) {
                    $_SESSION["message"] = 'title';
                } else if($updateManifest['dateCreated'] != $oldManifest['dateCreated']) {
                    $_SESSION["message"] = 'dateCreated';
                } else if($updateManifest['username'] != $oldManifest['username']) {
                    $_SESSION["message"] = 'username';
                } else {
                    $collection->update(array("datasetURL" => $_POST['datasetURL']),
                                        $updateManifest,
                                        array("upsert" => true));
                    $_SESSION["message"] = 'success';
                }
              }

              $foundManifest = $collection->findOne(array("datasetURL" => $_POST['datasetURL']));
              $manifestString = json_encode($foundManifest, JSON_PRETTY_PRINT);
        }
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
            var oldjson;
            $( document ).ready(function() {
                oldjson = JSON.parse(document.getElementById("textarea1").innerHTML);
            });
            function auto_grow(element) {
                element.style.height = "5px";
                element.style.height = (element.scrollHeight+20)+"px";
            }
            function compareKeys(a, b) {
              var aKeys = Object.keys(a).sort();
              var bKeys = Object.keys(b).sort();
              //console.log(aKeys);
              //console.log(bKeys);
              return JSON.stringify(aKeys) === JSON.stringify(bKeys);
            }
            function Submit() {
              var newjson = JSON.parse(document.getElementById("textarea1").value);
              if(!compareKeys(oldjson, newjson)){
              	alert("You can't change any of the fields!");
              } else {
                document.getElementById("updateEntry").value = document.getElementById("textarea1").value;
                document.Edit.action="editManifest.php";
                document.Edit.submit();
              }
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
                case 'datasetURL':
                  ?>
                  <div class="alert alert-danger">You can't change the dataset URL.</div>
                  <?php
                break;
                case 'title':
                  ?>
                  <div class="alert alert-danger">You can't change the title.</div>
                  <?php
                break;
                case 'dateCreated':
                  ?>
                  <div class="alert alert-danger">You can't change the creation date.</div>
                  <?php
                break;
                case 'username':
                  ?>
                  <div class="alert alert-danger">You can't change the username.</div>
                  <?php
                break;
                case 'success':
                  ?>
                  <div class="alert alert-success">Successfully updated manifest entry!</div>
                  <?php
                break;
                default:
                  break;
              }
              unset($_SESSION["message"]);
        ?>
        <h1>Edit Manifest</h1>
        <div class="alert alert-info">All edits must be properly formatted JSON or submit will not work.</div>
        <form id="fbootstrap" name="Edit" action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="col-md-4 col-md-offset-5">
            <div class="input-group">
                <div class="form-group">
                    <textarea autofocus onfocus="auto_grow(this)" name="textarea" id="textarea1"><?=$manifestString?></textarea>
                </div>
            </div>
            <input type="hidden" name="updateEntry" id="updateEntry" value=""/>
            <input type="hidden" name="datasetURL" value="<?=$_POST['datasetURL']?>"/>
            <button class="btn btn-info" type="button" name="makeAdmin" onclick="Submit()">Submit</button>
        </form>

    </body>
</html>

<!--Copyright (c) 2016 Kevin Free, Matthew Romero Moore, Ryan Tray, Jonathon Israel, Ryan Jones, Matthew Schwarz

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.-->
