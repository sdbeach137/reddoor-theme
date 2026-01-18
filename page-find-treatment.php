<?php
/**
 * Template Name: Providers Archive with Search & Geolocation
 * Description: Browse all providers with search, filters, and geolocation
 */

get_header();

// Handle search/filter
$search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$filter_county = isset($_GET['county']) ? sanitize_text_field($_GET['county']) : '';
$filter_city = isset($_GET['city']) ? sanitize_text_field($_GET['city']) : '';

// Build query args
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'rdr_provider',
    'posts_per_page' => 12,
    'paged' => $paged,
    'orderby' => 'title',
    'order' => 'ASC'
);

// Add search
if ($search_query) {
    $args['s'] = $search_query;
}

// Add taxonomy filters
$tax_query = array();
if ($filter_county) {
    $tax_query[] = array(
        'taxonomy' => 'rdr_county',
        'field' => 'slug',
        'terms' => $filter_county
    );
}
if ($filter_city) {
    $tax_query[] = array(
        'taxonomy' => 'rdr_city',
        'field' => 'slug',
        'terms' => $filter_city
    );
}
if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
}

$providers = new WP_Query($args);

// Get all counties and cities for filters
$all_counties = get_terms(array(
    'taxonomy' => 'rdr_county',
    'hide_empty' => true,
    'orderby' => 'name'
));

$all_cities = get_terms(array(
    'taxonomy' => 'rdr_city',
    'hide_empty' => true,
    'orderby' => 'count',
    'order' => 'DESC',
    'number' => 30
));
?>

<style>
/* Providers Archive Page */
.providers-archive-page {
    background: #F3F4F6;
    min-height: 100vh;
}

/* Hero Header */
.archive-hero {
    background: linear-gradient(135deg, #1F2933 0%, #2d3a47 100%);
    color: #fff;
    padding: 50px 20px;
}

.archive-hero .container {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
}

.archive-hero h1 {
    font-size: 2.5rem;
    color: #fff;
    margin-bottom: 10px;
}

.archive-hero .subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 30px;
}

