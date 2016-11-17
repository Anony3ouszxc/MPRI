<?php
use Apps\Models\Files;
use Apps\Models\Category;
$thumb   = '';
$pdffile = '';
if ( !empty( $this->data['record'] ) ) {
  $data     = $this->data['record'];
  $edit     = true;
  $formLink = $this->data['baseUrl'].'post/update-post/'.$data['post_id'];
  if ( $data['file_id'] ) {
    $image = Files::find( $data['file_id'] );
    $thumb = $this->data['baseUrl'].$image->attributes['thumbpath'];
  }

  if( $data['pdf_id'] ){
     $pdf     = Files::find( $data['pdf_id'] );
     $pdffile = $pdf->attributes['filename'];
  }
} else {
  $edit     = false;
  $formLink = $this->data['baseUrl'].'post/add-post';
}
$categoryList = Category::findByType( 'post' );
$catname      = 'name_'.$this->data['lang'];
?>
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <!-- <h2><?php echo $this->trans->get( 'title_new_post' )?></h2> -->
          <small>*คำแนะนำการตั้งค่าขนาดของภาพไฮไลท์<br>
Slider (ภาพสไลเดอร์ในหน้าแรก) = 1920x600px<br>
Post(ภาพก่อนคลิกดูรายละเอียด) = 400x250px<br>
Page (ภาพตรงส่วนหัว) = 1920x350px<br>
Banner (ภาพตรงไอคอน, โลโก้หน่วยงาน) = 500x500px<br>

*ส่วนภาพประกอบเนื้อหา แนะนำว่าควรลดขนาดความกว้างภาพไม่ให้เกิน 1024px</small>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form action="<?php echo $formLink?>" enctype="multipart/form-data" method="POST" role="form">
            <?php if ( $thumb ) { echo '<img src='.$thumb.' atl="">'; }?>
            <div class="form-group">
              <label for="inputFile"><?php echo $this->trans->get( 'file_input' )?></label>
              <input type="file" id="inputFile" class="form-control" name="fileinput" accept="image/*">
            </div>
            <div class="form-group">
              <label for="inputTitle"><?php echo $this->trans->get( 'title' )?></label>
              <input type="text" class="form-control" id="inputTitle" placeholder="<?php echo $this->trans->get( 'title' )?>" name="data[title]" value="<?php if ( $edit ) { echo $data['title']; }?>" required>
            </div>
            <div class="form-group">
              <label for="inputDetail"><?php echo $this->trans->get( 'description' )?></label>
              <textarea ui-jp="summernote" ui-options="{height:'200px'}" class="form-control" id="inputDetail" rows="4" placeholder="<?php echo $this->trans->get( 'description' )?>" name="data[detail]"><?php if ( $edit ) { echo $data['detail']; }?></textarea>
              <!-- <div id="inputDetail" ui-jp="summernote" ui-options="{height:'200px'}"></div> -->
            </div>
            <div class="form-group">
              <label for="inputPDF"><?php echo $this->trans->get( 'pdf_attach' ); if( $pdffile ){ echo '('.$pdffile.')'; }?></label>
              <input type="file" id="inputPDF" class="form-control" name="pdfinput" accept="application/pdf">
            </div>
            <div class="form-group">
              <label for="categoryLabel"><?php echo $this->trans->get( 'categories' )?></label>
              <select name="data[category_id]" class="form-control" id="categoryLabel"  required><!-- ui-jp="select2" ui-options="{tags:true}" -->
              <?php
              foreach ( $categoryList as $category ) {
                $selected = ( !empty( $data ) && $data['category_id']==$category->category_id ) ? 'selected':'';
                echo '<option value="'.$category->category_id.'" '.$selected.'>'.$category->$catname.'</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
                <label for="inputSort"><?php echo $this->trans->get( 'priority' )?></label>
                <input type="number" class="form-control" id="inputSort" placeholder="<?php echo $this->trans->get( 'priority' )?>" name="data[priority]" value="<?php if ( $edit ) { echo $data['priority']; }?>" required>
              </div>
              <div class="col-sm-9"></div>
            </div>
          </div>
          <?php if( !empty($this->data['roleAccess']['page_publish']) || $this->data['isAdmin'] ){?>
          <p><?php echo $this->trans->get('publish')?></p>
          <div class="list inset box">
            <div class="list-item">
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-3">
                    <table class="table">
                      <td style="width:20%;vertical-align:middle;padding-left:0px;width:auto;"><?php echo $this->trans->get( 'active' )?></td>
                      <td>
                        <label class="ui-switch ui-switch-md info m-t-xs" >
                          <input type="checkbox" name="data[publish]" class="has-value"
                          <?php if ( $edit && $data['publish']=='on' ) { echo 'checked'; }?>>
                          <i></i>
                        </label>
                      </td>
                    </table>
                  </div>
                  <div class="col-sm-9"></div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-6">
                    <label><?php echo $this->trans->get( 'start_date' )?></label>
                    <div class="input-group date" ui-jp="datetimepicker" ui-options="{
                        icons: {
                          time: 'fa fa-clock-o',
                          date: 'fa fa-calendar',
                          up: 'fa fa-chevron-up',
                          down: 'fa fa-chevron-down',
                          previous: 'fa fa-chevron-left',
                          next: 'fa fa-chevron-right',
                          today: 'fa fa-screenshot',
                          clear: 'fa fa-trash',
                          close: 'fa fa-remove'
                        },
                        format: 'YYYY-MM-DD HH:mm:ss'
                      }">
                      <input type="text" name="data[publish_start]" value="<?php if($edit){ echo $data['publish_start']; }?>" class="form-control has-value">
                      <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label><?php echo $this->trans->get( 'end_date' )?></label>
                    <div class="input-group date" ui-jp="datetimepicker" ui-options="{
                        icons: {
                          time: 'fa fa-clock-o',
                          date: 'fa fa-calendar',
                          up: 'fa fa-chevron-up',
                          down: 'fa fa-chevron-down',
                          previous: 'fa fa-chevron-left',
                          next: 'fa fa-chevron-right',
                          today: 'fa fa-screenshot',
                          clear: 'fa fa-trash',
                          close: 'fa fa-remove'
                        },
                        format: 'YYYY-MM-DD HH:mm:ss'
                      }">
                      <input type="text" name="data[publish_end]" value="<?php if($edit){ echo $data['publish_end']; }?>" class="form-control has-value">
                      <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-3">
                <label for="inputClass"><?php echo $this->trans->get('container_class')?></label>
                <input type="text" class="form-control" id="inputClass" placeholder="<?php echo $this->trans->get('container_class')?>" name="data[container_class]" value="<?php if($edit){ echo $data['container_class']; }?>">
              </div>
              <div class="col-sm-9"></div>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-sm dark m-b"><?php echo $this->trans->get( 'save' )?></button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>