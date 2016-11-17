<?php
use Apps\Models\Files;
  if(!empty($this->data['record'])){
    $data     = $this->data['record'];
    $edit     = true;
    $formLink = $this->data['baseUrl'].'module/update-category/'.$data['category_id'];
    if( $data['file_id'] ){
      $image = Files::find( $data['file_id']);
      $thumb = $this->data['baseUrl'].$image->attributes['filepath'];
    }
  } else {
    $edit     = false;
    $formLink = $this->data['baseUrl'].'module/add-category';
    $thumb    = '';
  }
?>
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <!-- <h2><?php echo $this->trans->get('title_new_post')?></h2> -->
          <small>*คำแนะนำการตั้งค่าขนาดของภาพไฮไลท์<br>
Slider (ภาพสไลเดอร์ในหน้าแรก) = 1920x600px<br>
Post(ภาพก่อนคลิกดูรายละเอียด) = 400x250px<br>
Page (ภาพตรงส่วนหัว) = 1920x350px<br>
Banner (ภาพตรงไอคอน, โลโก้หน่วยงาน) = 500x500px<br>

*ส่วนภาพประกอบเนื้อหา แนะนำว่าควรลดขนาดความกว้างภาพไม่ให้เกิน 1024px</small>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form action="<?php echo $formLink?>" enctype="multipart/form-data" method="POST">
            <?php if( $thumb ){ echo '<img src='.$thumb.' atl="">'; }?>
            <div class="form-group">
              <label for="inputFile"><?php echo $this->trans->get('file_input')?></label>
              <input type="file" id="inputFile" class="form-control has-value" name="fileinput" accept="image/*">
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-6">
                  <label for="categoryLabelTH"><?php echo $this->trans->get('name_th')?></label>
                  <input type="text" class="form-control" id="categoryLabelTH" placeholder="<?php echo $this->trans->get('name_th')?>" name="data[name_th]" value="<?php if($edit){ echo $data['name_th']; }?>" required>
                </div>
                <div class="col-sm-6">
                  <label for="categoryLabelEN"><?php echo $this->trans->get('name_en')?></label>
                  <input type="text" class="form-control" id="categoryLabelEN" placeholder="<?php echo $this->trans->get('name_en')?>" name="data[name_en]" value="<?php if($edit){ echo $data['name_en']; }?>" required>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-3">
                  <label for="inputSort"><?php echo $this->trans->get('priority')?></label>
                  <input type="number" class="form-control" id="inputSort" placeholder="<?php echo $this->trans->get('priority')?>" name="data[priority]" value="<?php if($edit){ echo $data['priority']; }?>" required>
                </div>
                <div class="col-sm-9"></div>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-sm dark m-b"><?php echo $this->trans->get('save')?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>