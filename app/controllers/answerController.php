<?php

class AnswerController extends BaseController {
	// Save user's answer
	public static function answer($quiz_id, $question_id) {
		$params = $_POST;
		$account = $_SESSION['account'];
		$nextQuestion = $question_id+1;
		// Save answer to database
		Answer::save($account->id, $question_id, $params['answer']);
		// Check if last question in quiz
		if ($question_id % 10 != 0) {
			Redirect::to('/quiz/' . $quiz_id . '/' . $nextQuestion);
		} else {
			$quiz = Quiz::find($quiz_id);
			Redirect::to('/quiz/' . $quiz_id . '/end', array('quiz' => $quiz));
		}

	}
	// Delete all user's answers
	public static function deleteAll($account_id) {
		// Check if user is admin
		if (self::get_user_logged_in()->admin) {
			Answer::deleteAll($account_id);
			Redirect::to('/admin/' . $account_id);
		} else {
			Redirect::to('/');
		}
		
	}
	// Delete user's answers in given quiz
	public static function deleteByQuiz($account_id, $quiz_id) {
		// Check if user admin or self
		if (self::get_user_logged_in()->admin || self::get_user_logged_in()->id == $account_id) {
			Answer::deleteByQuiz($account_id, $quiz_id);
			Redirect::to('/admin/' . $account_id);
		} else {
			Redirect::to('/');
		}
	}
}
?>