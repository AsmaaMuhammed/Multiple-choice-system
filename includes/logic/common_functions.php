<?php
  // Accept a user ID and returns true if user is admin and false if otherwise
  function isAdmin($user_id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE id=? AND role_id= 1 LIMIT 1";
    $user = getSingleRecord($sql, 'i', [$user_id]); // get single user from database
    if (!empty($user)) {
      return true;
    } else {
      return false;
    }
  }
  function loginById($user_id) {
    global $conn;

    $sql = "SELECT u.id, u.role_id, u.username, r.name as role
            FROM users u 
            LEFT JOIN roles r ON u.role_id=r.id
             WHERE u.id=? LIMIT 1";
    $user = getSingleRecord($sql, 'i', [$user_id]);
    if (!empty($user)) {
      // put logged in user into session array
      $_SESSION['user'] = $user;
      $_SESSION['success_msg'] = "You are now logged in";

        $permissionsSql = "SELECT p.name as permission_name, p.method as permission_method FROM permissions as p
                            JOIN permission_role as pr ON p.id=pr.permission_id
                            WHERE pr.role_id=?";
        $userPermissions = getMultipleRecords($permissionsSql, "i", [$user['role_id']]);
        $_SESSION['userPermissions'] = $userPermissions;
        // if user is admin, redirect to dashboard, otherwise to homepage
        if (isAdmin($user_id)) {
        header('location: ' . BASE_URL . 'admin/dashboard.php');
      } else {
            $sql = "SELECT u.id, u.role_id, u.username, r.name as role,c.id as class_id, c.name as class_name,t.id as test_id, t.test_grade
            FROM users u 
            LEFT JOIN roles r ON u.role_id=r.id
            LEFT JOIN classes c ON u.class_id=c.id
            LEFT JOIN tests t ON t.class_id=c.id
             WHERE u.id=? and t.is_active= 1 LIMIT 1";
            $user = getSingleRecord($sql, 'i', [$user_id]);
            $_SESSION['user'] = $user;
            $_SESSION['success_msg'] = "You are now logged in";
            $classId = $user['class_id'];
            $adminEmail = getAdminEmail($classId);
            $_SESSION['adminEmail'] = $adminEmail;
            $questions = getQuestionsByClassId($classId, $user['id'], $user['test_id']);
            $_SESSION['questions'] = $questions;
            $_SESSION['questions_count'] = count($questions);
            $_SESSION['finished'] = $questions == 1?1:0;
        header('location: ' . BASE_URL . 'admin/users/userExam.php');
      }
      exit(0);
    }
  }
function getQuestionsByClassId($classId, $user_id, $test_id){
    global $conn;
    $result = [];
    $checkIfAnswer = checkIfUserFinishTheTestBefore($user_id, $test_id);
    if($checkIfAnswer){
        return 1; //finished
    }
    else {
        $sql = "SELECT t.id,t.test_grade, t.class_id, c.name as class_name,q.id as ques_id, q.name as ques_name, q.choice1, q.choice2, q.choice3, q.choice4, q.correct_answer, q.ques_grade
           FROM tests t 
           LEFT JOIN classes c ON t.class_id=c.id
           LEFT JOIN questions q ON q.test_id = t.id
           WHERE t.is_active =1 and t.class_id = ?";
        $questions = getMultipleRecords($sql, 'i', [$classId]);
        foreach ($questions as $key => $value) {
            if($value['choice1'] != null) {
                $choice1 = $value['choice1'];
                $choice2 = $value['choice2'];
                $choice3 = $value['choice3'];
                $choice4 = $value['choice4'];
                $correct_answer = $value['correct_answer'];
                $wrong = array_diff([$choice1, $choice2, $choice3, $choice4], [$correct_answer]);
                $result[] = [
                    'id' => $value['ques_id'],
                    'question_string' => $value['ques_name'],
                    'choices' => [
                        'correct' => $value['correct_answer'],
                        'wrong' => $wrong
                    ],
                    'question_grade' => $value['ques_grade']
                ];
            }
        }
    }
    return json_encode($result);
}

