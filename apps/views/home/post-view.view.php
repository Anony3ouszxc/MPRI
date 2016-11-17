<?php
$baseUrl  = $this->data['baseUrl'];
$filepath = isset( $this->data['file']['filepath'] )
			? $baseUrl.$this->data['file']['filepath']
			: '';
if( !file_exists($filepath) ){
	$filepath = '';
}
$pdfpath = isset($this->data['pdf']['filepath'])
		   ? $this->data['pdf']['filepath']
		   : '';
if( !file_exists($pdfpath) ){
	$pdfpath = '';
}
?>
<div class="section padding-small bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li>
						<a href="<?php echo $baseUrl?>"><?php echo $this->trans->get('home')?></a>
					</li>
					<li>
						<a href="<?php echo $baseUrl?>post-group/<?php echo $this->data['post']['category_id']?>"><?php echo $this->data['category']?></a>
					</li>
					<li class="active"><?php echo $this->title?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="section bg-light-grey box-shadow">
	<div class="container <?php echo $this->data['post']['container_class']?>">
		<div class="row">
			<div class="col-sm-12 col-md-9 container_content">
				<h1 class="heading"><?php echo $this->data['title']?></h1>
				<div class="row">
					<div class="col-sm-12 text-muted m-b-xs">
						<span class="m-r"><?php echo $this->thai_date($this->data['post']['ts'])?></span>
						<!-- <div class="fb-share-button" data-href="<?php echo $baseUrl?>post-view/<?php echo $this->data['post']['post_id']?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="<?php echo $baseUrl?>post-view/<?php echo $this->data['post']['post_id']?>"><?php echo $this->trans->get('share')?></a></div> -->
						<a href="#"
                        	data-id="<?php echo $this->data['post']['post_id']?>"
                        	data-url="<?php echo $baseUrl?>post-view/<?php echo $this->data['post']['post_id']?>"
                        	data-title="<?php echo urlencode($this->data['post']['title'])?>"
                        	data-summary="<?php echo urlencode($this->data['post']['detail'])?>"
                        	data-image="<?php echo urlencode($baseUrl.$this->data['file']['filepath'])?>" class="share-fb m-r"><i class="fa -o fa-share-alt"></i> <span id="count-share-<?php echo $this->data['post']['post_id']?>"><?php echo $this->data['post']['share']?></span></a>
                        <a href="#"
                           data-id="<?php echo $this->data['post']['post_id']?>"
                           data-url="<?php echo $baseUrl?>post-view/<?php echo $this->data['post']['post_id']?>" class="like-fb m-r"><i class="fa -o -alt fa-eye"></i> <?php echo $this->data['post']['view']?></a>
					</div>
					<?php if($filepath){?>
					<div>
						<img src="<?php echo $filepath?>" class="w-full">
					</div>
					<?php }?>
					<div class="col-sm-12 content">

						<div><?php echo html_entity_decode($this->data['post']['detail'])?></div>
						<!-- Go to www.addthis.com/dashboard to customize your tools -->
						<div class="addthis_sharing_toolbox"></div>
						<?php if( $pdfpath ){?>
						<iframe src = "/viewer/#../<?php echo $pdfpath?>" width='100%' height='600' allowfullscreen webkitallowfullscreen></iframe>
						<?php }?>
					</div>
				</div>
				<div class="row padding-small">
					<div class="col-sm-6">
						<?php if($this->data['previousId']){?>
							<a href="<?php echo $baseUrl?>post-view/<?php echo $this->data['previousId']?>"><button class="btn btn-outline rounded b-primary text-primary b-2x"><?php echo $this->trans->get('previous')?></button></a>
						<?php }?>
					</div>
					<div class="col-sm-6 text-right">
						<?php if($this->data['nextId']){?>
							<a href="<?php echo $baseUrl?>post-view/<?php echo $this->data['nextId']?>"><button class="btn btn-outline rounded b-primary text-primary b-2x"><?php echo $this->trans->get('next')?></button></a>
						<?php }?>
					</div>
				</div>

				<!-- <div class="row padding-small">
					<div class="m-b-lg col-sm-12">
						<span class="label success pos-rlt m-r-xs"><b class="arrow right b-success pull-in"></b>Tags</span>
						<a href="" class="m-x-xs">Angular</a>
						<a href="" class="m-x-xs">Bootstrap</a>
						<a href="" class="m-x-xs">Web</a>
						<a href="" class="m-x-xs">App</a>
					</div>
				</div> -->
			</div>
			<?php include('interest-list.view.php');?>
		</div>
	</div>
</div>
