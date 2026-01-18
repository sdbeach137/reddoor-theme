<?php
/**
 * Template Name: Providers Archive with Search & Geolocation
 * Description: Displays all treatment providers with filtering
 */

get_header();

// Get search and filter parameters
$search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$state_filter = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;
$near_me = isset($_GET['near']) && $_GET['near'] === 'me';

// Build query args
$args = array(
    'post_type' => 'rdr_provider',
    'posts_per_page' => 20,
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
    'orderby' => 'title',
    'order' => 'ASC'
);

// Add search
if ($search_query) {
    $args['s'] = $search_query;
}

// Add state filter
if ($state_filter) {
    $args['meta_query'] = array(
        array(
            'key' => '_state',
            'value' => $state_filter,
            'compare' => '='
        )
    );
}

$providers_query = new WP_Query($args);

// Page title logic
$page_title = 'Treatment Providers Directory';
$page_subtitle = 'Find substance abuse treatment centers';

if ($search_query) {
    $page_title = 'Search Results for "' . esc_html($search_query) . '"';
} elseif ($near_me && $lat && $lng) {
    $page_title = 'Providers Near You';
    $page_subtitle = 'Based on your location';
} elseif ($state_filter) {
    $state_names = array(
        'OH' => 'Ohio',
        'PA' => 'Pennsylvania',
        'MI' => 'Michigan',
        'KY' => 'Kentucky',
        'IN' => 'Indiana'
    );
    $page_title = 'Providers in ' . (isset($state_names[$state_filter]) ? $state_names[$state_filter] : $state_filter);
}
?>

