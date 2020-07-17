<?php
  // variable declaration. These variables will be used in the user form
  $user_id = 0;
  $role_id = NULL;
  $class_id = NULL;
  $username = "";
  $email = "";
  $password = "";
  $passwordConf = "";
  $profile_picture = "";
  $isEditing = false;
  $users = array();
  $errors = array();
define ('SERVER', "localhost");
define ('USER',  "root");
define ('PASSWORD',"123");
define ('DB', "multiple-choice-quiz");

  function getAllRoles(){
    global $conn;
    $sql = "SELECT id, name FROM roles";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $roles = $result->fetch_all(MYSQLI_ASSOC);
    return $roles;
  }
  // ... variables declaration is up here ...
// ACTION: update user
if (isset($_POST['update_user'])) { // if user clicked update_user button ...
    $user_id = $_POST['user_id'];
    updateUser($user_id);
}
// ACTION: Save User
if (isset($_POST['save_user'])) {  // if user clicked save_user button ...
    saveUser();
}
// ACTION: fetch user for editting
if (isset($_GET["edit_user"])) {
  $user_id = $_GET["edit_user"];
  editUser($user_id);
}
// ACTION: Delete user
if (isset($_GET['delete_user'])) {
  $user_id = $_GET['delete_user'];
  deleteUser($user_id);
}

function updateUser($user_id) {
  global $conn, $errors, $username, $role_id, $class_id, $email, $isEditing;
  $errors = validateUser($_POST, ['update_user', 'update_profile']);

  // receive all input values from the form
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt the password before saving in the database
  $profile_picture = uploadProfilePicture();
  if (count($errors) === 0) {
    if (isset($_POST['role_id'])) {
      $role_id = $_POST['role_id'];
    }
      if (isset($_POST['class_id'])) {
          $class_id = $_POST['class_id'];
      }
    $sql = "UPDATE users SET username=?, role_id=?, class_id=?, email=?, password=?, profile_picture=? WHERE id=?";
    $result = modifyRecord($sql, 'siisssi', [$username, $role_id, $class_id, $email, $password, $profile_picture, $user_id]);

    if ($result) {
      $_SESSION['success_msg'] = "User account successfully updated";
      header("location: " . BASE_URL . "admin/users/userList.php");
      exit(0);
    }
  } else {
    // continue editting if there were errors
    $isEditing = true;
  }
}
// Save user to database
function saveUser(){
  global $conn, $errors, $username, $role_id, $class_id, $email, $isEditing;
  $errors = validateUser($_POST, ['save_user']);
  // receive all input values from the form
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt the password before saving in the database
  $profile_picture = uploadProfilePicture(); // upload profile picture and return the picture name
  if (count($errors) === 0) {
    if (isset($_POST['role_id'])) {
      $role_id = $_POST['role_id'];
    }
      if (isset($_POST['class_id'])) {
          $class_id = $_POST['class_id'];
      }
    $sql = "INSERT INTO users SET username=?, role_id=?, class_id=?, email=?, password=?, profile_picture=?";
    $result = modifyRecord($sql, 'siisss', [$username, $role_id, $class_id, $email, $password, $profile_picture]);

    if($result){
      $_SESSION['success_msg'] = "User account created successfully";
      header("location: " . BASE_URL . "admin/users/userList.php");
      exit(0);
    } else {
      $_SESSION['error_msg'] = "Something went wrong. Could not save user in Database";
    }
  }
}
function getAdminUsers(){
  global $conn;
  // for every user, select a user role name from roles table, and then id, role_id and username from user table
  // where the role_id on user table matches the id on roles table
  $sql = "SELECT r.name as role, u.id, u.role_id, u.username
          FROM users u
          LEFT JOIN roles r ON u.role_id=r.id
          WHERE role_id= 1 AND u.id != ?";
    $userId =$_SESSION['user']['id'];
    $users = getMultipleRecords($sql, 'i', [$userId]);
  return $users;
}

