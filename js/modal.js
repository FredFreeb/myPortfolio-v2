let modal = document.getElementById("myModal");
let btn = document.getElementById("myButton");
let span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
    modal.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
}
}
////////////////// effet multivers
// let myDiv = document.getElementById('showFullscreenButton');
// 		let toggleFullscreenButton = document.getElementById('toggleFullscreenButton');

// 		toggleFullscreenButton.addEventListener('click', function() {
// 			if (!document.fullscreenElement) {
// 				myDiv.requestFullscreen();
// 			} else {
// 				document.exitFullscreen();
// 			}
// 		});