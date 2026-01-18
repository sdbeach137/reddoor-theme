/**
 * Daily Readings Widget JavaScript
 * Handles coin display, set selection, and journey reset
 */

(function($) {
    'use strict';

    // Cookie helpers
    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + "=" + value + ";expires=" + d.toUTCString() + ";path=/";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i=0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Initialize on document ready
    $(document).ready(function() {
        initializeCoins();
        setupEventHandlers();
    });

    function initializeCoins() {
        // Get current day from widget
        const currentDay = parseInt($('.milestone-coins').data('current-day')) || 1;
        
        // Get selected coin set (default: heaven)
        const coinSet = getCookie('rdr_coin_set') || 'heaven';
        applyCoinSet(coinSet);
        
        // Get earned coins
        const earnedCoins = JSON.parse(getCookie('rdr_coins_earned') || '[]');
        
        // Update coin states
        $('.coin').each(function() {
            const coinDay = parseInt($(this).data('day'));
            
            if (currentDay >= coinDay) {
                $(this).addClass('earned');
                
                // Check if this is a newly earned coin
                if (currentDay === coinDay && !earnedCoins.includes(coinDay)) {
                    earnedCoins.push(coinDay);
                    setCookie('rdr_coins_earned', JSON.stringify(earnedCoins), 365);
                    
                    // Show celebration after brief delay
                    setTimeout(function() {
                        showCelebration(coinDay);
                    }, 500);
                }
            }
        });
    }

    function applyCoinSet(setName) {
        // Remove all coin set classes
        $('.milestone-coins').removeClass(function (index, className) {
            return (className.match(/(^|\s)coins-\S+/g) || []).join(' ');
        });
        
        // Add selected coin set class
        if (setName !== 'heaven') {
            $('.milestone-coins').addClass('coins-' + setName);
        }
        
        // Update selector modal
        $('.coin-set-option').removeClass('selected');
        $('.coin-set-option[data-set="' + setName + '"]').addClass('selected');
    }

    function setupEventHandlers() {
        // Coin set selector
        $('#changeCoinSet').on('click', function(e) {
            e.preventDefault();
            $('#coinSelectorModal').addClass('show').show();
        });
        
        // Coin set selection
        $('.coin-set-option').on('click', function() {
            const setName = $(this).data('set');
            setCookie('rdr_coin_set', setName, 365);
            applyCoinSet(setName);
            $('#coinSelectorModal').removeClass('show').hide();
        });
        
        // Journey reset
        $('#resetJourney').on('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to reset your journey? This will clear all progress and start you back at Day 1.')) {
                resetJourney();
            }
        });
        
        // Modal close buttons
        $('.close-modal').on('click', function() {
            $(this).closest('.modal').removeClass('show').hide();
        });
        
        // Click outside modal to close
        $('.modal').on('click', function(e) {
            if ($(e.target).is('.modal')) {
                $(this).removeClass('show').hide();
            }
        });
    }

    function showCelebration(day) {
        const celebrations = {
            1: { emoji: '🚪', title: 'Welcome!', message: 'You\'ve taken your first step through the red door!' },
            7: { emoji: '✨', title: 'One Week!', message: 'You\'ve completed 7 days of readings!' },
            30: { emoji: '🌟', title: 'One Month!', message: 'You\'ve completed 30 days of readings!' },
            60: { emoji: '💫', title: 'Two Months!', message: 'You\'ve completed 60 days of readings!' },
            90: { emoji: '🏆', title: 'Complete!', message: 'You\'ve completed all 90 days! You\'re amazing!' }
        };

        const celebration = celebrations[day];
        if (celebration) {
            $('#celebrationCoin').text(celebration.emoji);
            $('#celebrationTitle').text(celebration.title);
            $('#celebrationMessage').text(celebration.message);
            $('#celebrationModal').addClass('show').show();
        }
    }

    function resetJourney() {
        // Delete existing cookies by setting expiry to past
        document.cookie = 'rdr_journey_start=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = 'rdr_coins_earned=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = 'rdr_coin_set=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        
        // Set new start date
        setCookie('rdr_journey_start', Date.now(), 365);
        setCookie('rdr_coins_earned', JSON.stringify([]), 365);
        
        // Reload page
        location.reload();
    }

})(jQuery);
