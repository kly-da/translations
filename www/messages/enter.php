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
				echo("�� �� ����� �������. ��������� �� ���������� �������� � ������� �������.");
			}
			else
			{
				$_SESSION["nick"]=$nick;
				echo("����� ����������, $nick! �������� �� ���� <a href='messager'>������</a>.");
			};
		
	};
?>

