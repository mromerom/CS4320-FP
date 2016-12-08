<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }

    if($_SESSION['usertype'] != 'admin') {
        header("Location: index.php");
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
            function BanUser(){
                var r = confirm("Are you sure you want to ban '<?=$_POST['search']?>'?");
                if(r == true){
                    document.getElementById("postAction").value = "ban";
                    document.Manage.action = "manageUsers.php";
                    document.Manage.submit();
                    return true;
                }else{
                    return false;
                }
            }
            function MakeAdmin(){
                var r = confirm("Are you sure you want to promote '<?=$_POST['search']?>' to admin?");
                if(r == true){
                    document.getElementById("postAction").value = "promote";
                    document.Manage.action = "manageUsers.php";
                    document.Manage.submit();
                    return true;
                }else{
                    return false;
                }
            }
        </script>
    </head>
    <body>
        <?php
        include_once("navbar.php");
        ?>
        <?php
        switch ($_SESSION["message"])
        {
          case 'unfound':
            ?>
            <div class="alert alert-warning">No user exists with this username.</div>
            <?php
          break;
          case 'success':
            ?>
            <div class="alert alert-success">Success!</div>
            <?php
          break;
          case 'failed':
            ?>
            <div class="alert alert-danger">User change failed!</div>
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
                <h2>Search for a username</h2>
                <form action='manageUsers.php' method='POST'>
                    <input type="text" name="search">
                    <input class="btn btn-info" type="submit" name="submit" value="Search">
                </form>
                <?php
                if ((isset($_POST["submit"])) || (isset($_POST["postAction"]))) {
                    $connection = new MongoClient();
                    $db = $connection->collections;
                    $collection = $db->users;

                    $foundUser = $collection->findOne(array("username" => $_POST['search']));

                    switch ($_POST["postAction"])
                    {
                        case 'ban':
                            if($collection->remove(array("username" => $_POST['user'])) == TRUE) {
                                $_SESSION['message'] = "success";
                                header("Location: manageUsers.php");
                                exit();
                            } else {
                                $_SESSION['message'] = "failed";
                                header("Location: manageUsers.php");
                                exit();
                            }
                        break;
                        case 'promote':
                            if($collection->update(array('username' => $_POST['user']), array('$set' => array('usertype' => 'admin'))) == TRUE) {
                              $_SESSION['message'] = "success";
                              header("Location: manageUsers.php");
                              exit();
                          } else {
                              $_SESSION['message'] = "failed";
                              header("Location: manageUsers.php");
                              exit();
                            }
                        break;
                        default:
                        break;
                    }

                    if($foundUser == NULL) {
                        $_SESSION['message'] = 'unfound';
                        header("Location: manageUsers.php");
                        exit();
                    }
                }
                ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>User Type</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <br>
                    <tbody>
                        <?php
                        if ($foundUser != NULL){
                        ?>
                            <form name="Manage" method="POST">
                                <tr>
                                    <?php
                                    echo '<td><input type="hidden" name="user" value="'.$foundUser["username"].'">'.$foundUser["username"].'</td>';
                                    echo '<td><input type="hidden" name="usertype" value="'.$foundUser["usertype"].'">'.$foundUser["usertype"].'</td>';
                                    ?>
                                    <input type="hidden" name="postAction" id="postAction" value="">
                                    <td><button class="btn btn-danger" type="button" name="banUser" onclick="BanUser()">Ban User</button></td>
                                    <td><button class="btn btn-info" type="button" name="makeAdmin" onclick="MakeAdmin()">Make Admin</button></td>
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
