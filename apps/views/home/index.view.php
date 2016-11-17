<?php
use Apps\Models\Files;
use Apps\Models\Embed;
use Apps\Models\Category;
$lang    = $this->data['lang'];
$baseUrl = $this->data['baseUrl'];
$embed   = array(
	'calendar'   => Embed::findByCode('calendar'),
	'poll'       => Embed::findByCode('poll'),
	'lastupdate' => Files::findBySql('select ts from logs order by ts desc limit 1')
);
$visit = Files::findBySql('select message from logs where logs_type="visit" limit 1');
$lastupdate = $embed['lastupdate']->offsetGet(0)->ts;
$visitCount = $visit->offsetGet(0)->message;
?>
<div class="box-shadow news-highlight section  bg-green">
	<div class="container">
		<div class="row">
			<div class="col-md-4 text-center">
				<div class="block-icon-main">
					<?php foreach( $this->data['banner'][0] as $groupId => $linkList){
						foreach( $linkList as $link ){
							if( $link->file_id ){
								$file      = Files::find($link->file_id);
								$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
							} else {
								$thumbpath = '';
							}
					?>
					<a href="<?php echo $link->link?>" class="padding-small">
						<img src="<?php echo $thumbpath?>" class="img-responsive">
					</a>
					<?php
						}
					}?>
				</div>
			</div>
			<div class="col-md-8">
				<?php
					if(!empty($this->data['postList'][0])){
						foreach( $this->data['postList'][0] as $groupId => $postList ){
						$group = Category::find($groupId);
						$groupName = $group->attributes['name_' . $lang];
				?>
				<div class="row">
					<div class="col-md-7">
						<h3 class="heading green-front text-white">
						<i class="fa fa-fw fa-newspaper-o"></i>&nbsp;<?php echo $groupName?></h3>
					</div>
					<div class="col-md-5 text-right">
						<a href="<?php echo $baseUrl.'post-group/'.$groupId?>" class="b-2x b-primary btn btn-default btn-outline heading rounded text-primary">
							<i class="fa fa-bars fa-fw pull-left"></i>
							<?php echo $this->trans->get('see_all')?>
						</a>
					</div>
				</div>
				<div class="row">
					<?php
				 	foreach( $postList as $post){
						if( $post->file_id ){
							$file      = Files::find($post->file_id);
							$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
						} else {
							$thumbpath = '';
						}
					?>
					<div class="col-md-4 col-xs-6">
						<div class="box">
							<div class="item dark">
								<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>">
									<img src="<?php echo $thumbpath?>" class="w-full">
								</a>
								<div class="item-overlay black-overlay w-full">
									<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="center text-md">
										<i class="-circle-o fa fa-4x fa-search"></i>
									</a>
								</div>
								<div class="bottom gd-overlay p-a-xs"></div>
								<div class="top item-overlay text-right p-x-xs">
									<a href="" ui-toggle-class="" class="text-md p-a-sm inline">
										<i class="fa fb-share-alt inline"></i>
										<i class="fa fb-share-alt text-danger none"></i>
									</a>
								</div>
							</div>
							<div class="p-a">
								<div class="text-muted m-b-xs">
									<span class="m-r"><?php echo $this->thai_date($post->ts)?></span>
                                    <a href="#"
                                    	data-id="<?php echo $post->post_id?>"
                                    	data-url="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>"
                                    	data-title="<?php echo urlencode($post->title)?>"
                                    	data-summary="<?php echo urlencode($post->detail)?>"
                                    	data-image="<?php echo urlencode($thumbpath)?>" class="share-fb m-r"><i class="fa -o fa-share-alt"></i> <span id="count-share-<?php echo $post->post_id?>"><?php echo $post->share?></span></a>
                                    <a href="#"
                                       data-id="<?php echo $post->post_id?>"
                                       data-url="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="like-fb m-r"><i class="fa -o -alt fa-eye"></i> <?php echo $post->view?></a>
								</div>
								<div class="m-b h-2x">
									<?php echo $post->title?>
								</div>
								<div>
									<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="btn btn-xs white"><?php echo $this->trans->get('read_more')?></a>
								</div>
								<div></div>
							</div>
						</div>
					</div>
					<?php
							}
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="section bg-white">
	<div class="container">
		<div class="col-md-12">
			<h3 class=" heading">
				<i class="fa fa-fw fa-external-link"></i>&nbsp;เว็บไซด์อื่นๆที่เกี่ยวข้อง</h3>
		</div>	
		<div class="col-md-4 block-icon-app">
			<div class="row">
				<?php foreach( $this->data['banner'][1] as $groupId => $linkList){?>

					<?php $count=0; foreach( $linkList as $link ){
						if( $count > 2 ){
							$count = 0;
							echo '</div></div>';
							echo '<div class="col-md-4 block-icon-app"><div class="row">';
						}
						if( $link->file_id ){
							$file      = Files::find($link->file_id);
							$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
						} else {
							$thumbpath = '';
						}
					?>
					<div class="col-md-4 col-xs-4 ">
						<a href="<?php echo $link->link?>" target="_blank">
							<img class="center-block img-responsive" src="<?php echo $thumbpath?>">
							<h6 class="text-center"><?php echo $link->title?></h6>
						</a>
					</div>
					<?php $count++; }?>
				<?php }?>
			</div>
		</div>
	</div>
</div>

<div class="section bg-2">
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<?php
					if(!empty($this->data['postList'][1])){
						foreach( $this->data['postList'][1] as $groupId => $postList ){
						$group = Category::find($groupId);
						$groupName = $group->attributes['name_' . $lang];
				?>
				<div class="col-md-12">
					<div class="col-lg-7 col-md-6">
						<h3 class="heading green-front text-white" contenteditable="true">
						<i class="fa fa-fw fa-leaf"></i>&nbsp;<?php echo $groupName?></h3>
					</div>
					<div class="heading col-lg-5 col-md-6 hidden-md hidden-sm hidden-xs text-right">
						<a href="<?php echo $baseUrl.'post-group/'.$groupId?>" class="b-2x b-primary btn btn-default btn-outline rounded text-primary">
							<i class="fa fa-bars fa-fw pull-left"></i>
							<?php echo $this->trans->get('see_all')?>
						</a>
					</div>
				</div>
				<div class="row">
					<?php
				 	foreach( $postList as $post){
						if( $post->file_id ){
							$file      = Files::find($post->file_id);
							$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
						} else {
							$thumbpath = '';
						}
					?>
					<div class="col-md-4 col-xs-6">
						<div class="box">
							<div class="item dark">
								<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>">
									<img src="<?php echo $thumbpath?>" class="w-full">
								</a>
								<div class="item-overlay black-overlay w-full">
									<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="center text-md">
										<i class="-circle-o fa fa-4x fa-search"></i>
									</a>
								</div>
								<div class="bottom gd-overlay p-a-xs"></div>
								<div class="top item-overlay text-right p-x-xs">
									<a href="" ui-toggle-class="" class="text-md p-a-sm inline">
										<i class="fa fb-share-alt inline"></i>
										<i class="fa fb-share-alt text-danger none"></i>
									</a>
								</div>
							</div>
							<div class="p-a">
								<div class="text-muted m-b-xs">
									<span class="m-r"><?php echo $this->thai_date($post->ts)?></span>
                                    <a href="#"
                                    	data-id="<?php echo $post->post_id?>"
                                    	data-url="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>"
                                    	data-title="<?php echo urlencode($post->title)?>"
                                    	data-summary="<?php echo urlencode($post->detail)?>"
                                    	data-image="<?php echo urlencode($thumbpath)?>" class="share-fb m-r"><i class="fa -o fa-share-alt"></i> <span id="count-share-<?php echo $post->post_id?>"><?php echo $post->share?></span></a>
                                    <a href="#"
                                       data-id="<?php echo $post->post_id?>"
                                       data-url="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="like-fb m-r"><i class="fa -o -alt fa-eye"></i> <?php echo $post->view?></a>
								</div>
								<div class="m-b h-2x">
									<?php echo $post->title?>
								</div>
								<div>
									<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="btn btn-xs white"><?php echo $this->trans->get('read_more')?></a>
								</div>
								<div></div>
							</div>
						</div>
					</div>
					<?php
							}
						}
					}
					?>
				</div>

			</div>
			<div class="col-md-3 col-xs-12">
				<?php foreach( $this->data['banner'][2] as $groupId => $linkList){
					$group = Category::find($groupId);
					$groupName = $group->attributes['name_' . $lang];
				?>
				<div class="row">
					<div class="col-md-12">
						<h3 class="text-white heading">
						<i class="fa fa-fw fa-database"></i>&nbsp;<?php echo $groupName?></h3>
					</div>
				</div>
				<div class="row">
					<?php foreach( $linkList as $link ){
						if( $link->file_id ){
							$file      = Files::find($link->file_id);
							$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
						} else {
							$thumbpath = '';
						}
					?>
					<div class="col-xs-6 col-md-12">
						<a href="<?php echo $link->link?>">
						<img src="<?php echo $thumbpath?>" class="img-responsive"></a>
					</div>
					<?php }?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<?php
				if( !empty($this->data['postList'][2]) ){
					foreach( $this->data['postList'][2] as $groupId => $postList ){
					$group     = Category::find($groupId);
					$groupName = $group->attributes['name_' . $lang];
				?>
				<h4 class="heading green-front"><?php echo $groupName;?>
				<br>
				<br>
				</h4>
				<div class="list box">
					<?php
				 	foreach( $postList as $post){
						if( $post->file_id ){
							$file      = Files::find($post->file_id);
							$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
						} else {
							$thumbpath = '';
						}
					?>
					<div class="list-item">
						<div class="list-left">
							<img src="<?php echo $thumbpath?>" class="w-40 circle">
						</div>
						<div class="list-body">
							<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="_500">
								<?php echo $post->title?>
							</a>
							<small class="block text-muted"></small>
						</div>
					</div>
					<?php }?>
				</div>
				<?php
					}
				}?>
			</div>
			<div class="col-md-4">
				<?php
				if( !empty($this->data['postList'][3]) ){
					foreach( $this->data['postList'][3] as $groupId => $postList ){
					$group     = Category::find($groupId);
					$groupName = $group->attributes['name_' . $lang];
				?>
				<h4 class="heading green-front"><?php echo $groupName;?>
				<br>
				<br>
				</h4>
				<div class="list box">
					<?php
				 	foreach( $postList as $post){
						if( $post->file_id ){
							$file      = Files::find($post->file_id);
							$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
						} else {
							$thumbpath = '';
						}
					?>
					<div class="list-item">
						<div class="list-left">
							<img src="<?php echo $thumbpath?>" class="w-40 circle">
						</div>
						<div class="list-body">
							<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="_500">
								<?php echo $post->title?>
							</a>
							<small class="block text-muted"></small>
						</div>
					</div>
					<?php }?>
				</div>
				<?php
					}
				}?>
			</div>
			<div class="col-md-4">
				<?php
				if( !empty($this->data['postList'][4]) ){
					foreach( $this->data['postList'][4] as $groupId => $postList ){
					$group     = Category::find($groupId);
					$groupName = $group->attributes['name_' . $lang];
				?>
				<h4 class="heading green-front"><?php echo $groupName;?>
				<br>
				<br>
				</h4>
				<div class="list box">
					<?php
				 	foreach( $postList as $post){
						if( $post->file_id ){
							$file      = Files::find($post->file_id);
							$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
						} else {
							$thumbpath = '';
						}
					?>
					<div class="list-item">
						<div class="list-left">
							<img src="<?php echo $thumbpath?>" class="w-40 circle">
						</div>
						<div class="list-body">
							<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="_500">
								<?php echo $post->title?>
							</a>
							<small class="block text-muted"></small>
						</div>
					</div>
					<?php }?>
				</div>
				<?php
					}
				}?>
			</div>
		</div>
	</div>
</div>
<div class="section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<h3 class="heading text-center ">
				<i class="fa fa-fw fa-calendar"></i><?php echo $this->trans->get('calendar')?></h3>
				<div class="box">
		          <div class="box-body">
			            <div class="p-y-md">
			            	<?php
			            	if($embed['calendar']->count()){
			            		echo base64_decode($embed['calendar']->offsetGet(0)->embed);
			            	}
			            	?>
			            </div>
			        </div>
		    	</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<h3 class="heading text-center ">
				<i class="fa fa-fw fa-check-square-o"></i><?php echo $this->trans->get('poll')?></h3>
				<div class="box">
		          <div class="box-body">
			            <div class="p-y-md">
			            	<?php
			            	if($embed['poll']->count()){
			            		echo base64_decode($embed['poll']->offsetGet(0)->embed);
			            	}
			            	?>
			            </div>
			        </div>
		    	</div>
			</div>
			<div class="col-md-4 col-xs-12 block-update">
				<div class="box">
					<ul class="list inset m-a-0" contenteditable="true">
						<li class="list-item">
							<a herf="" class="list-left">
								<span class="w-40 circle accent">
									<i class="fa fa-edit"></i>
								</span>
							</a>
							<div class="list-body">
								<div>วันที่ปรับปรุงข้อมูลล่าสุด
									<em class="text-accent"><?php echo $this->thai_date($lastupdate)?></em>
								</div>
							</div>
						</li>
						<li class="list-item">
							<a herf="" class="list-left">
								<span class="w-40 circle info">
									<i class="fa fa-bar-chart"></i>
								</span>
							</a>
							<div class="list-body">
								<div>จำนวนคนเข้าเยี่ยมชมวันนี้ :
									<em class="text-info"><?php echo $visitCount?></em>
								</div>
							</div>
						</li>
					</ul>
				</div>
            </div>
		</div>
	</div>
</div>