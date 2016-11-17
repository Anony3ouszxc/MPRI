<?php use Apps\Models\Post;
$post           = new Post();
$baseUrl        = $this->data['baseUrl'];
$data['top']    = $post->getTop('priority');
$data['bottom'] = $post->getTop('view');
?>
<div class="col-md-3 sidebar">
	<div class="box" id="box-article-recommended">
		<div class="box-inner">
			<h4><?php echo $this->trans->get('article_recommended')?></h4>
			<?php
			if(!empty($data['top'])){
			foreach( $data['top'] as $rows){?>
			<div class="p-a">
				<div class="text-muted m-b-xs">
					<span class="m-r"><?php echo $this->thai_date($rows->ts)?></span>
					<a href="" class="m-r"><i class="fa -o fa-share-alt"></i> <?php echo $rows->share?></a>
					<a href="" class="m-r"><i class="fa -o -alt fa-eye"></i> <?php echo $rows->view?></a>
				</div>
				<div class="m-b h-2x">
					<a href="<?php echo $baseUrl.'post-view/'.$rows->post_id?>" class="_800"><?php echo $rows->title?></a>
				</div>
				<div></div>
			</div>
			<?php }}?>
		</div>
	</div>
	<div class="box" id="box-article-top">
		<div class="box-inner">
			<h4><?php echo $this->trans->get('article_top_view')?></h4>
			<?php
			if( !empty($data['bottom'])){
			foreach( $data['bottom'] as $rows){?>
			<div class="p-a">
				<div class="text-muted m-b-xs">
					<span class="m-r"><?php echo $this->thai_date($rows->ts)?></span>
					<a href="" class="m-r"><i class="fa -o fa-share-alt"></i> <?php echo $rows->share?></a>
					<a href="" class="m-r"><i class="fa -o -alt fa-eye"></i> <?php echo $rows->view?></a>
				</div>
				<div class="m-b h-2x">
					<a href="<?php echo $baseUrl.'post-view/'.$rows->post_id?>" class="_800"><?php echo $rows->title?></a>
				</div>
				<div></div>
			</div>
			<?php } }?>
		</div>
	</div>
</div>