/* Search Box */
.search-box {
    max-width: 700px;
    margin: 0 auto;
    background: #fff;
    border-radius: 50px;
    padding: 8px;
    display: flex;
    gap: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.search-box input[type="text"] {
    flex: 1;
    border: none;
    padding: 15px 25px;
    font-size: 1rem;
    border-radius: 50px;
    outline: none;
}

.search-box select {
    padding: 15px 20px;
    border: none;
    background: #f8f9fa;
    border-radius: 50px;
    font-size: 0.95rem;
    cursor: pointer;
    outline: none;
}

.search-box button {
    background: #B11226;
    color: #fff;
    border: none;
    padding: 15px 30px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.search-box button:hover {
    background: #8B0F1F;
}

/* Main Content Layout */
.archive-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 20px;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 40px;
    align-items: start;
}

/* Sidebar Filters */
.filters-sidebar {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    position: sticky;
    top: 20px;
}

.filter-section {
    margin-bottom: 25px;
}

.filter-section:last-child {
    margin-bottom: 0;
}

.filter-section h3 {
    font-size: 1rem;
    color: #1F2933;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.filter-list {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 300px;
    overflow-y: auto;
}

.filter-list li {
    margin-bottom: 5px;
}

.filter-list a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    color: #444;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.filter-list a:hover,
.filter-list a.active {
    background: #B11226;
    color: #fff;
}

.filter-list .count {
    font-size: 0.8rem;
    background: rgba(0,0,0,0.1);
    padding: 2px 8px;
    border-radius: 10px;
}

.filter-list a:hover .count,
.filter-list a.active .count {
    background: rgba(255,255,255,0.2);
}

.view-all-link {
    display: block;
    margin-top: 10px;
    color: #B11226;
    font-size: 0.85rem;
    text-decoration: none;
}

.view-all-link:hover {
    text-decoration: underline;
}

/* Clear Filters */
.clear-filters {
    display: block;
    width: 100%;
    padding: 12px;
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 8px;
    color: #666;
    text-align: center;
    text-decoration: none;
    font-size: 0.9rem;
    margin-top: 20px;
    transition: all 0.2s;
}

.clear-filters:hover {
    background: #e9ecef;
    color: #333;
}

/* Main Providers Area */
.providers-main {
    min-width: 0;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.results-count {
    font-size: 1rem;
    color: #666;
}

.results-count strong {
    color: #1F2933;
}

.active-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.active-filter {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #B11226;
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
}

.active-filter a {
    color: #fff;
    text-decoration: none;
    opacity: 0.8;
}

.active-filter a:hover {
    opacity: 1;
}

/* Provider Cards Grid */
.providers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.provider-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    border: 2px solid transparent;
    display: flex;
    flex-direction: column;
}

.provider-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.provider-card.premium {
    border-color: #C9A227;
}

.provider-card.premium::before {
    content: '';
    display: block;
    height: 4px;
    background: linear-gradient(90deg, #C9A227 0%, #dbb84d 100%);
}

.card-content {
    padding: 18px;
    flex: 1;
}

.premium-badge {
    display: inline-block;
    background: linear-gradient(135deg, #C9A227 0%, #dbb84d 100%);
    color: #1F2933;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.provider-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1F2933;
    margin-bottom: 8px;
    line-height: 1.3;
}

.provider-name a {
    color: inherit;
    text-decoration: none;
}

.provider-name a:hover {
    color: #B11226;
}

.provider-address {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    color: #666;
    font-size: 0.85rem;
    margin-bottom: 6px;
    line-height: 1.4;
}

.provider-address svg {
    flex-shrink: 0;
    margin-top: 2px;
    width: 12px;
    height: 12px;
}

.provider-phone {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 0;
}

.provider-phone svg {
    width: 12px;
    height: 12px;
}

.provider-phone a {
    color: #B11226;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
}

.provider-phone a:hover {
    text-decoration: underline;
}

.provider-county {
    display: inline-block;
    background: #f0f4f8;
    color: #4a5568;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-top: 10px;
}

.card-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border-top: 1px solid #eee;
    margin-top: auto;
}

.btn-card {
    padding: 12px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.btn-call {
    background: #B11226;
    color: #fff;
}

.btn-call:hover {
    background: #8B0F1F;
}

.btn-details {
    background: #f8f9fa;
    color: #1F2933;
    border-left: 1px solid #eee;
}

.btn-details:hover {
    background: #e9ecef;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 40px;
}

.pagination a,
.pagination span {
    padding: 10px 16px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    color: #1F2933;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.pagination a:hover {
    background: #B11226;
    color: #fff;
    border-color: #B11226;
}

.pagination .current {
    background: #B11226;
    color: #fff;
    border-color: #B11226;
}

/* No Results */
.no-results {
    text-align: center;
    padding: 60px 30px;
    background: #fff;
    border-radius: 12px;
}

.no-results h3 {
    color: #1F2933;
    margin-bottom: 15px;
}

.no-results p {
    color: #666;
    margin-bottom: 25px;
}

.btn-browse {
    display: inline-block;
    background: #B11226;
    color: #fff;
    padding: 14px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-browse:hover {
    background: #8B0F1F;
}

/* Crisis Banner */
.crisis-banner {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin-top: 25px;
}

.crisis-banner h4 {
    color: #fff;
    font-size: 1rem;
    margin-bottom: 8px;
}

.crisis-banner p {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 12px;
}

.crisis-banner .hotline {
    display: inline-block;
    background: #fff;
    color: #B11226;
    padding: 10px 25px;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
}

.crisis-banner .hotline:hover {
    transform: scale(1.05);
}

/* Responsive */
@media (max-width: 1024px) {
    .archive-content {
        grid-template-columns: 1fr;
    }
    
    .filters-sidebar {
        position: static;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .filter-section {
        margin-bottom: 0;
    }
    
    .crisis-banner {
        grid-column: 1 / -1;
    }
}

@media (max-width: 768px) {
    .archive-hero h1 {
        font-size: 1.8rem;
    }
    
    .search-box {
        flex-direction: column;
        border-radius: 12px;
        padding: 15px;
    }
    
    .search-box input[type="text"],
    .search-box select,
    .search-box button {
        width: 100%;
        border-radius: 8px;
    }
    
    .providers-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-sidebar {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .card-actions {
        grid-template-columns: 1fr;
    }
    
    .btn-details {
        border-left: none;
        border-top: 1px solid #eee;
    }
}
</style>

<div class="providers-archive-page">
    <!-- Hero Header with Search -->
    <section class="archive-hero">
        <div class="container">
            <h1>Find Substance Abuse Treatment</h1>
            <p class="subtitle">Search <?php echo $providers->found_posts; ?>+ treatment providers across Ohio</p>
            
            <form class="search-box" method="GET" action="">
                <input type="text" name="s" placeholder="Search by name, city, or keyword..." value="<?php echo esc_attr($search_query); ?>">
                <select name="county">
                    <option value="">All Counties</option>
                    <?php if ($all_counties && !is_wp_error($all_counties)) : ?>
                        <?php foreach ($all_counties as $county) : ?>
                            <option value="<?php echo esc_attr($county->slug); ?>" <?php selected($filter_county, $county->slug); ?>>
                                <?php echo esc_html($county->name); ?> (<?php echo $county->count; ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>
    </section>
    
    <!-- Main Content -->
    <div class="archive-content">
        <!-- Sidebar Filters -->
        <aside class="filters-sidebar">
            <div class="filter-section">
                <h3>Filter by County</h3>
                <ul class="filter-list">
                    <?php if ($all_counties && !is_wp_error($all_counties)) : ?>
                        <?php 
                        $county_count = 0;
                        foreach ($all_counties as $county) : 
                            if ($county_count >= 15) break;
                            $is_active = ($filter_county === $county->slug);
                            $url = add_query_arg('county', $county->slug, remove_query_arg('paged'));
                        ?>
                        <li>
                            <a href="<?php echo esc_url($url); ?>" class="<?php echo $is_active ? 'active' : ''; ?>">
                                <?php echo esc_html($county->name); ?>
                                <span class="count"><?php echo $county->count; ?></span>
                            </a>
                        </li>
                        <?php 
                            $county_count++;
                        endforeach; ?>
                    <?php endif; ?>
                </ul>
                <?php if (count($all_counties) > 15) : ?>
                    <a href="<?php echo home_url('/'); ?>" class="view-all-link">View all counties →</a>
                <?php endif; ?>
            </div>
            
            <div class="filter-section">
                <h3>Filter by City</h3>
                <ul class="filter-list">
                    <?php if ($all_cities && !is_wp_error($all_cities)) : ?>
                        <?php foreach ($all_cities as $city) : 
                            $is_active = ($filter_city === $city->slug);
                            $url = add_query_arg('city', $city->slug, remove_query_arg('paged'));
                        ?>
                        <li>
                            <a href="<?php echo esc_url($url); ?>" class="<?php echo $is_active ? 'active' : ''; ?>">
                                <?php echo esc_html($city->name); ?>
                                <span class="count"><?php echo $city->count; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            
            <?php if ($search_query || $filter_county || $filter_city) : ?>
                <a href="<?php echo get_permalink(); ?>" class="clear-filters">✕ Clear All Filters</a>
            <?php endif; ?>
            
            <div class="crisis-banner">
                <h4>Need Help Now?</h4>
                <p>24/7 crisis support available</p>
                <a href="tel:988" class="hotline">Call 988</a>
            </div>
        </aside>
        
        <!-- Providers Grid -->
        <main class="providers-main">
            <div class="results-header">
                <p class="results-count">
                    <?php if ($search_query || $filter_county || $filter_city) : ?>
                        Found <strong><?php echo $providers->found_posts; ?></strong> providers
                        <?php if ($filter_county) : ?>
                            in <strong><?php echo esc_html(get_term_by('slug', $filter_county, 'rdr_county')->name); ?> County</strong>
                        <?php endif; ?>
                        <?php if ($filter_city) : ?>
                            in <strong><?php echo esc_html(get_term_by('slug', $filter_city, 'rdr_city')->name); ?></strong>
                        <?php endif; ?>
                        <?php if ($search_query) : ?>
                            matching "<strong><?php echo esc_html($search_query); ?></strong>"
                        <?php endif; ?>
                    <?php else : ?>
                        Showing <strong><?php echo $providers->found_posts; ?></strong> treatment providers
                    <?php endif; ?>
                </p>
                
                <?php if ($search_query || $filter_county || $filter_city) : ?>
                <div class="active-filters">
                    <?php if ($filter_county) : ?>
                        <span class="active-filter">
                            <?php echo esc_html(get_term_by('slug', $filter_county, 'rdr_county')->name); ?>
                            <a href="<?php echo esc_url(remove_query_arg('county')); ?>">✕</a>
                        </span>
                    <?php endif; ?>
                    <?php if ($filter_city) : ?>
                        <span class="active-filter">
                            <?php echo esc_html(get_term_by('slug', $filter_city, 'rdr_city')->name); ?>
                            <a href="<?php echo esc_url(remove_query_arg('city')); ?>">✕</a>
                        </span>
                    <?php endif; ?>
                    <?php if ($search_query) : ?>
                        <span class="active-filter">
                            "<?php echo esc_html($search_query); ?>"
                            <a href="<?php echo esc_url(remove_query_arg('s')); ?>">✕</a>
                        </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($providers->have_posts()) : ?>
                <div class="providers-grid">
                    <?php while ($providers->have_posts()) : $providers->the_post(); 
                        $is_paid = get_post_meta(get_the_ID(), '_is_paid', true);
                        $street = get_post_meta(get_the_ID(), '_street', true);
                        if (!$street) $street = get_post_meta(get_the_ID(), '_address', true);
                        $city = get_post_meta(get_the_ID(), '_city', true);
                        $state = get_post_meta(get_the_ID(), '_state', true);
                        $zip = get_post_meta(get_the_ID(), '_zip', true);
                        $phone = get_post_meta(get_the_ID(), '_phone', true);
                        $counties = wp_get_post_terms(get_the_ID(), 'rdr_county');
                        
                        // Build address string
                        $address_parts = array();
                        if ($street) $address_parts[] = $street;
                        if ($city) $address_parts[] = $city;
                        if ($state) $address_parts[] = $state;
                        $address_line = implode(', ', $address_parts);
                        if ($zip) $address_line .= ' ' . intval($zip);
                    ?>
                    
                    <div class="provider-card <?php echo $is_paid ? 'premium' : ''; ?>">
                        <div class="card-content">
                            <?php if ($is_paid) : ?>
                                <span class="premium-badge">✓ Verified</span>
                            <?php endif; ?>
                            
                            <h3 class="provider-name">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <?php if ($address_line) : ?>
                            <div class="provider-address">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span><?php echo esc_html($address_line); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($phone) : ?>
                            <div class="provider-phone">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#B11226" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($counties && !is_wp_error($counties)) : ?>
                                <span class="provider-county"><?php echo esc_html($counties[0]->name); ?> County</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-actions">
                            <?php if ($phone) : ?>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>" class="btn-card btn-call">
                                📞 Call Now
                            </a>
                            <?php else : ?>
                            <a href="<?php the_permalink(); ?>" class="btn-card btn-call">
                                View Info
                            </a>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="btn-card btn-details">
                                View Details →
                            </a>
                        </div>
                    </div>
                    
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <?php 
                $pagination = paginate_links(array(
                    'total' => $providers->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                    'type' => 'array'
                ));
                
                if ($pagination) : ?>
                <div class="pagination">
                    <?php foreach ($pagination as $page) : ?>
                        <?php echo $page; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
            <?php else : ?>
                <div class="no-results">
                    <h3>No Providers Found</h3>
                    <p>
                        <?php if ($search_query || $filter_county || $filter_city) : ?>
                            Try adjusting your search or filters to find more providers.
                        <?php else : ?>
                            We don't have any providers listed yet.
                        <?php endif; ?>
                    </p>
                    <?php if ($search_query || $filter_county || $filter_city) : ?>
                        <a href="<?php echo get_permalink(); ?>" class="btn-browse">Clear Filters & View All</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
