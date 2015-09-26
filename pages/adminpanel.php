<?php
require_once(dirname(__DIR__) . "/php/classes/autoload.php");
require_once("/etc/apache2/mysql/encrypted-config.php");

/**
 * Get the relative path.
 * @see https://raw.githubusercontent.com/kingscreations/farm-to-you/master/php/lib/header.php FarmToYou Header
 **/
require_once(dirname(__DIR__) . "/root-path.php");
$CURRENT_DEPTH = substr_count(__DIR__, "/");
$ROOT_DEPTH = substr_count($ROOT_PATH, "/");
$DEPTH_DIFFERENCE = $CURRENT_DEPTH - $ROOT_DEPTH;
$PREFIX = str_repeat("../", $DEPTH_DIFFERENCE);

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

/* BEGIN FORM SUBMISSION STUFF */

if(isset($_POST["business"])) {

	$pdo = connectToEncryptedMySQL("/etc/apache2/mysql/nmapproved.ini");

	$name = $_POST["business"];
	$location = $_POST["location"];
	$phone = $_POST["phone"];
	$website = $_POST["website"];
	$email = $_POST["email"];
	$category = $_POST["category"];
	$subcategory = $_POST["subcategory"];

	$categoryObj = new Category(null, $category);
	$categoryObj->insert($pdo);

	$subcategoryObj = new Subcategory(null, $categoryObj->getCategoryId(), $subcategory);
	$subcategoryObj->insert($pdo);

	$business = new Business(null, $name, $location, $phone, $website, $email, $subcategoryObj->getSubCategoryId());
	$business->insert($pdo);
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>
		<link type="text/css" href="<?php echo $PREFIX; ?>css/admin.css" rel="stylesheet"/>
	</head>
	<body>
		<header>
			<div class="container">
				<h3 style="text-align: center">Admin-Panel</h3>
			</div>
		</header>

		<div class="container">
			<form class="form-horizontal" role="form" method="post" action="<?php echo $PREFIX; ?>pages/adminpanel.php">
				<div class="col-sm-6 col-sm-offset-3">

					<div class="form-group">
						<label for="business">Business</label>
						<input type="text" class="form-control" id="business" placeholder="Business">
					</div>

					<div class="form-group">
						<label for="location">Location</label>
						<input type="text" class="form-control" id="location" placeholder="Location">
					</div>

					<div class="form-group">
						<label for="phone">Phone</label>
						<input type="text" class="form-control" id="phone" placeholder="Phone">
					</div>

					<div class="form-group">
						<label for="website">Website</label>
						<input type="text" class="form-control" id="website" placeholder="Website">
					</div>

					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" class="form-control" id="email" placeholder="Email">
					</div>

					<div class="form-group">
						<label for="category">Category</label>
						<input type="text" class="form-control" id="category" placeholder="Category">
					</div>

					<div class="form-group">
						<label for="subcategory">Subcategory</label>
						<input type="text" class="form-control" id="subcategory" placeholder="Subcategory">
					</div>

					<div class="form-group">
						<input id="submit" name="submit" type="submit" value="Send" class="btn btn-default">
					</div>
				</div>
			</form>
		</div>
	</body>
</html>