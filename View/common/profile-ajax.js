document.getElementById('loadProfileBtn').addEventListener('click', function() {

	const profileDiv = document.getElementById('profileData');
	profileDiv.innerHTML = "Loading profile...";

	const xhr = new XMLHttpRequest();
	xhr.onload = function() {
		console.log(xhr.responseText);
		profileDiv.innerHTML = xhr.responseText;
	}
	xhr.open("GET", "../../Controller/ajax/get-profile.php");
	xhr.send();
});

function submitPasswordChange(pForm) {

	const currentPassword = pForm.currentPassword.value;
	const newPassword = pForm.newPassword.value;
	const confirmPassword = pForm.confirmPassword.value;
	const messageDiv = document.getElementById("passwordMessage");
	let flag = true;

	if (newPassword.length < 6) {
		flag = false;
		alert("New password must be at least 6 characters.");
	}
	if (newPassword !== confirmPassword) {
		flag = false;
		alert("Passwords do not match.");
	}

	if (flag) {

		messageDiv.innerHTML = "Updating password...";

		const xhr = new XMLHttpRequest();
		xhr.onload = function() {
			console.log(xhr.responseText);
			messageDiv.innerHTML = xhr.responseText;
			if (xhr.responseText.indexOf("successfully") !== -1) {
				pForm.reset();
			}
		}
		xhr.open("POST", "../../Controller/ajax/change-password.php");
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.send("current_password=" + encodeURIComponent(currentPassword) +
			"&new_password=" + encodeURIComponent(newPassword) +
			"&confirm_password=" + encodeURIComponent(confirmPassword));
	}

	return false;
}
