<?php

class Answer extends BaseModel {

	public $account_id, $question_id, $option;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}
	
	public static function find($option) {
		$query = DB::connection()->prepare('SELECT * FROM answer WHERE option = :option LIMIT 1');
		$query->execute(array('option' => $option));
		$row = $query->fetch();

		if ($row) {
			$answer = new Answer(array(
				'account_id' =>$row['account_id'],
				'question_id' =>$row['question_id'],
				'option' =>$row['option'],
			));

			return $answer;
		}

		return null;
	}

	public static function save($account_id, $question_id, $option) {
		$query = DB::connection()->prepare('INSERT INTO answer (account_id, question_id, option) VALUES (:account_id, :question_id, :option)');
		$query->execute(array('account_id' => $account_id, 'question_id' => $question_id, 'option' => $option));
		$row = $query->fetch();
	}

	public static function getPoints($account_id, $quiz_id) {
		// SQL query that finds all answers given by user in specific quiz 
		$query = DB::connection()->prepare('SELECT * FROM answer LEFT JOIN question ON question.id = answer.question_id LEFT JOIN quiz ON quiz.id = question.quiz_id WHERE quiz.id = :quiz_id AND answer.option = question.correctOption AND answer.account_id = :account_id');
		$query->execute(array('account_id' => $account_id, 'quiz_id' => $quiz_id));
		$rows = $query->fetchAll();
		return count($rows);
	}

	public static function findByAccountAndQuestion($account_id, $question_id) {
		// SQL query that finds user's answer to specific question
		$query = DB::connection()->prepare('SELECT * FROM answer WHERE account_id = :account_id AND question_id = :question_id LIMIT 1');
		$query->execute(array('account_id' => $account_id, 'question_id' => $question_id));
		$row = $query->fetch();

		if ($row) {
			$answer = new Answer(array(
				'account_id' =>$row['account_id'],
				'question_id' =>$row['question_id'],
				'option' =>$row['option']
			));

			return $answer;
		}

		return null;
	}

	public static function quizHasAnswers($account_id, $quiz_id) {
		// SQL query that checks if quiz already answered by user
		$query = DB::connection()->prepare('SELECT * FROM answer LEFT JOIN question ON question_id = answer.question_id LEFT JOIN quiz ON quiz.id = question.quiz_id WHERE answer.account_id = :account_id AND quiz.id = :quiz_id');
		$query->execute(array('account_id' => $account_id, 'quiz_id' => $quiz_id));
		$rows = $query->fetchAll();
		if (count($rows) > 0) {
			return true;
		}
		return false;
	}

	public static function deleteAll($account_id) {
		$query = DB::connection()->prepare('DELETE FROM answer WHERE account_id = :account_id');
		$query->execute(array('account_id' => $account_id));
	}

	public static function deleteByQuiz($account_id, $quiz_id) {
		// SQL query that finds all questions that relate to given quiz
		$query = DB::connection()->prepare('SELECT id FROM question WHERE quiz_id = :quiz_id');
		$query->execute(array('quiz_id' => $quiz_id));
		$rows = $query->fetchAll();
		// delete answers from all of the questions given by last query
		foreach ($rows as $row) {
			$query = DB::connection()->prepare('DELETE FROM answer WHERE account_id = :account_id AND question_id = :question_id');
			$query->execute(array('account_id' => $account_id, 'question_id' => $row['id']));
		}
	}

	public static function getUsersAnswerWithQuestion($account_id, $question_id) {
		// SQL query that finds user's answers to given question and also gives description question and answer
		$query = DB::connection()->prepare('SELECT answer.account_id, answer.question_id AS answer_question_id, answer.option AS optionNo, question.id AS question_id, question.description AS question, question.quiz_id, question.correctOption, option.id AS option_id, option.description AS option, option.optionNo, option.question_id AS option_question_id FROM answer LEFT JOIN question ON question.id = answer.question_id LEFT JOIN option ON option.question_id = question.id WHERE answer.account_id = :account_id AND answer.question_id = :question_id AND option.optionNo = answer.option ORDER BY question.id');
		$query->execute(array('account_id' => $account_id, 'question_id' => $question_id));
		$row = $query->fetch();

		if ($row) {
			$answer = new Answer(array(
				'account_id' =>$row['account_id'],
				'question_id' =>$row['question_id'],
				'option' =>$row['optionNo']
			));

			$answer->question = $row['question'];
			$answer->optionText = $row['option'];
			if ($answer->option == $row['correctOption']) {
				$answer->correct = true;
			} else {
				$answer->correct = false;
			}
			

			return $answer;
		}

		return null;
	}

}