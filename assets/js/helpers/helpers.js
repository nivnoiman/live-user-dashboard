export const formatDate = (timestamp) => {

	const date = new Date(Date.parse(timestamp));
	const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',	'November', 'December'];

	return months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear()  + date.getTime();
};