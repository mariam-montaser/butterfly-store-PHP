<?php

$sql = 'SELECT * FROM categories WHERE parent = 0';
$pquery = $db->query($sql);


?>

<!-- navbar -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<a href="index.php" class="navbar-brand">Butterfly Store</a>
				<ul class="nav navbar-nav">
					<!-- menu items -->
					<?php

					while ($parent = mysqli_fetch_assoc($pquery)) :
						$parent_id = $parent['id'];
						$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
						$cquery = $db->query($sql2);
					?>
					<li class="dropdown">
						<a href="" class="dropdown-toggle text-uppercase" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<!-- submenu items -->
							<?php
								while($child = mysqli_fetch_assoc($cquery)) :
							?>
							<li>
								<a href="category.php?cat=<?=$child['id']?>" class="text-capitalize"><?php echo $child['category'];?></a>
							</li>
							<?php endwhile; ?>
						</ul>
					</li>

					<?php endwhile; ?>
					<li class="pull-right">
						<a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a>
					</li>
				</ul>
			</div>
		</nav>
		<!-- end navbar -->