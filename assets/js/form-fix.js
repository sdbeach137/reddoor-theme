/* ==========================================================================
   FORM ERROR FIX
   
   Add this to your theme's main JavaScript file
   OR create a new file and enqueue it in functions.php
   
   Upload to: /wp-content/themes/reddoor-theme/assets/js/form-fix.js
   ========================================================================== */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Safely check for search form before adding listeners
        var searchForm = document.querySelector('.search-form');
        var searchInput = document.querySelector('.search-input');
        var searchBar = document.querySelector('.search-bar');
        
        // Only add event listeners if form exists
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                // Form handling code here
            });
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                // Input handling code here
            });
        }
        
        if (searchBar) {
            searchBar.addEventListener('keyup', function(e) {
                // Keyup handling code here
            });
        }
        
        // Prevent any undefined errors for removed elements
        window.handleSearch = window.handleSearch || function() {};
        window.submitSearch = window.submitSearch || function() {};
        
    });
})();
