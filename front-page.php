<?php
/**
 * Template Name: Homepage
 * Description: Red Door Recovery Network Homepage
 */

get_header(); ?>

<main class="homepage">
    <!-- HERO SEARCH SECTION -->
    <div class="hero-search-section">
        <div class="hero-search-content">
            <h1 class="hero-title">Find a treatment<br>near you</h1>
            
            <div class="hero-location">
                <button type="button" class="geolocation-btn-large" id="useLocation" title="Use my location">
                    📍 Use My Location
                </button>
                <p class="location-helper">Or browse by county or city below</p>
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
        
        <aside class="right-sidebar-top">
            <!-- RED DOOR GRAPHIC -->
            <div class="hero-door-graphic">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/red_door_no_texts.png" 
                     alt="Red Door" 
                     style="width: 420px !important; max-width: 420px !important; height: auto !important;">
            </div>
        </aside>
    </div>

    <!-- COUNTIES AND WIDGET ROW -->
    <div class="counties-and-widget-container">
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
        
        <!-- DAILY READING WIDGET -->
        <aside class="right-sidebar-widget">
            <div class="daily-reading-widget">
                <div class="widget-header">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/through-the-red-door-header.png" alt="Through the Red Door">
                </div>
                
                <div class="widget-content">
                    <?php
                    // Get or set journey start date
                    if (!isset($_COOKIE['rdr_journey_start'])) {
                        $start_date = time();
                        setcookie('rdr_journey_start', $start_date, time() + (365 * 24 * 60 * 60), '/');
                    } else {
                        $start_date = intval($_COOKIE['rdr_journey_start']);
                    }
                    
                    // Calculate current day (1-90)
                    $days_passed = floor((time() - $start_date) / (60 * 60 * 24));
                    $current_day = ($days_passed % 90) + 1;
                    
                    // Ensure day is always positive
                    if ($current_day < 1) {
                        $current_day = 1;
                    }
                    
                    // Get current day's reading from database
                    $reading_query = new WP_Query(array(
                        'post_type' => 'rdr_reading',
                        'meta_key' => '_day_number',
                        'meta_value' => $current_day,
                        'posts_per_page' => 1
                    ));
                    
                    $reading_section = 'Day ' . $current_day;
                    $core_idea = 'Welcome to your daily reading journey.';
                    $interpretation = 'Each day brings new insights and opportunities for growth.';
                    
                    if ($reading_query->have_posts()) {
                        $reading_query->the_post();
                        $reading_section = get_post_meta(get_the_ID(), '_section', true) . ' - ' . get_the_title();
                        $core_idea = get_post_meta(get_the_ID(), '_core_idea', true);
                        $interpretation = get_post_meta(get_the_ID(), '_interpretation', true);
                        wp_reset_postdata();
                    }
                    ?>
                    
                    <p class="day-counter">Day <span id="currentDay"><?php echo $current_day; ?></span> of 90</p>
                    
                    <div class="reading-section">
                        <h4>Section & Chapter</h4>
                        <p id="readingSection"><?php echo esc_html($reading_section); ?></p>
                    </div>
                    
                    <div class="reading-section">
                        <h4>Core Idea</h4>
                        <p id="coreIdea"><?php echo esc_html($core_idea); ?></p>
                    </div>
                    
                    <div class="reading-section">
                        <h4>Modern Clinical Interpretation</h4>
                        <p id="modernInterpretation"><?php echo esc_html($interpretation); ?></p>
                    </div>
                    
                    <!-- COIN SPRITE DISPLAY -->
                    <div class="milestone-coins" data-current-day="<?php echo $current_day; ?>">
                        <div class="coin coin-day1" data-day="1" data-position="0" title="Day 1: First Step"></div>
                        <div class="coin coin-day7" data-day="7" data-position="1" title="Day 7: One Week"></div>
                        <div class="coin coin-day30" data-day="30" data-position="2" title="Day 30: One Month"></div>
                        <div class="coin coin-day60" data-day="60" data-position="3" title="Day 60: Two Months"></div>
                        <div class="coin coin-day90" data-day="90" data-position="4" title="Day 90: Complete"></div>
                    </div>
                    
                    <div class="widget-actions">
                        <a href="<?php echo home_url('/full-reading/'); ?>" class="btn-read-full">Read Full Passage</a>
                        <a href="#" id="changeCoinSet" class="change-coins-link">Change Coin Style</a>
                        <a href="#" id="resetJourney" class="reset-link">Reset Journey</a>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- RESOURCES SECTION -->
    <section class="resources-section">
        <h2>Get The Help You Need Every Step of The Way</h2>
        <div class="resource-cards">
            <a href="<?php echo home_url('/stigma/'); ?>" class="resource-card card-stigma">
                <h3>Stigma</h3>
                <p>Understanding and overcoming stigma in recovery</p>
            </a>
            <a href="<?php echo home_url('/trauma-informed-care/'); ?>" class="resource-card card-trauma">
                <h3>Trauma-Informed Care</h3>
                <p>Evidence-based approaches to healing</p>
            </a>
            <a href="<?php echo home_url('/about-us/'); ?>" class="resource-card card-about">
                <h3>About Us</h3>
                <p>Learn about Red Door Recovery Network</p>
            </a>
            <a href="<?php echo home_url('/peer-support/'); ?>" class="resource-card card-peer">
                <h3>Peer Recovery Support</h3>
                <p>Connect with others in recovery</p>
            </a>
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

    <!-- Coin Set Selector Modal -->
    <div id="coinSelectorModal" class="modal coin-selector-modal">
        <div class="modal-content coin-selector-content">
            <span class="close-modal">&times;</span>
            <h2>Choose Your Coin Style</h2>
            <p>Select the milestone coins you'd like to earn on your journey:</p>
            
            <div class="coin-sets-grid">
                <div class="coin-set-option" data-set="heaven">
                    <div class="coin-preview coin-preview-heaven"></div>
                    <h3>Heaven</h3>
                    <p>Angelic clouds and divine light</p>
                </div>
                
                <div class="coin-set-option" data-set="skull">
                    <div class="coin-preview coin-preview-skull"></div>
                    <h3>Memento Mori</h3>
                    <p>Dark reflection and transformation</p>
                </div>
                
                <div class="coin-set-option" data-set="underwater">
                    <div class="coin-preview coin-preview-underwater"></div>
                    <h3>Ocean Depths</h3>
                    <p>Underwater serenity and life</p>
                </div>
                
                <div class="coin-set-option" data-set="lotus">
                    <div class="coin-preview coin-preview-lotus"></div>
                    <h3>Lotus Bloom</h3>
                    <p>Spiritual growth and enlightenment</p>
                </div>
                
                <div class="coin-set-option" data-set="galaxy">
                    <div class="coin-preview coin-preview-galaxy"></div>
                    <h3>Cosmic Journey</h3>
                    <p>Stars, galaxies, and infinite space</p>
                </div>
                
                <div class="coin-set-option" data-set="jungle">
                    <div class="coin-preview coin-preview-jungle"></div>
                    <h3>Jungle Path</h3>
                    <p>Tropical growth and adventure</p>
                </div>
                
                <div class="coin-set-option" data-set="flower">
                    <div class="coin-preview coin-preview-flower"></div>
                    <h3>Garden Blooms</h3>
                    <p>Colorful flowers and natural beauty</p>
                </div>
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
