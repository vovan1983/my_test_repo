<?php require_once dirname(__FILE__) . '/classes/List_Tables.php';?>
<?php require_once dirname(__FILE__) . '/vendors/validator.php';?>
<?php require_once dirname(__FILE__) . '/classes/CRUD_Gift.php'; ?>

<?php $page_url = "admin.php?page=".$_REQUEST['page']; ?>

<div class="wrap nosubsub">
	
	<div id="ajax-response"></div>
	
	<br class="clear" />

<div id="col-container">


<div id="col-right">
<?php if (empty($edit_mode)):?>
<div class="col-wrap">
<div class="form-wrap">
<?php 
$wp_gift_table = new Gift_List_Table('GiftName');
echo '<div class="wrap"><h2>Gifts Table</h2>';
$wp_gift_table->prepare_items();
?>
<form method="post" id="gift-form">
<input type="hidden" name="page" value="admin_edit_users_gifts">

<?php

$wp_gift_table->search_box( 'search', 'id' );?>

<?$wp_gift_table->display();
echo '</div>'; 

?>
</form>
<!--table paste here -->
</div>
</div>
<?php else: ?>

<?php endif;?>
</div>
<div id="col-left">
<div class="col-wrap">
<?php if (empty($edit_mode)):?>	
<h2><?php echo 'Add new Gift' ?></h2>
<?else:?>
<h2><?php echo 'Edit Gift' ?></h2>
<?endif;?>
<div class="form-wrap" id="add-gift-form">
<form id="addtag" method="post" action="<?=$page_url?>" class="validate" >

<?php if (!empty($edit_mode)):?>	
<input type="hidden" name="id" value="<?php echo $edit_mode['id']?>" />
<?endif;?>

<?$field='GiftName';?>	
<?php $highlight = (isset($errors[$field])) ? 'form-invalid' : ''; ?>
<?php if (!empty($edit_mode)):?>
<?php $backfield = ( isset($errors[$field]) || isset($_POST[$field]) ) ? $_POST[$field] : $edit_mode[$field];?>
<?else:?>
<?php $backfield = (isset($_POST[$field])) ? $_POST[$field] : '';?>
<?endif?>
<div class="form-field form-required <?=$highlight?>">
	<label for="<?=$field;?>">Gift Name:</label>
	<input name="<?=$field;?>" id="GiftName" type="text" value="<?=$backfield;?>" size="40"/>
	<p class="error_gift"><?=(isset($errors[$field])) ? $errors[$field] : '';?></p>
	<p><?php _e('Please enter Gift Name.'); ?></p>
</div>

<?$field='GiftDesc';?>
<?php $highlight = (isset($errors[$field])) ? 'form-invalid' : ''; ?>
<?php if (!empty($edit_mode)):?>
<?php $backfield = ( isset($errors[$field]) || isset($_POST[$field])) ? $_POST[$field] : $edit_mode[$field];?>
<?else:?>
<?php $backfield = (isset($_POST[$field])) ? $_POST[$field] : '';?>
<?endif?>
<div class="form-field form-required <?=$highlight?>">
	<label for="<?=$field;?>">Gift Description:</label>
	<input name="<?=$field;?>" id="GiftDesc" type="text" value="<?=$backfield;?>" size="40" />
	<p class="error_gift"><?=(isset($errors[$field])) ? $errors[$field] : '';?></p>
	<p><?php _e('Please enter Gift Description.'); ?></p>
</div>

<?$field='CutoffDate';?>
<?php $highlight = (isset($errors[$field])) ? 'form-invalid' : ''; ?>
<?php if (!empty($edit_mode)):?>
<?php $backfield = ( isset($errors[$field]) || isset($_POST[$field])) ? $_POST[$field] : $edit_mode[$field];?>
<?else:?>
<?php $backfield = (isset($_POST[$field])) ? $_POST[$field] : '';?>
<?endif?>
<div class="form-field form-required <?=$highlight?>">
	<label for="<?=$field;?>">CutOff Date:</label>
	<input readonly="readonly" name="<?=$field;?>" id="CutOffDate" type="text" value="<?=$backfield;?>" size="40" />
	<p class="error_gift"><?=(isset($errors[$field])) ? $errors[$field] : '';?></p>
	<p><?php _e('Please enter Cut Off Date.'); ?></p>
</div>

<?$field='CutoffNumber';?>
<?php $highlight = (isset($errors[$field]) ) ? 'form-invalid' : ''; ?>
<?php if (!empty($edit_mode)):?>
<?php $backfield = ( isset($errors[$field]) || isset($_POST[$field])) ? $_POST[$field] : $edit_mode[$field];?>
<?else:?>
<?php $backfield = (isset($_POST[$field])) ? $_POST[$field] : '';?>
<?endif?>
<div class="form-field form-required <?=$highlight?>">
	<label for="<?=$field;?>">Cutoff Number:</label>
	<input name="<?=$field;?>" id="CutoffNumber" type="text" value="<?=$backfield;?>" size="40" />
	<p class="error_gift"><?=(isset($errors[$field])) ? $errors[$field] : '';?></p>
	<p><?php _e('Please enter Cut Off Number.'); ?></p>
</div>

<?$field='Active';?>
<?php if (!empty($edit_mode)):?>
<?php $backfield = ( $edit_mode[$field] == '1' || isset($_POST[$field])) ? 'checked="checked"' : '';?>
<?else:?>
<?php $backfield = (isset($_POST[$field])) ? 'checked="checked"' : '';?>
<?endif?>
<div class="form-field form-required">
	<label for="<?=$field;?>">Active:</label>
	<input type="checkbox" id="Active" name="<?=$field;?>" value="1" class="gift-active" <?=$backfield;?> />
	<p><?php _e('Activate new gift.'); ?></p>
</div>

<?php if (empty($edit_mode)):?>	
<?php submit_button( 'Add new Gift', 'button' ); ?>
<?else:?>
<div class="edit-class">
<?php //submit_button( 'Edit Gift', 'button' ); ?>
<!--<a class="cancel" href="<?//=$page_url?>">Back</a>-->
<input id="submit" class="button gift-edit" type="submit" value="Edit Gift" name="submit">
<a accesskey="c" href="<?=$page_url?>" title="<?php esc_attr_e( 'Cancel' ); ?>" class="cancel button-primary aligncenter gift-cancel"><?php _e( 'Cancel' ); ?></a>
</div>
<?endif;?>

</form>
</div></div></div>
	
</div>
</div>
