/**
 * Set cookie
 * @param string name - cookie name ( unique )
 * @param string value - cookie data
 * @param int days - how many days to save the cookie
 * @return void
 */
export const setCookie = (name, value, days) => {
	var expires = "";
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
/**
 * Get exist cookie by name
 * @param string name - the unique name of the cookie
 * @return mixed (null|string) null if not exists or cookie data on success
 */
export const getCookie = (name) => {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}
/**
 * Remove cookie by name 
 * @param string name - the unique name of the cookie
 */
export const removeCookie = (name) => {
	document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}