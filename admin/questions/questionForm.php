<?php include('../../config.php') ?>
<?php require_once '../middleware.php'; ?>
<?php include(ROOT_PATH . '/includes/logic/common_functions.php') ?>
<?php include(ROOT_PATH . '/admin/questions/questionLogic.php') ?>

<?php   $tests = getAllTests(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Create new question </title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
  <!-- Custom styles -->
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <?php include(INCLUDE_PATH . "/layouts/admin_navbar.php") ?>
  <div class="col-md-8 col-md-offset-2">
      <a href="questionList.php" class="btn btn-primary">
        <span class="glyphicon glyphicon-chevron-left"></span>
        Questions
      </a>
      <hr>
      <form class="form" action="questionForm.php" method="post">
        <?php if ($isEditting === true): ?>
          <h1 class="text-center">Update Question</h1>
        <?php else: ?>
          <h1 class="text-center">Create Question</h1>
        <?php endif; ?>
        <br />

        <?php if ($isEditting === true): ?>
          <input type="hidden" name="question_id" value="<?php echo $question_id ?>">
        <?php endif; ?>
        <div class="form-group <?php echo isset($errors['name']) ? 'has-error': '' ?>">
          <label class="control-label">Question</label>
            <textarea name="name" value="<?php echo $name; ?>"  rows="3" cols="10" class="form-control"><?php echo $name; ?></textarea>
          <?php if (isset($errors['name'])): ?>
            <span class="help-block"><?php echo $errors['name'] ?></span>
          <?php endif; ?>
        </div>
          <div class="form-group <?php echo isset($errors['choice1']) ? 'has-error': '' ?>">
              <label class="control-label">Choice1</label>
              <input type="text" name="choice1" value="<?php echo $choice1; ?>" class="form-control">
              <?php if (isset($errors['choice1'])): ?>
                  <span class="help-block"><?php echo $errors['choice1'] ?></span>
              <?php endif; ?>
          </div>
          <div class="form-group <?php echo isset($errors['choice2']) ? 'has-error': '' ?>">
              <label class="control-label">Choice2</label>
              <input type="text" name="choice2" value="<?php echo $choice2; ?>" class="form-control">
              <?php if (isset($errors['choice2'])): ?>
                  <span class="help-block"><?php echo $errors['choice2'] ?></span>
              <?php endif; ?>
          </div>
          <div class="form-group <?php echo isset($errors['choice3']) ? 'has-error': '' ?>">
              <label class="control-label">Choice3</label>
              <input type="text" name="choice3" value="<?php echo $choice3; ?>" class="form-control">
              <?php if (isset($errors['choice3'])): ?>
                  <span class="help-block"><?php echo $errors['choice3'] ?></span>
              <?php endif; ?>
          </div>
          <div class="form-group <?php echo isset($errors['choice4']) ? 'has-error': '' ?>">
              <label class="control-label">Choice4</label>
              <input type="text" name="choice4" value="<?php echo $choice4; ?>" class="form-control">
              <?php if (isset($errors['choice4'])): ?>
                  <span class="help-block"><?php echo $errors['choice4'] ?></span>
              <?php endif; ?>
          </div>
          <div class="form-group <?php echo isset($errors['correct_answer']) ? 'has-error': '' ?>">
              <label class="control-label">Correct answer</label>
              <input type="text" name="correct_answer" value="<?php echo $correct_answer; ?>" class="form-control">
              <?php if (isset($errors['correct_answer'])): ?>
                  <span class="help-block"><?php echo $errors['correct_answer'] ?></span>
              <?php endif; ?>
          </div>
          <div class="form-group <?php echo isset($errors['ques_grade']) ? 'has-error': '' ?>">
              <label class="control-label">Grade</label>
              <input type="text" name="ques_grade" value="<?php echo $ques_grade; ?>" class="form-control">
              <?php if (isset($errors['ques_grade'])): ?>
                  <span class="help-block"><?php echo $errors['ques_grade'] ?></span>
              <?php endif; ?>
          </div>
          <div class="form-group <?php echo isset($errors['test_id']) ? 'has-error' : '' ?>">
              <label class="control-label">Test</label>
              <select class="form-control" name="test_id">
                  <option value="" >Select Test</option>
                  <?php foreach ($tests as $test): ?>
                      <?php if ($test['id'] == $test_id): ?>
                          <option value="<?php echo $test['id'] ?>" selected><?php echo $test['test_name'] ?></option>
                      <?php else: ?>
                          <option value="<?php echo $test['id'] ?>"><?php echo $test['test_name'] ?></option>
                      <?php endif; ?>
                  <?php endforeach; ?>
              </select>
              <?php if (isset($errors['test_id'])): ?>
                  <span class="help-block"><?php echo $errors['test_id'] ?></span>
              <?php endif; ?>
          </div>
        <div class="form-group">
          <?php if ($isEditting === true): ?>
            <button type="submit" name="update_question" class="btn btn-primary">Update question</button>
          <?php else: ?>
            <button type="submit" name="save_question" class="btn btn-success">Save question</button>
          <?php endif; ?>
        </div>
      </form>
  </div>
  <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
</body>
</html>