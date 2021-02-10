import config from './config.js';
import {getCookie} from './cookie.js';
import handleLogin from './login.js';
import Dashboard from './dashboard.js';
/**
 * Invoke handle login
 */
if( getCookie(config.user_cookie_name) ){
	Dashboard();
} else{
	handleLogin();
}

