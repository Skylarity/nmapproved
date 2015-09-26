<?php
require_once($PREFIX . "php/classes/autoload.php");
//require_once($PREFIX . "php/classes/business.php");
require_once("/etc/apache2/mysql/encrypted-config.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$pdo = connectToEncryptedMySQL("/etc/apache2/mysql/nmapproved.ini");

$_SESSION["businesses"] = Business::getBusinessesByString($pdo, "categoryId", $_SESSION["category"]);

/* /TESTING */

?>
<div class="subcategory">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="cat-title"><?php echo ucwords($_GET["subcategory"]); ?></h1>
			</div>
		</div>
		<?php
		$businesses = $_SESSION["businesses"];

		if(count($businesses) > 0) {
			foreach($businesses as $business) {
				$businessName = ucwords($business->getName());
				$businessLocation = $business->getLocation();
				require($PREFIX . "php/lib/business-listing.php");
			}
		} else {
			echo "<p>No businesses found.</p>";
		}
		?>
	</div>
</div>
</div>