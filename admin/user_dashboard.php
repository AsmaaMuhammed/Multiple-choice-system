<?php include('../config.php') ?>
<?php include(ROOT_PATH . '/admin/middleware.php') ?>
<?php include(INCLUDE_PATH . "/logic/common_functions.php"); ?>
<?php
$userPermissions = isset($_SESSION['userPermissions'])? $_SESSION['userPermissions']: [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <!-- Custome styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include(INCLUDE_PATH . "/layouts/navbar.php") ?>

<div class="col-md-4 col-md-offset-4">
    <h1 class="text-center">User</h1>
    <br />
    <ul class="list-group">

        <?php foreach ($userPermissions as $key => $value): ?>
            <?php
            $folder = explode('_',$value['permission_method'])[0].'s';
            $file = str_replace('_','',$value['permission_method']).'.php';
            ?>
            <a href="<?php echo BASE_URL . 'admin/'.$folder.'/'.$file ?>" class="list-group-item"><?php echo $value['permission_name'] ?></a>
        <?php endforeach; ?>
    </ul>
</div>
<?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
</body>
</html>