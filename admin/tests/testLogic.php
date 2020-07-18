<?php
  $test_id = 0;
  $name = "";
  $class_id = null;
  $test_grade = 0;
  $is_active = 1;
  $description = "";
  $isEditting = false;
  $tests = array();
  $errors = array();

  // ACTION: update test
  if (isset($_POST['update_test'])) {
      $test_id = $_POST['test_id'];
      updateTest($test_id);
  }
  // ACTION: Save test
  if (isset($_POST['save_test'])) {
      saveTest();
  }
  // ACTION: fetch test for editting
  if (isset($_GET["edit_test"])) {
    $test_id = $_GET['edit_test'];
    editTest($test_id);
  }
  // ACTION: Delete test
  if (isset($_GET['delete_test'])) {
    $test_id = $_GET['delete_test'];
    deleteTest($test_id);
  }
  // Save test to database
  function saveTest(){
    global $conn, $errors, $name,$class_id,$test_grade, $description;
    $errors = validateTest($_POST, ['save_test']);
    if (count($errors) === 0) {
       // receive form values
       $name = $_POST['name'];
       $description = $_POST['description'];
       $class_id = $_POST['class_id'];
       $test_grade = $_POST['test_grade'];

        $sql2 = "UPDATE tests SET is_active=0 where class_id =?";
        $result2 = modifyMultipleRecord($sql2, 'i', [$class_id]);

       $sql = "INSERT INTO tests SET name=?, description=?, class_id=?, test_grade=?";
       $result = modifyRecord($sql, 'ssii', [$name, $description,$class_id,$test_grade]);

       if ($result) {
         $_SESSION['success_msg'] = "test created successfully";
         header("location: " . BASE_URL . "admin/tests/testList.php");
         exit(0);
       } else {
         $_SESSION['error_msg'] = "Something went wrong. Could not save test in Database";
       }
    }
  }
  function updateTest($test_id){
    global $conn, $errors, $name, $description,$class_id,$test_grade, $isEditting; // pull in global form variables into function
    $errors = validateTest($_POST, ['update_test']); // validate form
    if (count($errors) === 0) {
      // receive form values
      $name = $_POST['name'];
      $description = $_POST['description'];
      $class_id = $_POST['class_id'];
      $test_grade = $_POST['test_grade'];
      $sql = "UPDATE tests SET name=?, description=?, class_id=?, test_grade=? WHERE id=?";
      $result = modifyRecord($sql, 'ssiii', [$name, $description,$class_id,$test_grade,$test_id]);

      $sql2 = "UPDATE tests SET is_active=0 WHERE id !=? and class_id =?";
      $result2 = modifyMultipleRecord($sql2, 'ii', [$test_id,$class_id]);

      if ($result) {
        $_SESSION['success_msg'] = "test successfully updated";
        $isEditting = false;
        header("location: " . BASE_URL . "admin/tests/testList.php");
        exit(0);
      } else {
        $_SESSION['error_msg'] = "Something went wrong. Could not save test in Database";
      }
    }
  }
  function editTest($test_id){
    global $conn, $name, $description,$class_id,$test_grade, $isEditting;
    $sql = "SELECT * FROM tests WHERE id=? LIMIT 1";
    $test = getSingleRecord($sql, 'i', [$test_id]);

    $test_id = $test['id'];
    $name = $test['name'];
    $description = $test['description'];
      $class_id = $test['class_id'];
      $test_grade = $test['test_grade'];
    $isEditting = true;
  }
  function deleteTest($test_id) {
    global $conn;
    $sql = "DELETE FROM tests WHERE id=?";
    $result = modifyRecord($sql, 'i', [$test_id]);
    if ($result) {
      $_SESSION['success_msg'] = "test trashed!!";
      header("location: " . BASE_URL . "admin/tests/testList.php");
      exit(0);
    }
  }

  function getAllQuestions($test_id){
    global $conn;
    $sql = "SELECT * FROM questions WHERE test_id=?";
    $questions = getMultipleRecords($sql, 'i', [$test_id]);
    return $questions;
  }

  function saveTestQuestions($questions_ids, $test_id) {
    global $conn;
    $sql = "DELETE FROM questions WHERE test_id=?";
    $result = modifyRecord($sql, 'i', [$test_id]);

    if ($result) {
      foreach ($questions_ids as $id) {
        $sql_2 = "INSERT INTO questions SET test_id=?, question_id=?";
        modifyRecord($sql_2, 'ii', [$test_id, $id]);
      }
    }

    $_SESSION['success_msg'] = "Permissions saved";
    header("location: testList.php");
    exit(0);
  }



