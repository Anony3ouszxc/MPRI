<?php
use Apps\Models\Files;
use Apps\Models\Category;
  if(!empty($this->data['record'])){
    $data     = $this->data['record'];
    $edit     = true;
    $formLink = $this->data['baseUrl'].'module/update-slider/'.$data['slider_id'];
    if( $data['file_id'] ){
      $image = Files::find( $data['file_id']);
      $thumb = $this->data['baseUrl'].$image->attributes['thumbpath'];
    }
  } else {
    $edit     = false;
    $formLink = $this->data['baseUrl'].'module/add-slider';
    $thumb    = '';
  }
  $categoryList = Category::findByType('module');
$catname      = 'name_'.$this->data['lang'];
?>
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <!-- <h2><?php echo $this->trans->get('title_new_slider')?></h2> -->
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
              <input type="file" id="inputFile" class="form-control has-value" name="fileinput" accept="image/*" <?php if(!$edit){ echo 'required';}?>>
            </div>
            <div class="form-group">
              <label for="inputTitle"><?php echo $this->trans->get('title')?></label>
              <input type="text" class="form-control" id="inputTitle" placeholder="<?php echo $this->trans->get('title')?>" name="data[title]" value="<?php if($edit){ echo $data['title']; }?>" required>
            </div>
            <div class="form-group">
              <label for="inputDetail"><?php echo $this->trans->get('description')?></label>
              <textarea class="form-control" id="inputDetail" rows="4" placeholder="<?php echo $this->trans->get('description')?>" name="data[detail]" required><?php if($edit){ echo $data['detail']; }?></textarea>
            </div>
            <div class="form-group">
              <label for="categoryLabel"><?php echo $this->trans->get('categories')?></label>
              <select name="data[category_id]" class="form-control" id="categoryLabel"  required><!-- ui-jp="select2" ui-options="{tags:true}" -->
                <?php
                foreach($categoryList as $category){
                  $selected = ($data['category_id']==$category->category_id) ? 'selected':'';
                  echo '<option value="'.$category->category_id.'" '.$selected.'>'.$category->$catname.'</option>';
                }
                ?>
              </select>
              <!-- <div class="row">
                <div class="col-sm-6">
                  <label for="categoryLabelEN"><?php echo $this->trans->get('category_en')?></label>
                  <select name="data[category_en]" class="form-control" id="categoryLabelEN" ui-jp="select2" ui-options="{tags:true}" required>
                    <?php
                    foreach($this->data['categoryListEN'] as $category){
                      $selected = ($data['category_en']==$category) ? 'selected':'';
                      echo '<option value="'.$category->category_en.'" '.$selected.'>'.$category->category_en.'</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="categoryLabelTH"><?php echo $this->trans->get('category_th')?></label>
                  <select name="data[category_th]" class="form-control" id="categoryLabelTH" ui-jp="select2" ui-options="{tags:true}" required>
                    <?php
                    foreach($this->data['categoryListTH'] as $category){
                      $selected = ($data['category_th']==$category) ? 'selected':'';
                      echo '<option value="'.$category->category_th.'" '.$selected.'>'.$category->category_th.'</option>';
                    }
                    ?>
                  </select>
                </div>
              </div> -->
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
              <label for="inputLink"><?php echo $this->trans->get('external_link')?></label>
              <input type="url" class="form-control" id="inputLink" rows="4" placeholder="<?php echo $this->trans->get('external_link')?>" name="data[link]" value="<?php if($edit){ echo $data['link']; }?>">
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
              <button type="submit" class="btn btn-sm dark m-b"><?php echo $this->trans->get('save')?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>