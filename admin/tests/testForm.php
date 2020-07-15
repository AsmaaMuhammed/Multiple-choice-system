<?php include('../../config.php') ?>
<?php require_once '../middleware.php'; ?>
<?php include(ROOT_PATH . '/includes/logic/common_functions.php') ?>
<?php include(ROOT_PATH . '/admin/tests/testLogic.php') ?>
<?php $classes = getAllClasses(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Create new test </title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
  <!-- Custom styles -->
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <?php include(INCLUDE_PATH . "/layouts/admin_navbar.php") ?>
  <div class="col-md-8 col-md-offset-2">
      <a href="testList.php" class="btn btn-primary">
        <span class="glyphicon glyphicon-chevron-left"></span>
        Tests
      </a>
      <hr>
      <form class="form" action="testForm.php" method="post">
        <?php if ($isEditting === true): ?>
          <h1 class="text-center">Update Test</h1>
        <?php else: ?>
          <h1 class="text-center">Create Test</h1>
        <?php endif; ?>
        <br />

        <?php if ($isEditting === true): ?>
          <input type="hidden" name="test_id" value="<?php echo $test_id ?>">
        <?php endif; ?>
        <div class="form-group <?php echo isset($errors['name']) ? 'has-error': '' ?>">
          <label class="control-label">Test name</label>
          <input type="text" name="name" value="<?php echo $name; ?>" class="form-control">
          <?php if (isset($errors['name'])): ?>
            <span class="help-block"><?php echo $errors['name'] ?></span>
          <?php endif; ?>
        </div>
        <div class="form-group <?php echo isset($errors['description']) ? 'has-error': '' ?>">
          <label class="control-label">Description</label>
          <textarea name="description" value="<?php echo $description; ?>"  rows="3" cols="10" class="form-control"><?php echo $description; ?></textarea>
          <?php if (isset($errors['description'])): ?>
            <span class="help-block"><?php echo $errors['description'] ?></span>
          <?php endif; ?>
        </div>
          <div class="form-group <?php echo isset($errors['class_id']) ? 'has-error' : '' ?>">
              <label class="control-label">Test Class</label>
              <select class="form-control" name="class_id">
                  <option value="" ></option>
                  <?php foreach ($classes as $class): ?>
                      <?php if ($class['id'] === $class_id): ?>
                          <option value="<?php echo $class['id'] ?>" selected><?php echo $class['name'] ?></option>
                      <?php else: ?>
                          <option value="<?php echo $class['id'] ?>"><?php echo $class['name'] ?></option>
                      <?php endif; ?>
                  <?php endforeach; ?>
              </select>
              <?php if (isset($errors['class_id'])): ?>
                  <span class="help-block"><?php echo $errors['class_id'] ?></span>
              <?php endif; ?>
          </div>
          <div class="form-group <?php echo isset($errors['test_grade']) ? 'has-error': '' ?>">
              <label class="control-label">Grade</label>
              <input type="text" name="test_grade" value="<?php echo $test_grade; ?>" class="form-control">
              <?php if (isset($errors['test_grade'])): ?>
                  <span class="help-block"><?php echo $errors['test_grade'] ?></span>
              <?php endif; ?>
          </div>
        <div class="form-group">
          <?php if ($isEditting === true): ?>
            <button type="submit" name="update_test" class="btn btn-primary">Update Test</button>
          <?php else: ?>
            <button type="submit" name="save_test" class="btn btn-success">Save Test</button>
          <?php endif; ?>
        </div>
      </form>
  </div>
  <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
</body>
</html>