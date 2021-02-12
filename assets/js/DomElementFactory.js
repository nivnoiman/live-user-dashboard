/**
 * Handle DOM element creation
 * I created this for none injection innerHTML script ( use innerText lnstead )
 */
export default class DomElementFactory {
	constructor(elmentBuildingPath) {
		this.path = elmentBuildingPath;
		this.elements = [];
		this.buildStucture();
	}

	/**
	 * Build the structure elements and save to the elements class prop
	 * for using '@','(',')','[',']','<','>' inside attribute use = '%40','%28','%29','%5B','%5D','%3C','%3E'
	 */
	buildStucture = () => {
		const elemnts = this.path.split('>');
		for (let i = 0; i < elemnts.length; i++) {
			const elementData = elemnts[i].split('@');
			for (let j = 0; j < elementData.length; j++) {
				const elementAttr = elementData[j].split('=');
				if (elementAttr.length == 1) { // element
					let tmpElement;
					const tagName = elementData[j].trim();
					if (tagName) {
						if (tagName.includes('<')) {
							let containerIndex = i - tagName.replace(/[^<]/g, "").length - 1;
							tmpElement = document.createElement(tagName.replaceAll('<', '').trim());
							tmpElement.setAttribute('container-index', containerIndex)
						} else {
							tmpElement = document.createElement(tagName);
						}
						this.elements.push(tmpElement);
					}
				} else { // attribute
					if (elementAttr[0] == 'innerText') {
						this.elements[i].innerText = elementAttr[1];
					} else {
						this.elements[i].setAttribute(elementAttr[0], this.replaceUniqueChars(elementAttr[1]));
					}
				}
			}
		}
	}
	/**
	 * handle replace unique chars ( decode )
	 * @param string string to fix
	 * @return string after replaceing unique chars
	 */
	replaceUniqueChars = (string) => {
		const from = ['%40', '%28', '%29', '%5B', '%5D', '%3C', '%3E'];
		const to = ['@', '(', ')', '[', ']', '<', '>'];
		for (let i = 0; i < from.length; i++) {
			string = string.replaceAll(from[i], to[i]);
		}
		return string;
	}
	/**
	 * append the strcure to the container
	 * @param string container selector query selector
	 */
	appendTo = (container) => {
		if (this.elements) {
			const domWrapper = document.querySelector(container);
			let lastContainer;
			for (let i = 0; i < this.elements.length; i++) {
				let containerIndex = this.elements[i].getAttribute('container-index');
				if (containerIndex) { // for fix same tree leaf
					this.elements[i].removeAttribute('container-index');
					lastContainer = this.elements[containerIndex];
				}
				if (!i) {
					lastContainer = domWrapper.appendChild(this.elements[i]);
				} else {
					lastContainer = lastContainer.appendChild(this.elements[i]);
				}
			}
		}
	}
	/**
	 * clear and append the strcure to the container
	 * @param string container selector query selector
	 */
	appendNew = (container) => {
		document.querySelector(container).innerHTML = '';
		this.appendTo(container);
	}
}
