<?php
require_once($PREFIX . "php/classes/autoload.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* TESTING */

$_SESSION["subcategory"] = "bakery";
$bus1 = new Business(null, "Test 1", "PLace St. 1234", "5055555555", "website.com", "email@website.com", "eat", "bakery");
$bus2 = new Business(null, "Test 2", "PLace St. 1234", "5057777777", "website.com", "email@website.com", "eat", "bakery");
$bus3 = new Business(null, "Test 3", "PLace St. 1234", "5059999999", "website.com", "email@website.com", "eat", "bakery");
var_dump($bus1);
$_SESSION["businesses"] = [$bus1, $bus2, $bus3];

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