<?php

/**
 * 
 */
class Option extends BaseModel {

	public $id, $description, $optionNo, $question_id;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}
    //Searches every option from given question
    public static function findByQuestion($question) {
        $query = DB::connection()->prepare('SELECT * FROM option WHERE question_id = :question ORDER BY optionNo');
        $query->execute(array('question' => $question));
        $rows = $query->fetchAll();
        $options = array();
        foreach ($rows as $row) {
            $options[] = new Option(array(
            	'id' => $row['id'],
                'description' => $row['description'],
                'optionNo' => $row['optionNo'],
                'question_id' => $row['question_id']
            ));
        }
        return $options;
    }
    // Searches given option from given question
    public static function find($question, $optionNo) {
    	$query = DB::connection()->prepare('SELECT * FROM option WHERE question_id = :question AND optionNo = :optionNo LIMIT 1');
    	$query->execute(array('question' => $question, 'optionNo' => $optionNo));
    	$row = $query->fetch();
    	if ($row) {
    		$option = new Option(array(
                'id' => $row['id'],
                'description' => $row['description'],
                'optionNo' => $row['optionNo'],
                'question_id' => $row['question_id']
    		));
    		return $option;
    	}
    	return null;
    }
	


}