import { setCookie } from './cookie.js';
import { validateEmail, vlidateRequiredFields } from './formHelper.js';

/**
 * Handle all login functionality
 */
const handleLogin = () => {
	const loginForm = document.getElementById("login-form");
	const loginButton = document.getElementById("login-submit");
	const loginMsg = document.getElementById("login-message");
	/**
	 * clean all current messages
	 */
	const cleanMessage = () => {
		loginMsg.innerText = '';
	}
	/**
	 * Append message to the container
	 */
	const showMessage = (message, type) => {
		loginMsg.innerText = message;
		loginMsg.classList.remove('success', 'error');
		loginMsg.classList.add(type);
	}

	if (loginButton) {
		loginButton.addEventListener("click", (e) => {
			e.preventDefault();
			cleanMessage();

			const email = loginForm.email.value;
			const password = loginForm.password.value;

			if (!vlidateRequiredFields(loginForm)) {
				if (validateEmail(email)) {
					if (email === "test@test.com" && password === "123456789") {
						showMessage('Please Wait...', 'success');
						setCookie('user', '12345');
					} else {
						showMessage('Ops.. Invalid email or/and password', 'error');
					}
				} else {
					showMessage('Email is not valid ', 'error');
				}
			} else {
				showMessage('Empty Fields ', 'error');
			}
		})
	}
}
export default handleLogin;