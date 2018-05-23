<?php

class AccountController extends BaseController {

	//Show registering page
	public static function showRegistering() {
		View::make('/register/register.html');
	}
	// Register
	public static function register() { 
		// Get form information from request
		$params = $_POST;
		// Check if password is allowed
		$errors = Account::validate_password($params['password']);
		if (count($errors)) {
			View::make('/register/register.html', array('errors' => $errors, 'studentNumber' => $params['studentNumber'], 'firstName' => $params['firstName'], 'lastName' => $params['lastName']));
		}
		// Check if passwords match
		if ($params['password'] != $params['passwordTwo']) {
			View::make('/register/register.html', array('errors' => array('Passwords don\'t match!'), 'studentNumber' => $params['studentNumber'], 'firstName' => $params['firstName'], 'lastName' => $params['lastName']));
		}
		// Set attributes into array
		$attributes = array(
			'studentNumber' => $params['studentNumber'],
			'firstName' => $params['firstName'],
			'lastName' => $params['lastName'],
			'passwordHash' => password_hash($params['password'], PASSWORD_DEFAULT),
			'admin' => 0
		);
		// Create account object and validate it
		$account = new Account($attributes);
		$errors = $account->errors();
		// if errors in validation, return user to registering page
		if(count($errors) == 0) {
			// if no errors, save the account and log user in
			Account::save($account->studentNumber, $account->firstName, $account->lastName, $account->passwordHash);
			$account = Account::find($account->studentNumber);
			$_SESSION['account'] = $account;
			$_SESSION['studentNo'] = $attributes['studentNumber'];
			Redirect::to('/quizzes', array('message' => 'Account registered successfully!'));
		} else {
			View::make('/register/register.html', array('errors' => $errors, 'studentNumber' => $params['studentNumber'], 'firstName' => $params['firstName'], 'lastName' => $params['lastName']));
		}

	}
	// Show admin page
	public static function admin() {
		// Check if user is admin
		if (self::get_user_logged_in()->admin) {
			$accounts = Account::findAll();
			View::make('/admin/adminIndex.html', array('accounts'=>$accounts));
		}else{
			Redirect::to('/');
		}
	}
	// Show account list
	public static function accountView($account_id) {
		// Check if user is admin
		if (self::get_user_logged_in()->admin) {
			// Find users answered quizzes
			$accountsQuizzes = Quiz::findAccountsQuizzes($account_id);
			$quizzes = Quiz::allAlphabetical();
			$account = Account::findById($account_id);
			// Initialize points for quizzes
			foreach ($quizzes as $quiz) {
				foreach ($accountsQuizzes as $accountQuiz) {
					if ($quiz->id == $accountQuiz->id) {
						$quiz->points = $accountQuiz->points;
					}
				}
			}
			View::make('/admin/accountInfo.html', array('account' => $account, 'quizzes' => $quizzes));
		} else {
			Redirect::to('/');
		}
	}
	// Show user's answers for a quiz
	public static function showAnswers($account_id, $quiz_id) {
		// Check if user is admin
		if (self::get_user_logged_in()->admin) {
			// find quiz's questions 
			$questions = Question::findByQuiz($quiz_id);
			$answers = array();
			// Initialize answers into array
			foreach ($questions as $question) {
				$answers[] = Answer::getUsersAnswerWithQuestion($account_id, $question->id);
			}
			$account = Account::findById($account_id);
			$quiz = Quiz::find($quiz_id);
			View::make('/admin/accountsAnswers.html', array('answers' => $answers, 'account' => $account, 'quiz' => $quiz));
		} else {
			Redirect::to('/');
		}
	}
}
?>