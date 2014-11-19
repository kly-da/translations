<?
  header("Content-Type: application/json");
  $command = $_POST["com"];
  switch ($command) {
    case "add":
      $command = 0;
      break;
    case "delete":
      $command = 1;
      break;
    default:
      die(json_encode(array("status" => "fail"), 256));
  }

  $list = $_POST["list"];
  switch ($list) {
    case "translators":
      $list = 0;
      break;
    case "moderators":
      $list = 1;
      break;
    case "administrators":
      $list = 2;
      break;
    default:
      die(json_encode(array("status" => "fail"), 256));
  }

  $text_id = intval($_POST["text_id"]);
  $user_id = intval($_POST["user_id"]);
  if ($text_id <= 0)
      die(json_encode(array("status" => "fail"), 256));

  if ($user_id <= 0)
      die(json_encode(array("status" => "user_null"), 256));

  include('../../mod_db.php');
  include('../../mod_auth.php');

  $user -> loadTextOutById($text_id);
  if (!($user -> isTextAdministratorOrCreator() || $user -> isManageUser()))
      die(json_encode(array("status" => "fail"), 256));

  //=================================== Описание функций

  function addUser() {
    global $list, $text_id, $user_id;
    $query = "SELECT `role` FROM `text_roles` WHERE `text_id` = $text_id AND `user_id` = $user_id";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    if (!$row) {
      $query = "INSERT INTO `text_roles`(`text_id`, `user_id`, `role`) VALUES ($text_id, $user_id, $list)";
      $result = mysql_query($query);
      if (!$result)
        die(json_encode(array("status" => "fail"), 256));
      $status = "ok";
    } else {
      if ($row["role"] == $list)
        die(json_encode(array("status" => "already"), 256));
      $query = "UPDATE `text_roles` SET `role` = $list WHERE `text_id` = $text_id AND `user_id` = $user_id";
      $result = mysql_query($query);
      if (!$result)
        die(json_encode(array("status" => "fail"), 256));
      $status = "list";
    }

    $query = "SELECT `name` FROM `user` WHERE `user_id` = $user_id";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    if (!$row)
      die(json_encode(array("status" => "fail"), 256));

    print json_encode(array("status" => $status, "user" => array("id" => $user_id, "name" => $row["name"])), 256);
  }

  function deleteUser() {
    global $list, $text_id, $user_id;
    $query = "DELETE FROM `text_roles` WHERE `text_id` = $text_id AND `user_id` = $user_id AND `role` = $list";
    $result = mysql_query($query);
    if (!$result)
      print json_encode(array("status" => "fail"), 256);
    else if (mysql_affected_rows() > 0)
      print json_encode(array("status" => "ok"), 256);
    else
      print json_encode(array("status" => "not_found"), 256);
  }

  //=================================== Основной код

  if ($command == 0)
    addUser();
  else
    deleteUser();
?>