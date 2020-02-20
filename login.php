<?php

	session_start();
	$NoNavbar = "";
	include "init.php"; // include the heder.. etc.

	if (isset($_session['email']) AND isset($session['pass'])) {
		header('location:index.php');
		exit();	
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$email	 	  = $_POST['email'];
			$password	  = $_POST['pass'];
			$hashedpass = sha1($password);
		
		$stmt = $con->prepare("SELECT 
									email , password 
							   FROM 
							   		users 
							   WHERE 
							   		email = ? 
							   AND 
									password = ? ");
									   
		$stmt->execute(array($email,$hashedpass));
		$count= $stmt->rowCount();

		if($count > 0 ) {

			$_SESSION['email'] = $email;
			$_SESSION['pass'] = $hashedpass;

			header('location: index.php');
			exit();
		}
	}
	













?>
<div class="login">
	<div class="outer-login">
		<div class="signin-header"><h1>Sign In With</h1></div>
		<div class="signin-social">
			<span class="btn btn-primary "><a href="https://www.facebook.com/">Facebook</a></span>
			<span class="btn btn-default"><a href="https://www.google.com/">Google</a></span>
		</div>

	</div>
	<div class="inner-login">
	<form action = '' method="POST"> 	<!--Alert !!  Put The Method In ACTION  -->
		<label class="label labels">Email</label>
		<input  class='form-control field' type="text" name="email" autocomplete="off" required="required">
	
		<label class="label labels">Password <span><a href="">Forget?</a></span></label>
		<input class='form-control field'type="password" name="pass" autocomplete="off" required="required">
	
		<input class='form-control btn btn-primary button' type="submit" name="signin"value='Sign In'>
	</form>
	
	<p>Not a member?<a href="signup.php">Signupnow</a></p>

	<!-- signin.php is the page where we will make a register -->
	</div>
</div>

<?php
	include $temp . "footer.php";
?>
