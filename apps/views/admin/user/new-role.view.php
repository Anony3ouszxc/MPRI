<?php
if(!empty($this->data['record'])){
	$data                = $this->data['record'];
	$edit                = true;
	$formLink            = $this->data['baseUrl'].'user/update-role/'.$data['role_id'];
	$data['role_config'] = unserialize($data['role_config']);
  } else {
    $edit     = false;
    $formLink = $this->data['baseUrl'].'user/add-role';
  }
  $menuList = $this->data['menuList'];
?>
<div class="padding">
	<div class="box">
		<div class="box-header">
			<!-- <h2><?php echo $this->trans->get('title_new_role')?></h2> -->
			<small>*คำแนะนำการตั้งค่าขนาดของภาพไฮไลท์<br>
Slider (ภาพสไลเดอร์ในหน้าแรก) = 1920x600px<br>
Post(ภาพก่อนคลิกดูรายละเอียด) = 400x250px<br>
Page (ภาพตรงส่วนหัว) = 1920x350px<br>
Banner (ภาพตรงไอคอน, โลโก้หน่วยงาน) = 500x500px<br>

*ส่วนภาพประกอบเนื้อหา แนะนำว่าควรลดขนาดความกว้างภาพไม่ให้เกิน 1024px</small>
		</div>
		<form role="form" action="<?php echo $formLink?>" method="POST">
			<div class="box-body">
				<div class="form-group">
					<label for="inputRolename"><?php echo $this->trans->get('role_name')?></label>
					<input type="text" class="form-control" id="inputRolename" placeholder="<?php echo $this->trans->get('role_name')?>" required name="data[role_name]"
					<?php if( $edit ){ echo 'value="'.$data['role_name'].'"';}?>>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered m-a-0">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo $this->trans->get('department')?></th>
							<th><?php echo $this->trans->get('see_menu')?></th>
							<th><?php echo $this->trans->get('writer')?></th>
							<th><?php echo $this->trans->get('publisher')?></th>
						</tr>
					</thead>
					<tfoot>
					<tr><td colspan="3"><button type="submit" class="btn btn-sm dark m-b"><?php echo $this->trans->get('save')?></button></td></tr>
					</tfoot>
					<tbody>
						<?php $i = 1;foreach($this->data['menuList'] as $code => $auth){?>
						<tr>
							<td><?php echo $i?></td>
							<td><?php echo $auth?></td>
							<td>
								<div class="col-sm-10">
									<label class="ui-switch ui-switch-md info m-t-xs">
										<input type="checkbox" name="data[role_config][<?php echo $code."_see"?>]" class="has-value"
										<?php if( $edit && $data['role_config'][$code.'_see'] ){ echo 'checked'; }?>>
										<i></i>
									</label>
								</div>
							</td>
							<td>
								<div class="col-sm-10">
									<label class="ui-switch ui-switch-md info m-t-xs">
										<input type="checkbox" name="data[role_config][<?php echo $code."_write"?>]" class="has-value"
										<?php if( $edit && !empty($data['role_config'][$code.'_write']) ){ echo 'checked'; }?>>
										<i></i>
									</label>
								</div>
							</td>
							<td>
								<div class="col-sm-10">
									<label class="ui-switch ui-switch-md info m-t-xs">
										<input type="checkbox" name="data[role_config][<?php echo $code."_publish"?>]" class="has-value"
										<?php if( $edit && !empty($data['role_config'][$code.'_publish']) ){ echo 'checked'; }?>>
										<i></i>
									</label>
								</div>
							</td>
						</tr>
						<?php $i++; }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>