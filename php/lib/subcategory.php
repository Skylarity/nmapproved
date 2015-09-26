<?php
require_once($PREFIX . "php/classes/autoload.php");
//require_once($PREFIX . "php/classes/business.php");
require_once("/etc/apache2/mysql/encrypted-config.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$pdo = connectToEncryptedMySQL("/etc/apache2/mysql/nmapproved.ini");

$subcategory = Subcategory::getSubcategoryByName($pdo, $_GET["subcategory"]);
$subcategoryName = $subcategory->getName();
$subcategoryId = $subcategory->getSubCategoryId();
$businesses = Business::getBusinessesByString($pdo, "categoryId", $subcategoryId);

?>
<div class="subcategory">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="cat-title"><?php echo ucwords($subcategoryName); ?></h1>
			</div>
		</div>
		<?php
		if(count($businesses) > 0) {
			foreach($businesses as $business) {
				$businessName = ucwords($business->getName());
				$businessLocation = $business->getLocation();
				$businessPhone = $business->getPhone();
				$businessWebsite = $business->getWebsite();
				$businessEmail = $business->getEmail();

				$descriptions = Description::getDescriptionByBusinessId($pdo, $business->getBusinessId());

				require($PREFIX . "php/lib/business-listing.php");
			}
		} else {
			echo "<p>No businesses found.</p>";
		}
		?>
	</div>
</div>
</div>