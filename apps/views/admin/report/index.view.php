<div class="padding">
	<?php foreach( $this->data['report'] as $row ){
		echo '<div class="row">';
		foreach( $row as $col ){ ?>
			<div class="col-sm-12 col-md-4">
				<div class="box">
					<div class="box-header">
						<span class="label success pull-right"><?php echo $col['count']?></span>
						<h3><?php echo $col['title']?></h3>
						<small><?php echo $col['subtitle']?></small>
					</div>
					<div class="box-body">
						<div class="streamline b-l m-b m-l">
							<?php foreach( $col['list'] as $list ){?>
							<div class="sl-item">
								<div class="sl-left">
									<img src="/assets/themeforest/assets/images/p0.jpg" class="img-circle">
								</div>
								<div class="sl-content">
									<a href class="text-info"><?php echo $list['title']?></a>
									<span class="m-l-sm sl-date label success"><?php echo $list['count']?></span>
									<div class="sl-date"><?php echo $this->trans->get('last_update')?> <?php echo $list['ts']?></div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php }
		echo '</div>';
	} ?>
	</div>
</div>