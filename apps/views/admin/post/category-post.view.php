<?php use Apps\Models\User;
$baseUrl = $this->data['baseUrl']?>
<div class="padding">
  <div class="box">
  	<!-- start tool table -->
	<form class="navbar-form form-inline pull-right pull-none-sm navbar-item v-m" method="GET" action="<?php echo $baseUrl?>/post/category-post" role="search">
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
		<a href="<?php echo $baseUrl.'post/form-category'?>" class="btn btn-sm btn-outline b-black text-black margin-left"><?php echo $this->trans->get('add_new')?></a>
	</div>
	<!-- end tool table -->
    <div class="table-responsive">
      <table class="table table-striped b-t b-b">
        <thead>
          <tr>
            <th></th>
            <th style="width:5%"><?php echo $this->trans->get('id')?></th>
            <th style="width:20%"><?php echo $this->trans->get('name_th')?></th>
            <th style="width:20%"><?php echo $this->trans->get('name_en')?></th>
            <th style="width:15%"><?php echo $this->trans->get('author')?></th>
            <th style="width:15%"><?php echo $this->trans->get('priority')?></th>
            <th style="width:25%"><?php echo $this->trans->get('latest_modify')?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach( $this->data['categoryList'] as $category ){
          $user = ($category->user_id > 0)
                  ? User::findBySql( "select * from user where user_id=" . $category->user_id )
                  : (object) ['firstname' => ''];
          ?>
          <tr data-id="<?php echo $category->category_id?>">
            <td class="no-wrap">
              <button class="btn btn-sm dark" id="btn-edit" data-target="<?php echo $baseUrl.'post/edit-category/'.$category->category_id?>"><?php echo $this->trans->get('btn_edit')?></button>
              <button class="btn btn-sm danger" id="btn-delete"><?php echo $this->trans->get('btn_delete')?></button>
            </td>
            <td><?php echo $category->category_id?></td>
            <td><?php echo $category->name_th?></td>
            <td><?php echo $category->name_en?></td>
            <td><?php echo $user->firstname?></td>
            <td align="center"><?php echo $category->priority?></td>
            <td><?php echo $category->lastupdate?></td>
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