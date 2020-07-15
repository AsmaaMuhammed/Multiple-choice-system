<?php include('../../config.php') ?>
<?php require_once '../middleware.php'; ?>
<?php include(ROOT_PATH . '/admin/tests/testLogic.php') ?>
<?php include(ROOT_PATH . '/includes/logic/common_functions.php') ?>
<?php
  $tests = getAllTests();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Area - Tests </title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
  <!-- Custome styles -->
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <?php include(INCLUDE_PATH . "/layouts/admin_navbar.php") ?>
  <div class="col-md-8 col-md-offset-2">
    <a href="testForm.php" class="btn btn-success">
      <span class="glyphicon glyphicon-plus"></span>
      Create new test
    </a>
    <hr>
    <h1 class="text-center">Tests</h1>
    <br />
    <?php if (isset($tests)): ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>N</th>
            <th>Test name</th>
            <th>Test Class</th>
            <th>Test grade</th>
            <th>Questions</th>
            <th colspan="3" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tests as $key => $value): ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td><?php echo $value['test_name'] ?></td>
              <td><?php echo $value['class_name'] ?></td>
              <td><?php echo $value['test_grade'] ?></td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/questions/questionList.php?test=<?php echo $value['id'] ?>" class="btn btn-sm btn-info">
                  questions
                </a>
              </td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/tests/testForm.php?edit_test=<?php echo $value['id'] ?>" class="btn btn-sm btn-success">
                  <span class="glyphicon glyphicon-pencil"></span>
                </a>
              </td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/tests/testForm.php?delete_test=<?php echo $value['id'] ?>" class="btn btn-sm btn-danger">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <h2 class="text-center">No tests in database</h2>
    <?php endif; ?>
  </div>
  <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
</body>
</html>
