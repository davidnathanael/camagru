<div class='main-content'>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/FiltersBar.php'; ?>
	<!-- <hr> -->
	<div class="camera">
			<input disabled type="file" name="fileToUpload" id="fileToUpload">
		<video id="video">Video stream not available.</video>
		<button disabled id="startbutton">Please select a filter</button>
	</div>

	<canvas id="canvas">
	</canvas>
	<div class="output">
		<img id="photo" alt="The screen capture will appear in this box.">
	</div>
</div>

<script src="js/webcam.js"></script>
<script src="js/filters.js"></script>
