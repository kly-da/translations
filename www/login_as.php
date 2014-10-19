<?
  $uid = intval($_GET["id"]);
  if ($uid <= 0) {
    $uid = 1;
  }

  include('mod_db.php');

  $query = 'SELECT `hash` FROM `user` WHERE `user_id` = ' . $uid;
  $result = mysql_query($query);
  $row = mysql_fetch_assoc($result);
  if (!$row) {
    print "FAIL: User Not Found";
    die();
  }
  
  setcookie('uid', $uid, time() + 366 * 24 * 60 * 60);
  setcookie('hash', $row["hash"], time() + 366 * 24 * 60 * 60);
  
  print "OK!!";
?>
