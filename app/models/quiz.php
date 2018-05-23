<?php

/**
 * 
 */
class Quiz extends BaseModel {

	public $name, $id;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}
    // Find all quizzes in alphabetical order
    public static function allAlphabetical() {
        $query = DB::connection()->prepare('SELECT * FROM quiz ORDER BY name');
        $query->execute();
        $rows = $query->fetchAll();
        $quizzes = array();
        foreach ($rows as $row) {
            $quizzes[] = new Quiz(array(
            	'id' => $row['id'],
                'name' => $row['name']
            ));
        }
        return $quizzes;
    }
    // Find quiz by id
    public static function find($id) {
    	$query = DB::connection()->prepare('SELECT * FROM quiz WHERE id = :id LIMIT 1');
    	$query->execute(array('id' => $id));
    	$row = $query->fetch();
    	if ($row) {
    		$quiz = new Quiz(array(
    			'id' => $row['id'],
    			'name' => $row['name']
    		));
    		return $quiz;
    	}
    	return null;
    }
	// Find all quizzes answered by user
    public static function findAccountsQuizzes($account_id) {
        $query = DB::connection()->prepare('SELECT DISTINCT quiz.id, quiz.name FROM quiz LEFT JOIN question ON question.quiz_id = quiz.id LEFT JOIN answer ON answer.question_id = question.id WHERE answer.account_id = :account_id');
        $query->execute(array('account_id' => $account_id));
        $rows = $query->fetchAll();
        $quizzes = array();

        foreach ($rows as $row) {
            $quizzes[] = new Quiz(array(
                'id' => $row['id'],
                'name' => $row['name']
            ));
        }

        foreach ($quizzes as $quiz) {
            $quiz->points = Answer::getPoints($account_id, $quiz->id);
        }

        return $quizzes;
    }

}