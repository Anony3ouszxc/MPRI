<?php use Apps\Models\Role;
$baseUrl = $this->data['baseUrl']?>
<div class="padding">
  <div class="box">
    <!-- <div class="box-header">
      <h2><?php echo $this->trans->get('title_all_user')?></h2>
    </div> -->
    <!-- start tool table -->
    <form class="navbar-form form-inline pull-right pull-none-sm navbar-item v-m" method="GET" action="<?php echo $baseUrl?>/user/all-user" role="search">
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
      <a class="btn btn-sm btn-outline b-black text-black margin-left" href="<?php echo $baseUrl.'user/new-user'?>"><?php echo $this->trans->get('add_new')?></a>
    </div>
    <!-- end tool table -->
    <div class="table-responsive">
      <table class="table table-striped b-t b-b">
        <thead>
          <tr>
            <th></th>
            <th style="width:5%"><?php echo $this->trans->get('id')?></th>
            <th style="width:15%"><?php echo $this->trans->get('user_username')?></th>
            <th style="width:20%"><?php echo $this->trans->get('user_firstname')?></th>
            <th style="width:20%"><?php echo $this->trans->get('user_lastname')?></th>
            <th style="width:20%"><?php echo $this->trans->get('user_email')?></th>
            <th style="width:20%"><?php echo $this->trans->get('user_role')?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach( $this->data['userList'] as $user ){
            if( !is_null($user->role_id) ){
              $role = Role::find($user->role_id);
              $rolename = is_object($role) ? $role->attributes['role_name'] : '-';
            } else {
              $rolename = '-';
            }
            ?>
            <tr data-id="<?php echo $user->user_id?>" target="">
              <td class="no-wrap">
                <button class="btn btn-sm dark" id="btn-edit" data-target="<?php echo $baseUrl.'user/edit-user/'.$user->user_id?>"><?php echo $this->trans->get('btn_edit')?></button>
                <button class="btn btn-sm danger" id="btn-delete"><?php echo $this->trans->get('btn_delete')?></button>
              </td>
              <td><?php echo $user->user_id?></td>
              <td><?php echo $user->username?></td>
              <td><?php echo $user->firstname?></td>
              <td><?php echo $user->lastname?></td>
              <td><?php echo $user->email?></td>
              <td><?php echo $rolename?></td>
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