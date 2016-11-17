<?php
use Apps\Models\Menu;
use Apps\Models\Page;
function generateTree( $id, $parentId=''){
	$current = Menu::find( $id );
	$parent  = ( $parentId ) ? 'treegrid-parent-'.$parentId : '';
	$tpl = '<tr class="treegrid-'.$id.' '.$parent.'"><td>'.$current->attributes['name'].'</td>';
	$tpl .= '<td><input type="text" name="data[name_en]['.$id.']" class="form-control" data-default="'.$current->attributes['name_en'].'" value="'.$current->attributes['name_en'].'"></td>';
	$tpl .= '<td>'.getOption($current->attributes['menu_id'], $current->attributes).'</td></tr>';
	$menu = Menu::findByParentId( $id );
	if( $menu->count() ){
		foreach( $menu as $_menu ){
			$tpl .= generateTree($_menu->menu_id, $id);
		}
	}
	return $tpl;
}

function getOption($id, $rowData=''){
	$selectId = '';
	$url      = '';
	if(is_array($rowData)){
		if( $rowData['page_id'] > 0 ){
			$selectId = 'page-' . $rowData['page_id'];
		} elseif( $rowData['post_id'] > 0 ){
			$selectId = 'post-' . $rowData['post_id'];
		} elseif( $rowData['category_id'] > 0 ){
			$selectId = 'category-' . $rowData['category_id'];
		} elseif( $rowData['url'] != '' ){
			$url = $rowData['url'];
		}
	}
	$pageList = Page::findBySql('select concat(\'(page) \',title) title,concat(\'page-\',page_id) as id from page');
	// $postList = Page::findBySql('select concat(\'(post) \',title) title,concat(\'post-\',post_id) as id from post');
	$categoyList = Page::findBySql('select concat(\'(category) \',name_th) title,concat(\'category-\',category_id) as id from category where type=\'post\'');
	$optionList = array_merge( $pageList->asArray(), $categoyList->asArray() );
	$options  = '<select name="data[menu]['.$id.']" id="menu-'.$id.'" data-default="'.$selectId.'" class="form-control select2" ui-jp="select2" ui-options="{theme: \'bootstrap\', tags: \'true\'}">';
	$options .= '<option value=0></option>';
	foreach( $optionList as $option ){
		$selected = ($selectId == $option->id) ? 'selected' : '';
		$options .= '<option value="' . $option->id .'" '.$selected.'>'.$option->title.'</option>';
	}
	if( $url ){
		$options .= '<option value="'.$url.'" selected>'.$url.'</option>';
	}
	$options .= '</select>';
	return $options;
}
?>
<div class="padding" id="page-map-manu">
	<div class="row">
		<div class="col-sm-12">
			<!-- <p class="m-a-0 m-b"><?php echo $this->trans->get('map_menu');?></p> -->
			<table class="tree" class="col-sm-12">
				<thead>
					<tr>
						<th style="width:35%"><?php echo $this->trans->get('menu_name_th')?></th>
						<th style="width:35%" class="hidden-xs"><?php echo $this->trans->get('menu_name_en')?></th>
						<th><?php echo $this->trans->get('target_link')?></th>
					</tr>
				</thead>
				<?php
				$menu = Menu::findByParentId(0);
				foreach( $menu as $_menu ){
					echo generateTree( $_menu->menu_id );
				}
				?>
			</table>
		</div>
	</div>
</div>