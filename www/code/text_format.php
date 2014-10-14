<?
  class Formatter {

    private $month = array("1"=>"янв", "2"=>"фев", "3"=>"мар", "4"=>"апр", "5"=>"май", "6"=>"июн", "7"=>"июл","8"=>"авг","9"=>"сен","10"=>"окт","11"=>"ноя","12"=>"дек");

    public function Formatter() {
    }

    public function toStringChangedDate($timestamp) {
      $tms = strtotime($timestamp);
      if (date('Ymd') == date('Ymd', $tms))
        return "сегодня";
      else if (date('Ymd', time() - 60 * 60 * 24) == date('Ymd', $tms))
        return "вчера";
      else {
        return date('j', $tms) . ' ' . $this -> month[date('n', $tms)] . ' ' . date('y', $tms);
      }
    }

  }

?>