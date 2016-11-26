<!DOCTYPE html>
<?php
    session_start();
    if(isset($_SESSION['username']))
    {
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
    </head>
    <body>
        <div class="container-fluid">
            <br>
            <br>
            <div class="row text-center">
                <?php
              switch ($_SESSION["fail"])//Checks for fail flags
              {
                case '-1'://All Database errors
                  ?>
                  <div class="alert alert-danger">Could not log in.</div>
                  <?php
                break;
                case 'invalidusername':
                  ?>
                  <div class="alert alert-warning">You have entered an invaid username.</div>
                  <?php
                break;
                case 'invalidpassword':
                  ?>
                  <div class="alert alert-warning">You have entered an invalid password.</div>
                  <?php
                break;
                default:
                  break;
              }
              unset($_SESSION["fail"]);
               ?>
            </div>
            <h1>Login</h1>
            <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="col-md-4 col-md-offset-5">
                <div class="row">
                    <div class="input-group">
                        <div class="form-group">
                            <label class="inputdefault">Username</label>
                            <input class="form-control" type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label class="inputdefault">Password</label>
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-info" type="submit" name="submit" value="Log In">
                            <a class="btn btn-info" href="createUser.php">Create User</a>
                        </div>
                    </div>
                </div>
            </form>

            <?php
		            if(isset($_POST['submit'])) {

                    //Connect to database and select users collection
                    $m = new MongoClient();
                    $db = $m->collections;
                    $collection = $db->users;

                    //Username is a unique identifier so this will only return one or zero entries
                    $foundUser = $collection->findOne(array("username" => $_POST['username']));

                    //If there is no entry with the typed username
                    if($foundUser == NULL) {
                        $_SESSION['fail'] = 'invalidusername';
                        header("Location: login.php");
                        exit();
                    }

                    //Get the salt and hashed password out of the table
                    $salt = $foundUser['salt'];
                    $hpass = $foundUser['hpass'];

                    //Verify that the typed password matches the entry
                    if(password_verify($salt.$_POST['password'], $hpass)){
                        $_SESSION['username'] = $_POST['username'];
                        header("Location: index.php");
                        exit();
                    } else {
                        $_SESSION['fail'] = 'invalidpassword';
                        header("Location: login.php");
                        exit();
                    }
                }
            ?>
        </div>
    </body>
</html>