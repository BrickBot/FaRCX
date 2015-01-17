move = function(dir) {
	$.ajax({
		url: "./drive.php",
		data: {
			direction: dir,
			time: $("#time").val(),
			power: $("#power").val()
		}
	});
}

$(document).ready(function() {
	$("#up").click(function() { move('forward') });
	$("#left").click(function() { move('left') });
	$("#down").click(function() { move('backward') });
	$("#right").click(function() { move('right') });
});