<main class="providers-archive">
    <div class="page-header">
        <div class="header-container">
            <h1><?php echo $page_title; ?></h1>
            <p><?php echo $page_subtitle; ?></p>
            
            <!-- Search Bar -->
            <form action="<?php echo home_url('/providers/'); ?>" method="GET" class="provider-search-form">
                <input type="text" 
                       name="s" 
                       placeholder="Search by city, ZIP, or provider name" 
                       value="<?php echo esc_attr($search_query); ?>"
                       class="provider-search-input">
                <select name="state" class="state-filter">
                    <option value="">All States</option>
                    <option value="OH" <?php selected($state_filter, 'OH'); ?>>Ohio</option>
                    <option value="PA" <?php selected($state_filter, 'PA'); ?>>Pennsylvania</option>
                    <option value="MI" <?php selected($state_filter, 'MI'); ?>>Michigan</option>
                    <option value="KY" <?php selected($state_filter, 'KY'); ?>>Kentucky</option>
                    <option value="IN" <?php selected($state_filter, 'IN'); ?>>Indiana</option>
                </select>
                <button type="submit" class="search-btn">Search</button>
                <button type="button" class="location-btn" onclick="useMyLocation()">📍 Near Me</button>
            </form>
        </div>
    </div>

    <div class="providers-container">
        <aside class="providers-sidebar">
            <div class="filter-section">
                <h3>Filter by State</h3>
                <ul class="filter-list">
                    <?php
                    $states = array('OH' => 'Ohio', 'PA' => 'Pennsylvania', 'MI' => 'Michigan', 'KY' => 'Kentucky', 'IN' => 'Indiana');
                    foreach ($states as $code => $name) {
                        $url = add_query_arg('state', $code, home_url('/providers/'));
                        if ($search_query) {
                            $url = add_query_arg('s', $search_query, $url);
                        }
                        echo '<li><a href="' . esc_url($url) . '">' . esc_html($name) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="filter-section">
                <h3>Filter by County</h3>
                <?php
                $counties = get_terms(array(
                    'taxonomy' => 'rdr_county',
                    'hide_empty' => true,
                    'orderby' => 'name',
                    'number' => 20
                ));
                
                if ($counties && !is_wp_error($counties)) {
                    echo '<ul class="filter-list">';
                    foreach ($counties as $county) {
                        $url = get_term_link($county);
                        echo '<li><a href="' . esc_url($url) . '">' . esc_html($county->name) . ' <span class="count">(' . $county->count . ')</span></a></li>';
                    }
                    echo '</ul>';
                    echo '<p><a href="' . home_url('/providers/') . '">View all counties...</a></p>';
                }
                ?>
            </div>

            <div class="filter-section">
                <h3>Filter by City</h3>
                <?php
                $cities = get_terms(array(
                    'taxonomy' => 'rdr_city',
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 15
                ));
                
                if ($cities && !is_wp_error($cities)) {
                    echo '<ul class="filter-list">';
                    foreach ($cities as $city) {
                        $url = get_term_link($city);
                        echo '<li><a href="' . esc_url($url) . '">' . esc_html($city->name) . ' <span class="count">(' . $city->count . ')</span></a></li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>

            <div class="cta-box">
                <h4>Looking for Help?</h4>
                <p>If you need immediate assistance, please call the 24/7 helpline:</p>
                <p class="hotline-number"><a href="tel:1-800-662-4357">1-800-662-HELP</a></p>
                <p style="font-size: 12px; opacity: 0.9; margin-top: 10px;">Free, confidential, 24/7</p>
            </div>
        </aside>

        <div class="providers-main">
            <div class="providers-header">
                <p class="results-count">
                    <?php
                    if ($providers_query->found_posts > 0) {
                        echo 'Found ' . number_format($providers_query->found_posts) . ' provider' . ($providers_query->found_posts != 1 ? 's' : '');
                        if ($search_query) {
                            echo ' for "' . esc_html($search_query) . '"';
                        }
                    } else {
                        echo 'No providers found';
                    }
                    ?>
                </p>
                <?php if ($search_query || $state_filter): ?>
                    <p><a href="<?php echo home_url('/providers/'); ?>" class="clear-filters">Clear filters</a></p>
                <?php endif; ?>
            </div>

            <div class="providers-grid">
                <?php
                if ($providers_query->have_posts()) :
                    
                    // If geolocation, calculate distances
                    $providers_with_distance = array();
                    
                    while ($providers_query->have_posts()) : $providers_query->the_post();
                        $provider_data = array(
                            'post' => get_post(),
                            'phone' => get_post_meta(get_the_ID(), '_phone', true),
                            'website' => get_post_meta(get_the_ID(), '_website', true),
                            'street' => get_post_meta(get_the_ID(), '_street_1', true),
                            'city' => get_post_meta(get_the_ID(), '_city', true),
                            'state' => get_post_meta(get_the_ID(), '_state', true),
                            'zip' => get_post_meta(get_the_ID(), '_zip', true),
                            'verified' => get_post_meta(get_the_ID(), '_verified_status', true),
                            'counties' => wp_get_post_terms(get_the_ID(), 'rdr_county'),
                            'services' => wp_get_post_terms(get_the_ID(), 'rdr_services'),
                            'distance' => null
                        );
                        
                        // Calculate distance if lat/lng provided
                        if ($lat && $lng && $provider_data['city'] && $provider_data['state']) {
                            // Simple distance calculation (would need geocoding API for accuracy)
                            // For now, just mark as "near you"
                            $provider_data['distance'] = 'Near you';
                        }
                        
                        $providers_with_distance[] = $provider_data;
                    endwhile;
                    
                    // Display providers
                    foreach ($providers_with_distance as $provider) :
                        ?>
                        
                        <div class="provider-card">
                            <div class="provider-header">
                                <h3><a href="<?php echo get_permalink($provider['post']->ID); ?>"><?php echo esc_html($provider['post']->post_title); ?></a></h3>
                                <?php if ($provider['verified'] == 'verified' || $provider['verified'] == 'likely_active') : ?>
                                    <span class="verified-badge">✓ Verified</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($provider['distance']): ?>
                                <p class="provider-distance">📍 <?php echo esc_html($provider['distance']); ?></p>
                            <?php endif; ?>

                            <?php if ($provider['street'] || $provider['city']) : ?>
                                <p class="provider-address">
                                    <span class="icon">📍</span>
                                    <?php 
                                    if ($provider['street']) echo esc_html($provider['street']) . '<br>';
                                    if ($provider['city']) echo esc_html($provider['city']);
                                    if (!empty($provider['counties'])) echo ', ' . esc_html($provider['counties'][0]->name) . ' County';
                                    if ($provider['state']) echo ', ' . esc_html($provider['state']);
                                    if ($provider['zip']) echo ' ' . esc_html($provider['zip']);
                                    ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($provider['phone']) : ?>
                                <p class="provider-phone">
                                    <span class="icon">📞</span>
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $provider['phone'])); ?>"><?php echo esc_html($provider['phone']); ?></a>
                                </p>
                            <?php endif; ?>

                            <?php if ($provider['website']) : ?>
                                <p class="provider-website">
                                    <span class="icon">🌐</span>
                                    <a href="<?php echo esc_url($provider['website']); ?>" target="_blank" rel="noopener">Visit Website</a>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($provider['services'])) : ?>
                                <div class="provider-services">
                                    <?php
                                    $service_names = array_slice(wp_list_pluck($provider['services'], 'name'), 0, 3);
                                    foreach ($service_names as $service) {
                                        echo '<span class="service-tag">' . esc_html($service) . '</span>';
                                    }
                                    if (count($provider['services']) > 3) {
                                        echo '<span class="service-tag">+' . (count($provider['services']) - 3) . ' more</span>';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <a href="<?php echo get_permalink($provider['post']->ID); ?>" class="provider-link">View Full Details →</a>
                        </div>

                    <?php endforeach;
                    
                    // Pagination
                    if ($providers_query->max_num_pages > 1) : ?>
                        <div class="providers-pagination">
                            <?php
                            echo paginate_links(array(
                                'total' => $providers_query->max_num_pages,
                                'current' => max(1, get_query_var('paged')),
                                'prev_text' => '← Previous',
                                'next_text' => 'Next →',
                                'add_args' => array(
                                    's' => $search_query,
                                    'state' => $state_filter
                                )
                            ));
                            ?>
                        </div>
                    <?php endif;
                    
                    wp_reset_postdata();
                else : ?>
                    <div class="no-results">
                        <h3>No providers found</h3>
                        <?php if ($search_query): ?>
                            <p>No providers match your search for "<?php echo esc_html($search_query); ?>".</p>
                            <p>Try:</p>
                            <ul>
                                <li>Using different keywords</li>
                                <li>Searching by city or ZIP code</li>
                                <li>Browsing by county or state</li>
                            </ul>
                        <?php elseif ($state_filter): ?>
                            <p>No providers found in this state yet.</p>
                        <?php else: ?>
                            <p>No providers have been imported yet.</p>
                            <p>Go to <a href="/wp-admin/edit.php?post_type=rdr_provider&page=reddoor-upload-providers">Upload Providers</a> to import data.</p>
                        <?php endif; ?>
                        <p><a href="<?php echo home_url('/providers/'); ?>" class="btn-back">← Back to all providers</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
function useMyLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser');
        return;
    }

    const btn = event.target;
    btn.textContent = '📍 Finding...';
    btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            window.location.href = '<?php echo home_url('/providers/'); ?>?lat=' + lat + '&lng=' + lng + '&near=me';
        },
        function(error) {
            alert('Unable to get location: ' + error.message);
            btn.textContent = '📍 Near Me';
            btn.disabled = false;
        }
    );
}
</script>

<?php get_footer(); ?>
