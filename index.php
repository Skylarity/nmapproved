<?php
$CURRENT_DIR = __DIR__;
require_once("php/lib/head-utils.php");
?>
<!-- Facebook stuff -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if(d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<!-- /Facebook stuff -->
<div class="sfooter-content">
    <?php require_once("php/lib/header.php"); ?>
    <?php require_once("php/lib/info.php"); ?>
    <?php require_once("php/lib/eat-shop-play.php"); ?>
</div>
<?php require_once("php/lib/footer.php"); ?>
</body>
</html>