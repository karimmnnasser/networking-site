<?php
	include "init.php";
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
		<label class="label labels">Username</label>
		<input  class='form-control field' type="text" name="username" autocomplete="off" required="required">
	
		<label class="label labels">Password <span><a href="">Forget?</a></span></label>
		<input class='form-control field'type="password" name="password" autocomplete="off" required="required">
	
		<input class='form-control btn btn-primary button' type="submit" name="signin"value='Sign In'>
	</form>
	
	<p>Not a member?<a href="signin.php">Signupnow</a></p>

	<!-- signin.php is the page where we will make a register -->
	</div>
</div>

<?php
	include $temp . "footer.php";
?>
