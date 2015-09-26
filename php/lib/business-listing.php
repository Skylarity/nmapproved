<div class="row business-listing">
	<div class="col-md-12">
		<!--		<div class="col-md-3">-->
		<!--			<div>Image goes here.</div>-->
		<!--		</div>-->
		<!--		<div class="col-md-9">-->
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-12">
							<h3 class="bus-h3"><?php echo $businessName; ?></h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php
							foreach($descriptions as $description) {
								$text = $description->getDescription();
								echo "<p>$text</p>";
							}
							?>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<span class="bus-info"><?php echo $businessPhone; ?></span>
					<br/>
					<span class="bus-info"><?php echo $businessLocation; ?></span>
					<br/>
					<span class="bus-info"><?php echo $businessWebsite; ?></span>
				</div>
			</div>
		</div>
	</div>
</div>