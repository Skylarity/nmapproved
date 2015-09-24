<?php
//require_once($PREFIX . "php/classes/autoload.php");
require_once($PREFIX . "php/classes/business.php");
require_once("/etc/apache2/mysql/encrypted-config.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$pdo = connectToEncryptedMySQL("/etc/apache2/mysql/nmapproved.ini");

/* TESTING */

$_SESSION["subcategory"] = "play";

$businessesBySubcat = Business::getBusinessesByString($pdo, "subcategory", $_SESSION["subcategory"]);
//var_dump($businessesBySubcat);
//foreach($businessesBySubcat as $b) {
//	var_dump($b);
//}

$_SESSION["businesses"] = $businessesBySubcat;

/* /TESTING */

?>
<div class="subcategory">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="cat-title"><?php echo ucwords($_SESSION["subcategory"]); ?></h1>
			</div>
		</div>
		<?php
		$businesses = $_SESSION["businesses"];

		foreach($businesses as $business) {
			$businessName = ucwords($business->getName());
			require($PREFIX . "php/lib/business-listing.php");
		}
		?>
	</div>
</div>
</div>