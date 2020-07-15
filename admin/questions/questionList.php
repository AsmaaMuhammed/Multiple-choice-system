<?php include('../../config.php') ?>
<?php require_once '../middleware.php'; ?>
<?php include(ROOT_PATH . '/admin/questions/questionLogic.php') ?>
<?php include(ROOT_PATH . '/includes/logic/common_functions.php') ?>
<?php
  $questions = getAllQuestions();
  $tests = getAllTests();
  $test_id = 0;
    if (isset($_GET['test'])) {
        $test_id = $_GET['test'];
    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Area - Questions </title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
  <!-- Custome styles -->
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <?php include(INCLUDE_PATH . "/layouts/admin_navbar.php") ?>
  <div class="col-md-8 col-md-offset-2">
    <a href="questionForm.php" class="btn btn-success">
      <span class="glyphicon glyphicon-plus"></span>
      Create new question
    </a>

      <a href="<?php echo BASE_URL ?>admin/questions/questionList.php" class="btn btn-primary">
          <span class="glyphicon glyphicon-chevron-left">All Questions</span>
      </a>
      <br><br>


      <div class="row">
          <div class="col-md-4">
              <select class="form-control select-test" name="test_id" data-url = "<?php echo BASE_URL ?>admin/questions/questionList.php?test=">
                  <option value="" >Select Test</option>
                  <?php foreach ($tests as $test): ?>
                      <?php if ($test['id'] == $test_id): ?>
                          <option value="<?php echo $test['id'] ?>" selected><?php echo $test['test_name']  ?></option>
                      <?php else: ?>
                          <option value="<?php echo $test['id'] ?>"><?php echo $test['test_name'] ?></option>
                      <?php endif; ?>
                  <?php endforeach; ?>
              </select>
          </div>
      </div>
    <hr>
    <h1 class="text-center">Questions</h1>
    <br />
    <?php if (isset($questions)): ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>N</th>
            <th colspan="3">Question</th>
            <th>choice1</th>
            <th>choice2</th>
            <th>choice3</th>
            <th>choice4</th>
            <th>Correct</th>
            <th>Grade</th>
            <th>Test</th>
            <th colspan="3" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($questions as $key => $value): ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td colspan="3"><?php echo $value['question_name'] ?></td>
              <td><?php echo $value['choice1'] ?></td>
              <td><?php echo $value['choice2'] ?></td>
              <td><?php echo $value['choice3'] ?></td>
              <td><?php echo $value['choice4'] ?></td>
              <td><?php echo $value['correct_answer'] ?></td>
              <td><?php echo $value['ques_grade'] ?></td>
              <td><?php echo $value['test_name'] ?></td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/questions/questionForm.php?edit_question=<?php echo $value['id'] ?>" class="btn btn-sm btn-success">
                  <span class="glyphicon glyphicon-pencil"></span>
                </a>
              </td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/questions/questionForm.php?delete_question=<?php echo $value['id'] ?>" class="btn btn-sm btn-danger">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <h2 class="text-center">No Questions in database</h2>
    <?php endif; ?>
  </div>
  <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
<script>
    $('.select-test').change(function () {
        var testId = $(this).val();

        var url = $(this).attr('data-url');
        window.location.replace(url+ testId);
    });
</script>
</body>
</html>
