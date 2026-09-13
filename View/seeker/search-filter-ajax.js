function submitFilter(pForm) {

	const location = pForm.location.value;
	const roomDetails = document.querySelectorAll('input[name="room_details[]"]:checked');
	const price = pForm.price.value;

	let url = "../../Controller/ajax/filter-listings.php?";

	if (location) {
		url += "location=" + encodeURIComponent(location) + "&";
	}

	if (roomDetails.length > 0) {
		const values = [];
		roomDetails.forEach(function(cb) {
			values.push(cb.value);
		});
		url += "room_details=" + encodeURIComponent(values.join(",")) + "&";
	}

	if (price) {
		url += "price=" + encodeURIComponent(price) + "&";
	}

	url = url.replace(/[&?]$/, "");

	const resultsList = document.getElementById("resultsList");
	const resultsCount = document.getElementById("resultsCount");
	resultsList.innerHTML = "Searching for listings...";
	resultsCount.innerHTML = "";

	const xhr = new XMLHttpRequest();
	xhr.onload = function() {
		console.log(xhr.responseText);
		resultsCount.innerHTML = "";
		resultsList.innerHTML = xhr.responseText;
	}
	xhr.open("GET", url);
	xhr.send();

	return false;
}
