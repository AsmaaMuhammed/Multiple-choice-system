<?php require_once '../../config.php'; ?>
<?php require_once '../middleware.php'; ?>
<?php include(INCLUDE_PATH . "/logic/common_functions.php"); ?>
<?php include(ROOT_PATH . '/admin/users/userLogic.php'); ?>

<?php
      $class_id = isset($_SESSION['user']['class_id'])? $_SESSION['user']['class_id']: 0;
      $user_id = isset($_SESSION['user'])? $_SESSION['user']['id']: 0;
      $test_id = isset($_SESSION['user']['test_id'])? $_SESSION['user']['test_id']: 0;
      $test_grade = isset($_SESSION['user']['test_grade'])? $_SESSION['user']['test_grade']: 0;
      $questions_count = isset($_SESSION['questions_count'])? $_SESSION['questions_count']: 0;
      $questions = isset($_SESSION['questions'])? $_SESSION['questions']: [];
      $finished = isset($_SESSION['finished'])? $_SESSION['finished']: 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <!-- Custome styles -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/quiz.css">

</head>
<body>
<?php include(INCLUDE_PATH . "/layouts/navbar.php") ?>
<?php //include(INCLUDE_PATH . "/layouts/messages.php") ?>
<div class="container" style="margin-bottom: 150px;">
    <input type="hidden" name="class_id" value="<?php echo $class_id ?>">
    <input type="hidden" id="test_id" name="test_id" value="<?php echo $test_id ?>">
    <input type="hidden" id="test_grade" name="test_grade" value="<?php echo $test_grade ?>">
    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ?>">
    <input type="hidden" id="base_url" name="base_url" value="<?php echo BASE_URL; ?>">
    <div id="questions" style="display: none;">
        <?php echo $questions ?>
    </div>
<!--    <div class="row">-->
<!--        <div class="col-md-4 col-md-offset-4">-->
<!--            <a href="../user_dashboard.php" class="btn btn-primary" style="margin-bottom: 5px;">-->
<!--                <span class="glyphicon glyphicon-chevron-left"></span>-->
<!--            </a>-->
<!--        </div>-->
<!--    </div>-->
<!--    --><?php //echo $finished ;?>
    <div class="row">
        <div class="exam_status">
            <?php if ($finished == 0): ?>
                <?php if (count(json_decode($questions)) >0): ?>
                    <div id="quiz">
                        <h1 id="quiz-name"></h1>
                        <button type="submit" name="answer_exam" id="submit-button">Submit Answers</button>
                        <button id="next-question-button">Next Question</button>
                        <button id="prev-question-button">Previous</button>

                        <div id="quiz-results">

                            <p id="quiz-results-message"></p>
                            <p id="quiz-results-score"></p>
                            <button id="quiz-report-button" type="submit" name="quiz-report-button">Report</button>

                        </div>
                    </div>
                <?php else: ?>
                    <h2 class="text-center">There is no Questions added to Your Class</h2>
                <?php endif ?>
            <?php endif; ?>
        </div>
        <?php if ($finished == 1): ?>
            <h2 class="text-center exam_status_msg">You answered the questions before</h2>
        <?php endif ?>
    </div>

</div>
<?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
<script type="application/javascript" src="../../assets/js/quiz.js"></script>
</body>
</html>