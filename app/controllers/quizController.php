<?php

class QuizController extends BaseController {
	// Find all quizzes and show quizzes page 
	public static function showAll() {
		$quizzes = Quiz::allAlphabetical();
		View::make('/quiz/quizzes.html', array('quizzes' => $quizzes));
	}
	// Show quiz by id
	public static function show($id) {
		// Find the quiz and it's first question
		$quiz = Quiz::find($id);
		$firstQuestion = Question::findFirstFromQuiz($id);
		// Check if user already answered this quiz
		$answered = Answer::quizHasAnswers($_SESSION['account']->id, $id);
		if ($answered) {
			// If answers are found, show points
			$points = Answer::getPoints($_SESSION['account']->id, $id);
			View::make('/quiz/quiz.html', array('quiz' => $quiz, 'firstQuestion' => $firstQuestion, 'points' => $points));
		} else {
			View::make('/quiz/quiz.html', array('quiz' => $quiz, 'firstQuestion' => $firstQuestion));
		}
		
	}
	// Show question
	public static function question($quiz_id, $question_id) {
		$quiz = Quiz::find($quiz_id);
		$question = Question::find($question_id);
		$options = Option::findByQuestion($question_id);
		// If question already answered, redirect to next question
		$answer = Answer::findByAccountAndQuestion($_SESSION['account']->id, $question_id);
		if ($answer) {
			$next = $question_id + 1;
			Redirect::to('/quiz/' . $quiz_id . '/' . $next);
		} else {
			View::make('/question/question.html', array('quiz' => $quiz, 'question' => $question, 'options' => $options));
		}

	}
	// Show ending for quiz
	public static function showEnding($id) {
		$quiz = Quiz::find($id);
		// Find points
		$points = Answer::getPoints($_SESSION['account']->id, $id);
		View::make('/quiz/end.html', array('quiz' => $quiz, 'points' => $points));
	}
	// Reset the given quiz and redirect to start it again
	public static function redo($quiz_id, $question_id) {
		Answer::deleteByQuiz(self::get_user_logged_in()->id, $quiz_id);
		Redirect::to('/quiz/' . $quiz_id . '/' . $question_id);
	}
}
?>