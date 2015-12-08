<?php include "templates/header.php" ?>
<?php include "templates/admin/header.php" ?>

	<h1>All Messages</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
	<div class="errorMessage"><?php echo $results['errorMessage']?></div>
<?php } ?>

<?php if ( isset( $results['statusMessage'] ) ) { ?>
	<div class="statusMessage"><?php echo $results['statusMessage']?></div>
<?php } ?>

<p><a href="admin.php?action=newMessage">Add a New Message</a></p>

	<table>

		<tr>
			<th>ID</th>
			<th>Sender ID</th>
			<th>Recipient ID</th>
			<th>Message</th>
			<th>Dispatch Datetime</th>
		</tr>

	<?php foreach ( $results['messages'] as $message ) { ?>
			<tr onclick="location='admin.php?action=editMessage&amp;messageId=<?php echo $message->id?>'">
				<td><?php echo $message->id?></td>
				<td><?php echo $message->senderId?></td>
				<td><?php echo $message->recipientId?></td>
				<td><?php echo $message->message?></td>
				<td><?php echo date('G:i:s j M Y', $message->dispatchTimestamp)?></td>
			</tr>
	<?php } ?>

	</table>

<?php include "templates/footer.php"?>

