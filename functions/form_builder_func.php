<?php

	/* Form builder v1.0
	By UnPointZero Webagency
	www.unpointzero.com
	*/

	function form_input_hidden($name,$value)
	{
		?>
		<input id="<?php echo $name; ?>" name="<?php echo $name; ?>" type="hidden" value="<?php echo $value; ?>" />
		<?php
	}
	
	function form_input($name,$value,$size=32)
	{
		?>
		<input id="<?php echo $name; ?>" size="<?php echo $size; ?>" type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
		<?php
	}
	
	function form_input_disabled($name,$value,$size=32)
	{
		?>
		<input id="<?php echo $name; ?>" disabled size="<?php echo $size; ?>" type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
		<?php
	}

	function form_textarea($name,$value)
	{
		?>
		<textarea id="<?php echo $name; ?>" name="<?php echo $name; ?>" cols="35" rows="4"><?php echo $value; ?></textarea>
		<?php
	}
	
	function form_checkbox($name,$desc,$value,$default)
	{
		?>
		<label><input id="<?php echo $name; ?>" name="<?php echo $name; ?>" type="checkbox" value="<?php echo $value; ?>" <?php if($default==$value) { echo "checked"; } ?> /><?php echo $desc; ?></label><br />
		<?php
	}
	
	function form_select($name,$values,$default="") 
	{
		?>
		<select id="<?php echo $name; ?>" name="<?php echo $name; ?>">
		<option <?php if($default="" || $default==null) { echo 'selected="selected"'; } ?>>Selectionnez</option>
		<?php
		foreach($values as $value => $value_desc)
		{
			?>
			<option value="<?php echo $value; ?>" <?php if ($default==$value) { echo "selected=\"selected\""; } ?>><?php echo $value_desc; ?></option>
			<?php
		}
		?>
		</select>
		<?php
	}
	
	function form_selectmultiple($name,$values,$default="") 
	{
		$def = unserialize($default);
		?>
		<select id="<?php echo $name; ?>" class="<?php echo $name; ?>" multiple name="<?php echo $name; ?>[]">
		<?php
		foreach($values as $value => $value_desc)
		{
			if(is_array($def)) {
				?>
				<option value="<?php echo $value; ?>" <?php if (in_array($value,$def)) { echo "selected=\"selected\""; } ?>><?php echo $value_desc; ?></option>
				<?php
			}
			else {
			?>
			<option value="<?php echo $value; ?>" <?php if ($default==$value) { echo "selected=\"selected\""; } ?>><?php echo $value_desc; ?></option>
			<?php
			}
		}
		?>
		</select>
		<?php
	}
	
	function form_selectsubmit($name,$values,$default="") 
	{
		?>
		<select id="<?php echo $name; ?>" name="<?php echo $name; ?>" onchange="this.form.submit()">
		<option>Selectionnez</option>
		<?php
		foreach($values as $value => $value_desc)
		{
			?>
			<option value="<?php echo $value; ?>" <?php if ($default==$value) { echo "selected=\"selected\""; } ?>><?php echo $value_desc; ?></option>
			<?php
		}
		?>
		</select>
		<?php
	}
	
	function form_radio($name,$values,$default)
	{
		foreach($values as $val => $value_desc)
		{
		?>
		<label><input type="radio" name="<?php echo $name; ?>" value="<?php echo $val; ?>" <?php if($val==$default) { echo "checked=\"checked\""; } ?> /><?php echo $value_desc; ?></label><br />
		<?php
		}
	}
	
	function upload_form($name,$accept="")
	{
	?>
	<input id="<?php echo $name; ?>" type="file" name="<?php echo $name; ?>" accept="<?php echo $accept; ?>" />
	<?php
	}
	
	function primary_btn($name,$value)
	{
	?>
	<input class="button-primary" type="button" name="<?php echo $name; ?>" value="<?php echo $value; ?>" id="<?php echo $name; ?>" />
	<?php
	}
	
	function submit_btn($name,$value)
	{
	?>
	<input class="button-primary" type="submit" name="<?php echo $name; ?>" value="<?php echo $value; ?>" id="<?php echo $name; ?>" />
	<?php
	}
	
	function secondary_btn($name,$value)
	{
	?>
	<input class="button-secondary" type="button" name="<?php echo $name; ?>" value="<?php echo $value; ?>" id="<?php echo $name; ?>" />
	<?php
	}

?>
