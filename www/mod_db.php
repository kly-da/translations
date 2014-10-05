<?
  $conn = @mysql_connect('localhost', 'trans_user', '123');
  if (!$conn) {
    header('Location: /error/internal.php');
    die();
  }
  mysql_select_db('translations', $conn);
?>