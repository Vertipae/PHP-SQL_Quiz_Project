<?php


class Account extends BaseModel {

	public $id, $studentNumber, $firstName, $lastName, $admin, $passwordHash;
	// Initialize object and validators
	public function __construct($attributes) {
		parent::__construct($attributes);
		$this->validators = array('validate_studentNumber', 'validate_names');
	}
	// Find all accounts
	public static function findAll() {
		// Connect to database and prepare the SQL query
		$query = DB::connection()->prepare('SELECT * FROM account');
		// Execute the query
		$query->execute();
		// Fetch the results
		$rows = $query->fetchAll();
		$accounts = array();
		// Initialize results into array of objects
		foreach ($rows as $row) {
			$accounts[] = new Account(array(
				'id' =>$row['id'],
				'studentNumber' =>$row['studentNumber'],
				'passwordHash' =>$row['passwordHash'],
				'firstName' =>$row['firstName'],
				'lastName' =>$row['lastName'],
				'admin' =>$row['admin'],
			));
		}
		return $accounts;
	}
	// Find account by id
	public static function findById($id) {
		$query = DB::connection()->prepare('SELECT * FROM account WHERE id = :id LIMIT 1');
		// Execute the query and set the parameters
		$query->execute(array('id' => $id));
		$row = $query->fetch();

		if ($row) {
			$account = new Account(array(
				'id' =>$row['id'],
				'studentNumber' =>$row['studentNumber'],
				'passwordHash' =>$row['passwordHash'],
				'firstName' =>$row['firstName'],
				'lastName' =>$row['lastName'],
				'admin' =>$row['admin'],
			));

			return $account;
		}

		return null;
	}


	public static function find($studentNumber) {
		$query = DB::connection()->prepare('SELECT * FROM account WHERE studentNumber = :studentNumber LIMIT 1');
		$query->execute(array('studentNumber' => $studentNumber));
		$row = $query->fetch();

		if ($row) {
			$account = new Account(array(
				'id' =>$row['id'],
				'studentNumber' =>$row['studentNumber'],
				'passwordHash' =>$row['passwordHash'],
				'firstName' =>$row['firstName'],
				'lastName' =>$row['lastName'],
				'admin' =>$row['admin'],
			));

			return $account;
		}

		return null;
	}


	// Save new account into database
	public static function save($studentNumber, $firstName, $lastName, $passwordHash) {
		$query = DB::connection()->prepare('INSERT INTO account (studentNumber, firstName, lastName, passwordHash) VALUES (:studentNumber, :firstName, :lastName, :passwordHash)');
		$query ->execute(array('studentNumber' => $studentNumber, 'firstName' => $firstName, 'lastName' => $lastName, 'passwordHash' => $passwordHash ));
	}
	
	// Validators

	public function validate_studentNumber() {
		$errors = array();

		$account = self::find($this->studentNumber);

		if ($account) {
			$errors[] = 'Account with this student number already exists!';
		}

		if ($this->studentNumber == '' || $this->studentNumber == null) {
			$errors[] = 'Student number is required!';
		}

		if (strlen($this->studentNumber) > 10 || strlen($this->studentNumber) < 7) {
			$errors[] = 'Student number has to be 7-10 numbers long!';
		}

		if (is_numeric($this->studentNumber) == FALSE) {
			$errors[] = 'Student number has to be a number!';
		}

		return $errors;
	}
	public function validate_password($password) {
		$errors = array();

		if ($password == '' || $password == null) {
			$errors[] = 'Password is required!';
		}

		if (strlen($password) > 20 || strlen($password) < 8) {
			$errors[] = 'Password has to be 8-20 characters long!';
		}
		return $errors;
	}

	public function validate_names() {
		$errors = array();

		if ($this->firstName == '' || $this->firstName == null) {
			$errors[] = 'First name is required!';
		}
		if ($this->lastName == '' || $this->lastName == null) {
			$errors[] = 'Last name is required!';
		}

		if (strlen($this->firstName) > 20) {
			$errors[] = 'First name is too long!';
		}
		if (strlen($this->lastName) > 20) {
			$errors[] = 'Last name is too long!';
		}
		return $errors;
	}


}