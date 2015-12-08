<?php include "templates/header.php" ?>
<?php include "templates/admin/header.php" ?>


	<h1><?php echo $results['pageTitle']?></h1>

	<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
		<input type="hidden" name="messageId" value="<?php echo $results['message']->id?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
	<div class="errorMessage"><?php echo $results['errorMessage']?></div>
<?php } ?>

	<ul>
		<li>
			<label for="senderId">Sender ID</label>
			<input type="text" name="senderId" id="senderId" 
			value="<?php echo htmlspecialchars( $results['message']->senderId )?>"/>
		</li>

		<li>
			<label for="recipientId">Recipient ID</label>
			<input type="text" name="recipientId" id="recipientId"
			value="<?php echo htmlspecialchars( $results['message']->recipientId )?>"/>
		</li>

		<li>	
			<label for="message">Message</label>
			<input type="text" name="message" id="message"
			value="<?php echo htmlspecialchars( $results['message']->message )?>"/>
		</li>

		<li>	
			<label for="dispatchTimestamp">Dispatch Datetime</label>
			<input type="datetime-local" name="dispatchTimestamp" id="dispatchTimestamp" 
			step = "1"
			value="<?php echo $results['message'] ? date("Y-m-d\TG:i:s", $results['message']->dispatchTimestamp) : "" ?>"/>
		</li>
	</ul>

	<div class="buttons">
		<input type="submit" name="saveChanges" value="Save" />
		<input type="submit" formnovalidate name="cancel" value="Cancel" />
	</div>

	</form>

<?php if ( $results['message']->id ) { ?>
	<p><a href="admin.php?action=deleteMessage&amp;messageId=<?php echo $results['message']->id ?>"
		onclick="return confirm('Delete This Message?')">Delete This Message</a></p>
<?php } ?>

<?php include "templates/footer.php" ?>