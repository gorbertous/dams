<?php
	$options = array();
	if(!empty($ditpl['deposits_footer'])){
		if($ditpl['deposits_footer'][0]=='{'){
			$json = json_decode($ditpl['deposits_footer'], true);
			foreach ($json as $key => $value) {
				if(!empty($key)) $options[] = $key;
				else $options[] = $value;
			}
		}else{
			$options[] = $ditpl['deposits_footer'];
		}
	}
	if(!empty($ditpl['footer_force'])){
		$options = array(array_shift($options));
	}
?>
<?php if(empty($ditpl['footer_force'])): ?>
	<?php if(empty($cpty_id) || $cpty_id!=EXIMBANKA_ID): ?>
	<?php foreach ($custom_texts as $key => $value): ?>
		<option value="<?php echo $value['CustomText']['custom_id'] ?>"><?php echo $value['CustomText']['dropdown_txt'] ?></option>
	<?php endforeach ?>
	<option value="D">funds at Depositor's account</option>
	<option value="TM">funds at TMs account</option>

	<?php endif ?>
	<option value="">---</option>
<?php endif ?>

<?php if(!empty($options)) foreach($options as $key=>$value): ?>
<option value="CUSTOM_<?php print $key ?>"><?php print $value ?></option>
<?php endforeach ?>