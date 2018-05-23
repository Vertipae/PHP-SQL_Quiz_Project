<?php
// Base for controllers
class BaseController {
    public static function get_user_logged_in() {
        if (isset($_SESSION['studentNo'])) {
            $account = Account::find($_SESSION['studentNo']);
            return $account;
        }
        return null;
    }
    public static function check_logged_in() {
        if (!isset($_SESSION['studentNo'])) {
            Redirect::to('/', array('message' => 'Please log in'));
        }
    }
}
?>