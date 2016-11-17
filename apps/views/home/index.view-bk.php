<?php
use Apps\Models\Files;
use Apps\Models\Embed;
use Apps\Models\Category;
$lang    = $this->data['lang'];
$baseUrl = $this->data['baseUrl'];
$embed   = array(
	'calendar' => Embed::findByCode('calendar'),
	'poll'     => Embed::findByCode('poll'),
	'stats'    => Embed::findByCode('stats')
);
?>
<div class="section news-highlight box-shadow" id="section-news-highlight">
	<div class="container">
		<div class="row">
			<?php
			if(!empty($this->data['postList'])){
				foreach( $this->data['postList'][0] as $groupId => $postList ){
				$group = Category::find($groupId);
				$groupName = $group->attributes['name_' . $lang];
			?>
			<div class="col-md-6 block-news-col-2 ">
				<div class="row">
					<div class="col-md-8">
						<h4 class="heading green-front"><?php echo $groupName?></h4>
					</div>
					<div class=" col-md-4 text-right">
						<a class="heading btn btn-outline rounded b-primary text-primary b-2x" href="<?php echo $baseUrl?>post-group/<?php echo $groupId?>">
							<i class="fa fa-bars fa-fw pull-left"></i>
							<?php echo $this->trans->get('see_all')?>
						</a>
					</div>
				</div>
				<div class="row">
					<?php foreach( $postList as $post){
					if( $post->file_id ){
						$file      = Files::find($post->file_id);
						$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
					} else {
						$thumbpath = '';
					}
					?>
					<div class="col-md-6 col-xs-6">
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
									<!-- <div class="fb-share-button" data-href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" data-layout="button_count" data-size="small" data-mobile-iframe="true">
										<a class="fb-xfbml-parse-ignore" target="_blank" href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>"><?php echo $this->trans->get('share')?></a>
									</div> -->
                                    <a href="#"
                                    	data-id="<?php echo $post->post_id?>"
                                    	data-url="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>"
                                    	data-title="<?php echo urlencode($post->title)?>"
                                    	data-summary="<?php echo urlencode($post->detail)?>"
                                    	data-image="<?php echo urlencode($thumbpath)?>" class="share-fb m-r"><i class="fa -o fa-share-alt"></i> <span id="count-share-<?php echo $post->post_id?>"><?php echo $post->share?></span></a>
                                    <a href="#"
                                       data-id="<?php echo $post->post_id?>"
                                       data-url="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" class="like-fb m-r"><i class="fa -o -alt fa-eye"></i> <?php echo $post->view?></a>
									<!-- <div
									  class="fb-like"
									  data-share="true"
									  data-width="450"
									  data-show-faces="true"
									  data-href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>"
									  data-header="<?php echo $post->title?>"
									  data-layout="button_count" data-size="small" data-mobile-iframe="true">
									</div> -->
									<!-- <div class="fb-share-button"
										data-href="http://www.your-domain.com/your-page.html"
										data-layout="button_count">
									</div> -->
									<!-- <div class="fb-share-button"
									data-href="http://www.sanook.com"
									data-layout="button_count"
									data-size="small"
									data-mobile-iframe="true">
										<a class="fb-xfbml-parse-ignore" target="_blank" href="http://www.sanook.com/">Share</a>
									</div> -->

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
					<?php }?>
				</div>
			</div>
			<?php
			}
			foreach( $this->data['postList'][1] as $groupId => $postList ){
				$group = Category::find($groupId);
				$groupName = $group->attributes['name_' . $lang];
			?>
			<div class="col-md-6 block-news-col-2 ">
				<div class="row">
					<div class="col-md-8">
						<h4 class="heading green-front"><?php echo $groupName?></h4>
					</div>
					<div class=" col-md-4 text-right">
						<a class="heading btn btn-outline rounded b-primary text-primary b-2x" href="<?php echo $baseUrl?>post-group/<?php echo $groupId?>">
							<i class="fa fa-bars fa-fw pull-left"></i>
							<?php echo $this->trans->get('see_all')?>
						</a>
					</div>
				</div>
				<div class="row">
					<?php foreach( $postList as $post){
						if( $post->file_id ){
							$file      = Files::find($post->file_id);
							$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
						} else {
							$thumbpath = '';
						}
					?>
					<div class="col-md-6 col-xs-6">
						<div class="box">
							<div class="item dark">
								<a href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>"><img src="<?php echo $thumbpath?>" class="w-full"></a>
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
									<!-- <div class="fb-share-button" data-href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="<?php echo $baseUrl?>post-view/<?php echo $post->post_id?>"><?php echo $this->trans->get('share')?></a></div> -->
									<!-- <a href="" class="m-r"><i class="fa -o fb-share-alt"></i> 4</a>
									<a href="" class="m-r"><i class="fa -o -alt fa-eye"></i> 99</a> -->
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
					<?php }?>
				</div>
			</div>
			<?php
			}
			}
			?>
		</div>
	</div>
