<!-- ############ LAYOUT START-->
<div class="app-footer white">
	<div>
		<div class="p-a text-xs">
			<div class="pull-right text-muted">
				&copy; Copyright <strong><?php echo $this->params['app_name']?></strong>
				<span class="hidden-xs-down">-
				<?php
				$text  = $this->trans->get('power_by');
				$text .= ' '.$this->params['author'];
				$text .= ' '.$this->trans->get('version');
				$text .= ' '.$this->params['version'];
				echo $text;
				?>
				</span>
				<a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
			</div>
			<div class="nav">
				<a class="nav-link" href="#"></a>
			</div>
		</div>
	</div>
</div>
<div class="app-body amber bg-auto w-full">
	<div class="text-center pos-rlt p-y-md">
		<h1 class="text-shadow m-a-0 text-white text-4x">
		<span class="text-2x font-bold block m-t-lg">101</span>
		</h1>
		<h2 class="h1 m-y-lg text-black">OOPS!</h2>
		<p class="h5 m-y-lg text-u-c font-bold text-black"><?php echo $this->trans->get('under_construction')?></p>

	</div>
</div>