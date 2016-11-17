<?php
use Apps\Models\User;
$baseUrl     = $this->data['baseUrl'];
$rolePublish = ($this->isAdmin || !empty($this->roleAccess['page_publish'])) ? 1 : 0;
?>
<div class="padding">
  <div class="box">
    <!-- <div class="box-header">
      <h2><?php echo $this->trans->get('title_all_page')?></h2>
    </div> -->
     <!-- start tool table -->
    <form class="navbar-form form-inline pull-right pull-none-sm navbar-item v-m" method="GET" action="<?php echo $baseUrl?>/page/all-page" id="form-search" role="search">
      <div class="form-group l-h m-a-0">
        <div class="input-group input-group-sm">
          <input type="text" class="form-control p-x b-a rounded" name="keyword" value="<?php echo $this->keyword?>" placeholder="<?php echo $this->trans->get('keyword')?>">
          <span class="input-group-btn">
            <button type="submit" class="btn white b-a rounded no-shadow"><i class="fa fa-search"></i></button>
          </span>
        </div>
      </div>
    </form>
    <div class="navbar-item">
      <a class="btn btn-sm btn-outline b-black text-black margin-left" href="<?php echo $baseUrl.'page/new-page'?>"><?php echo $this->trans->get('add_new')?></a>
    </div>
    <!-- end tool table -->
    <div class="table-responsive">
      <table class="table table-striped b-t b-b">
        <thead>
          <tr>
            <th></th>
            <th style="width:10%"><?php echo $this->trans->get('id')?></th>
            <th style="width:20%"><?php echo $this->trans->get('title')?></th>
            <th style="width:20%"><?php echo $this->trans->get('author')?></th>
            <th style="width:10%"><?php echo $this->trans->get('active')?></th>
            <th style="width:20%"><?php echo $this->trans->get('last_publish')?></th>
            <th style="width:20%"><?php echo $this->trans->get('latest_modify')?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach( $this->data['pageList'] as $page ){
            $user   = ($page->user_id > 0)
                      ? User::findBySql( "select * from user where user_id=" . $page->user_id )
                      : (object) ['firstname' => ''];
            $status = $page->publish == 'on' ? 'active' : '';
            ?>
            <tr data-id="<?php echo $page->page_id?>">
              <td class="no-wrap">
                <button class="btn btn-sm dark" data-target="<?php echo $baseUrl.'page/edit-page/'.$page->page_id?>" id="btn-edit"><?php echo $this->trans->get('btn_edit')?></button>
                <button class="btn btn-sm danger" id="btn-delete"><?php echo $this->trans->get('btn_delete')?></button>
              </td>
              <td><?php echo $page->page_id?></td>
              <td><?php echo $page->title?></td>
              <td><?php echo $user->firstname?></td>
              <td align="center">
                <a href="#" class="active-status <?php echo $status?>" ui-toggle-class="" ui-class="">
                  <i class="fa fa-check text-success none"></i>
                  <i class="fa fa-times text-danger inline"></i>
                </a>
              </td>
              <td>
                <?php echo $page->last_publish?>
              </td>
              <td><?php echo $page->ts?></td>
            </tr>
          <?php }?>
        </tbody>
        <tfoot class="hide-if-no-paging">
          <tr>
            <td colspan="8" class="text-center footable-visible">
                <nav><?php echo $this->data['pagination']?></nav>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>