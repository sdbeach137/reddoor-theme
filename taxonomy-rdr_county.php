<?php
/**
 * Template for County Taxonomy Archive
 * Layout: Provider cards (main) + Sticky city sidebar (right) + Counties section (bottom)
 */

get_header();

$term = get_queried_object();

// Get cities in this county
$providers_in_county = get_posts(array(
    'post_type' => 'rdr_provider',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'rdr_county',
            'field' => 'term_id',
            'terms' => $term->term_id
        )
    ),
    'fields' => 'ids'
));

$cities_in_county = array();
if ($providers_in_county) {
    $cities_in_county = get_terms(array(
        'taxonomy' => 'rdr_city',
        'hide_empty' => true,
        'object_ids' => $providers_in_county,
        'orderby' => 'count',
        'order' => 'DESC'
    ));
    if (is_wp_error($cities_in_county)) {
        $cities_in_county = array();
    }
}

// Get all counties for the bottom section
$all_counties = get_terms(array(
    'taxonomy' => 'rdr_county',
    'hide_empty' => true,
    'orderby' => 'name'
));

// Query providers - FIXED: removed meta_key requirement that was filtering out providers
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$provider_query = new WP_Query(array(
    'post_type' => 'rdr_provider',
    'posts_per_page' => 12,
    'paged' => $paged,
    'tax_query' => array(
        array(
            'taxonomy' => 'rdr_county',
            'field' => 'term_id',
            'terms' => $term->term_id
        )
    ),
    'orderby' => 'title',
    'order' => 'ASC'
));
?>

<style>
/* County Page Layout */
.county-page {
    background: #F3F4F6;
    min-height: 100vh;
}

.county-header {
    background: linear-gradient(135deg, #1F2933 0%, #2d3a47 100%);
    color: #fff;
    padding: 50px 20px;
    text-align: center;
}

.county-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    color: #fff;
}

.county-header .subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 15px;
}

.breadcrumb {
    font-size: 0.9rem;
    opacity: 0.7;
}

.breadcrumb a {
    color: #fff;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

/* Main Content Layout */
.county-content-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 20px;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 40px;
    align-items: start;
}

/* Provider Cards Main Area */
.providers-main {
    min-width: 0;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
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

/* Provider Cards Grid */
.providers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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
    margin-bottom: 12px;
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

.provider-services {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 15px;
}

.service-tag {
    background: #f0f4f8;
    color: #4a5568;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 500;
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

/* Sticky City Sidebar */
.city-sidebar {
    position: sticky;
    top: 20px;
}

.sidebar-widget {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 25px;
}

.sidebar-widget h3 {
    font-size: 1.1rem;
    color: #1F2933;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0f0f0;
}

.city-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.city-list li {
    margin-bottom: 8px;
}

.city-list a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    color: #1F2933;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.city-list a:hover {
    background: #B11226;
    color: #fff;
}

.city-list .count {
    font-size: 0.8rem;
    background: rgba(0,0,0,0.1);
    padding: 2px 8px;
    border-radius: 10px;
}

.city-list a:hover .count {
    background: rgba(255,255,255,0.2);
}

/* Help Box */
.help-box {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: #fff;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
}

.help-box h4 {
    color: #fff;
    font-size: 1.1rem;
    margin-bottom: 12px;
}

.help-box p {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 15px;
}

.help-box .hotline {
    display: block;
    background: #fff;
    color: #B11226;
    padding: 12px 20px;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
}

.help-box .hotline:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Counties Section */
.counties-section {
    background: #fff;
    padding: 60px 20px;
    margin-top: 40px;
}

.counties-container {
    max-width: 1400px;
    margin: 0 auto;
}

.counties-section h2 {
    text-align: center;
    font-size: 2rem;
    color: #1F2933;
    margin-bottom: 15px;
}

.counties-section .section-subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 40px;
}

.county-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}

.county-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #1F2933;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.county-link:hover {
    background: #B11226;
    color: #fff;
    border-color: #8B0F1F;
}

.county-link.current {
    background: #B11226;
    color: #fff;
    border-color: #8B0F1F;
}

.county-link .count {
    font-size: 0.8rem;
    background: rgba(0,0,0,0.1);
    padding: 2px 8px;
    border-radius: 10px;
}

