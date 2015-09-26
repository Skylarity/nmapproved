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

if(isset($_POST["submit"])) {

	$pdo = connectToEncryptedMySQL("/etc/apache2/mysql/nmapproved.ini");

	/* BUSINESS */
	$name = $_POST["business"];
	$location = $_POST["location"];
	$phone = $_POST["phone"];
	$website = $_POST["website"];
	$email = $_POST["email"];
	$category = strtolower($_POST["category"]);
	$subcategory = strtolower($_POST["subcategory"]);

	$categoryObj = Category::getCategoryByCategoryName($pdo, $category);

	$subcategoryObj = Subcategory::getSubcategoryByName($pdo, $subcategory);
	if($subcategoryObj === null) {
		$subcategoryObj = new Subcategory(null, $categoryObj->getCategoryId(), $subcategory);
	}
	$subcategoryObj->insert($pdo);

	$business = new Business(null, $name, $location, $phone, $website, $email, $subcategoryObj->getSubCategoryId());
	$business->insert($pdo);

	/* IMAGES */
	$uploadPath = $PREFIX . "uploads";
	$date = new DateTime();
	$uploadFile = $uploadPath . "upload" . $date->format("U") . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

	echo '<pre>';
	if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
		echo "File is valid, and was successfully uploaded." . PHP_EOL;
	} else {
		echo "Possible file upload attack!" . PHP_EOL;
	}

	echo 'Here is some more debugging info:';
	print_r($_FILES);

	print "</pre>";
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
			<div class="row">
				<form enctype="multipart/form-data" class="form-horizontal" role="form" method="post"
					  action="<?php echo $PREFIX; ?>pages/adminpanel.php">
					<div class="col-sm-6 col-sm-offset-3">

						<div class="form-group">
							<label for="business">Business</label>
							<input type="text" class="form-control" id="business" name="business"
								   placeholder="Business">
						</div>

						<div class="form-group">
							<label for="location">Location</label>
							<input type="text" class="form-control" id="location" name="location"
								   placeholder="Location">
						</div>

						<div class="form-group">
							<label for="phone">Phone</label>
							<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
						</div>

						<div class="form-group">
							<label for="website">Website</label>
							<input type="text" class="form-control" id="website" name="website" placeholder="Website">
						</div>

						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Email">
						</div>

						<div class="form-group">
							<label for="category">Category (e.g. eat, shop, play)</label>
							<input type="text" class="form-control" id="category" name="category"
								   placeholder="Category">
						</div>

						<div class="form-group">
							<label for="subcategory">Subcategory (e.g. bar, shoestore, theme park)</label>
							<input type="text" class="form-control" id="subcategory" name="subcategory"
								   placeholder="Subcategory">
						</div>

						<div class="form-group">
							<label for="image">Image</label>
							<!-- MAX_FILE_SIZE must precede the file input field -->
							<input type="hidden" name="MAX_FILE_SIZE" value="30000"/>
							<!-- Name of input element determines name in $_FILES array -->
							<input type="file" class="form-control" id="subcategory" name="image"/>
						</div>

						<div class="form-group">
							<input id="submit" name="submit" type="submit" value="Add business"
								   class="btn btn-default center-block">
						</div>
					</div>
				</form>
			</div>
			<div class="row">
				<?php
				if(isset($_POST["submit"])) {
					echo "<p class=\"alert alert-success\" role=\"alert\">{$_POST["business"]} has been submitted.</p>";
				}
				?>
			</div>
		</div>
	</body>
</html>