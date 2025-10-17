/**
 * GA Plugin - Admin Interface JavaScript
 *
 * Handles dynamic UI interactions for tracking script configuration
 *
 * @package GA_Plugin
 * @since   1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Only run on tracking_script post edit screen
        if (!$('body').hasClass('post-type-tracking_script')) {
            return;
        }

        initPageSelectorToggle();
    });

    /**
     * Toggle page selector visibility based on scope selection
     *
     * Shows the page selector when scope is "specific_pages" and hides it
     * when scope is "global". Uses smooth slide animation for better UX.
     *
     * @since 1.0.0
     */
    function initPageSelectorToggle() {
        var $scopeSelect = $('#gap_scope');
        var $pageSelector = $('.gap-pages-selector');

        // Exit if elements don't exist
        if (!$scopeSelect.length || !$pageSelector.length) {
            return;
        }

        // Handle scope selection change
        $scopeSelect.on('change', function() {
            if ($(this).val() === 'specific_pages') {
                $pageSelector.slideDown(200);
            } else {
                $pageSelector.slideUp(200);
            }
        });

        // Set initial state on page load
        $scopeSelect.trigger('change');
    }

})(jQuery);
