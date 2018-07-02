//make the message switch from yellow to white or vice versa for ten seconds and then remove message
var loops = 0;
var colorChanger = setInterval(function() {
	if (++loops == 11) {
		document.getElementById('msg').innerHTML = '';
		window.clearInterval(colorChanger)
	} else {
		//if "loops" is even
		if (loops/2 == Math.floor(loops/2)) {
			document.getElementById('msg').style.color = 'yellow';
		} else {
			document.getElementById('msg').style.color = 'white';
		}
	}
}, 1000);