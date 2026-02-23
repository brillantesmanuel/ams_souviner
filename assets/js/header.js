/**
 * Header JavaScript
 * Handles header interactions and cart updates
 */

document.addEventListener('DOMContentLoaded', function() {
	// Update cart count on AJAX requests
	if (typeof jQuery !== 'undefined') {
		jQuery(document).on('added_to_cart', function() {
			updateCartCount();
		});

		jQuery(document).on('removed_from_cart', function() {
			updateCartCount();
		});
	}

	// Smooth navbar collapse on scroll
	let lastScrollTop = 0;
	const navbar = document.querySelector('.navbar');
	const topHeader = document.querySelector('.top-header');

	if (navbar) {
		window.addEventListener('scroll', function() {
			let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

			if (scrollTop > 100) {
				navbar.classList.add('sticky-nav');
				if (topHeader) {
					topHeader.style.display = 'none';
				}
			} else {
				navbar.classList.remove('sticky-nav');
				if (topHeader) {
					topHeader.style.display = 'block';
				}
			}

			lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
		});
	}

	// Highlight active menu item
	const currentURL = window.location.href;
	const menuItems = document.querySelectorAll('.navbar-nav .nav-link');

	menuItems.forEach(function(link) {
		if (link.href === currentURL) {
			link.classList.add('active');
		}
	});

	// Cart count update function
	function updateCartCount() {
		const cartUrl = new XMLHttpRequest();
		cartUrl.open('GET', document.location, false);
		cartUrl.send();

		// Update cart badge
		const cartBadge = document.querySelector('.cart-count');
		if (cartBadge && typeof wc_cart_fragments_params !== 'undefined') {
			// Get the total count from WooCommerce
			const cartCount = document.querySelectorAll('.woocommerce-cart-item').length;
			if (cartBadge) {
				setTimeout(function() {
					location.reload(); // Reload to get updated count
				}, 500);
			}
		}
	}

	// Mobile menu toggle
	const navbarToggler = document.querySelector('.navbar-toggler');
	const navbarCollapse = document.querySelector('.navbar-collapse');

	if (navbarToggler && navbarCollapse) {
		navbarToggler.addEventListener('click', function() {
			navbarCollapse.classList.toggle('show');
		});

		// Close menu when link is clicked
		const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
		navLinks.forEach(function(link) {
			link.addEventListener('click', function() {
				if (navbarCollapse.classList.contains('show')) {
					navbarToggler.click();
				}
			});
		});
	}

	// Add hover effect to cart icon
	const cartIcon = document.querySelector('a[href*="cart"]');
	if (cartIcon) {
		cartIcon.addEventListener('mouseenter', function() {
			this.style.transform = 'scale(1.1)';
			this.style.transition = 'transform 0.3s ease';
		});

		cartIcon.addEventListener('mouseleave', function() {
			this.style.transform = 'scale(1)';
		});
	}
});
