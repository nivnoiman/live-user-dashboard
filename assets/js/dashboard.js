import config from './config.js';
import {getCookie} from './cookie.js';



const Dashboard = () => {
	const dashbaord = document.getElementById("dashboard");
	const userInfoWrapper = dashbaord.querySelector('.user-info');
	let userInfo    = getCookie(config.user_cookie_name);

	const createInfoBlock = ( labelText, infoText ) => {
		const container = document.createElement("div");
		const label = document.createElement("span");
		const info = document.createElement("span");

		label.innerText = labelText;
		info.innerText = infoText;

		container.appendChild(label);
		container.appendChild(info);

		userInfoWrapper.appendChild(container);
	}

	const writeUserInfo = () =>{
		const welcomeWrapper = dashbaord.querySelector('.welcome-message');
		welcomeWrapper.innerText = 'Welcome ' + userInfo.fullname;

		createInfoBlock('Username', userInfo.username);
		createInfoBlock('Login time', userInfo.logintime);
		createInfoBlock('Last update time', userInfo.updatetime);
		createInfoBlock('User IP', userInfo.ip);

	}

	if( userInfo ){
		writeUserInfo()
	}
}

export default Dashboard;