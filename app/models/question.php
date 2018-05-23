<?php

/**
 * 
 */
class Question extends BaseModel {

	public $id, $description, $quiz_id, $correctOption;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

    // Find questiom by if
    public static function find($id) {
    	$query = DB::connection()->prepare('SELECT * FROM question WHERE id = :id LIMIT 1');
    	$query->execute(array('id' => $id));
    	$row = $query->fetch();
    	if ($row) {
    		$question = new Question(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'quiz_id' => $row['quiz_id'],
                'correctOption' => $row['correctOption']
            ));
    		return $question;
    	}
    	return null;
    }
    // Find quiz's first question
    public static function findFirstFromQuiz($quiz_id) {
        $query = DB::connection()->prepare('SELECT * FROM question WHERE quiz_id = :quiz_id ORDER BY id LIMIT 1');
        $query->execute(array('quiz_id' => $quiz_id));
        $row = $query->fetch();
        if ($row) {
            $question = new Question(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'quiz_id' => $row['quiz_id'],
                'correctOption' => $row['correctOption']
            ));
            return $question;

        }
        return null;
    }
    // Find all questions of specific quiz
    public static function findByQuiz($quiz_id) {
        $query = DB::connection()->prepare('SELECT * FROM question WHERE quiz_id = :quiz_id ORDER BY id');
        $query->execute(array('quiz_id' => $quiz_id));
        $rows = $query->fetchAll();

        $questions = array();
        foreach ($rows as $row) {
            $questions[] = new Question(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'quiz_id' => $row['quiz_id'],
                'correctOption' => $row['correctOption']
            ));
        }
        return $questions;
    }


}