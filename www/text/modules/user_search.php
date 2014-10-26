<?
  header("Content-Type: application/json");
  $search_string = $_POST["q"];
  if (!isset($search_string)) {
    $arr = array("users" => array());
    print json_encode($arr, 256);
    die();
  }

  include('../../mod_db.php');

  $users = array();
  $search_string = addCslashes(mysql_real_escape_string($search_string), '\%_');
  $query = "SELECT `user_id`, `name` FROM `user` WHERE `name` LIKE \"$search_string%\" ORDER BY `name` LIMIT 0, 5";
  $result = mysql_query($query);

  while ($row = mysql_fetch_assoc($result)) {
    $new_user = array("user_id" => $row["user_id"], "name" => $row["name"]);
    array_push($users, $new_user);
  }
  mysql_free_result($result);

  $arr = array("users" => $users);
  print json_encode($arr, 256);
?>