<?php

/**
 * Generator template
 *
 * @package NGV\src\views
 * @since   1.17
 */
?>
<div class="subtabs gen-subtabs">
	<ul class="tab">
		<li>
			<a href="#gen-tab-1/" id="first-gen-tab" class="gen-tab-links" data-index="gen-tab-1" data-target="gen-tab-content"><?php echo __('Numbers', 'ngv') ?></a>
		</li>
		<li>
			<a href="#gen-tab-2/" id="sec-gen-tab" class="gen-tab-links" data-index="gen-tab-2" data-target="gen-tab-content"><?php echo __('Serials', 'ngv') ?></a>
		</li>
	</ul>
	<div class="ngv-30">
		<div id="gen-tab-1" class="gen-tab-content">
			<div class="ngv-bottom-16">
				<strong><?php echo __('Enter two numbers, for example 10000 - 20000.', 'ngv') ?><br><?php echo __('This plugin will generate numbers between the two numbers.', 'ngv') ?></strong>
			</div>
			<div class="enter-number">
				<div class="ngv-bottom-8"><?php echo __('Enter first number', 'ngv') ?></div>
				<input type="number" id="firstnumber" name="firstnumber">
			</div>
			<div class="enter-number">
				<div class="ngv-bottom-8"><?php echo __('Enter last number', 'ngv') ?></div>
				<input type="number" id="lastnumber" name="lastnumber">
			</div>
			<br>
			<div class="enter-options">
				<div class="ngv-bottom-8"><?php echo __('Quantity', 'ngv') ?></div>
				<input type="number" id="quantity">
			</div>
			<div class="enter-options">
				<div class="ngv-bottom-8"><?php echo __('Select if you want to sort or randomize', 'ngv') ?></div>
				<select class="w-100" id="sort">
					<option value="1"><?php echo __('Sort', 'ngv') ?></option>
					<option value="2"><?php echo __('Random', 'ngv') ?></option>
				</select>
			</div>
			<textarea rows="4" cols="50" id="show-numbers" class="ngv-canvas"></textarea>
			<div>
				<button id="gen-numbers-button" class="button-primary"><?php echo __('Generate', 'ngv') ?></button>
				<button id="cpy-numbers-button" class="button-primary"><?php echo __('Copy to clipboard', 'ngv') ?></button>
			</div>
		</div>
		<div id="gen-tab-2" class="gen-tab-content">
			<div class="ngv-bottom-10">
				<p class="ngv-top-0"><?php echo __('Generates unique serials separated with whitespace.', 'ngv') ?></p>
			</div>
			<div class="ngv-top-10 ngv-bottom-10">
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input" data="charset" type="checkbox"> <?php echo __('Generate using characterset', 'ngv') ?>
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input" data="uppercase" type="checkbox" disabled> <?php echo __('Uppercase', 'ngv') ?>
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input" data="lowercase" type="checkbox" disabled> <?php echo __('Lowercase', 'ngv') ?>
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input" data="digits" type="checkbox" disabled> <?php echo __('Use digits', 'ngv') ?>
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input mw-100" data="length" type="number" value="20" disabled> <?php echo __('Serial length', 'ngv') ?>
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input mw-100" data="qty" type="number" value="100" disabled> <?php echo __('Quantity', 'ngv') ?>
				</div>
			</div>
			<textarea rows="4" cols="50" id="show-serials" class="ngv-canvas"></textarea>
			<div>
				<button id="gen-serials-button" class="button-primary"><?php echo __('Generate', 'ngv') ?></button>
				<button id="cpy-serials-button" class="button-primary"><?php echo __('Copy to clipboard', 'ngv') ?></button>
			</div>
		</div>
	</div>
</div>