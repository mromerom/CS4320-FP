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
                  <div class="alert alert-danger">Could not create user.</div>
                  <?php
                break;
                case 'invalidusername':
                  ?>
                  <div class="alert alert-warning">Username is already taken.</div>
                  <?php
                break;
                case 'passwordnotmatch':
                  ?>
                  <div class="alert alert-warning">Password and Confirm Password did not match.</div>
                  <?php
                break;
                default:
                  break;
              }
              unset($_SESSION["message"]);
               ?>
            </div>
            <h1>Create User</h1>
            <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="col-md-4 col-md-offset-5">
                <div class="row">
                    <div class="input-group">
                        <div class="form-group">
                            <label class="inputdefault">First Name</label>
                            <input class="form-control" type="text" name="fname" placeholder="First Name" required>
                        </div>
                        <div class="form-group">
                            <label class="inputdefault">Last Name</label>
                            <input class="form-control" type="text" name="lname" placeholder="Last Name" required>
                        </div>
                        <div class="form-group">
                            <label class="inputdefault">Username</label>
                            <input class="form-control" type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label class="inputdefault">User Type</label>
                            <select class="form-control" name="usertype">
	                               <option value="student">Student</option>
	                               <option value="scientist">Data Scientist</option>
                                 <!--
                                    To create an admin user, uncomment the following line:
                                    <option value="admin">Admin</option>
                                 -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="inputdefault">Password</label>
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <label class="inputdefault">Confirm Password</label>
                            <input class="form-control" type="password" name="confirmpass" placeholder="Confirm Password" required>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-info" type="submit" name="submit" value="Create">
                            <a class="btn btn-info" href="login.php">Back to Login</a>
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

                    //Check that there is an entry in the collection with this username
                    //There will only be one since it's a unique identifier
                    if($collection->findOne(array("username" => $_POST['username'])) != NULL) {
                        $_SESSION['fail'] = 'invalidusername';
                        header("Location: createUser.php");
                        exit();
                    }

                    //Check that the fields of password and confirm password are the same thing
                    if($_POST['password'] != $_POST['confirmpass']) {
                        $_SESSION['fail'] = 'passwordnotmatch';
                        header("Location: createUser.php");
                        exit();
                    }

                    //Create salt and hash the password
                    $salt = mt_rand();
                    $hpass = password_hash($salt.$_POST['password'], PASSWORD_BCRYPT);

                    //Create the entry for the database
                    $entry = array(
                        "fname" => $_POST['fname'],
                        "lname" => $_POST['lname'],
                        "username" => $_POST['username'],
                        "usertype" => $_POST['usertype'],
                        "salt" => $salt,
                        "hpass" => $hpass
                    );

                    //Insert the entry into the users collection and login if successful
                    if($collection->insert($entry)){
                        $_SESSION['username'] = $_POST['username'];
                        $_SESSION['usertype'] = $_POST['usertype'];
                        $_SESSION['fname'] = $_POST['fname'];
                        $_SESSION['lname'] = $_POST['lname'];
                        $_SESSION['message'] = "created";
                        header("Location: index.php");
                        exit();
                    } else {
                        $_SESSION['fail'] = '-1';
                        header("Location: createUser.php");
                        exit();
                    }
                }
            ?>

        </div>
    </body>
</html>

<!--Copyright (c) 2016 Kevin Free, Matthew Romero Moore, Ryan Tray, Jonathon Israel, Ryan Jones, Matthew Schwarz

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.-->
