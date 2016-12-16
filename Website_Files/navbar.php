<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#Navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id = "Navbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="search.php">Search Manifests</a></li>
                <?php
                  if ($_SESSION['usertype'] == 'scientist' || $_SESSION['usertype'] == 'admin') //Only display this menu option if a data scientist
                  {
                    ?>
                    <li><a href="createManifest.php">Create New Manifest</a></li>
                <?php
                  }
                  if ($_SESSION['usertype'] == 'admin') //Only display this menu option if an admin
                    {
                 ?>
                <li><a href="manageUsers.php">Manage Users</a></li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>

<!--Copyright (c) 2016 Kevin Free, Matthew Romero Moore, Ryan Tray, Jonathon Israel, Ryan Jones, Matthew Schwarz

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.-->
