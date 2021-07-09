<?php

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	include_once('controller.php');
	include_once('dbConnect.php');
	
	$class = new MainSlideClass();

	// ----------------INSERT PRESENTATION IMAGES-------------------
	if (isset($_GET['presentation']) && $_GET['presentation'] == 'insert') {


		$id = $_POST['id'];
		$subject = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['subject'])));
		$image = $_FILES['image'];

		$class->InsertPresentationImage($id, $subject, $image);

	}
	// ------------------INSERT SLIDE IMAGES------------------------

	if (isset($_GET['slayd']) && $_GET['slayd'] == 'insert') {

       $id = $_POST['id'];
       $image = $_FILES['image'];
       $class->InsertSlideImage($id, $image);
		
	}

	// ----------------SELECT PRESENTATION IMAGES ------------------
	

	if (isset($_GET['presentation']) && $_GET['presentation'] == 'select') {
		
		echo json_encode($class->SelectPresentation());
	}

	// ------------------SELECT SLIE IMAGES -------------------------

	if (isset($_GET['slide']) && $_GET['slide'] == 'select') {

		echo json_encode($class->SelectSlides());
	}

	// ----------------INSERT LINKS ------------------------
	if (isset($_GET['links']) && $_GET['links'] == 'insert' ) {

		$postdata = file_get_contents("php://input");
		$class->InsertLinks($postdata, $conn);
	}


	// -----------------SELECT LINKS ----------------

	if (isset($_GET['links']) && $_GET['links'] == 'select') {

		echo json_encode($class->SelectLinks());
	}
	// ---------------ONLINE ORDERS INSERT -------------------------
	if (isset($_GET['onlineOrder']) && $_GET['onlineOrder'] == 'insert') {

		$postdata = file_get_contents("php://input");
		$class->insertOnlineOrder($postdata, $conn);

	}
	// --------------------SELECT NEW ORDERS ---------------------------

	if (isset($_GET['newOrders']) && $_GET['newOrders'] == 'select') {

		echo json_encode($class->SelectNewOrders());
	}

	// -------------------------ACCEPT NEW ORDERS-----------------------
	if (isset($_GET['newOrder']) && $_GET['newOrder'] == 'accept') {

		$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['id'])));
		echo json_encode($class->AcceptNewOrders($id));
	}

	// ------------------------REMOVE NEW ORDERS --------------------------

	if (isset($_GET['newOrder']) && $_GET['newOrder'] == 'remove') {

		$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['id'])));
		echo json_encode($class->RemoveNewOrders($id));
	}
	// -------------------------- WAITING ORDERS----------------------------

	if (isset($_GET['newOrders']) && $_GET['newOrders'] == 'wait') {

		echo json_encode($class->WaitingOrders());
	}


	if (isset($_GET['newOrder']) && $_GET['newOrder'] == 'ready') {

		$id = mysqli_real_escape_string($conn, htmlspecialchars(trim($_GET['id'])));
	 	$time=time();
		$currentDate = date("Y-m-d",$time);

		echo json_encode($class->ReadyOrders($id, $currentDate));
	}

	// ------------------ PREPARED ORDERS ------------

	if (isset($_GET['newOrder']) && $_GET['newOrder'] == 'prepared' ) {

		echo json_encode($class->PreparedOrders());
	}

	// -----------------------REMOVED ORDERS-------------------------
	if (isset($_GET['newOrders']) && $_GET['newOrders'] == 'remove') {

		echo json_encode($class->RemovedOrders());
	
	}

	// ----------------GET COUNTS---------------------
	// ---------------COUNTS OF NEW ORDERS----------------
	if (isset($_GET['newOrders']) && $_GET['newOrders'] == 'total') {
	
		echo json_encode($class->TotalNewOrders());
	
	}
	// ---------------------COUNTS OF WAITING ORDERS----------------------

	if (isset($_GET['waitingOrders']) && $_GET['waitingOrders'] == 'total') {

		echo json_encode($class->TotalWaitingOrders());		
		
	}
	// -----------------------------COUNTS OF PREPARED ORDERS-------------------

	if (isset($_GET['preparedOrders']) && $_GET['preparedOrders'] == 'total') {

		echo json_encode($class->TotalPreparedOrders());
	
	}

	// ------------------COUNTS OF REMOVED ORDERS---------------------------
	if (isset($_GET['removingOrders']) && $_GET['removingOrders'] == 'total') {

		echo json_encode($class->TotalRemovedOrders());
	
	}


	// -----------------------------------------------------------------------------------
	// --------------------ADD NEW USER --------------------------------------------------
	// -----------------------------------------------------------------------------------

	if (isset($_GET['newUser']) && $_GET['newUser'] == 'insert') {

		$postdata = file_get_contents("php://input");
		echo json_encode($class->insertNewUser($postdata, $conn));
	}
	// ----------------------SELECT USERS-------------------------------------

	if (isset($_GET['newUser']) && $_GET['newUser'] == 'select') {

		echo json_encode($class->fetchAllUsers());

	}
	// ---------------------------------DELETE USERS------------------------
	if (isset($_GET['newUser']) && $_GET['newUser'] == 'delete') {

		$id = $_GET['id'];
		echo json_encode($class->deleteUser($id));

	}
	// -----------------------------------------------------------------
	// --------------------LOGIN AUTHENTICATION-------------------------
	// -----------------------------------------------------------------

	if (isset($_GET['login']) && $_GET['login'] == 'ok') {

		$postdata = file_get_contents("php://input");
		echo json_encode($class->login($postdata, $conn));
	}



?>