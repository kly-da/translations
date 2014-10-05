<?
  if (!isset($conn)) {
    include('mod_db.php');
  }
  
  class User {
    public $uid;
    public $name;
    public $settings;
    private $group;
    
    function User($conn) {
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
    }
    
    public function isRegistered() {
      return $this -> group >= 0;
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