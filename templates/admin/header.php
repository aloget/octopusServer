<div id="adminHeader">
	<br>
	<h2>Octopus Admin</h2>
	<p>You are logged in as <b> <?php echo htmlspecialchars( $_SESSION['username'] ) ?> </b>
	<a href="admin.php?action=logout">Log Out</a><br>
</div>
<div id="menu">
	<ui>
		<li><a href="admin.php?action=listUsers">Users</a></li>
		<li><a href="admin.php?action=listMessages">Messages</a></li>
	</ui>
</div>

