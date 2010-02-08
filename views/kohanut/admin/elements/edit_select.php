<div class="grid_16">
	<div class="box">
		<h1>Editing <?php echo ucfirst($element->type()) ?></h1>
		
		<?php include Kohana::find_file('views', 'kohanut/admin/errors') ?>
		
		<form method="post">
			
			<p>
				<label for="which">Select a <?php echo ucfirst($element->type()) ?></label>
				<?php
				
				$choices = $element->select_list($element->pk());

				echo Form::select('element', $choices, $element->id) ?>
				
			</p>
			
			<p>
				<?php echo Form::submit('submit','Save Changes',array('class'=>'submit')) ?>
				<?php echo html::anchor(Route::get('kohanut-admin')->uri(array('controller'=>'pages','action'=>'edit','params'=>$page)),'cancel'); ?>
			</p>
			
		</form>
		
		</div>
	</div>

</div>