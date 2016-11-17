<?php $baseUrl = $this->data['baseUrl']?>
<div class="padding">
  <div class="box">
    <!-- <div class="box-header">
      <h2><?php echo $this->trans->get('all_role')?></h2>
    </div> -->
    <!-- start tool table -->
    <form class="navbar-form form-inline pull-right pull-none-sm navbar-item v-m" method="GET" action="<?php echo $baseUrl?>/user/all-role" role="search">
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
      <a class="btn btn-sm btn-outline b-black text-black margin-left" href="<?php echo $baseUrl.'user/new-role'?>"><?php echo $this->trans->get('add_new')?></a>
    </div>
    <!-- end tool table -->
    <div class="table-responsive">
      <table class="table table-striped b-t b-b">
        <thead>
          <tr>
            <th></th>
            <th style="width:5%">#</th>
            <th style="width:25%"><?php echo $this->trans->get('role_name')?></th>
            <th style="width:70%"><?php echo $this->trans->get('authorize')?></th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1;foreach( $this->data['roleList'] as $role ){?>
          <tr data-id="<?php echo $role->role_id?>">
            <td class="no-wrap">
              <button class="btn btn-sm dark" id="btn-edit" data-target="<?php echo $baseUrl.'user/edit-role/'.$role->role_id?>"><?php echo $this->trans->get('btn_edit')?></button>
              <button class="btn btn-sm danger" id="btn-delete"><?php echo $this->trans->get('btn_delete')?></button>
            </td>
            <td><?php echo $i?></td>
            <td><?php echo $role->role_name?></td>
            <td>
              <?php
              $roleConfig = unserialize($role->role_config);
              if( count($roleConfig) ){
                echo join(", ", array_keys($roleConfig));
              } else {
                echo '-';
              }
              ?>
            </td>
          </tr>
          <?php $i++;} ?>
        </tbody>
      </table>
    </div>
  </div>
</div>