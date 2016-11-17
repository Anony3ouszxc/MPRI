<?php
use Apps\Models\Files;
$thumb = '';
if( !empty($this->userInfo['file_id']) ){
  $image = Files::find( $this->userInfo['file_id'] );
  $thumb = $this->data['baseUrl'] . $image->attributes['thumbpath'];
}
?>
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2><?php echo $this->trans->get('title_profile')?></h2>
          <small>*คำแนะนำการตั้งค่าขนาดของภาพไฮไลท์<br>
Slider (ภาพสไลเดอร์ในหน้าแรก) = 1920x600px<br>
Post(ภาพก่อนคลิกดูรายละเอียด) = 400x250px<br>
Page (ภาพตรงส่วนหัว) = 1920x350px<br>
Banner (ภาพตรงไอคอน, โลโก้หน่วยงาน) = 500x500px<br>

*ส่วนภาพประกอบเนื้อหา แนะนำว่าควรลดขนาดความกว้างภาพไม่ให้เกิน 1024px</small>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="/user/update-profile/<?php echo $this->userInfo['user_id']?>" method="POST" enctype="multipart/form-data" id="form-update-profile">
            <div class="form-group">
              <?php if ( $thumb ) { echo '<img src='.$thumb.' atl="">'; }?>
              <label for="inputFile"><?php echo $this->trans->get( 'avatar' )?></label>
              <input type="file" id="inputFile" class="form-control" name="fileinput" accept="image/*">
            </div>
            <div class="form-group">
              <label for="inputUsername"><?php echo $this->trans->get('user_username')?></label>
              <input type="text" id="inputUsername" class="form-control" disabled value="<?php echo $this->userInfo['username']?>">
            </div>
          <!--   <div class="form-group">
              <label for="inputOldPassword"><?php echo $this->trans->get('old_password')?></label>
              <input type="password" class="form-control" id="inputOldPassword" placeholder="<?php echo $this->trans->get('old_password')?>" name="data[old_password]" required>
            </div> -->
            <div class="form-group">
              <label for="inputPassword"><?php echo $this->trans->get('password')?></label>
              <input type="password" class="form-control" id="inputPassword" placeholder="<?php echo $this->trans->get('password')?>" name="data[password]">
            </div>
            <div class="form-group">
              <label for="inputpFirstname"><?php echo $this->trans->get('user_firstname')?></label>
              <input type="text" class="form-control" id="inputpFirstname" placeholder="<?php echo $this->trans->get('user_firstname')?>" name="data[firstname]" value="<?php echo $this->userInfo['firstname']?>" required>
            </div>
            <div class="form-group">
              <label for="inputLastname"><?php echo $this->trans->get('user_lastname')?></label>
              <input type="text" class="form-control" id="inputLastname" placeholder="<?php echo $this->trans->get('user_lastname')?>" name="data[lastname]" value="<?php echo $this->userInfo['lastname']?>" required>
            </div>
            <div class="form-group">
              <label for="inputEmail"><?php echo $this->trans->get('user_email_addr')?></label>
              <div class="input-group">
                <div class="input-group-addon">@</div>
                <input type="email" class="form-control" id="inputEmail" placeholder="<?php echo $this->trans->get('user_email_addr')?>" name="data[email]" value="<?php echo $this->userInfo['email']?>" required>
              </div>
            </div>
            <button type="submit" class="btn btn-sm dark m-b"><?php echo $this->trans->get('save')?></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>