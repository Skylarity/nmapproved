<?php
require_once($PREFIX . "php/classes/autoload.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* TESTING */

$_SESSION["subcategories"] = ["wow", "these", "are", "tests", "good", "job", "you", "sure", "did", "it", "wow"];

/* /TESTING */

?>
<div class="category">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="cat-title"><?php echo "Title"; ?></h1>
            </div>
        </div>
        <?php
        $subcategories = $_SESSION["subcategories"];

        $i = 1;
        echo "<div class=\"row\">" . PHP_EOL;
        foreach($subcategories as $subcategory) {
            $subcategory = ucwords($subcategory);
            if($i % 4 === 0) {
                echo "</div>" . PHP_EOL;
                echo "<div class=\"row\">" . PHP_EOL;
            }
            echo "<div class=\"col-md-3\">" . PHP_EOL;
            require($PREFIX . "php/lib/subcategory-listing.php");
            echo "</div>" . PHP_EOL;
        }
        ?>
    </div>
</div>
</div>