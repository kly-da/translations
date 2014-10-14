<?PHP
session_start();
error_reporting("E_ALL");
echo("<title>");
echo("</title>");
$action=htmlspecialchars($_GET["action"]);
if($action=="enter")
	{
		$nick=htmlspecialchars($_POST["nick"]);
		if(($nick==null)or(trim($nick)==null))
			{
				echo("Вы не ввели никнейм. Вернитесь на предыдущую страницу и введите никнейм.");
			}
			else
			{
				$_SESSION["nick"]=$nick;
				echo("Добро пожаловать, $nick! Пройдите по этой <a href='messager'>ссылке</a>.");
			};
		
	};
?>

