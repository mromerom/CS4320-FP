<?php
	if(isset($_SESSION['authentication'])){?>
		<script type="text/javascript">
        <!--
        window.location = "login2.php"
        //-->
        </script>
        You're already logged in, redirecting you.
	<?php }else{
	
		if(isset($_POST['login'])){
		
			$postedUsername = $_POST['username']; //Gets the posted username, put's it into a variable.
			$postedPassword = $_POST['password']; //Gets the posted password, put's it into a variable.
			$userDatabaseSelect = $m->collections->users; //Selects the user collection
			$userDatabaseFind = $userDatabaseSelect->find(array('userid' => $postedUsername)); //Does a search for Usernames with the posted Username Variable
				
				//Iterates through the found results
				foreach($userDatabaseFind as $userFind) {
				    $storedUsername = $userFind['username'];
				    $storedPassword = $userFind['password'];
				}
	
				if($postedUsername == $storedUsername && $postedPassword == $storedPassword){ 
					$_SESSION['authentication'] = 1;
					?>
					
					<script type="text/javascript">
					<!--
					window.location = "index.php"
					//-->
					</script> <?php
					
				}else{
					
					$wrongflag = 1;
				}
				
			}else{}
?>

