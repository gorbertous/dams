<h2>Update of Rating</h2>
<p><?php print $count ?> ratings updated: </p>
<?php if(!empty($updated)) foreach($updated as $i=>$rating){
	print '<h3>Update '.($i+1).'</h3>';
	if(!empty($rating['Rating'])) foreach($rating['Rating'] as $key=>$val){
		print '- '.$key.': '.$val.'<br>';
	}
}?>