<?php
/**
 * Template for Single Provider
 * Properly formatted with paid/free distinction
 */

get_header();

// Get provider data
$post_id = get_the_ID();
$is_paid = get_post_meta($post_id, '_is_paid', true);

// Try multiple meta key possibilities for address
$street = get_post_meta($post_id, '_street', true);
if (!$street) $street = get_post_meta($post_id, '_address', true);

$city = get_post_meta($post_id, '_city', true);
$state = get_post_meta($post_id, '_state', true);
$zip = get_post_meta($post_id, '_zip', true);
$phone = get_post_meta($post_id, '_phone', true);
$website = get_post_meta($post_id, '_website', true);
$email = get_post_meta($post_id, '_email', true);
$description = get_post_meta($post_id, '_description', true);
$services_offered = get_post_meta($post_id, '_services', true);

// Get taxonomies
$counties = wp_get_post_terms($post_id, 'rdr_county');
$services = get_the_terms($post_id, 'rdr_service');

// Clean zip code - remove decimal
$zip_clean = $zip ? intval($zip) : '';

// Build full address
$full_address = '';
if ($street) $full_address .= $street . ', ';
if ($city) $full_address .= $city . ', ';
if ($state) $full_address .= $state . ' ';
if ($zip_clean) $full_address .= $zip_clean;
$full_address = rtrim($full_address, ', ');

// Google Maps link
$maps_link = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($full_address);
?>

<style>
/* Single Provider Page Styles */
.single-provider-page {
    background: #F3F4F6;
    min-height: 100vh;
    padding-bottom: 60px;
}

.provider-breadcrumb {
    background: #1F2933;
    padding: 15px 20px;
}

.provider-breadcrumb .container {
    max-width: 1200px;
    margin: 0 auto;
}

.provider-breadcrumb a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    font-size: 0.9rem;
}

.provider-breadcrumb a:hover {
    color: #fff;
}

.provider-breadcrumb span {
    color: rgba(255,255,255,0.5);
    margin: 0 8px;
}

.provider-breadcrumb .current {
    color: #fff;
}

.provider-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 40px;
    align-items: start;
}

/* Main Content */
.provider-main {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.provider-header {
    padding: 30px;
    border-bottom: 1px solid #eee;
}

.provider-header h1 {
    font-size: 2rem;
    color: #1F2933;
    margin-bottom: 10px;
    line-height: 1.3;
}

.verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
    color: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 15px;
}

.verified-badge svg {
    width: 14px;
    height: 14px;
}

.provider-body {
    padding: 30px;
}

.info-section {
    margin-bottom: 30px;
}

.info-section:last-child {
    margin-bottom: 0;
}

.info-section h2 {
    font-size: 1.1rem;
    color: #B11226;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.info-section p {
    color: #444;
    line-height: 1.7;
    margin-bottom: 10px;
}

/* Contact Info */
.contact-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.contact-item:last-child {
    border-bottom: none;
}

.contact-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.contact-icon svg {
    width: 18px;
    height: 18px;
    stroke: #B11226;
}

.contact-details {
    flex: 1;
}

.contact-label {
    font-size: 0.8rem;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.contact-value {
    color: #1F2933;
    font-weight: 500;
}

.contact-value a {
    color: #B11226;
    text-decoration: none;
}

.contact-value a:hover {
    text-decoration: underline;
}

/* Location */
.location-address {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 15px;
}

.location-address p {
    margin: 0;
    color: #1F2933;
    font-weight: 500;
}

.maps-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #B11226;
    text-decoration: none;
    font-weight: 500;
}

.maps-link:hover {
    text-decoration: underline;
}

