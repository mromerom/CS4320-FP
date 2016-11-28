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
                <li><a href="banUsers.php">Ban Users</a></li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>