function getAllUsers(){
  global $conn;
  // for every user, select a user role name from roles table, and then id, role_id and username from user table
  // where the role_id on user table matches the id on roles table
  $sql = "SELECT r.name as role,c.name as class, u.id, u.role_id, u.username
          FROM users u
          LEFT JOIN roles r ON u.role_id=r.id
          LEFT JOIN classes c ON u.class_id=c.id
          WHERE u.id != ?";

  $users = getMultipleRecords($sql, 'i', [$_SESSION['user']['id']]);
  return $users;
}

function getUsers(){
  global $conn;
  // for every user, select a user role name from roles table, and then id, role_id and username from user table
  // where the role_id on user table matches the id on roles table
  $sql = "SELECT r.name as role, u.id, u.role_id, u.username
          FROM users u
          LEFT JOIN roles r ON u.role_id=r.id
          WHERE role_id 2 AND u.id != ?";

  $users = getMultipleRecords($sql, 'i', [$_SESSION['user']['id']]);
  return $users;
}

function editUser($user_id){
  global $conn, $user_id, $role_id, $class_id, $username, $email, $isEditing, $profile_picture;

  $sql = "SELECT * FROM users WHERE id=?";
  $user = getSingleRecord($sql, 'i', [$user_id]);

  $user_id = $user['id'];
  $role_id = $user['role_id'];
  $class_id = $user['class_id'];
  $username = $user['username'];
  $profile_picture = $user['profile_picture'];
  $email = $user['email'];
  $isEditing = true;
}
function deleteUser($user_id) {
  global $conn;
  $sql = "DELETE FROM users WHERE id=?";
  $result = modifyRecord($sql, 'i', [$user_id]);

  if ($result) {
    $_SESSION['success_msg'] = "User trashed!!";
    header("location: " . BASE_URL . "admin/users/userList.php");
    exit(0);
  }
}
if (isset($_POST['update_profile'])) {
    $user_id = $_SESSION['user']['id'];
    if (!isset($user_id)) {
      $_SESSION['success_msg'] = "You have to be logged in to update your profile";
      header("location: " . BASE_URL . "login.php");
      exit(0);
    } else {
      updateUser($user_id); // Update logged in user profile
    }
}
if (isset($_POST['answer_exam'])) {
    $questionsIds = json_decode($_POST['ids']);
    $questionsAnswers = $_POST['answers'];
    $questionsGrads = $_POST['grads'];
    //send session data in post request
    $userId = $_POST['user_id'];
    $testId = $_POST['test_id'];

    $conn = new mysqli(SERVER,USER, PASSWORD, DB);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    foreach ($questionsIds as $i=>$row)
    {
        //echo $i;
        $sql = "INSERT INTO users_answers SET user_id=?, test_id=?, ques_id=?, user_answer=?, user_grade=?";
        $params = [$userId, $testId, $questionsIds[$i], $questionsAnswers[$i], $questionsGrads[$i]];
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiisi', ...$params);
        $result = $stmt->execute();
//        $user = getSingleRecord($sql, 'iiisi', $params);
    }
    $stmt->close();
    return json_encode(1);

}
if (isset($_POST['exam_status'])) {
    //send session data in post request
    $userId = $_POST['user_id'];
    $testId = $_POST['test_id'];

    $conn = new mysqli(SERVER,USER, PASSWORD, DB);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT u.id
            FROM users_answers u 
             WHERE u.id=? and u.test_id= ? LIMIT 1";
//    $user = getSingleRecord($sql, 'ii', [$userId, $testId]);
    $params = [$userId, $testId];
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!empty($user)) {
        echo 1;//finished
    }
    else
        echo 0;//
}

if (isset($_POST['quiz_report_button'])) {
    $userId = $_POST['user_id'];
    $testId = $_POST['test_id'];
//    $conn = new mysqli(SERVER,USER, PASSWORD, DB);
//    header('location: ' . BASE_URL . 'admin/users/userGrade.php');
    echo $userId;
    echo $testId;
}

function sendEmail()
{
    $name = isset($_SESSION['user'])?$_SESSION['user']['username']:'';
    $grade = '';
    $to_email = isset($_SESSION['adminEmail'])?$_SESSION['adminEmail']:'';
    $subject = 'Answering Quiz';
    $message = $name.' finished the quiz with Grade '.$grade;
    $headers = FROM_EMAIL;
    $res = mail($to_email,$subject,$message,$headers);
    return $res;
}