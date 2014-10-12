<?
  if (!isset($conn)) {
    include('mod_db.php');
  }

  class User {
    public $uid;
    public $name;
    public $settings;
    private $group;
    private $role;

    public function User($conn) {
      $this -> role = -1;

      $uid = $_COOKIE["uid"];
      $hash = $_COOKIE["hash"];
      if (!isset($uid)) {
        $this -> fail();
        return;
      }

      $query = 'SELECT `name`, `hash`, `group`, `settings` FROM `user` WHERE `user_id` = ' . intval($uid);

      $result = mysql_query($query);

      if (mysql_num_rows($result) == 0) {
        $this -> failWithFree($result);
        return;
      }

      $row = mysql_fetch_assoc($result);
      if ($row["hash"] != $hash) {
        $this -> failWithFree($result);
        return;
      }
      $this -> uid = intval($uid);
      $this -> name = $row["name"];
      $this -> group = $row["group"];
      $this -> settings = $row["settings"];
      mysql_free_result($result);
    }

    public function loadTextOut($text_row) {
      if ($this -> group < 0) {
        $this -> role = -1;
        return;
      } else if ($text_row["creator"] == $this -> uid) {
        $this -> role = 3;
        return;
      }

      $text_id = $text_row["text_id"];

      $query = 'SELECT `role`
        FROM
          `text_roles`
        WHERE
          `text_id` = ' . $text_id . ' AND `user_id` = ' . $this -> uid;

      $result = mysql_query($query);
      $row = mysql_fetch_assoc($result);
      if (!$row) {
        $this -> role = -1;
      } else {
        $this -> role = $row["role"];
      }
      mysql_free_result($result);
    }

    public function isRegistered() {
      return $this -> group >= 0;
    }

    public function isNormalUser() {
      return $this -> group == 0;
    }

    public function isManageUser() {
      return $this -> group >= 1;
    }

    public function isModerator() {
      return $this -> group == 1;
    }

    public function isAdministrator() {
      return $this -> group == 2;
    }

    public function isTextNoRole() {
      return $this -> role == -1;
    }

    public function isTextTranslator() {
      return $this -> role == 0;
    }

    public function isTextModerator() {
      return $this -> role == 1;
    }

    public function isTextAdministratorOrCreator() {
      return $this -> role >= 2;
    }

    public function isTextAdministrator() {
      return $this -> role == 2;
    }

    public function isTextCreator() {
      return $this -> role == 3;
    }

    private function fail() {
      $this -> group = -1;
      setcookie ("uid", "", 1);
      setcookie ("hash", "", 1);
    }

    private function failWithFree($result) {
      $this -> fail();
      mysql_free_result($result);
    }

  }

  $user = new User($conn);
?>