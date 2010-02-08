<div class="kohanut_element_ctl">
	<p class="title"><?php echo $title ?></p>
	<ul class="kohanut_element_actions">
		<?php
		echo "<li>" .
			html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'edit','params'=>$block->id)),
			html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/pencil.png'))) . "Edit" ) .
		"</li>\n";
		echo "<li>" .
			html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'moveup','params'=>$block->id)),
			html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/arrow_up.png'))) . "Move Up" ) .
		"</li>\n";
		echo "<li>" .
			html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'movedown','params'=>$block->id)),
			html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/arrow_down.png'))) . "Move Down" ) .
		"</li>\n";
		echo "<li>" .
			html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'elements','action'=>'delete','params'=>$block->id)),
			html::image( Route::get('kohanut-media')->uri(array('file'=>'img/fam/delete.png'))) . "Delete" ) .
		"</li>\n";
		?>
	</ul>
	<div style="clear:left"></div>
</div>