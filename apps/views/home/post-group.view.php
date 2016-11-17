<?php
use Apps\Models\Files;
$baseUrl = $this->data['baseUrl'];
$data    = $this->data['record'];?>
<div class="section padding-small bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li>
						<a href="<?php echo $baseUrl?>"><?php echo $this->trans->get('home')?></a>
					</li>
					<li class="active"><?php echo $this->title?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="section bg-light-grey box-shadow">
	<div class="container <?php echo $this->data['category']['container_class']?>">
		<div class="row">
			<div class="col-sm-12 col-md-9">
				<h1 class="heading"><?php echo $this->title?></h1>
				<div><?php echo html_entity_decode($this->data['category']['top_desc'])?></div>
				<div class="row">
					<?php foreach( $data as $row ){
						if( $row->file_id ){
							$file      = Files::find( $row->file_id );
							$thumbpath = isset($file->attributes['thumbpath']) ? $baseUrl . $file->attributes['thumbpath'] : $baseUrl . 'images/thumb-image.jpg';
						} else {
							$thumbpath = $baseUrl . 'images/thumb-image.jpg';
						}
					?>
					<div class="col-xs-6 col-md-4">
						<div class="box">
							<div class="item dark">
								<a href="<?php echo $baseUrl.'post-view/'.$row->post_id?>"><img src="<?php echo $thumbpath?>" class="w-full"></a>
								<div class="item-overlay black-overlay w-full">
									<a href="<?php echo $baseUrl.'post-view/'.$row->post_id?>" class="center text-md"><i class="-circle-o fa fa-4x fa-search"></i></a>
								</div>
								<div class="bottom gd-overlay p-a-xs"></div>
								<div class="top item-overlay text-right p-x-xs">
									<a href="" ui-toggle-class="" class="text-md p-a-sm inline">
										<i class="fa fa-share-alt inline"></i>
										<i class="fa fa-share-alt text-danger none"></i>
									</a>
								</div>
							</div>
							<div class="p-a">
								<div class="text-muted m-b-xs">
									<span class="m-r"><?php echo $this->thai_date($row->ts)?></span>
									<a href="#"
                                    	data-id="<?php echo $row->post_id?>"
                                    	data-url="<?php echo $baseUrl?>post-view/<?php echo $row->post_id?>"
                                    	data-title="<?php echo urlencode($row->title)?>"
                                    	data-summary="<?php echo urlencode($row->detail)?>"
                                    	data-image="<?php echo urlencode($thumbpath)?>" class="share-fb m-r"><i class="fa -o fa-share-alt"></i> <span id="count-share-<?php echo $row->post_id?>"><?php echo $row->share?></span></a>
                                    <a href="#"
                                       data-id="<?php echo $row->post_id?>"
                                       data-url="<?php echo $baseUrl?>post-view/<?php echo $row->post_id?>" class="like-fb m-r"><i class="fa -o -alt fa-eye"></i> <?php echo $row->view?></a>
									<!-- <div class="fb-share-button" data-href="<?php echo $baseUrl.'post-view/'.$row->post_id?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="<?php echo $baseUrl.'post-view/'.$row->post_id?>"><?php echo $this->trans->get('share')?></a></div> -->
								</div>
								<div class="m-b h-2x">
									<a href="<?php echo $baseUrl.'post-view/'.$row->post_id?>" class="_800"><?php echo $row->title?></a>
								</div>
								<div>
									<a href="<?php echo $baseUrl.'post-view/'.$row->post_id?>" class="btn btn-xs white"><?php echo $this->trans->get('more')?></a>
								</div>
								<div></div>
							</div>
						</div>
					</div>
					<?php } ?>
					</div>
					<?php echo $this->data['pagination']?>
				</div>
			<?php include('interest-list.view.php');?>
			<div><?php echo html_entity_decode($this->data['category']['bottom_desc'])?></div>
		</div>
	</div>
</div>
