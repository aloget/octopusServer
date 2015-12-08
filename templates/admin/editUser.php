<?php include "templates/header.php" ?>
<?php include "templates/admin/header.php" ?>


	<h1><?php echo $results['pageTitle']?></h1>

	<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
		<input type="hidden" name="userId" value="<?php echo $results['user']->id?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
	<div class="errorMessage"><?php echo $results['errorMessage']?></div>
<?php } ?>

	<ul>
		<li>
			<label for="username">Username</label>
			<input type="text" name="username" id="username" 
			value="<?php echo htmlspecialchars( $results['user']->username )?>"/>
		</li>

		<li>
			<label for="password">Password</label>
			<input type="text" name="password" id="password"
			value="<?php echo htmlspecialchars( $results['user']->password )?>"/>
		</li>

		<li>	
			<label for="token">X-Token</label>
			<input type="text" name="token" id="token"
			value="<?php echo htmlspecialchars( $results['user']->token )?>"/>
		</li>
	</ul>

	<div class="buttons">
		<input type="submit" name="saveChanges" value="Save" />
		<input type="submit" formnovalidate name="cancel" value="Cancel" />
	</div>

	</form>

<?php if ( $results['user']->id ) { ?>
	<p><a href="admin.php?action=deleteUser&amp;userId=<?php echo $results['user']->id ?>"
		onclick="return confirm('Delete This User?')">Delete This User</a></p>
<?php } ?>

<?php include "templates/footer.php" ?>


