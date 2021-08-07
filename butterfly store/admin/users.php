<?php

require_once '../core/init.php';
if(!isLoggedIn()) {
	loginRedirect();
}

//check permission
if(!hasPermission('admin')) {
	permissionRedirect('index.php');
}
include 'includes/head.php';
include 'includes/navbar.php';
//echo $_SEESION['BUser'];

//delete user
if(isset($_GET['delete'])) {
	$deleteID = sanitize($_GET['delete']);
	$db->query("DELETE FROM users WHERE id = '$deleteID'");
	$_SEESION['success'] = 'User has been successfully deleted.';
	header('Location:users.php');
}
if(isset($_GET['add'])) {
	$name = (isset($_POST['name'])?sanitize($_POST['name']):'');
	$email = (isset($_POST['email'])?sanitize($_POST['email']):'');
	$pass = (isset($_POST['pass'])?sanitize($_POST['pass']):'');
	$confirm = (isset($_POST['confirm'])?sanitize($_POST['confirm']):'');
	$permissions = (isset($_POST['permission'])?sanitize($_POST['permission']):'');

	//validate add form
	$errors = array();
	if($_POST) {

		$emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
		$emailCount = mysqli_num_rows($emailQuery);

		//check email in db
		if($emailCount != 0) {
			$errors[] = 'This email is already exist.';
		}

		$required = array('name', 'email', 'pass', 'confirm', 'permission');
		foreach ($required as $item ) {
			if(empty($_POST[$item])) {
				$errors[] = 'You must fill out all fields.';
				break;
			}
		}
		//check password
		if(strlen($pass) < 5) {
			$errors[] = 'Your password must be at least 5 charchters.';
		}

		//check pass and confirm
		if($pass != $confirm) {
			$errors[] = 'Your password dosen\'t match the confirm.';
		}

		//check email
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'You must enter a valid email.';
		}

		if(!empty($errors)) {
			echo display_errors($errors);
		} else {
			//add to db
			$hashed = password_hash($pass, PASSWORD_DEFAULT);
			$db->query("INSERT INTO users(full_name, email, password, permissions) VALUES('$name', '$email', '$hashed', '$permissions')");
			$_SEESION['success'] = 'User has been added successfully.';
			header('Location:users.php');
			exit();
		}
	}
	?>

	<h2 class="text-center">Add New User</h2>
	<form action="users.php?add=1" method="post">
		<th></th>
		<div class="form-group col-md-6">
			<label for="name">Full Name:</label>
			<input type="taxt" name="name" id="name" class="form-control" value="<?=$name?>" />
		</div>
		<div class="form-group col-md-6">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control" value="<?=$email?>" />
		</div>
		<div class="form-group col-md-6">
			<label for="pass">Password:</label>
			<input type="password" name="pass" id="pass" class="form-control" value="<?=$pass?>" />
		</div>
		<div class="form-group col-md-6">
			<label for="confirm">Confirm Password:</label>
			<input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm?>" />
		</div>
		<div class="form-group col-md-6">
			<label for="permission">Permissions:</label>
			<select class="form-control" name="permission" id="permission">
				<option value="" <?=(($permissions == '')?'selected':'')?>></option>
				<option value="editor" <?=(($permissions == 'editor')?'selected':'')?>>Editor</option>
				<option value="admin,editor" <?=(($permissions == 'admin,editor')?'selected':'')?>>Admin</option>
			</select>
		</div>
		<div class="form-group col-md-6 text-right" style="margin-top: 25px">
			<a href="users.php" class="btn btn-default">cancel</a>
			<input type="submit" value="Add User" class="btn btn-primary" />
		</div>
	</form>

<?php
} else {

	//get users data from db
	$userQuery = $db->query("SELECT * FROM users ORDER BY full_name");
	?>

	<h2 class="text-center">Users</h2>
	<a href="users.php?add=1" class="btn btn-success pull-right" id="add-btn">Add New User</a>
	<hr>
	<table class="table table-bordered ">
		<thead class="text-capitalize">
			<th></th>
			<th>name</th>
			<th>email</th>
			<th>join date</th>
			<th>last login</th>
			<th>permissions</th>
		</thead>
		<tbody>
			<?php while($users = mysqli_fetch_assoc($userQuery)): ?>
				<tr class="text-capitalize">
					<td>
						<?php if($users['id'] != $userData['id']): ?>
							<a href="users.php?delete=<?=$users['id'];?>" class="btn btn-default btn-xs"> <span class="glyphicon glyphicon-remove-sign"></span></a>
						<?php endif; ?>
					</td>
					<td><?=$users['full_name']?></td>
					<td><?=$users['email']?></td>
					<td><?=prettyDate($users['join_date']);?></td>
					<td><?=(($users['last_login'] == '0000-00-00 00:00:00')?'Never':prettyDate($users['last_login']))?></td>
					<td><?=$users['permissions']?></td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>

<?php }
include 'includes/footer.php';
?>  