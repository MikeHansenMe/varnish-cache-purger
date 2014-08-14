<?php

function vp_url_req() {
	?>
	<div id="updated" class="error">
		<p>A full url is required. Example: http://domain.com/</p>
	</div>
	<?php
}

function vp_404() {
	?>
	<div id="updated" class="error">
		<p>The page you tried to purge was not found in the varnish cache.</p>
	</div>
	<?php
}

function vp_405() {
	?>
	<div id="updated" class="error">
		<p>You submitted an invalid request that was rejected.</p>
	</div>
	<?php
}

function vp_200() {
	?>
	<div id="updated" class="updated">
		<p>Cache successfully purged.</p>
	</div>
	<?php
}

function vp_general_error() {
	?>
	<div id="updated" class="error">
		<p>Something went wrong with your request. Please try again.</p>
	</div>
	<?php
}
