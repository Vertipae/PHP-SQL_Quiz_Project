<?php
// Router 
function check_logged_in() {
	BaseController::check_logged_in();
}

$routes->get('/', function() {
    IndexController::index();
});


$routes->get('/quizzes', 'check_logged_in', function() {
	QuizController::showAll();
});

$routes->post('/login', function() {
	LoginController::login();
});

$routes->get('/register', function() {
	AccountController::showRegistering();
});

$routes->post('/register', function() {
	AccountController::register();
});

$routes->get('/quiz/:id', 'check_logged_in', function($id) {
	QuizController::show($id);
});

$routes->get('/quiz/:id/end', 'check_logged_in', function($id) {
	QuizController::showEnding($id);
});

$routes->get('/quiz/:quiz_id/:question_id', 'check_logged_in', function($quiz_id, $question_id) {
	QuizController::question($quiz_id, $question_id);
});

$routes->get('/quiz/:quiz_id/:question_id/redo', 'check_logged_in', function($quiz_id, $question_id) {
	QuizController::redo($quiz_id, $question_id);
});

$routes->post('/quiz/:quiz_id/:question_id', 'check_logged_in', function($quiz_id, $question_id) {
	AnswerController::answer($quiz_id, $question_id);
});

$routes->post('/logout', function() {
	LoginController::logout();
});

$routes->get('/admin', 'check_logged_in', function() {
	AccountController::admin();
});

$routes->get('/admin/:account_id', 'check_logged_in', function($account_id) {
	AccountController::accountView($account_id);
});

$routes->post('/admin/:account_id', 'check_logged_in', function($account_id) {
	AnswerController::deleteAll($account_id);
});

$routes->post('/admin/:account_id/:quiz_id', 'check_logged_in', function($account_id, $quiz_id) {
	AnswerController::deleteByQuiz($account_id, $quiz_id);
});

$routes->get('/admin/:account_id/:quiz_id', 'check_logged_in', function($account_id, $quiz_id) {
	AccountController::showAnswers($account_id, $quiz_id);
});
?>