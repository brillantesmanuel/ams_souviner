/**
 * Front Page JavaScript
 * Handles animations and interactions for the front page
 */

document.addEventListener('DOMContentLoaded', function() {
	// Smooth scroll for anchor links
	const anchorLinks = document.querySelectorAll('a[href^="#"]');
	anchorLinks.forEach(function(link) {
		link.addEventListener('click', function(e) {
			const href = this.getAttribute('href');
			if (href !== '#' && document.querySelector(href)) {
				e.preventDefault();
				const target = document.querySelector(href);
				target.scrollIntoView({
					behavior: 'smooth',
					block: 'start'
				});
			}
		});
	});

	// Intersection Observer for fade-in animations
	const observerOptions = {
		threshold: 0.1,
		rootMargin: '0px 0px -50px 0px'
	};

	const observer = new IntersectionObserver(function(entries) {
		entries.forEach(function(entry) {
			if (entry.isIntersecting) {
				entry.target.classList.add('fade-in-visible');
				observer.unobserve(entry.target);
			}
		});
	}, observerOptions);

	// Observe all sections for fade-in effect
	const sections = document.querySelectorAll(
		'.hero-section, .categories-section, .featured-products-section, .deals-section, .newsletter-section'
	);
	sections.forEach(function(section) {
		observer.observe(section);
	});

	// Add fade-in class for initial styling
	sections.forEach(function(section) {
		section.classList.add('fade-in');
	});

	// Lazy load images
	if ('IntersectionObserver' in window) {
		const images = document.querySelectorAll('img[data-lazy]');
		const imageObserver = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					const img = entry.target;
					img.src = img.dataset.lazy;
					img.removeAttribute('data-lazy');
					imageObserver.unobserve(img);
				}
			});
		});
		images.forEach(function(img) {
			imageObserver.observe(img);
		});
	}

	// Add hover effects to category cards
	const categoryCards = document.querySelectorAll('.category-card');
	categoryCards.forEach(function(card) {
		card.addEventListener('mouseenter', function() {
			this.classList.add('hovered');
		});
		card.addEventListener('mouseleave', function() {
			this.classList.remove('hovered');
		});
	});

	// Add to cart button feedback
	const addToCartButtons = document.querySelectorAll('.product-card .button');
	addToCartButtons.forEach(function(button) {
		button.addEventListener('click', function() {
			// Show temporary feedback
			const originalText = this.textContent;
			this.textContent = 'Adding...';
			
			setTimeout(function() {
				button.textContent = originalText;
			}, 1500);
		});
	});

	// Product card intersection for staggered load
	const productCards = document.querySelectorAll('.product-card');
	const productObserver = new IntersectionObserver(function(entries) {
		entries.forEach(function(entry, index) {
			if (entry.isIntersecting) {
				setTimeout(function() {
					entry.target.classList.add('fade-in-visible');
				}, index * 100);
			}
		});
	}, { threshold: 0.1 });

	productCards.forEach(function(card) {
		card.classList.add('fade-in');
		productObserver.observe(card);
	});

	// Newsletter form submission
	const newsletterForm = document.querySelector('.newsletter-section form');
	if (newsletterForm) {
		newsletterForm.addEventListener('submit', function(e) {
			e.preventDefault();
			const button = this.querySelector('.btn');
			const originalText = button.textContent;
			
			button.disabled = true;
			button.textContent = 'Subscribing...';

			// Simulate API call
			setTimeout(function() {
				button.textContent = 'Subscribed!';
				button.classList.remove('btn-light');
				button.classList.add('btn-success');
				
				setTimeout(function() {
					button.disabled = false;
					button.textContent = originalText;
					button.classList.add('btn-light');
					button.classList.remove('btn-success');
					newsletterForm.reset();
				}, 2000);
			}, 1500);
		});
	}

	// Smooth counter animation for stats (if added later)
	const counters = document.querySelectorAll('[data-count]');
	const counterObserver = new IntersectionObserver(function(entries) {
		entries.forEach(function(entry) {
			if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
				animateCounter(entry.target);
				entry.target.classList.add('counted');
				counterObserver.unobserve(entry.target);
			}
		});
	}, { threshold: 0.5 });

	counters.forEach(function(counter) {
		counterObserver.observe(counter);
	});

	// Counter animation function
	function animateCounter(element) {
		const target = parseInt(element.dataset.count, 10);
		const duration = 2000;
		const increment = target / (duration / 16);
		let current = 0;

		const timer = setInterval(function() {
			current += increment;
			if (current >= target) {
				element.textContent = target;
				clearInterval(timer);
			} else {
				element.textContent = Math.floor(current);
			}
		}, 16);
	}

	// Scroll to top button
	const windowHeight = window.innerHeight;
	const scrollButton = document.querySelector('.scroll-to-top');
	
	if (scrollButton) {
		window.addEventListener('scroll', function() {
			if (window.scrollY > windowHeight) {
				scrollButton.classList.add('show');
			} else {
				scrollButton.classList.remove('show');
			}
		});

		scrollButton.addEventListener('click', function() {
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		});
	}
});

// Add CSS classes for fade-in animations
const style = document.createElement('style');
style.textContent = `
	.fade-in {
		opacity: 0;
		transition: opacity 0.6s ease-out;
	}
	
	.fade-in-visible {
		opacity: 1;
	}

	.scroll-to-top {
		position: fixed;
		bottom: 20px;
		right: 20px;
		background: #0d6efd;
		color: white;
		border: none;
		border-radius: 50%;
		width: 50px;
		height: 50px;
		cursor: pointer;
		display: none;
		z-index: 999;
		font-size: 1.5rem;
		box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
		transition: all 0.3s ease;
	}

	.scroll-to-top.show {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.scroll-to-top:hover {
		background: #0a58ca;
		transform: translateY(-5px);
		box-shadow: 0 6px 20px rgba(13, 110, 253, 0.6);
	}
`;
document.head.appendChild(style);
