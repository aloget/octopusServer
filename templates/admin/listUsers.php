<?php include "templates/header.php" ?>
<?php include "templates/admin/header.php" ?>

	<h1>All Users</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
	<div class="errorMessage"><?php echo $results['errorMessage']?></div>
<?php } ?>

<?php if ( isset( $results['statusMessage'] ) ) { ?>
	<div class="statusMessage"><?php echo $results['statusMessage']?></div>
<?php } ?>

<p><a href="admin.php?action=newUser">Add a New User</a></p>

	<table>

		<tr>
			<th>ID</th>
			<th>Username</th>
			<th>Password</th>
			<th>X-Token</th>
		</tr>

	<?php foreach ( $results['users'] as $user ) { ?>

			<tr onclick="location='admin.php?action=editUser&amp;userId=<?php echo $user->id?>'">
				<td><?php echo $user->id?></td>
				<td><?php echo $user->username?></td>
				<td><?php echo $user->password?></td>
				<td><?php echo $user->token?></td>
			</tr>
	<?php } ?>

	</table>

<?php include "templates/footer.php"?>

