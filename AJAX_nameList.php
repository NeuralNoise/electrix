<?php
	
	include("includes/commonFunctions.inc.php");
	
	if(isset($_GET['search']))
	{
		$nameList = array();
		
		
                
		try
		{
                    include('includes/mysql_credentials.php');
                    $dbo = new PDO("mysql:host=localhost;dbname=".$mysql_database, $mysql_username, $mysql_password);
                    $dbo-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
			$stmt = $dbo->prepare('SELECT fullname, displayname FROM profile');
			
			$stmt->execute();
			
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($result as $cur_name)
			{
				$nameList[] = $cur_name['fullname'].
								'&nbsp;|
								<span style="color:#666">'.$cur_name['displayname'].'</span>
								|&nbsp;
								<a href="#" onclick="addToRecipients(\''.$cur_name['displayname'].'\')">Add</a>';
			}
		}
		catch(PDOException $e)
		{
			echo("<span class='alert'>An error occurred! Please try again later!</span>");
			exit();
		}
		catch(Exception $e)
		{
			echo("<span class='alert'>Unexpected error occurred! Please report to administrator!</span>");
			exit();
		}
		
		$matches = array();
		
		foreach($nameList as $name)
		{
			$pattern = "/^".$_GET['search']."/i";
			if(preg_match($pattern, $name))
				$matches[] = $name;
		}
		
		foreach($matches as $suggestion)
		{
			echo($suggestion.'<br/>');
		}
	}
?>