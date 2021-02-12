import config from './config.js'
/**
 * Retrieve the Api Endpoint
 * @param string endpoint 
 */
const getApiEndpoint = (endpoint) => {
	return `${config.server_ip}:${config.server_port}/server/${endpoint}`;
}
/**
 * Fetch Api request
 * @param string enpoint - service endpoint
 * @param object params - api request params
 * @return Promise
 */
const fetchRequest = async (endpoint, params, method) => {

	const requestOptions = {
		method: method,
		redirect: 'follow',
		cache: 'no-cache',
	};
	return await fetch(getApiEndpoint('?' + endpoint + '&') + queryParams(params), requestOptions).then(data => {
		return data.json().then(jsonData => {
			return jsonData;
		})
	})
}

/**
 * Retrieve the Api Token
 * @param Object params - the params to send to the request
 * @return string - params ready to send via fetch
 */
const queryParams = (params) => {
	return Object.keys(params)
		.map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
		.join('&');
}
/**
 * Handle User login fetch request
 * @param string email 
 * @param string password 
 * @return Promise
 */
export const userLogin = async (email, password) => {
	return await fetchRequest('user_login', { email, password })
}
/**
 * Retrieve online users & update current user online ping
 * @return Promise
 */
export const handleRefreshOnlineUsers = async (token) => {
	return await fetchRequest('ping_online_user', { token }, 'PATCH');
}