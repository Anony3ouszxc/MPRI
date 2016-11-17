<?php $baseUrl = $this->data['baseUrl']?>
<div class="section padding-small bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="<?php echo $baseUrl?>"><?php echo $this->trans->get('home')?></a></li>
					<li class="active"><?php echo $this->data['title']?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="section padding-small bg-white">
	<div class="container <?php echo $this->data['custom_css']?>">
		<?php foreach( $this->data['content'] as $row ){?>
			<div class="row">
				<?php foreach( $row['config'] as $index => $_layout){?>
					<div class="col-md-<?php echo $_layout?>">
						<?php echo html_entity_decode($row['content'][$index])?>
					</div>
				<?php } ?>
			</div>
		<?php }?>
		<?php if( $this->data['pdffile'] ){?>
		<iframe src = "/viewer/#../<?php echo $this->data['pdffile']?>" width='100%' height='600' allowfullscreen webkitallowfullscreen></iframe>
		<?php } ?>
	</div>
</div>