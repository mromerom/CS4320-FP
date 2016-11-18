<?php
	if(isset($_SESSION['authentication'])){?>
		<script type="text/javascript">
        <!--
        window.location = "index.php"
        //-->
        </script>
        You're already logged in, redirecting you.
	<?php }else{
	
		if(isset($_POST['login'])){
		
			$postedUsername = $_POST['username']; //Gets the posted username, put's it into a variable.
			$postedPassword = $_POST['password']; //Gets the posted password, put's it into a variable.
			$userDatabaseSelect = $m->collections->users; //Selects the user collection
			$userDatabaseFind = $userDatabaseSelect->find(array('username' => $postedUsername)); //Does a search for Usernames with the posted Username Variable
				
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
					window.location = "main.php"
					//-->
					</script> <?php
					
				}else{
					
					$wrongflag = 1;
				}
				
			}else{}
?>

<div class='login'>
            <form action='login.php' method='post'>
               <br/> You're not logged in as an admin, please do so. <br/><br/>
                <?php if($wrongflag == 1){ echo "<font size='2px' color='red' face='Arial'> Wrong Username/Password </font><br/>";} ?>
                <input class='login-text' type='text' name='username' value='Username' onFocus="if(this.value == 'Username') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Username';}">
                <input class='login-text' type='password' name='password' value='Password' onFocus="if(this.value == 'Password') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'Password';}">
                <input class='login-button' type='submit' name='login' value='Login'>
            </form>
