<?php
if(!empty($this->data['record'])){
    $data     = $this->data['record']->offsetGet(0)->attributes;
    $edit     = true;
  } else {
    $edit     = false;
  }
  $formLink = $this->data['baseUrl'].'module/save-poll';
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
          <form action="<?php echo $formLink?>" method="POST" id="embed-form">
            <div class="form-group">
              <textarea class="form-control" id="inputDetail" rows="4" placeholder="<?php echo $this->trans->get('embed')?>" name="data[embed]" required><?php if($edit){ echo base64_decode($data['embed']); }?></textarea>
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