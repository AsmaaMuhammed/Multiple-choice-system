<?php
	session_start(); // start session
	// connect to database
//define ('SERVER', "localhost");
//define ('USER',  "root");
//define ('PASSWORD',"123");
//define ('DB', "multiple-choice-quiz");
//$conn = new mysqli(SERVER,USER, PASSWORD, DB);
	$conn = new mysqli("localhost", "root", "", "multiple-choice-quiz");
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
  // define global constants
	define ('ROOT_PATH', realpath(dirname(__FILE__))); // path to the root folder
	define ('INCLUDE_PATH', realpath(dirname(__FILE__) . '/includes' )); // Path to includes folder
	define('BASE_URL', 'http://localhost/Multiple-choice-system/'); // the home url of the website
	define('FROM_EMAIL', 'someone@example.com'); // someone@example.com from email



	function getMultipleRecords($sql, $types = null, $params = []) {
		global $conn;
		$stmt = $conn->prepare($sql);
		if (!empty($params)) { // parameters must exist before you call bind_param() method
		  $stmt->bind_param($types, ...$params);
		}
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_all(MYSQLI_ASSOC);
		$stmt->close();
		return $user;
	  }
	  function getSingleRecord($sql, $types, $params) {
		global $conn;
		$stmt = $conn->prepare($sql);
		$stmt->bind_param($types, ...$params);
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		$stmt->close();
		return $user;
	  }
	  function modifyRecord($sql, $types, $params) {
		global $conn;
		$stmt = $conn->prepare($sql);
		$stmt->bind_param($types, ...$params);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	  }
      function modifyMultipleRecord($sql, $types = null, $params = []) {
		global $conn;
		$stmt = $conn->prepare($sql);
		$stmt->bind_param($types, ...$params);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	  }
?>
