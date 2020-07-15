<?php
  $question_id = 0;
  $name = "";
  $choice1 = "";
  $choice2 = "";
  $choice3 = "";
  $choice4 = "";
  $correct_answer = "";
  $test_id = 0;
  $ques_grade = 0;
  $isEditting = false;
  $questions = array();
  $errors = array();

  // ACTION: update question
  if (isset($_POST['update_question'])) {
      $question_id = $_POST['question_id'];
      updateQuestion($question_id);
  }
  // ACTION: Save Question
  if (isset($_POST['save_question'])) {
      saveQuestion();
  }
  // ACTION: fetch question for editting
  if (isset($_GET["edit_question"])) {
    $question_id = $_GET['edit_question'];
    editQuestion($question_id);
  }
  // ACTION: Delete question
  if (isset($_GET['delete_question'])) {
    $question_id = $_GET['delete_question'];
    deleteQuestion($question_id);
  }
  // Save question to database
  function saveQuestion(){
    global $conn, $errors, $name, $choice1, $choice2, $choice3, $choice4, $correct_answer, $test_id, $ques_grade ;
    $errors = validateQuestion($_POST, ['save_question']);
    if (count($errors) === 0) {
       // receive form values
        $name = $_POST['name'];
        $choice1 = $_POST['choice1'];
        $choice2 = $_POST['choice2'];
        $choice3 = $_POST['choice3'];
        $choice4 = $_POST['choice4'];
        $correct_answer = $_POST['correct_answer'];
        $test_id = $_POST['test_id'];
        $ques_grade = $_POST['ques_grade'];
       $sql = "INSERT INTO questions SET name=?, choice1=?, choice2=? , choice3=? , choice4=? , correct_answer=? , ques_grade=? , test_id=?";
       $result = modifyRecord($sql, 'ssssssii', [$name, $choice1, $choice2, $choice3, $choice4, $correct_answer, $ques_grade, $test_id]);

       if ($result) {
         $_SESSION['success_msg'] = "Question created successfully";
         header("location: " . BASE_URL . "admin/questions/questionList.php");
         exit(0);
       } else {
         $_SESSION['error_msg'] = "Something went wrong. Could not save question in Database";
       }
    }
  }
  function updateQuestion($question_id){
    global $conn, $errors, $name, $choice1, $choice2, $choice3, $choice4, $correct_answer, $test_id, $ques_grade, $isEditting; // pull in global form variables into function
    $errors = validateQuestion($_POST, ['update_question']); // validate form
    if (count($errors) === 0) {
      // receive form values
      $name = $_POST['name'];
        $choice1 = $_POST['choice1'];
        $choice2 = $_POST['choice2'];
        $choice3 = $_POST['choice3'];
        $choice4 = $_POST['choice4'];
        $correct_answer = $_POST['correct_answer'];
        $test_id = $_POST['test_id'];
        $ques_grade = $_POST['ques_grade'];
      $sql = "UPDATE questions SET name=?, choice1=?, choice2=? , choice3=? , choice4=? , correct_answer=? , ques_grade=? , test_id=? WHERE id=?";
      $result = modifyRecord($sql, 'ssssssiii', [$name, $choice1, $choice2, $choice3, $choice4, $correct_answer, $ques_grade, $test_id, $question_id]);

      if ($result) {
        $_SESSION['success_msg'] = "Question successfully updated";
        $isEditting = false;
        header("location: " . BASE_URL . "admin/questions/questionList.php");
        exit(0);
      } else {
        $_SESSION['error_msg'] = "Something went wrong. Could not save question in Database";
      }
    }
  }
  function editQuestion($question_id){
    global $conn, $name, $choice1, $choice2, $choice3, $choice4, $correct_answer, $test_id, $ques_grade, $isEditting;
    $sql = "SELECT * FROM questions WHERE id=? LIMIT 1";
    $question = getSingleRecord($sql, 'i', [$question_id]);

    $question_id = $question['id'];
    $name = $question['name'];
      $choice1 = $question['choice1'];
      $choice2 = $question['choice2'];
      $choice3 = $question['choice3'];
      $choice4 = $question['choice4'];
      $correct_answer = $question['correct_answer'];
      $test_id = $question['test_id'];
      $ques_grade = $question['ques_grade'];
    $isEditting = true;
  }
  function deleteQuestion($question_id) {
    global $conn;
    $sql = "DELETE FROM questions WHERE id=?";
    $result = modifyRecord($sql, 'i', [$question_id]);
    if ($result) {
      $_SESSION['success_msg'] = "Question trashed!!";
      header("location: " . BASE_URL . "admin/questions/questionList.php");
      exit(0);
    }
  }
  function getAllQuestions(){
    global $conn;
      if (isset($_GET['test'])) {
          $test_id = $_GET['test'];
          $sql = "SELECT q.id, q.name as question_name, q.choice1, q.choice2, q.choice3, q.choice4, q.correct_answer, q.ques_grade, t.name as test_name
           FROM questions q
           LEFT JOIN tests t  ON q.test_id=t.id
           WHERE t.is_active = 1 and  test_id=?";
          $questions = getMultipleRecords($sql, 'i', [$test_id]);
      }
      else {

          $sql = "SELECT q.id, q.name as question_name, q.choice1, q.choice2, q.choice3, q.choice4, q.correct_answer, q.ques_grade, t.name as test_name
           FROM questions q
           LEFT JOIN tests t  ON q.test_id=t.id
           WHERE t.is_active = 1";
          $questions = getMultipleRecords($sql);
      }

    return $questions;
  }





