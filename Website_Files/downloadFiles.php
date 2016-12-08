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

    header("Location: $dir");
?>