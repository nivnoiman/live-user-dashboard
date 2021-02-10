/**
 * Retrieve the Api Endpoint
 * @param string endpoint 
 */
const getApiEndpoint = (endpoint) => {
	return 'localhost:80/server/api!' + endpoint;
}
/**
 * Fetch Api request
 * @param string enpoint - service endpoint
 * @param object params - api request params 
 */
const fetchRequest = async (endpoint, parms) => {
	return await fetch(getApiEndpoint(endpoint + '?') + queryParams(), {
		method: 'GET',
		cache: 'no-cache',
		headers: {
			'Content-Type': 'application/json',
		}
	}).then(data => {
		return data.json().then(jsonData => {
			return jsonData;
		})
	})
}

const loginUser = async (email, password){
	fetchRequest('get_user', { email, password })
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