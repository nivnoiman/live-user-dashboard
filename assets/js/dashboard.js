import config from './config.js';
import { getCookie } from './cookie.js';
import DomElementFactory from './DomElementFactory.js';
import { strings } from './strings.js';
import { handleRefreshOnlineUsers } from './helpers/apiHelper.js';
import Popup from './popup.js';

const Dashboard = () => {

	let userInfo = getCookie(config.user_cookie_name);
	const onlineUsersSelector = '.online-users';
	let onlineUsersWrapper;
	let onlineUsers;
	/**
	 * Create Dom Block Container ( use this instead of regular html just for creative think - pure vanilla js  )
	 */
	const createDashboardBlock = () => {
		const welcomeMessage = strings.dashboard.welcome.replace('%s', userInfo.full_name);
		const dashboardStruction = `
		div@class=dashboard-wrapper>
			div@class=welcome-message@innerText=${welcomeMessage}>
				<
				h2@innerText=Online Users>
				<<
			div@class=online-users@innerText=...`;

		new DomElementFactory(dashboardStruction).appendTo('#main');
	}

	const showOnlineUsers = (users) => {
		onlineUsersWrapper.innerHTML = '';
		for (let i = 0; i < users.length; i++) {
			let onlineUser = `
			div@class=user-item@data-index=${i}>
				div@class=fullname>
				strong@innerText=${users[i].full_name}>
				<<
				div@class=username@innerText=${strings.dashboard.username} ${users[i].username}>
				<<<
				div@class=logintime@innerText=${strings.dashboard.logintime} ${users[i].login_time}>
				<<<<
				div@class=lastupdate@innerText=${strings.dashboard.lastupdate} ${users[i].update_time}>
				<<<<<
				div@class=ip@innerText=${strings.dashboard.ip} ${users[i].ip}>`;
			new DomElementFactory(onlineUser).appendTo(onlineUsersSelector);
		}
	}

	const initFetchingOnlineUsers = () => {
		onlineUsersWrapper = document.querySelector(onlineUsersSelector)
		handleFetchOnlineUsers();
		setInterval(() => {
			handleFetchOnlineUsers();
		}, config.online_refresh_time);
	}

	const handleFetchOnlineUsers = () => {
		handleRefreshOnlineUsers(userInfo.token).then(r => {
			onlineUsers = r.data;
			handleUpdateOnlineUsers(r.data)
		}).catch(e => console.log(e));
	}

	const handleUpdateOnlineUsers = (users) => {
		if (users) {
			showOnlineUsers(users);
		} else {
			onlineUsersWrapper.innerHTML = strings.dashboard.not_found;
		}
	}

	const handleUsersPopup = () => {
		onlineUsersWrapper.addEventListener("click", (e) => {
			let userIndex;
			if (e.path && !e.path[0].classList.contains('online-users')) {
				for (let i = 0; i < e.path.length; i++) {
					if (e.path[i].classList && e.path[i].classList.contains('user-item')) {
						userIndex = e.path[i].dataset.index;
					}
				}
				if (userIndex) {
					const popupContent = `
					<ul>
						<li><span>${strings.dashboard.agent}</span><span>${onlineUsers[userIndex].agent}</span></li>
						<li><span>${strings.dashboard.createdtime}</span><span>${onlineUsers[userIndex].created_time}</span></li>
						<li><span>${strings.dashboard.logincount}</span><span>${onlineUsers[userIndex].login_counter}</span></li>
					</ul>
					`;
					new Popup().open(onlineUsers[userIndex].full_name, popupContent);
				}
			}
		});
	}

	if (userInfo) {
		createDashboardBlock();
		initFetchingOnlineUsers();
		handleUsersPopup();
	}
}

export default Dashboard;
