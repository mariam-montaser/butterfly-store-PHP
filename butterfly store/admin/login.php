<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/butterfly store/core/init.php';
include 'includes/head.php';

//get the data from the login form
$email = ((isset($_POST['email']))?trim(sanitize($_POST['email'])):'');
$pass = ((isset($_POST['pass']))?trim(sanitize($_POST['pass'])):'');
//$hashed = password_hash($pass, PASSWORD_DEFAULT);
$errors = array();

// $password = '12345';
// $hashed =  password_hash($password, PASSWORD_DEFAULT);
// echo $hashed;
?>

<style>
	body{
		background-image: url('/butterfly store/images/bg.jpg');
		background-size: 100vw 100vh;
		background-attachment: fixed;
	}
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
				if(empty($_POST['email']) || empty($_POST['pass'])) {
					$errors[] = 'You must provide email and password.';
				}

				//valid email
				if(!filter_var($email)) {
					$errors[] = 'You must enter a valid email.';
				}

				//check password lenght
				if(strlen($pass) < 5) {
					$errors[] = 'Password must be at least 6 character.';
				} 

				// check user indb
				$query = $db->query("SELECT * FROM users WHERE email = '$email'");
				$user = mysqli_fetch_assoc($query);
				$userCount = mysqli_num_rows($query);
				//echo $userCount;
				if($userCount < 1) {
					$errors[] = 'This email dosen\'t exist in our database.';
				}

				//
				if(!password_verify($pass, $user['password'])) {
					$errors[] = 'The password dosen\'t match. Please try again.';
				}

				//display errors
				if(!empty($errors)) {
					echo display_errors($errors);
				} else {
					//login the user
					//echo "login";
					$userID = $user['id'];
					login($userID);
				}

			}	

		?>

	</div>
	<h2 class="text-center">Login</h2><hr>
	<form action="login.php" method="post">
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control" value="<?=$email;?>" />
		</div>
		<div class="form-group">
			<label for="pass">Password:</label>
			<input type="password" name="pass" id="pass" class="form-control" value="<?=$pass;?>" />
		</div>
		<div class="form-group">
			<input type="submit" value="Login" class="btn btn-primary" />
		</div>
	</form>
	<p class="text-right"><a href="/butterfly store/index.php">Visit Website</a></p>
</div>

<?php include 'includes/footer.php';?> 