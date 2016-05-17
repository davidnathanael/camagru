<div class='main-content'>
	<?php echo $_SESSION['login'] . " id: " . $_SESSION['id']; ?>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/FiltersBar.php'; ?>
	<div class="camera">
		<video id="video">Video stream not available.</video>
		<button disabled id="startbutton">Please select a filter</button>
	</div>

	<canvas id="canvas">
	</canvas>
	<div class="output">
		<img id="photo" alt="The screen capture will appear in this box.">
	</div>
</div>
