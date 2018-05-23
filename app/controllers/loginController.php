<?php
// require './app/models/account.php';
class LoginController extends BaseController {
	// Login
	public static function login() {
		// Get values from form
		$params = $_POST;
		$studentNumber = $params['studentNumber'];
		$password = $params['password'];
		// Find if account already exists
		$account = Account::find($studentNumber);
		if ($account) {
			// Verify password
			$verified = password_verify($password, $account->passwordHash);
			if($verified) {
				// Log user in
				$_SESSION['account'] = $account;
				$_SESSION['studentNo'] = $account->studentNumber;
				Redirect::to("/quizzes");
			} else {
				View::make("index/index.html", array('error' => 'Incorrect student number or password!', 'studentNumber' => $studentNumber));
			}
		} else {
			View::make("index/index.html", array('error' => 'Incorrect student number or password!', 'studentNumber' => $studentNumber));
		}
	}
	// Logout
	public static function logout() {
		// Log user out and redirect to index
		$_SESSION['account'] = null;
		$_SESSION['studentNo'] = null;
		Redirect::to("/");
	}
}
?>