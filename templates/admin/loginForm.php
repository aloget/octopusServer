<?php include "templates/header.php" ?>

	<form action="admin.php?action=login" method="post" style="width: 50%;">
		<input type="hidden" name="login" value="true" />

<?php if ( isset( $results['errorMessage'] ) ) { ?>
	<div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
	
	<ul>
		<li>
			<label for="username">Username</label>
			<input type="text" name="username" id="username"/>
		</li>

		<li>
			<label for="password">Password</label>
			<input type="password" name="password" id="password"/>
		</li>
	</ul>

	<div class="buttons">
		<input type="submit" name="login" value="Log In"/>
	</div>

	</form>

<?php include "templates/footer.php" ?>
