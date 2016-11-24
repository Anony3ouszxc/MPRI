<?php
use Apps\Models\Files;
use Apps\Models\Category;

$formLink = $this->data['baseUrl'].'page/update-intro';
$pageList = $this->data['pageList'];
$data     = $this->data['record']->offsetGet(0)->attributes;
?>
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <small><?php echo $this->trans->get('intro_title')?></small>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form action="<?php echo $formLink?>" enctype="multipart/form-data" method="POST" role="form">
            <div class="form-group">
              <label for="categoryLabel"><?php echo $this->trans->get( 'page' )?></label>
              <select name="data[page_id]" class="form-control" id="categoryLabel"  required><!-- ui-jp="select2" ui-options="{tags:true}" -->
              <?php

              foreach ( $pageList as $page ) {
                $selected = ( !empty( $data ) && $data['page_id']==$page->page_id ) ? 'selected':'';
                echo '<option value="'.$page->page_id.'" '.$selected.'>'.$page->title.'</option>';
              }

              ?>
            </select>
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
                          <?php if ( $data['publish']=='on' ) { echo 'checked'; }?>>
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
                      <input type="text" name="data[publish_start]" value="<?php if(!empty( $data )){ echo $data['publish_start']; }?>" class="form-control has-value">
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
                      <input type="text" name="data[publish_end]" value="<?php if(!empty( $data )){ echo $data['publish_end']; }?>" class="form-control has-value">
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
            <button type="submit" class="btn btn-sm dark m-b"><?php echo $this->trans->get( 'save' )?></button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>