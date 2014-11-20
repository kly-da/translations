<?PHP
session_start();
error_reporting("E_ALL");
mysql_connect("localhost", "root", "") or die (mysql_error ());
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}
mysql_select_db("translations") or die(mysql_error());
$action=trim(htmlspecialchars($_GET["action"]));
if($action=="writemsg")
	{
		$nick=$_SESSION["nick"];
		$msg=stripslashes(htmlspecialchars($_POST["tellform"]));
		$to=stripslashes(htmlspecialchars($_POST["recipient"]));
		if(($msg<>null) and (trim($msg)<>null))
			{
			
			$sqlQuery = "INSERT INTO `translations`.`messages` (`id`, `from`, `to`, `message`) VALUES (NULL,'$nick','$to','$msg')";
			$rs = mysql_query($sqlQuery);
			

			};
	};
mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<title>Мессанджер</title>

<style type="text/css">
</style>
</head>

<body>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="797" height="500"> <iframe width="100%" height="100%" src="chattext.php#chatanchor"></iframe>&nbsp;</td>
    <td width="199" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="200"><form id="form1" name="form1" method="post" action="?action=writemsg">
      &nbsp;Получатель:
	  </br>
	  &nbsp;<input name="recipient" type="text" id="recipient " size="50" />
	  </br>
	  &nbsp;Сообщение:
	  </br> 
	  &nbsp;<textarea rows="6" cols="200" name="tellform" id="tellform"> </textarea> 	  
      <!--<input name="tellform" type="text" id="tellform" size="100" />  -->
	  </br>
      &nbsp;<input type="submit" name="button" id="button" value="Отправить" />
    </form></td>
    <td id="version"><p>[<a href="../index.php" id="exit">Выход</a>]</p></td>
  </tr>
  
</table>
</body>
</html>