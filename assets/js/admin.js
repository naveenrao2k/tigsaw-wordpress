/**
 * Tigsaw Admin JavaScript
 */
(function($) {
	'use strict';

	$(document).ready(function() {
		const domain = tigsawAdmin.domain;
		const savedContainerId = tigsawAdmin.savedContainerId;

		// Function to verify container with API
		function verifyContainer(containerId, onSuccess, onError, isModal) {
			const loadingEl = isModal ? '#tigsaw-modal-activation-loading' : '#tigsaw-activation-loading';
			const errorEl = isModal ? '#tigsaw-modal-activation-error' : '#tigsaw-activation-error';
			const errorMsgEl = isModal ? '#tigsaw-modal-activation-error-message' : '#tigsaw-activation-error-message';

			// Skip verification on localhost or 127.0.0.1
			if (domain.indexOf('localhost') !== -1 || domain.indexOf('127.0.0.1') !== -1) {
				onSuccess();
				return;
			}

			$(loadingEl).removeClass('hidden').show();
			$(errorEl).hide();

			$.ajax({
				url: 'https://tigsaw.com/api/integration/verify?containerId=' + encodeURIComponent(containerId),
				type: 'PUT',
				dataType: 'json',
				success: function(response) {
					$(loadingEl).hide();
					
					if (response && response.status === true) {
						// Verification successful
						console.log('Container verified:', response.message);
						onSuccess();
					} else {
						// Container not found or verification failed
						const errorMessage = response.message || tigsawL10n.verificationFailed;
						$(errorMsgEl).text(errorMessage);
						$(errorEl).removeClass('hidden').show();
						onError(errorMessage);
					}
				},
				error: function(xhr, status, error) {
					$(loadingEl).hide();
					
					let errorMessage = tigsawL10n.unableToVerify;
					
					if (xhr.status === 404) {
						errorMessage += tigsawL10n.containerNotFound;
					} else if (xhr.status === 500) {
						errorMessage += tigsawL10n.internalError;
					} else {
						errorMessage += tigsawL10n.checkConnection;
					}
					
					$(errorMsgEl).text(errorMessage);
					$(errorEl).removeClass('hidden').show();
					onError(errorMessage);
					console.error('Verification API Error:', error, xhr);
				}
			});
		}

		// Intercept main form submission
		$('#tigsaw-settings-form').on('submit', function(e) {
			e.preventDefault();
			
			const selectedContainerId = $('#tigsaw_container_id').val();
			
			if (!selectedContainerId) {
				alert(tigsawL10n.selectContainer);
				return;
			}

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			$submitBtn.prop('disabled', true);

			// Verify container before submitting
			verifyContainer(
				selectedContainerId,
				function() {
					// Success - submit the form
					$submitBtn.prop('disabled', false);
					$form.off('submit').submit();
				},
				function(errorMessage) {
					// Error - re-enable button
					$submitBtn.prop('disabled', false);
				},
				false
			);
		});

		// Intercept modal form submission
		$('#tigsaw-modal-form').on('submit', function(e) {
			e.preventDefault();
			
			const selectedContainerId = $('#tigsaw_modal_container_id').val();
			
			if (!selectedContainerId) {
				alert(tigsawL10n.selectContainer);
				return;
			}

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			$submitBtn.prop('disabled', true);

			// Verify container before submitting
			verifyContainer(
				selectedContainerId,
				function() {
					// Success - submit the form
					$submitBtn.prop('disabled', false);
					$form.off('submit').submit();
				},
				function(errorMessage) {
					// Error - re-enable button
					$submitBtn.prop('disabled', false);
				},
				true
			);
		});

		// Modal fetch containers button
		$('#tigsaw-modal-fetch-btn').on('click', function() {
			$('#tigsaw-modal-loading').removeClass('hidden').show();
			$('#tigsaw-modal-error').hide();
			
			$.ajax({
				url: 'https://tigsaw.com/api/integration/get-container?url=' + encodeURIComponent(domain),
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					$('#tigsaw-modal-loading').hide();
					
					if (response && response.containerIds && Array.isArray(response.containerIds) && response.containerIds.length > 0) {
						const $select = $('#tigsaw_modal_container_id');
						$select.empty();
						$select.append('<option value="">' + tigsawL10n.selectContainerOption + '</option>');
						
						response.containerIds.forEach(function(containerId) {
							$select.append('<option value="' + containerId + '">' + containerId + '</option>');
						});
						
						$('#tigsaw-modal-fetch-section').hide();
						$('#tigsaw-modal-form').removeClass('hidden').show();
					} else {
						$('#tigsaw-modal-error').removeClass('hidden').show();
					}
				},
				error: function(xhr, status, error) {
					$('#tigsaw-modal-loading').hide();
					$('#tigsaw-modal-error').removeClass('hidden').show();
					$('#tigsaw-modal-fetch-section').show();
					console.error('API Error:', error);
				}
			});
		});

		// Main fetch containers button
		$('#tigsaw-fetch-btn').on('click', function() {
			$('#tigsaw-loading').removeClass('hidden').show();
			$('#tigsaw-error').hide();
			$('#tigsaw-fetch-section').hide();
			$('#tigsaw-manual-section').hide();
			
			$.ajax({
				url: 'https://tigsaw.com/api/integration/get-container?url=' + encodeURIComponent(domain),
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					$('#tigsaw-loading').hide();
					
					if (response && response.containerIds && Array.isArray(response.containerIds) && response.containerIds.length > 0) {
						const $select = $('#tigsaw_container_id');
						$select.empty();
						$select.append('<option value="">' + tigsawL10n.selectContainerOption + '</option>');
						
						response.containerIds.forEach(function(containerId) {
							$select.append('<option value="' + containerId + '">' + containerId + '</option>');
						});
						
						$('#tigsaw-settings-form').removeClass('hidden').show();
					} else {
						$('#tigsaw-error').removeClass('hidden').show();
						$('#tigsaw-fetch-section').show();
					}
				},
				error: function(xhr, status, error) {
					$('#tigsaw-loading').hide();
					$('#tigsaw-error').removeClass('hidden').show();
					$('#tigsaw-fetch-section').show();
					console.error('API Error:', error);
				}
			});
		});

		// Main manual container ID button
		$('#tigsaw-manual-btn').on('click', function() {
			$('#tigsaw-fetch-section').hide();
			$('#tigsaw-loading').hide();
			$('#tigsaw-error').hide();
			$('#tigsaw-settings-form').hide();
			$('#tigsaw-manual-section').removeClass('hidden').show();
			$('#tigsaw-manual-input').focus();
		});

		// Main manual container ID confirmation
		$('#tigsaw-manual-confirm').on('click', function() {
			const manualContainerId = $('#tigsaw-manual-input').val().trim();
			
			if (manualContainerId === '') {
				alert(tigsawL10n.enterValid);
				return;
			}

			// Validate format (alphanumeric, typically 8 characters)
			if (!/^[A-Z0-9]{6,12}$/i.test(manualContainerId)) {
				if (!confirm(tigsawL10n.containerFormat)) {
					return;
				}
			}

			// Populate dropdown with manual ID
			const $select = $('#tigsaw_container_id');
			$select.empty();
			$select.append('<option value="">' + tigsawL10n.selectContainerOption + '</option>');
			$select.append('<option value="' + manualContainerId + '" selected>' + manualContainerId + tigsawL10n.manualLabel + '</option>');

			// Hide manual section and show form
			$('#tigsaw-manual-section').hide();
			$('#tigsaw-settings-form').removeClass('hidden').show();
		});

		// Allow Enter key in main manual input
		$('#tigsaw-manual-input').on('keypress', function(e) {
			if (e.which === 13) {
				e.preventDefault();
				$('#tigsaw-manual-confirm').trigger('click');
			}
		});

		// Modal manual container ID button
		$('#tigsaw-modal-manual-btn').on('click', function() {
			$('#tigsaw-modal-fetch-section').hide();
			$('#tigsaw-modal-loading').hide();
			$('#tigsaw-modal-error').hide();
			$('#tigsaw-modal-form').hide();
			$('#tigsaw-modal-manual-section').removeClass('hidden').show();
			$('#tigsaw-modal-manual-input').focus();
		});

		// Modal manual container ID confirmation
		$('#tigsaw-modal-manual-confirm').on('click', function() {
			const manualContainerId = $('#tigsaw-modal-manual-input').val().trim();
			
			if (manualContainerId === '') {
				alert(tigsawL10n.enterValid);
				return;
			}

			// Validate format (alphanumeric, typically 8 characters)
			if (!/^[A-Z0-9]{6,12}$/i.test(manualContainerId)) {
				if (!confirm(tigsawL10n.containerFormat)) {
					return;
				}
			}

			// Populate dropdown with manual ID
			const $select = $('#tigsaw_modal_container_id');
			$select.empty();
			$select.append('<option value="">' + tigsawL10n.selectContainerOption + '</option>');
			$select.append('<option value="' + manualContainerId + '" selected>' + manualContainerId + tigsawL10n.manualLabel + '</option>');

			// Hide manual section and show form
			$('#tigsaw-modal-manual-section').hide();
			$('#tigsaw-modal-form').removeClass('hidden').show();
		});

		// Allow Enter key in modal manual input
		$('#tigsaw-modal-manual-input').on('keypress', function(e) {
			if (e.which === 13) {
				e.preventDefault();
				$('#tigsaw-modal-manual-confirm').trigger('click');
			}
		});

		// Remove script handler
		$('#tigsaw-remove-script').on('click', function() {
			if (confirm(tigsawL10n.removeConfirm)) {
				// Create hidden form to submit removal
				var form = $('<form method="post" action="options.php"></form>');
				form.append(tigsawAdmin.nonce);
				form.append('<input type="hidden" name="option_page" value="tigsaw_settings_group">');
				form.append('<input type="hidden" name="action" value="update">');
				form.append('<input type="hidden" name="tigsaw_container_id" value="">');
				form.append('<input type="hidden" name="tigsaw_script_enabled" value="0">');
				$('body').append(form);
				form.submit();
			}
		});

		// Change container button
		$('#tigsaw-change-container').on('click', function() {
			$('#tigsaw-change-modal').removeClass('hidden').show();
		});

		// Modal close button
		$('#tigsaw-modal-close, #tigsaw-modal-cancel').on('click', function() {
			$('#tigsaw-change-modal').hide();
			// Reset modal state
			$('#tigsaw-modal-fetch-section').show();
			$('#tigsaw-modal-loading').hide();
			$('#tigsaw-modal-error').hide();
			$('#tigsaw-modal-form').hide();
			$('#tigsaw-modal-manual-section').hide();
			$('#tigsaw-modal-activation-loading').hide();
			$('#tigsaw-modal-activation-error').hide();
		});

		// Close modal on background click
		$('#tigsaw-change-modal').on('click', function(e) {
			if (e.target === this) {
				$(this).hide();
				// Reset modal state
				$('#tigsaw-modal-fetch-section').show();
				$('#tigsaw-modal-loading').hide();
				$('#tigsaw-modal-error').hide();
				$('#tigsaw-modal-form').hide();
				$('#tigsaw-modal-manual-section').hide();
				$('#tigsaw-modal-activation-loading').hide();
				$('#tigsaw-modal-activation-error').hide();
			}
		});
	});

})(jQuery);