.county-link:hover .count,
.county-link.current .count {
    background: rgba(255,255,255,0.2);
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
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 1024px) {
    .county-content-wrapper {
        grid-template-columns: 1fr;
    }
    
    .city-sidebar {
        position: static;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .help-box {
        grid-column: span 2;
    }
}

@media (max-width: 768px) {
    .county-header h1 {
        font-size: 1.8rem;
    }
    
    .providers-grid {
        grid-template-columns: 1fr;
    }
    
    .city-sidebar {
        grid-template-columns: 1fr;
    }
    
    .help-box {
        grid-column: span 1;
    }
    
    .county-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .county-grid {
        grid-template-columns: 1fr;
    }
    
    .card-actions {
        grid-template-columns: 1fr;
    }
    
    .btn-details {
        border-left: none;
        border-top: 1px solid #eee;
    }
}
</style>

<div class="county-page">
    <!-- Header -->
    <div class="county-header">
        <h1><?php echo esc_html($term->name); ?> County Providers</h1>
        <p class="subtitle">Treatment centers in <?php echo esc_html($term->name); ?> County, Ohio</p>
        <p class="breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> / 
            <a href="<?php echo esc_url(get_post_type_archive_link('rdr_provider')); ?>">Providers</a> / 
            <?php echo esc_html($term->name); ?> County
        </p>
    </div>
    
    <!-- Main Content Area -->
    <div class="county-content-wrapper">
        <!-- Providers Main Area -->
        <div class="providers-main">
            <div class="results-header">
                <p class="results-count">
                    Found <strong><?php echo esc_html($term->count); ?></strong> provider<?php echo $term->count != 1 ? 's' : ''; ?> 
                    in <?php echo esc_html($term->name); ?> County
                </p>
            </div>
            
            <?php if ($provider_query->have_posts()) : ?>
                <div class="providers-grid">
                    <?php while ($provider_query->have_posts()) : $provider_query->the_post(); 
                        $is_paid = get_post_meta(get_the_ID(), '_is_paid', true);
                        // Try both possible meta keys for street address
                        $street = get_post_meta(get_the_ID(), '_street', true);
                        if (!$street) $street = get_post_meta(get_the_ID(), '_address', true);
                        $city = get_post_meta(get_the_ID(), '_city', true);
                        $state = get_post_meta(get_the_ID(), '_state', true);
                        $zip = get_post_meta(get_the_ID(), '_zip', true);
                        $phone = get_post_meta(get_the_ID(), '_phone', true);
                        $services = get_the_terms(get_the_ID(), 'rdr_service');
                    ?>
                    
                    <div class="provider-card <?php echo $is_paid ? 'premium' : ''; ?>">
                        <div class="card-content">
                            <?php if ($is_paid) : ?>
                                <span class="premium-badge">✓ Verified</span>
                            <?php endif; ?>
                            
                            <h3 class="provider-name">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <?php 
                            // Build address string
                            $address_parts = array();
                            if ($street) $address_parts[] = $street;
                            if ($city) $address_parts[] = $city;
                            if ($state) $address_parts[] = $state;
                            $address_line = implode(', ', $address_parts);
                            if ($zip) $address_line .= ' ' . intval($zip);
                            
                            if ($address_line) : ?>
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
                            
                            <?php if ($is_paid && $services && !is_wp_error($services)) : ?>
                            <div class="provider-services">
                                <?php 
                                $count = 0;
                                foreach ($services as $service) {
                                    if ($count >= 3) {
                                        echo '<span class="service-tag">+' . (count($services) - 3) . ' more</span>';
                                        break;
                                    }
                                    echo '<span class="service-tag">' . esc_html($service->name) . '</span>';
                                    $count++;
                                }
                                ?>
                            </div>
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
                    'total' => $provider_query->max_num_pages,
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
                    <p>We don't have any providers listed in <?php echo esc_html($term->name); ?> County yet.</p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('rdr_provider')); ?>" class="btn-browse">Browse All Providers</a>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </div>
        
        <!-- Sticky City Sidebar -->
        <aside class="city-sidebar">
            <?php if (!empty($cities_in_county) && !is_wp_error($cities_in_county)) : ?>
            <div class="sidebar-widget">
                <h3>Cities in <?php echo esc_html($term->name); ?> County</h3>
                <ul class="city-list">
                    <?php foreach ($cities_in_county as $city) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_term_link($city)); ?>">
                            <?php echo esc_html($city->name); ?>
                            <span class="count"><?php echo esc_html($city->count); ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <div class="help-box">
                <h4>Need Help Now?</h4>
                <p>If you or someone you know is in crisis, help is available 24/7.</p>
                <a href="tel:988" class="hotline">Call 988</a>
            </div>
        </aside>
    </div>
    
    <!-- Counties Section (like homepage) -->
    <section class="counties-section">
        <div class="counties-container">
            <h2>Browse by County</h2>
            <p class="section-subtitle">Find treatment providers throughout Ohio</p>
            
            <div class="county-grid">
                <?php if ($all_counties && !is_wp_error($all_counties)) : ?>
                    <?php foreach ($all_counties as $county) : 
                        $is_current = ($county->term_id === $term->term_id);
                    ?>
                    <a href="<?php echo esc_url(get_term_link($county)); ?>" 
                       class="county-link <?php echo $is_current ? 'current' : ''; ?>">
                        <?php echo esc_html($county->name); ?>
                        <span class="count"><?php echo esc_html($county->count); ?></span>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
