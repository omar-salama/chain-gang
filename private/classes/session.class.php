<?php
class Session {

  private $admin_id;
  public $username;
  public $last_login;
  private const MAX_SESSION_AGE = 60*60*24;

  public function __construct() {
    session_start();
    $this->check_stored_session();
  }

  public function login($admin) {
    if($admin){
      $this->admin_id = $_SESSION['admin_id'] = $admin->id;
      $this->username = $_SESSION['username'] = $admin->username;
      $this->last_login = $_SESSION['last_login'] = time();
    }
    return true;
  }

  public function is_loggedin() {
    return isset($this->admin_id) && $this->is_session_recent();
  }


  public function require_login() {
    if(!$this->is_loggedin()) {
      redirect_to(url_for('staff/login.php'));
    }
  }

  public function logout() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['username']);
    unset($_SESSION['last_login']);
    unset($this->admin_id);
    unset($this->username);
    unset($this->last_login);
  }

  private function check_stored_session() {
    if(isset($_SESSION['admin_id'])) {
      $this->admin_id = $_SESSION['admin_id'];
      $this->username = $_SESSION['username'];
      $this->last_login = $_SESSION['last_login'];
    }
  }

  private function is_session_recent() {
    if(($this->last_login + self::MAX_SESSION_AGE) <= time()) {
      return false;
    }
    return true;
  }

  public function message($msg="") {
    if(!empty($msg)) {
      // SET message
      $_SESSION['message'] = $msg;
    } else {
      // GET message
      return $_SESSION['message'] ?? '';
    }
  }

  public function display_message() {
    $msg = $this->message();
    unset($_SESSION['message']);
    if(isset($msg) && $msg != '') {
      return '<div id="message">' . h($msg) . '</div>';
    }
  }

}

?>
