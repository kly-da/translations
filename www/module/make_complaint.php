<?
  header("Content-Type: application/json");

  //=================================== Обработка параметров
  $type = $_POST["type"];
  switch ($type) {
    case "message":
      $type = 0;
      break;
    case "text":
      $type = 1;
      break;
    case "comment":
      $type = 3;
      break;
    default:
      die(json_encode(array("status" => "fail"), 256));
  }

  //=================================== Авторизация

  include('../mod_db.php');
  include('../mod_auth.php');

  if (!$user -> isRegistered()) {
    die(json_encode(array("status" => "fail"), 256));
  }

  //=================================== Описание функций

  function checkCount() {
    global $type, $object_id;
    $query = "SELECT COUNT(*) AS `complaint_count` FROM `complaint` WHERE `type` = $type AND `object_id` = $object_id";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    return $row["complaint_count"];
  }

  //=================================== Основной код

  $object_id = $_POST["id"];
  if (checkCount() == 0) {
    $uid = $user -> uid;
    $query = "INSERT INTO `complaint`
      (`type`,
      `object_id`,
      `user_id`)
    VALUES
      ($type,
      $object_id,
      $uid)";
    $result = mysql_query($query);
    if (!$result)
      die(json_encode(array("status" => "fail"), 256));
  }

  print json_encode(array("status" => "ok"), 256);
?>