<?php
use Apps\Models\Files;
$thumb = '';
$pdffile = '';
if(!empty($this->data['record'])){
	$data     = $this->data['record'];
	$edit     = true;
	$formLink = $this->data['baseUrl'].'page/update-page/'.$data['page_id'];
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
	$formLink = $this->data['baseUrl'].'page/add-page';
}
?>
<div class="padding">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header">
					<!-- <h2><?php echo $this->trans->get('title_new_page')?></h2> -->
					<small>*คำแนะนำการตั้งค่าขนาดของภาพไฮไลท์<br>
Slider (ภาพสไลเดอร์ในหน้าแรก) = 1920x600px<br>
Post(ภาพก่อนคลิกดูรายละเอียด) = 400x250px<br>
Page (ภาพตรงส่วนหัว) = 1920x350px<br>
Banner (ภาพตรงไอคอน, โลโก้หน่วยงาน) = 500x500px<br>

*ส่วนภาพประกอบเนื้อหา แนะนำว่าควรลดขนาดความกว้างภาพไม่ให้เกิน 1024px</small>
				</div>
				<div class="box-divider m-a-0"></div>
				<div class="box-body">
					<form action="<?php echo $formLink?>" enctype="multipart/form-data" method="POST" id="form-layout">
						<?php if ( $thumb ) { echo '<img src='.$thumb.' atl="">'; }?>
			            <div class="form-group">
			              <label for="inputFile"><?php echo $this->trans->get( 'banner_image' )?></label>
			              <input type="file" id="inputFile" class="form-control" name="fileinput" accept="image/*">
			            </div>
						<div class="form-group">
							<label for="inputTitle"><?php echo $this->trans->get('title')?></label>
							<input type="text" id="inputTitle" class="form-control has-value" name="data[title]" value="<?php if($edit){ echo $data['title']; }?>" required>
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
						<div class="layout-builder">
							<?php
							if($edit){
								$content = unserialize($data['content']);
								foreach( $content as $rowIndex => $row ){
									$rowName = 'data[content]['.$rowIndex.']';
									?>
									<div class="row form-group row-content" data-index="<?php echo $rowIndex?>">
										<?php foreach( $row['config'] as $colIndex => $_layout){?>
											<div class="col-md-<?php echo $_layout?>" <?php if($colIndex == 0){?> style="padding-left:24px" <?php }?> >
												<?php if($colIndex == 0){?>
												<i class="material-icons md-24 text-danger delete-layout" style="position: absolute;margin-left: -24px;top: 0%;"></i>
												<?php }?>
												<div class="box p-a">
													<textarea ui-jp="summernote" ui-options="{height: 200}" class="form-control" rows="4" name="<?php echo $rowName?>[content][]">
														<?php echo html_entity_decode($row['content'][$colIndex])?>
													</textarea>
													<input type="hidden" name="<?php echo $rowName?>[config][]" value="<?php echo $_layout?>">
												</div>
											</div>
										<?php } ?>
									</div>
								<?php }
							}?>
						</div>
						<div class="form-group">
			              <label for="inputPDF"><?php echo $this->trans->get( 'pdf_attach' ); if( $pdffile ){ echo '('.$pdffile.')'; }?></label>
			              <input type="file" id="inputPDF" class="form-control" name="pdfinput" accept="application/pdf">
			            </div>
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
			              	<button type="submit" class="btn btn-sm dark m-b"><?php echo $this->trans->get('save')?></button>
			            </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row" id="add-layout">
	<!-- Button trigger modal -->
	<center>
		<button class="md-btn md-fab m-b-sm white" data-toggle="modal" data-target="#formLayout"><i class="material-icons md-24"></i></button>
	</center>
</div>
<!-- Modal -->
<div class="modal fade modal-content " id="formLayout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $this->trans->get('layout')?></h4>
			</div>
			<div class="modal-body">
				<div class="padding">
					<div class="row">
						<div class="col-md-1">
							<p>
								<label class="md-check">
									<input type="radio" name="select-layout" value="12" checked>
									<i class="pink"></i>
								</label>
							</p>
						</div>
						<div class="col-md-11">
							<div class="col-md-12"><div class="box p-a">.col-md-12</div></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1">
							<p>
								<label class="md-check">
									<input type="radio" name="select-layout" value="6-6" checked>
									<i class="pink"></i>
								</label>
							</p>
						</div>
						<div class="col-md-11">
							<div class="col-md-6"><div class="box p-a">.col-md-6</div></div>
							<div class="col-md-6"><div class="box p-a">.col-md-6</div></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1">
							<p>
								<label class="md-check">
									<input type="radio" name="select-layout" value="4-4-4">
									<i class="pink"></i>
								</label>
							</p>
						</div>
						<div class="col-md-11">
							<div class="col-md-4"><div class="box p-a">.col-md-4</div></div>
							<div class="col-md-4"><div class="box p-a">.col-md-4</div></div>
							<div class="col-md-4"><div class="box p-a">.col-md-4</div></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1">
							<p>
								<label class="md-check">
									<input type="radio" name="select-layout" value="8-4">
									<i class="pink"></i>
								</label>
							</p>
						</div>
						<div class="col-md-11">
							<div class="col-md-8"><div class="box p-a">.col-md-8</div></div>
							<div class="col-md-4"><div class="box p-a">.col-md-4</div></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1">
							<p>
								<label class="md-check">
									<input type="radio" name="select-layout" value="4-8">
									<i class="pink"></i>
								</label>
							</p>
						</div>
						<div class="col-md-11">
							<div class="col-md-4"><div class="box p-a">.col-md-4</div></div>
							<div class="col-md-8"><div class="box p-a">.col-md-8</div></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-fw dark" data-dismiss="modal" id="btn-add-row"><?php echo $this->trans->get('add_row')?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->trans->get('close')?></button>
			</div>
		</div>
	</div>
</div>