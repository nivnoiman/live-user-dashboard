export default class Popup {
	constructor() {
		this.wrapper = document.createElement("div");
		this.wrapper.id = "popup-" + Date.now();
		this.wrapper.classList.add('popup-style');
		this.wrapper.onclick = this.close;
		document.body.appendChild(this.wrapper);

		this.innerWrap = document.createElement("div");
		this.innerWrap.classList.add("popup-inner");
		this.wrapper.appendChild(this.innerWrap);

		this.title = document.createElement("h2");
		this.title.classList.add("popup-title");
		this.innerWrap.appendChild(this.title);

		this.content = document.createElement("div");
		this.content.classList.add("popup-content");
		this.innerWrap.appendChild(this.content);

		this.btnClose = document.createElement("button");
		this.btnClose.classList.add("popup-close");
		this.btnClose.innerHTML = "X";
		this.innerWrap.appendChild(this.btnClose);
	}

	open = function (title, text) {
		this.title.innerHTML = title;
		this.content.innerHTML = text;
	}

	close = (e) => {
		if (e.path && ['popup-style', 'popup-close'].indexOf(e.path[0].className) >= 0 ) {
			document.getElementById(this.wrapper.id).remove();
		}
	}
};
