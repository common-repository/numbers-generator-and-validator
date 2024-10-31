<?php

/**
 * Generator template
 *
 * @package NGV\templates
 * @since   1.17
 */
?>
<div class="subtabs gen-subtabs">
	<ul class="tab">
		<li>
			<a href="#gen-tab-1/" id="first-gen-tab" class="gen-tab-links" data-index="gen-tab-1" data-target="gen-tab-content">Numbers</a>
		</li>
		<li>
			<a href="#gen-tab-2/" id="sec-gen-tab" class="gen-tab-links" data-index="gen-tab-2" data-target="gen-tab-content">Serials</a>
		</li>
	</ul>
	<div class="wrap">
		<div id="gen-tab-1" class="gen-tab-content">
			<div class="ngv-top-10 ngv-bottom-16">
				<strong>Enter two numbers, for example 10000 - 20000.<br>This plugin will generate numbers between the two numbers.</strong>
			</div>
			<div class="enter-number">
				<div class="ngv-bottom-8">Enter first number</div>
				<input type="number" id="firstnumber" name="firstnumber">
			</div>
			<div class="enter-number">
				<div class="ngv-bottom-8">Enter last number</div>
				<input type="number" id="lastnumber" name="lastnumber">
			</div>
			<br>
			<div class="enter-options">
				<div class="ngv-bottom-8">Quantity</div>
				<input type="number" id="quantity">
			</div>
			<div class="enter-options">
				<div class="ngv-bottom-8">Select if you want to sort or randomize</div>
				<select class="w-100" id="sort">
					<option value="1">Sort</option>
					<option value="2">Random</option>
				</select>
			</div>
			<textarea rows="4" cols="50" id="show-numbers" class="ngv-canvas"></textarea>
			<div>
				<button id="gen-numbers-button" class="button-primary">Generate</button>
				<button id="cpy-numbers-button" class="button-primary">Copy to clipboard</button>
			</div>
		</div>
		<div id="gen-tab-2" class="gen-tab-content">
			<div class="ngv-top-10 ngv-bottom-10">
				<p>Generates unique serials separated with whitespace.</p>
			</div>
			<div class="ngv-top-10 ngv-bottom-10">
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input" data="charset" type="checkbox"> Generate using characterset
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input" data="uppercase" type="checkbox" disabled> Uppercase
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input" data="lowercase" type="checkbox" disabled> Lowercase
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input" data="digits" type="checkbox" disabled> Use digits
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input mw-100" data="length" type="number" value="20" disabled> Serial length
				</div>
				<div class="ngv-input-row ngv-bottom-10">
					<input class="serial-charset-input mw-100" data="qty" type="number" value="100" disabled> Quantity
				</div>
			</div>
			<textarea rows="4" cols="50" id="show-serials" class="ngv-canvas"></textarea>
			<div>
				<button id="gen-serials-button" class="button-primary">Generate</button>
				<button id="cpy-serials-button" class="button-primary">Copy to clipboard</button>
			</div>
		</div>
	</div>
</div>