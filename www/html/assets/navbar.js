window.addEventListener('load', function() {
	let mainNav = document.getElementById('js-menu');
	let topMenu = document.getElementById('js-top-menu');

	let navbarToggle = document.getElementById('js-navbar-toggle');

	navbarToggle.addEventListener('click', function () {
		topMenu.classList.toggle('active-top');
		mainNav.classList.toggle('active');
	});
});
