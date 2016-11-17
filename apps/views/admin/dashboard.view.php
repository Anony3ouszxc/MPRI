<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">
				<small><?php echo $this->trans->get('hello')?> <?php echo $this->data['userInfo']['firstname'] .' ' .$this->data['userInfo']['lastname']?></small> <?php echo $this->trans->get('welcome_to')?> <?php echo $this->params['app_name']?>
			</h4>
			<small class="text-muted"><?php echo $this->trans->get('title_cms')?></small>
		</div>
	</div>
</div>
<?php include('post/new-post.view.php');?>
<!--
<div class="padding">
	<div class="row">
		<div class="col-sm-12 col-md-4">
			<div class="box">
				<div class="box-header">
					<span class="label success pull-right"><?php echo $this->data['count']['publish']?></span>
					<h3><?php echo $this->trans->get('latest_news')?></h3>
					<small><?php echo $this->trans->get('top5_publish')?></small>
				</div>
				<div class="box-body">
					<div class="streamline b-l m-b m-l">
						<?php foreach( $this->data['publish'] as $activity ){?>
						<div class="sl-item">
							<div class="sl-left">
								<img src="/assets/themeforest/assets/images/a2.jpg" class="img-circle">
							</div>
							<div class="sl-content">
								<a href class="text-info"><?php echo $activity->firstname." ".$activity->lastname?></a>
								<span class="m-l-sm sl-date"><?php echo $activity->ts?></span>
								<div><?php echo $activity->message?></div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-4">
			<div class="box">
				<div class="box-header">
					<span class="label success pull-right"><?php echo $this->data['count']['activity']?></span>
					<h3><?php echo $this->trans->get('latest_modify')?></h3>
					<small><?php echo $this->trans->get('top5_activity')?></small>
				</div>
				<div class="box-body">
					<div class="streamline b-l m-b m-l">
						<?php foreach( $this->data['activity'] as $activity ){?>
						<div class="sl-item">
							<div class="sl-left">
								<img src="/assets/themeforest/assets/images/a2.jpg" class="img-circle">
							</div>
							<div class="sl-content">
								<a href class="text-info"><?php echo $activity->firstname." ".$activity->lastname?></a>
								<span class="m-l-sm sl-date"><?php echo $activity->ts?></span>
								<div><?php echo $activity->message?></div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-4">
			<div class="box">
				<div class="box-header">
					<span class="label success pull-right"><?php echo $this->data['count']['login']?></span>
					<h3><?php echo $this->trans->get('latest_logged')?></h3>
					<small><?php echo $this->trans->get('top5_login')?></small>
				</div>
				<div class="box-body">
					<div class="streamline b-l m-b m-l">
						<?php foreach( $this->data['logged'] as $logged ){?>
						<div class="sl-item">
							<div class="sl-left">
								<img src="/assets/themeforest/assets/images/a2.jpg" class="img-circle">
							</div>
							<div class="sl-content">
								<a href class="text-info"><?php echo $logged->message?></a>
								<span class="m-l-sm sl-date"><?php echo $logged->ts?></span>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
 -->