/* Services Tags */
.services-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.service-tag {
    background: #e8f5e9;
    color: #2E7D32;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Sidebar */
.provider-sidebar {
    position: sticky;
    top: 20px;
}

.sidebar-cta {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: #fff;
    border-radius: 16px;
    padding: 30px;
    text-align: center;
    margin-bottom: 25px;
}

.sidebar-cta h3 {
    color: #fff;
    font-size: 1.3rem;
    margin-bottom: 10px;
}

.sidebar-cta p {
    opacity: 0.9;
    margin-bottom: 20px;
    font-size: 0.95rem;
}

.btn-call-large {
    display: block;
    background: #fff;
    color: #B11226;
    padding: 16px 30px;
    border-radius: 50px;
    font-size: 1.2rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
    margin-bottom: 15px;
}

.btn-call-large:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.btn-website {
    display: block;
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255,255,255,0.5);
    padding: 12px 20px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-website:hover {
    background: rgba(255,255,255,0.1);
    border-color: #fff;
}

/* Crisis Box */
.crisis-box {
    background: #fff;
    border-radius: 16px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.crisis-box h4 {
    color: #1F2933;
    margin-bottom: 10px;
}

.crisis-box p {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.crisis-number {
    display: block;
    background: #fef2f2;
    color: #B11226;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
    text-decoration: none;
}

.crisis-number:hover {
    background: #B11226;
    color: #fff;
}

/* Responsive */
@media (max-width: 900px) {
    .provider-container {
        grid-template-columns: 1fr;
    }
    
    .provider-sidebar {
        position: static;
    }
}

@media (max-width: 600px) {
    .provider-header h1 {
        font-size: 1.5rem;
    }
    
    .provider-header,
    .provider-body {
        padding: 20px;
    }
}
</style>

<div class="single-provider-page">
    <!-- Breadcrumb -->
    <nav class="provider-breadcrumb">
        <div class="container">
            <a href="<?php echo home_url('/'); ?>">Home</a>
            <span>/</span>
            <a href="<?php echo get_post_type_archive_link('rdr_provider'); ?>">Providers</a>
            <?php if ($counties && !is_wp_error($counties)) : ?>
                <span>/</span>
                <a href="<?php echo get_term_link($counties[0]); ?>"><?php echo esc_html($counties[0]->name); ?> County</a>
            <?php endif; ?>
            <span>/</span>
            <span class="current"><?php the_title(); ?></span>
        </div>
    </nav>
    
    <div class="provider-container">
        <!-- Main Content -->
        <main class="provider-main">
            <div class="provider-header">
                <?php if ($is_paid) : ?>
                <div class="verified-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    Verified Provider
                </div>
                <?php endif; ?>
                
                <h1><?php the_title(); ?></h1>
            </div>
            
            <div class="provider-body">
                <?php if ($description || has_excerpt()) : ?>
                <section class="info-section">
                    <h2>About This Provider</h2>
                    <p><?php echo $description ? esc_html($description) : get_the_excerpt(); ?></p>
                </section>
                <?php endif; ?>
                
                <?php if ($is_paid && $services_offered) : ?>
                <section class="info-section">
                    <h2>Services Offered</h2>
                    <p><?php echo esc_html($services_offered); ?></p>
                </section>
                <?php endif; ?>
                
                <?php if ($is_paid && $services && !is_wp_error($services)) : ?>
                <section class="info-section">
                    <h2>Treatment Services</h2>
                    <div class="services-tags">
                        <?php foreach ($services as $service) : ?>
                            <span class="service-tag"><?php echo esc_html($service->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>
                
                <!-- Contact Information -->
                <section class="info-section">
                    <h2>Contact Information</h2>
                    <ul class="contact-list">
                        <?php if ($phone) : ?>
                        <li class="contact-item">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </div>
                            <div class="contact-details">
                                <div class="contact-label">Phone</div>
                                <div class="contact-value">
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>">
                                        <?php echo esc_html($phone); ?>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($is_paid && $website) : ?>
                        <li class="contact-item">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                </svg>
                            </div>
                            <div class="contact-details">
                                <div class="contact-label">Website</div>
                                <div class="contact-value">
                                    <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener">
                                        Visit Website →
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($is_paid && $email) : ?>
                        <li class="contact-item">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <div class="contact-details">
                                <div class="contact-label">Email</div>
                                <div class="contact-value">
                                    <a href="mailto:<?php echo esc_attr($email); ?>">
                                        <?php echo esc_html($email); ?>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </section>
                
                <!-- Location -->
                <section class="info-section">
                    <h2>Location</h2>
                    <div class="location-address">
                        <p>
                            <?php if ($street) : ?><?php echo esc_html($street); ?><br><?php endif; ?>
                            <?php echo esc_html($city); ?><?php if ($state) : ?>, <?php echo esc_html($state); ?><?php endif; ?><?php if ($zip_clean) : ?> <?php echo esc_html($zip_clean); ?><?php endif; ?>
                            <?php if ($counties && !is_wp_error($counties)) : ?><br><?php echo esc_html($counties[0]->name); ?> County<?php endif; ?>
                        </p>
                    </div>
                    <?php if ($full_address) : ?>
                    <a href="<?php echo esc_url($maps_link); ?>" target="_blank" class="maps-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        View on Google Maps →
                    </a>
                    <?php endif; ?>
                </section>
            </div>
        </main>
        
        <!-- Sidebar -->
        <aside class="provider-sidebar">
            <div class="sidebar-cta">
                <h3>Contact <?php the_title(); ?></h3>
                <p>Reach out to learn more about their services and availability.</p>
                
                <?php if ($phone) : ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>" class="btn-call-large">
                    📞 <?php echo esc_html($phone); ?>
                </a>
                <?php endif; ?>
                
                <?php if ($is_paid && $website) : ?>
                <a href="<?php echo esc_url($website); ?>" target="_blank" class="btn-website">
                    Visit Website
                </a>
                <?php endif; ?>
            </div>
            
            <div class="crisis-box">
                <h4>Need Help Now?</h4>
                <p>24/7 National Helpline:</p>
                <a href="tel:1-800-662-4357" class="crisis-number">1-800-662-HELP</a>
                <p style="margin-top: 10px; font-size: 0.85rem;">Free, confidential, 24/7 treatment referral and information service.</p>
            </div>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
