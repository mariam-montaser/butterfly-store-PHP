<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/core/init.php';
if(!isLoggedIn()) {
	loginRedirect();
}
include 'includes/head.php';

//get the data from the login form
$hashed = $userData['password'];
$old_pass = ((isset($_POST['old-pass']))?trim(sanitize($_POST['old-pass'])):'');
$pass = ((isset($_POST['pass']))?trim(sanitize($_POST['pass'])):'');
$confirm = ((isset($_POST['confirm']))?trim(sanitize($_POST['confirm'])):'');
$new_hashed = password_hash($pass, PASSWORD_DEFAULT);
$userID = $userData['id'];
//$hashed = password_hash($pass, PASSWORD_DEFAULT);
$errors = array();

// $password = '12345';
// $hashed =  password_hash($password, PASSWORD_DEFAULT);
// echo $hashed, $password;
?>

<style>
	
	#login-form{
	width: 50%;
	height: 60%;
	border: 2px solid #666;
	border-radius: 10px;
	box-shadow: 7px 7px 15px rgba(0, 0, 0, .6);
	margin: 8% auto;
	padding: 15px;
	background-color: #fff
}
</style>
<div id="login-form">
	<div>
		
		<?php 
			//form validation
			if($_POST) {
				// if empty 
				if(empty($_POST['old-pass']) || empty($_POST['pass']) || empty($_POST['confirm']) ) {
					$errors[] = 'You must fill all fields.';
				}

				//check if old pass is match
				if(!password_verify($old_pass, $hashed)) {
					$errors[] = 'The old password dosen\'t match our record. Please try again.';
				}

				//check password lenght
				if(strlen($pass) < 5) {
					$errors[] = 'Password must be at least 6 character.';
				}

				//check if password match confirm
				if($pass != $confirm) {
					$errors[] = 'The new password and confirm dosen\'t match.';
				}

				

				//display errors
				if(!empty($errors)) {
					echo display_errors($errors);
				} else {
					//change password
					$db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$userID'");
					$_SESSION['success'] = 'Your password has been updated successfully.';
					header('location:index.php');
				}

			}	

		?>

	</div>
	<h2 class="text-center">Change Password</h2><hr>
	<form action="change_password.php" method="post">
		<div class="form-group">
			<label for="old-pass">Old Password:</label>
			<input type="password" name="old-pass" id="old-pass" class="form-control" value="<?=$old_pass;?>" />
		</div>
		<div class="form-group">
			<label for="pass">New Password:</label>
			<input type="password" name="pass" id="pass" class="form-control" value="<?=$pass;?>" />
		</div>
		<div class="form-group">
			<label for="confirm">Confirm Password:</label>
			<input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>" />
		</div>
		<div class="form-group">
			<a href="index.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="Change" class="btn btn-primary" />
		</div>
	</form>
	<p class="text-right"><a href="/butterfly store/index.php">Visit Website</a></p>
</div>

<?php include 'includes/footer.php';?> 