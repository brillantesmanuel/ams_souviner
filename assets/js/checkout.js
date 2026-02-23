/**
 * Checkout Page JavaScript
 * Handles interactions for Bootstrap-styled checkout page
 */

document.addEventListener('DOMContentLoaded', function() {
	// Handle shipping address toggle
	const shipToDiffAddressCheckbox = document.getElementById('ship-to-different-address-checkbox');
	const shippingAddressFields = document.getElementById('shipping-address-fields');

	if (shipToDiffAddressCheckbox && shippingAddressFields) {
		// Initial state
		if (shipToDiffAddressCheckbox.checked) {
			shippingAddressFields.style.display = 'block';
			shippingAddressFields.classList.add('show');
		} else {
			shippingAddressFields.style.display = 'none';
			shippingAddressFields.classList.remove('show');
		}

		// On change
		shipToDiffAddressCheckbox.addEventListener('change', function() {
			if (this.checked) {
				shippingAddressFields.style.display = 'block';
				shippingAddressFields.classList.add('show');
			} else {
				shippingAddressFields.style.display = 'none';
				shippingAddressFields.classList.remove('show');
			}
		});
	}

	// Handle payment method selection
	const paymentMethods = document.querySelectorAll('.wc_payment_methods input[type="radio"]');
	paymentMethods.forEach(function(method) {
		method.addEventListener('change', function() {
			// Remove selected class from all payment methods
			document.querySelectorAll('.wc_payment_methods li').forEach(function(li) {
				li.classList.remove('selected');
			});

			// Add selected class to current payment method
			if (this.checked) {
				this.closest('li').classList.add('selected');

				// Hide all payment boxes
				document.querySelectorAll('.payment_box').forEach(function(box) {
					box.style.display = 'none';
				});

				// Show relevant payment box
				const paymentBoxId = '.payment_method_' + this.value;
				const paymentBox = this.closest('li').querySelector(paymentBoxId);
				if (paymentBox) {
					paymentBox.style.display = 'block';
				}
			}
		});
	});

	// Initial state for payment methods
	const checkedMethod = document.querySelector('.wc_payment_methods input[type="radio"]:checked');
	if (checkedMethod) {
		checkedMethod.closest('li').classList.add('selected');
	}

	// Form validation message styling
	const formRows = document.querySelectorAll('.woocommerce-form-row');
	formRows.forEach(function(row) {
		const input = row.querySelector('input, select, textarea');
		if (input) {
			if (input.hasAttribute('required') && input.value === '') {
				input.classList.add('is-invalid');
			}

			input.addEventListener('change', function() {
				if (this.hasAttribute('required') && this.value === '') {
					this.classList.add('is-invalid');
				} else {
					this.classList.remove('is-invalid');
				}
			});
		}
	});

	// Scroll to first error on form submission
	const checkoutForm = document.querySelector('form.checkout');
	if (checkoutForm) {
		checkoutForm.addEventListener('submit', function(e) {
			const firstInvalid = this.querySelector('.is-invalid');
			if (firstInvalid) {
				firstInvalid.scrollIntoView({
					behavior: 'smooth',
					block: 'center'
				});
			}
		});
	}

	// Smooth reveal of shipping fields
	const shippingFieldsContainer = document.querySelector('.shipping_address');
	if (shippingFieldsContainer) {
		shippingFieldsContainer.style.overflow = 'hidden';
		shippingFieldsContainer.style.transition = 'max-height 0.3s ease-out';
	}
});
