<?php
	include("includes/mysql_credentials.php");
	
	$dbo = new PDO("mysql:host=localhost;dbname=".$mysql_database.";", $mysql_username, $mysql_password);
	
	if(isset($_GET['subject']) && isset($_GET['news_message']) && isset($_GET['displayname']))
	{
		$newsMessage = $_GET['news_message'];
		
		$subject = filter_var($_GET['subject'],FILTER_SANITIZE_STRING);
		
		$displayname = preg_replace("#[^A-Za-z. ]#",'',$_GET['displayname']);
		
		if(count($subject) == 0 || count($displayname) == 0 || count($subject) > 64 || count($displayname) > 64)
		{
			echo("<span class='alert'>Error: Invalid inputs or large message!</span>");
			return false;
		}
		
		$subject = "ELECTRIX NEWSLETTER - ".$subject." - ".$displayname." has sent a message";
		
		$body = file_get_contents("view_newsletter1.html");
		
		$body .= "Hi,<br/><br/>".$displayname." has sent you the following message via electrix.<br/><br/>";
		
		$body .= $newsMessage;
		
		$body .= file_get_contents("view_newsletter2.html");
		
		if(count($newsMessage != 0))
		{
			$stmt = $dbo->prepare("SELECT displayname, email1 FROM profile WHERE email1 != ''");
			
			$stmt->execute();
			
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($result as $recipient)
			{
				mail($recipient['email1'], $subject, $body, "FROM: ELECTRIX\r\nContent-type: text/html");
				
				/****** IMAP MAIL ******
		
				$inbox = imap_open("{localhost:143/imap/novalidate-cert}", "info@mageshravi.in", "infoPass") or $this->log->debug("Cannot connect to mailbox: ".imap_last_error());
				
				imap_mail($recipient['email1'], $subject, $body, "FROM: ELECTRIX\r\nContent-type: text/html");
				
				imap_close($inbox);
				
				/****** END OF IMAP MAIL ******/
			}
		}
		
		echo "<span class='success'>Newsletter sent successfully</span>";
	}

?>