</div>
<div class="section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-4 block-icon-app">
				<?php foreach( $this->data['banner'][0] as $groupId => $linkList){
					$group = Category::find($groupId);
					$groupName = $group->attributes['name_' . $lang];
				?>
				<h3 class="heading green-front">
				<i class="fa fa-star fa-fw"></i><?php echo $groupName?></h3>
				<div class="row">
					<?php foreach( $linkList as $link ){
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
							<h6 contenteditable="true" class="text-center"><?php echo $link->title?></h6>
						</a>
					</div>
					<?php }?>
				</div>
				<?php }?>
			</div>
			<div class="col-md-4 block-icon-app">
				<?php foreach( $this->data['banner'][1] as $groupId => $linkList){
					$group = Category::find($groupId);
					$groupName = $group->attributes['name_' . $lang];
				?>
				<h3 class="heading green-front">
				<i class="fa fa-star fa-fw"></i><?php echo $groupName?></h3>
				<div class="row">
					<?php foreach( $linkList as $link ){
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
							<h6 contenteditable="true" class="text-center"><?php echo $link->title?></h6>
						</a>
					</div>
					<?php }?>
				</div>
				<?php }?>
			</div>
			<?php if( !empty($this->data['banner'][2]) ){ ?>
			<?php foreach( $this->data['banner'][2] as $groupId => $linkList){
				$group = Category::find($groupId);
				$groupName = $group->attributes['name_' . $lang];
			?>
			<div class="col-md-4">
				<div class="col-lg-7 col-md-6">
					<h4 contenteditable="true" class="heading green-front"><?php echo $groupName?></h4>
				</div>
				<div class="heading col-lg-5 col-md-6 hidden-md hidden-sm hidden-xs text-right">

				</div>
				<div class="col-md-12">
					<ul class="list-group">
						<?php foreach( $linkList as $link ){?>
						<li class="list-group-item">
							<a href="<?php echo $link->link?>"><?php echo $link->title?></a>
						</li>
						<?php }?>
					</ul>
				</div>
			</div>
			<?php }?>
			<?php }?>
		</div>
	</div>
