<?
  //��������� ��������, ���������� ����� POST
  $command = $_POST["command"];
  
  if (!isset($command)) {
    header('Location: manual.php');
    die();
  }
  
  $a = 5;
  $b = 3;
  $c = $a + $b;
  
  if ($command == "without_pause") {
    //����� ���������� ������, ������ ���������, �� ����� ����� �� ������
    //��������������� �� �������
    header('Location: manual.php');
  } else {
    // ����� ��������������� ����� �����
    header('Refresh: 3; URL=manual.php');
    print "��������������� - ����� 3 �������. ��������� �������� - $c.";
  }  
?>