function checkIfUserFinishTheTestBefore($user_id, $test_id)
{
    global $conn;
    $sql = "SELECT u.id
            FROM users_answers u 
             WHERE u.id=? and u.test_id= ? LIMIT 1";
    $user = getSingleRecord($sql, 'ii', [$user_id, $test_id]);

    if (!empty($user)) {
        return 1;//finished
    }
    return 0;
}
// Accept a user object, validates user and return an array with the error messages
  function validateUser($user, $ignoreFields) {
  		global $conn;
      $errors = [];
      // password confirmation
      if (isset($user['passwordConf']) && ($user['password'] !== $user['passwordConf'])) {
        $errors['passwordConf'] = "The two passwords do not match";
      }
      // if passwordOld was sent, then verify old password
      if (isset($user['passwordOld']) && isset($user['user_id'])) {
        $sql = "SELECT * FROM users WHERE id=? LIMIT 1";
        $oldUser = getSingleRecord($sql, 'i', [$user['user_id']]);
        $prevPasswordHash = $oldUser['password'];
        if (!password_verify($user['passwordOld'], $prevPasswordHash)) {
          $errors['passwordOld'] = "The old password does not match";
        }
      }
      // the email should be unique for each user for cases where we are saving admin user or signing up new user
      if (in_array('save_user', $ignoreFields) || in_array('signup_btn', $ignoreFields)) {
        $sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
        $oldUser = getSingleRecord($sql, 'ss', [$user['email'], $user['username']]);
        if (!empty($oldUser['email']) && $oldUser['email'] === $user['email']) { // if user exists
          $errors['email'] = "Email already exists";
        }
        if (!empty($oldUser['username']) && $oldUser['username'] === $user['username']) { // if user exists
          $errors['username'] = "Username already exists";
        }
      }

      // required validation
  	  foreach ($user as $key => $value) {
        if (in_array($key, $ignoreFields)) {
          continue;
        }
  			if (empty($user[$key])) {
  				$errors[$key] = "This field is required";
  			}
  	  }
  		return $errors;
  }
  // upload's user profile profile picture and returns the name of the file
  function uploadProfilePicture()
  {
    // if file was sent from signup form ...
    if (!empty($_FILES) && !empty($_FILES['profile_picture']['name'])) {
        // Get image name
        $profile_picture = date("Y.m.d") . $_FILES['profile_picture']['name'];
        // define Where image will be stored
        $target = ROOT_PATH . "/assets/images/" . $profile_picture;
        // upload image to folder
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
          return $profile_picture;
          exit();
        }else{
          echo "Failed to upload image";
        }
    }
  }
  // Accept a post object, validates post and return an array with the error messages
function validateRole($role, $ignoreFields) {
  global $conn;
  $errors = [];
  foreach ($role as $key => $value) {
    if (in_array($key, $ignoreFields)) {
        continue;
    }
    if (empty($role[$key])) {
      $errors[$key] = "This field is required";
    }
  }
  return $errors;
}

// Accept a post object, validates post and return an array with the error messages
function validateTest($test, $ignoreFields) {
    global $conn;
    $errors = [];
    foreach ($test as $key => $value) {
        if (in_array($key, $ignoreFields)) {
            continue;
        }
        if (empty($test[$key])) {
            $errors[$key] = "This field is required";
        }
    }
    return $errors;
}
// Accept a post object, validates post and return an array with the error messages
function validateQuestion($question, $ignoreFields) {
    global $conn;
    $errors = [];
    foreach ($question as $key => $value) {
        if (in_array($key, $ignoreFields)) {
            continue;
        }
        if (empty($question[$key])) {
            $errors[$key] = "This field is required";
        }
    }
    return $errors;
}

function getAllClasses(){
    global $conn;
    $sql = "SELECT id, name FROM classes";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $roles = $result->fetch_all(MYSQLI_ASSOC);
    return $roles;
}

function getAllTests(){
    global $conn;
    $sql = "SELECT t.id, t.name as test_name, t.description as test_description, t.test_grade, t.class_id, c.name as class_name
           FROM tests t 
           LEFT JOIN classes c ON t.class_id=c.id
           WHERE t.is_active = 1";

    $tests = getMultipleRecords($sql);

    return $tests;
}
function getAdminEmail($classId)
{
    $adminEmail = '';
    $sql = "SELECT u.id,u.email
            FROM users u 
            WHERE u.role_id = 1 and u.class_id=? LIMIT 1";
    $user = getSingleRecord($sql, 'i', [$classId]);

    if (!empty($user)) {
            $adminEmail = $user['email'];
    }
    else
    {
        $sql = "SELECT u.id,u.email
            FROM users u 
            WHERE u.role_id = ? LIMIT 1";

        $user = getSingleRecord($sql, 'i', [1]);
        if (!empty($user)) {
            $adminEmail = $user['email'];
        }
    }
    return $adminEmail;
}