</div>
<div class="section bg-grey-medium" id="section-other-module">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<h3 class="heading text-center ">
				<i class="fa fa-fw fa-calendar"></i><?php echo $this->trans->get('calendar')?></h3>
				<div class="box">
		          <div class="box-body">
			            <div class="p-y-md">
										<!--
			            	<?php
			            	if($embed['calendar']->count()){
			            		echo html_entity_decode($embed['calendar']->offsetGet(0)->embed);
			            	}
			            	?>
									-->
									  <iframe src="https://calendar.google.com/calendar/embed?src=th.th%23holiday%40group.v.calendar.google.com&amp;ctz=Asia/Bangkok" style="border: 0" width="100%" height="300px" frameborder="0" scrolling="no"></iframe>
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
										<!--
			            	<?php
			            	if($embed['poll']->count()){
			            		echo (($embed['poll']->offsetGet(0)->embed));
			            	}
			            	?>
									-->
									<script>(function(t,e,n,o){var s,c,r;t.SMCX=t.SMCX||[],e.getElementById(o)||(s=e.getElementsByTagName(n),c=s[s.length-1],r=e.createElement(n),r.type="text/javascript",r.async=!0,r.id=o,r.src=["https:"===location.protocol?"https://":"http://","widget.surveymonkey.com/collect/website/js/wE5uf6Q2JvFh6_2ByHreMVIM_2FI2fUon07DUwgC4GW3RUx5Etp39BdMpzhQB9zJMDSG.js"].join(""),c.parentNode.insertBefore(r,c))})(window,document,"script","smcx-sdk");</script><a style="font: 12px Helvetica, sans-serif; color: #999; text-decoration: none;" href=https://www.surveymonkey.com/mp/customer-satisfaction-surveys/> Create your own user feedback survey </a>
			            </div>
			        </div>
		    	</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<h3 class="heading text-center ">
				<i class="fa fa-fw fa-bar-chart-o"></i><?php echo $this->trans->get('stats')?></h3>
				<div class="box">
		          <div class="box-body">
			            <div class="p-y-md">
										<!--
			            	<?php
			            	if($embed['stats']->count()){
			            		echo html_entity_decode($embed['stats']->offsetGet(0)->embed);
			            	}
			            	?>
									-->
									<!-- Histats.com  START  (standard)-->
<script type="text/javascript">document.write(unescape("%3Cscript src=%27http://s10.histats.com/js15.js%27type=%27text/javascript%27%3E%3C/script%3E"));</script>
<a href="http://www.histats.com" target="_blank" title="free stats" ><script  type="text/javascript" >
try {Histats.start(1,3180334,4,428,112,75,"00011111");
Histats.track_hits();} catch(err){};
</script></a>
<noscript><a href="http://www.histats.com" target="_blank"><img  src="http://sstatic1.histats.com/0.gif?3180334&101"alt="free stats" border="0"></a></noscript>  <!-- Histats.com  END  -->
<a href="http://appcho.co/rss-xml"><img alt="Brand" src="<?php echo $baseUrl?>assets/cms/images/rss-01.png"></a> 

			            </div>
			        </div>
		    	</div>
			</div>
		</div>
	</div>
</div>
<div class="section bg-grey-light" id="section-links-dep">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-xs-12 block-news-col-2 ">
				<?php foreach( $this->data['banner'][3] as $groupId=> $linkList){
					$group = Category::find($groupId);
					$groupName = $group->attributes['name_' . $lang];
				?>
				<h3 class="heading"><?php echo $groupName?></h3>
				<?php foreach( $linkList as $link ){
					if( $link->file_id ){
						$file      = Files::find($link->file_id);
						$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
					} else {
						$thumbpath = '';
					}
				?>
				<div class="col-md-3 col-sm-3 col-xs-3 block-links-moph-item">
					<a href="<?php echo $link->link?>" target="_blank">
						<img class="center-block img-circle img-responsive" src="<?php echo $thumbpath?>">
						<h6 contenteditable="true" class="text-center"><?php echo $link->title?></h6>
					</a>
				</div>
				<?php }?>
				<?php } ?>
			</div>
			<div class="col-md-6 col-xs-12 block-news-col-2 ">
				<?php foreach( $this->data['banner'][4] as $groupId => $linkList){
					$group = Category::find($groupId);
					$groupName = $group->attributes['name_' . $lang];
				?>
				<h3 class="heading"><?php echo $groupName?></h3>
				<?php foreach( $linkList as $link ){
					if( $link->file_id ){
						$file      = Files::find($link->file_id);
						$thumbpath = (file_exists($file->attributes['thumbpath'])? $baseUrl.$file->attributes['thumbpath']: '');
					} else {
						$thumbpath = '';
					}
				?>
				<div class="col-md-3 col-sm-3 col-xs-3 block-links-moph-item">
					<a href="<?php echo $link->link?>" target="_blank">
						<img class="center-block img-circle img-responsive" src="<?php echo $thumbpath?>">
						<h6 contenteditable="true" class="text-center"><?php echo $link->title?></h6>
					</a>
				</div>
				<?php }?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
