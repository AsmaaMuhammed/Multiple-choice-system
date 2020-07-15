<?php include("config.php") ?>
<?php include(INCLUDE_PATH . "/logic/common_functions.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Home</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
  <!-- Custome styles -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/quiz.css">

</head>
<body>
    <?php include(INCLUDE_PATH . "/layouts/navbar.php") ?>
    <?php include(INCLUDE_PATH . "/layouts/messages.php") ?>

    <div id="quiz">
        <h1 id="quiz-name"></h1>
        <button id="submit-button">Submit Answers</button>
        <button id="next-question-button">Next Question</button>
        <button id="prev-question-button">Previous</button>

        <div id="quiz-results">

            <p id="quiz-results-message"></p>
            <p id="quiz-results-score"></p>
            <button id="quiz-retry-button">Retry</button>

        </div>
    </div>
    <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
    <script type="application/javascript" src="assets/js/quiz.js"></script>
</body>
</html>    