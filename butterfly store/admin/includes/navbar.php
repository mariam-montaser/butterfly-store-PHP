<!-- navbar -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<a href="index.php" class="navbar-brand">Butterfly Store Admin</a>
				<ul class="nav navbar-nav">
					<li>
						<a href="brands.php" class="text-uppercase" >brands</a>
					</li>
					<li>
						<a href="categories.php" class="text-uppercase" >categories</a>
					</li>
					<li>
						<a href="products.php" class="text-uppercase" >products</a>
					</li>
					<li>
						<a href="archive.php" class="text-uppercase" >archive</a>
					</li>
					<?php if(hasPermission('admin')): ?>
						<li>
							<a href="users.php" class="text-uppercase" >users</a>
						</li>
					<?php endif; ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle text-capitalize" data-toggle="dropdown" >Holle <?=$userData['first'];?><span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="change_password.php">Change password</a>
							</li>
							<li>
								<a href="logout.php">Log Out</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<!-- end navbar -->