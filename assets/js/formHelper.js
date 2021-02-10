export const validateEmail = (email) => {
	const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}

export const vlidateRequiredFields = (formElement) => {
	const reqInputs = formElement.querySelectorAll("[required]");
	let error = false;
	[].forEach.call(reqInputs, input => {
		if (input.value.length == 0) {
			error = true;
			input.style.borderColor = "red"
		} else {
			input.style.borderColor = ""
		}
	});

	return error;
}