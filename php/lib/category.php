<?php
require_once($PREFIX . "php/classes/autoload.php");
require_once($PREFIX . "php/classes/subcategory.php");
require_once("/etc/apache2/mysql/encrypted-config.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$pdo = connectToEncryptedMySQL("/etc/apache2/mysql/nmapproved.ini");

$categoryId = Category::getCategoryByCategoryName($pdo, $_GET["category"])->getCategoryId();

$_SESSION["subcategories"] = Subcategory::getSubcategoriesByCategoryId($pdo, $categoryId);

?>
<div class="category">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="cat-title"><?php echo ucwords($_GET["category"]); ?></h1>
			</div>
		</div>
		<?php
		$subcategories = $_SESSION["subcategories"];

		if(count($subcategories) > 0) {
			$i = 1;
			echo "<div class=\"row\">" . PHP_EOL;
			foreach($subcategories as $subcategory) {
				$subcategory = $subcategory->getName();
				if($i % 4 === 0) {
					echo "</div>" . PHP_EOL;
					echo "<div class=\"row\">" . PHP_EOL;
				}
				echo "<div class=\"col-md-3\">" . PHP_EOL;
				require($PREFIX . "php/lib/subcategory-listing.php");
				echo "</div>" . PHP_EOL;
			}
		} else {
			echo "<p>No subcategories found.</p>";
		}
		?>
	</div>
</div>
</div>