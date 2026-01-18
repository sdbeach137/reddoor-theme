<?php
/**
 * Template Name: Homepage
 * Description: Red Door Recovery Network Homepage
 */

get_header(); ?>

<main class="homepage">
    <!-- HERO AND SIDEBAR SECTION -->
    <div class="hero-and-sidebar-container">
        <div class="hero-content">
            <h1 class="hero-title">Find a treatment<br>near you</h1>
            
            <div class="hero-search">
                <form action="<?php echo home_url('/providers/'); ?>" method="GET" class="search-form">
                    <input type="text" 
                           name="s" 
                           class="search-bar" 
                           placeholder="City, ZIP or provider name 🔍" 
                           value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>">
                    <button type="button" class="geolocation-btn" id="useLocation" title="Use my location">
                        📍 Use My Location
                    </button>
                </form>
            </div>
            
            <p class="hero-subtitle">
                Find detailed listings for Substance Abuse professionals in: 🇺🇸 
                <select id="stateSelector" onchange="filterByState(this.value)">
                    <option value="OH">Ohio</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="MI">Michigan</option>
                    <option value="KY">Kentucky</option>
                    <option value="IN">Indiana</option>
                </select>
            </p>
        </div>
        
        <aside class="right-sidebar">
            <!-- RED DOOR GRAPHIC -->
            <div class="hero-door-graphic">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/red-door.png" alt="Red Door">
            </div>
            
            <!-- DAILY READING WIDGET -->
            <div class="daily-reading-widget">
                <div class="widget-header">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/through-the-red-door-header.png" alt="Through the Red Door">
                </div>
                
                <div class="widget-content">
                    <p class="day-counter">Day <span id="currentDay">1</span> of 90</p>
                    
                    <div class="reading-section">
                        <h4>Section & Chapter</h4>
                        <p id="readingSection">Loading...</p>
                    </div>
                    
                    <div class="reading-section">
                        <h4>Core Idea</h4>
                        <p id="coreIdea">Loading...</p>
                    </div>
                    
                    <div class="reading-section">
                        <h4>Modern Clinical Interpretation</h4>
                        <p id="modernInterpretation">Loading...</p>
                    </div>
                    
                    <div class="milestone-coins">
                        <div class="coin" data-day="1" title="Day 1: First Step">🚪</div>
                        <div class="coin" data-day="7" title="Day 7: One Week">🥉</div>
                        <div class="coin" data-day="30" title="Day 30: One Month">🥈</div>
                        <div class="coin" data-day="60" title="Day 60: Two Months">🥇</div>
                        <div class="coin" data-day="90" title="Day 90: Complete">🏆</div>
                    </div>
                    
                    <div class="widget-actions">
                        <button class="btn-read-full">Read Full Passage</button>
                        <a href="#" id="resetJourney" class="reset-link">Reset Journey</a>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- COUNTIES AND CITIES -->
    <div class="main-content">
        <div class="directory-lists">
            <h2>Counties:</h2>
            <div class="county-grid" id="countyGrid">
                <?php
                // Get all counties with provider counts
                $counties = get_terms(array(
                    'taxonomy' => 'rdr_county',
                    'hide_empty' => true,
                    'orderby' => 'name'
                ));
                
                if ($counties && !is_wp_error($counties)) {
                    foreach ($counties as $county) {
                        $url = get_term_link($county);
                        echo '<a href="' . esc_url($url) . '" class="county-link" data-county="' . esc_attr($county->name) . '">' . 
                             esc_html($county->name) . 
                             ' <span class="count">(' . $county->count . ')</span></a>';
                    }
                } else {
                    echo '<p>No counties found. Import providers first.</p>';
                }
                ?>
            </div>

            <h2>Major Cities:</h2>
            <div class="city-grid" id="cityGrid">
                <?php
                // Get top cities with provider counts
                $cities = get_terms(array(
                    'taxonomy' => 'rdr_city',
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 40
                ));
                
                if ($cities && !is_wp_error($cities)) {
                    foreach ($cities as $city) {
                        $url = get_term_link($city);
                        echo '<a href="' . esc_url($url) . '" class="city-link" data-city="' . esc_attr($city->name) . '">' . 
                             esc_html($city->name) . 
                             ' <span class="count">(' . $city->count . ')</span></a>';
                    }
                } else {
                    echo '<p>No cities found. Import providers first.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- RESOURCES SECTION -->
    <section class="resources-section">
        <h2>Get The Help You Need Every Step of The Way</h2>
        <div class="resource-cards">
            <div class="resource-card card-stigma">
                <h3>Stigma</h3>
                <p>Understanding and overcoming stigma in recovery</p>
            </div>
            <div class="resource-card card-trauma">
                <h3>Trauma-Informed Care</h3>
                <p>Evidence-based approaches to healing</p>
            </div>
            <div class="resource-card card-about">
                <h3>About Us</h3>
                <p>Learn about Red Door Recovery Network</p>
            </div>
            <div class="resource-card card-peer">
                <h3>Peer Recovery Support</h3>
                <p>Connect with others in recovery</p>
            </div>
        </div>
    </section>

    <!-- Celebration Modal -->
    <div id="celebrationModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="celebration-content">
                <div class="coin-large" id="celebrationCoin">🏆</div>
                <h2 id="celebrationTitle">Milestone Achieved!</h2>
                <p id="celebrationMessage">Congratulations on your progress!</p>
            </div>
        </div>
    </div>
</main>

<script>
// Geolocation functionality
document.getElementById('useLocation').addEventListener('click', function() {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser');
        return;
    }

    this.textContent = '📍 Finding location...';
    this.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Redirect to providers page with coordinates
            window.location.href = '<?php echo home_url('/providers/'); ?>?lat=' + lat + '&lng=' + lng + '&near=me';
        },
        function(error) {
            alert('Unable to get your location: ' + error.message);
            document.getElementById('useLocation').textContent = '📍 Use My Location';
            document.getElementById('useLocation').disabled = false;
        }
    );
});

// State filter function
function filterByState(state) {
    window.location.href = '<?php echo home_url('/providers/'); ?>?state=' + state;
}

// Search form enhancement
document.querySelector('.search-form').addEventListener('submit', function(e) {
    const searchInput = this.querySelector('input[name="s"]');
    if (!searchInput.value.trim()) {
        e.preventDefault();
        alert('Please enter a city, ZIP code, or provider name');
        return false;
    }
});
</script>

<?php get_footer(); ?>
