<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }

    if(!isset($_POST['delete'])){
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }

    if(!isset($_POST['datasetURL'])){
      header("Location: ".$_SERVER['HTTP_REFERER']);
      exit();
    }

    if(!isset($_POST['manifestTitle'])){
      header("Location: ".$_SERVER['HTTP_REFERER']);
      exit();
    }

    if(!isset($_POST['manifestUsername'])){
      header("Location: ".$_SERVER['HTTP_REFERER']);
      exit();
    }

    $m = new MongoClient();
    $db = $m->collections;
    $collection = $db->manifests;

    $dir = "ManifestFiles/".$_POST['manifestUsername']."/".str_replace(' ', '', $_POST['manifestTitle']);

    if(!array_map('unlink', glob($dir."/*.*"))) {
        $_SESSION["message"] = 'deletefailed';
        header("Location: search.php");
        exit();
    }

    if(!rmdir($dir)) {
      $_SESSION["message"] = 'deletefailed';
      header("Location: search.php");
      exit();
    }

    if($collection->remove(array("datasetURL" => $_POST['datasetURL'])) == TRUE) {
        $_SESSION['message'] = "deleted";
        header("Location: search.php");
        exit();
    } else {
        $_SESSION['message'] = "deletefailed";
        header("Location: search.php");
        exit();
    }
?>

<!--Copyright (c) 2016 Kevin Free, Matthew Romero Moore, Ryan Tray, Jonathon Israel, Ryan Jones, Matthew Schwarz

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.-->
