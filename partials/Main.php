<div class='main-content'>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/FiltersBar.php'; ?>
	<div class="camera">
			<input disabled type="file" name="fileToUpload" id="fileToUpload">
			<div id="live-filters">
				<img src="../img/filters/girls.png" alt="" id="live-filter-girls">
				<img src="../img/filters/hair.png" alt="" id="live-filter-hair">
				<img src="../img/filters/mustache.png" alt="" id="live-filter-mustache">
				<img src="../img/filters/rainbow.png" alt="" id="live-filter-rainbow">
			</div>
		<video id="video">Video stream not available.
		</video>
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
