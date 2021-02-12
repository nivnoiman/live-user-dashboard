import config from './config.js';
import { getCookie } from './cookie.js';
import Login from './login.js';
import Dashboard from './dashboard.js';
/**
 * Invoke App
 */
window.addEventListener('load', () => {
	if (getCookie(config.user_cookie_name)) {
		Dashboard();
	} else {
		Login();
	}
});

