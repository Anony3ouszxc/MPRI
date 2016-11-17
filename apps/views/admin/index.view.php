<?php $baseUrl = $this->data['baseUrl']?>
<div class="center-block w-xxl w-auto-xs p-y-md">
	<div class="navbar">
		<div class="pull-center">
			<div>
				<a class="navbar-brand">
					<img src="<?php echo $baseUrl . $this->params['logo']?>" alt="logo"/>
					<span><?php echo $this->params['app_name'];?></span>
				</a>
			</div>
		</div>
	</div>
	<div class="p-a-md box-color r box-shadow-z1 text-color m-a">
		<div class="m-b text-sm">
			<?php echo $this->trans->get('sign_in_title')?>
		</div>
		<form name="form" method="POST" action="<?php echo $baseUrl?>admin/login">
			<div class="md-form-group float-label">
				<input type="email" class="md-input" ng-model="user.email" name="data[email]" required>
				<label><?php echo $this->trans->get('email')?></label>
			</div>
			<div class="md-form-group float-label">
				<input type="password" class="md-input" ng-model="user.password" name="data[password]" required>
				<label><?php echo $this->trans->get('password')?></label>
			</div>
			<!-- <div class="m-b-md">
				<label class="md-check">
					<input type="checkbox"><i class="primary"></i> Keep me signed in
				</label>
			</div> -->
			<button type="submit" class="btn primary btn-block p-x-md"><?php echo $this->trans->get('sign_in')?></button>
		</form>
	</div>
	<!-- <div class="p-v-lg text-center">
		<div class="m-b"><a href="#/access/forgot-password" class="text-primary _600">Forgot password?</a></div>
		<div>Do not have an account? <a href="#/access/signup" class="text-primary _600">Sign up</a></div>
	</div> -->
</div>