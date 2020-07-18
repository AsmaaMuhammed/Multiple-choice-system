<?php require_once '../../config.php'; ?>
<?php require_once '../middleware.php'; ?>
<?php include(INCLUDE_PATH . "/logic/common_functions.php"); ?>

<?php
$class_id = isset($_SESSION['user'])? $_SESSION['user']['class_id']: 0;
$user_id = isset($_SESSION['user'])? $_SESSION['user']['id']: 0;
$test_id = isset($_SESSION['user'])? $_SESSION['user']['test_id']: 0;
$test_grade = isset($_SESSION['user'])? $_SESSION['user']['test_grade']: 0;
$questions = isset($_SESSION['questions'])? $_SESSION['questions']: [];
$answers =   isset($_GET['user_answers'])?explode(',',$_GET['user_answers']):[];
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
<style>

</style>
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

    <div class="row">
        <div id="quiz">
            <?php if(isset($_GET['user_answers'])):?>
                <?php foreach(json_decode($questions) as $i=>$question):?>

                    <div id="question">
                        <h2><?php echo $question->question_string; ?></h2>
                        <input id="choices-<?php echo $i;?>" type="radio" name="choices-<?php echo $i;?>" value="<?php echo $question->choices->correct;?>"><label style="background-color: darkseagreen;!important;" for="choices-0"><?php echo $question->choices->correct;?></label>
                        <?php foreach($question->choices->wrong as $j=>$wrong):?>

                            <?php if ($wrong == $answers[$i] && $answers[$i]!= $question->choices->correct):?>
                                 <input id="choices-<?php echo $j;?>" type="radio" name="choices-<?php echo $j;?>" value="<?php echo $wrong;?>"><label style="background-color: orangered;!important;" for="choices-0"><?php echo $wrong;?></label>
                            <?php else: ?>
                                <input id="choices-<?php echo $j;?>" type="radio" name="choices-<?php echo $j;?>" value="<?php echo $wrong;?>"><label for="choices-0"><?php echo $wrong;?></label>
                            <?php endif;?>
                        <?php endforeach;?>
                    </div>

                <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>

</div>
<?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
<!--<script type="application/javascript" src="../../assets/js/quiz.js"></script>-->
</body>
</html>