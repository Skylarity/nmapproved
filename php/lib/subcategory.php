<?php
require_once($PREFIX . "php/classes/autoload.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* TESTING */

$_SESSION["businesses"] = ["wow", "these", "are", "businesses", "good", "job", "you", "sure", "did", "it", "wow"];

/* /TESTING */

?>
<div class="subcategory">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="cat-title"><?php echo "Title"; ?></h1>
            </div>
        </div>
        <?php
        $businesses = $_SESSION["businesses"];

        foreach($businesses as $business) {
            $business = ucwords($business);
            require($PREFIX . "php/lib/business-listing.php");
        }
        ?>
    </div>
</div>
</div>