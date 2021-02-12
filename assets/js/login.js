import config from './config.js'
import { setCookie } from './cookie.js';
import { userLogin } from './helpers/apiHelper.js';
import { validateEmail, vlidateRequiredFields } from './helpers/formHelper.js';
import DomElementFactory from './DomElementFactory.js';
import { strings } from './strings.js';
/**
 * Handle all login functionality
 */
const Login = () => {
	/**
	 * Create Dom Form Container ( use this instead of regular html just for creative think - pure vanilla js  )
	 */
	const createLoginForm = () => {
		const loginStruction = `
		div@class=login-warpper>
			form@method=post@id=login-form>
				fieldset>
					label@for=login-email>
						span@innerText=${strings.login.email}>
						<
						input@type=text@name=email@id=login-email@value=@placeholder=${strings.login.email_placeholder}@required=required>
						<<<
					label@for=login-password>
						span@innerText=${strings.login.password}>
						<
						input@type=password@name=password@id=login-password@value=@placeholder=${strings.login.password_placeholder}@required=required>
						<<<<<<<
					button@type=submit@id=login-submit>
						span@innerText=${strings.login.btn}>
						<<<<<<<<<<
					div@class=login-message-result@id=login-message`;

		new DomElementFactory(loginStruction).appendTo('#main');
	}

	const handleLoginForm = () => {
		const loginForm = document.getElementById("login-form");
		const loginButton = document.getElementById("login-submit");
		const loginMsg = document.getElementById("login-message");

		loginButton.addEventListener("click", (e) => {
			e.preventDefault();
			cleanMessage();

			const email = loginForm.email.value;
			const password = loginForm.password.value;

			if (!vlidateRequiredFields(loginForm)) {
				if (validateEmail(email)) {
					showMessage('Please Wait...');
					userLogin(email, password).then(r => {
						if( r.success ){
							console.log(r.data);
							setCookie(config.user_cookie_name, {...r.data});
							window.location.reload();
						} else{
							showMessage('Ops.. Invalid email or/and password', 'error');
						}
					}).catch(e => showMessage(`Error ${e}`, 'error'));
				} else {
					showMessage('Email is not valid ', 'error');
				}
			} else {
				showMessage('Empty Fields ', 'error');
			}
		})

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
	}
	/**
	 * Invoke Creation
	 */
	createLoginForm();
	handleLoginForm();

}
export default Login;