<?php
/**
 * Template Name: Full Reading Page
 * Description: Full-width "Through the Red Door" daily reading page
 */

get_header();

// Include crisis button component if it exists
$crisis_component = get_template_directory() . '/crisis-button-component.php';
if (file_exists($crisis_component)) {
    include($crisis_component);
}

// Get the user's journey day from cookie
$start_date = isset($_COOKIE['rdrn_start_date']) ? sanitize_text_field($_COOKIE['rdrn_start_date']) : null;

if ($start_date) {
    $start = new DateTime($start_date);
    $today = new DateTime();
    $diff = $start->diff($today);
    $current_day = $diff->days + 1;
    
    // Keep within 1-90 range, cycling if beyond
    if ($current_day > 90) {
        $current_day = (($current_day - 1) % 90) + 1;
    }
} else {
    // First visit - start at day 1
    $current_day = 1;
}

// Query for today's reading
$reading_args = array(
    'post_type' => 'rdr_reading',
    'posts_per_page' => 1,
    'meta_query' => array(
        array(
            'key' => '_day_number',
            'value' => $current_day,
            'compare' => '=',
            'type' => 'NUMERIC'
        )
    )
);

$reading_query = new WP_Query($reading_args);

// Fallback: If no reading found by day number, try alternate meta key
if (!$reading_query->have_posts()) {
    $reading_args = array(
        'post_type' => 'rdr_reading',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => 'reading_day',
                'value' => $current_day,
                'compare' => '=',
                'type' => 'NUMERIC'
            )
        )
    );
    $reading_query = new WP_Query($reading_args);
}

// Final fallback: Get any reading
if (!$reading_query->have_posts()) {
    $reading_args = array(
        'post_type' => 'rdr_reading',
        'posts_per_page' => 1,
        'orderby' => 'rand'
    );
    $reading_query = new WP_Query($reading_args);
}
?>

<style>
/* Full Reading Page Styles */
.full-reading-page {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px 60px;
}

.reading-header {
    text-align: center;
    margin-bottom: 40px;
}

.reading-header-image {
    max-width: 100%;
    height: auto;
    max-height: 350px;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.reading-content {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 50px;
    margin-bottom: 30px;
}

.day-badge {
    text-align: center;
    margin-bottom: 30px;
}

.day-number {
    display: inline-block;
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: #fff;
    font-size: 1.4rem;
    font-weight: 700;
    padding: 12px 35px;
    border-radius: 50px;
    letter-spacing: 1px;
}

.reading-meta {
    text-align: center;
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 2px solid #f0f0f0;
}

.reading-section {
    font-size: 0.95rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 8px;
}

.reading-title {
    font-size: 2rem;
    color: #1F2933;
    margin-bottom: 10px;
    font-weight: 700;
}

.reading-page-ref {
    font-size: 0.9rem;
    color: #888;
    font-style: italic;
}

.core-idea {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 5px solid #C9A227;
    padding: 25px 30px;
    margin: 35px 0;
    border-radius: 0 12px 12px 0;
}

.core-idea h3 {
    color: #C9A227;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 12px;
}

.core-idea p {
    font-size: 1.25rem;
    color: #1F2933;
    font-weight: 600;
    line-height: 1.6;
    margin: 0;
}

.passage-section {
    margin: 40px 0;
}

.passage-section h3 {
    color: #B11226;
    font-size: 1.1rem;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.passage-text {
    font-size: 1.1rem;
    line-height: 1.9;
    color: #333;
}

.passage-text p {
    margin-bottom: 1.2em;
}

.interpretation-section {
    background: #fafbfc;
    padding: 35px;
    border-radius: 12px;
    margin: 40px 0;
}

.interpretation-section h3 {
    color: #2E7D32;
    font-size: 1.1rem;
    margin-bottom: 20px;
}

.interpretation-text {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #444;
}

.reflection-section {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: #fff;
    padding: 35px;
    border-radius: 12px;
    margin: 40px 0;
    text-align: center;
}

.reflection-section h3 {
    color: #fff;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 15px;
    opacity: 0.9;
}

.reflection-prompt {
    font-size: 1.3rem;
    font-style: italic;
    line-height: 1.7;
    margin: 0;
}

.reading-footer {
    text-align: center;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
    margin-top: 40px;
}

.journey-progress {
    margin-bottom: 25px;
}

.progress-bar-container {
    background: #e9ecef;
    height: 12px;
    border-radius: 10px;
    overflow: hidden;
    margin: 15px 0;
}

.progress-bar {
    background: linear-gradient(90deg, #2E7D32 0%, #4CAF50 100%);
    height: 100%;
    border-radius: 10px;
    transition: width 0.5s ease;
}

.progress-text {
    font-size: 0.9rem;
    color: #666;
}

.reading-nav {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 25px;
}

.btn-reading {
    display: inline-block;
    padding: 14px 30px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: #fff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(177, 18, 38, 0.3);
}

.btn-secondary {
    background: #f8f9fa;
    color: #1F2933;
    border: 2px solid #e0e0e0;
}

.btn-secondary:hover {
    background: #e9ecef;
    border-color: #ccc;
}

.restart-journey {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid #eee;
}

.restart-link {
    color: #888;
    font-size: 0.9rem;
    text-decoration: none;
}

.restart-link:hover {
    color: #B11226;
    text-decoration: underline;
}

/* Attribution/Legal */
.reading-attribution {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    margin-top: 40px;
    font-size: 0.85rem;
    color: #666;
    text-align: center;
    line-height: 1.7;
}

/* No reading found state */
.no-reading {
    text-align: center;
    padding: 60px 30px;
    background: #f8f9fa;
    border-radius: 12px;
}

.no-reading h2 {
    color: #1F2933;
    margin-bottom: 15px;
}

.no-reading p {
    color: #666;
    margin-bottom: 25px;
}

/* Responsive */
@media (max-width: 768px) {
    .full-reading-page {
        padding: 20px 15px 40px;
    }
    
    .reading-content {
        padding: 30px 20px;
    }
    
    .reading-title {
        font-size: 1.5rem;
    }
    
    .core-idea p {
        font-size: 1.1rem;
    }
    
    .reflection-prompt {
        font-size: 1.1rem;
    }
    
    .reading-nav {
        flex-direction: column;
    }
    
    .btn-reading {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="full-reading-page">
    <!-- Reading Header with Image -->
    <div class="reading-header">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/through-the-red-door.png" 
             alt="Through the Red Door" 
             class="reading-header-image"
             onerror="this.src='<?php echo esc_url(get_template_directory_uri()); ?>/images/through-the-red-door.png'; this.onerror=null;">
    </div>

    <?php if ($reading_query->have_posts()) : ?>
        <?php while ($reading_query->have_posts()) : $reading_query->the_post(); 
            // Get reading meta
            $section = get_post_meta(get_the_ID(), '_section', true);
            $chapter = get_the_title();
            $page = get_post_meta(get_the_ID(), '_page', true);
            $core_idea = get_post_meta(get_the_ID(), '_core_idea', true);
            $interpretation = get_post_meta(get_the_ID(), '_interpretation', true);
            $reflection = get_post_meta(get_the_ID(), '_reflection', true);
            $passage = get_the_content();
        ?>
        
        <div class="reading-content">
            <!-- Day Badge -->
            <div class="day-badge">
                <span class="day-number">Day <?php echo esc_html($current_day); ?> of 90</span>
            </div>
            
            <!-- Reading Meta -->
            <div class="reading-meta">
                <?php if ($section) : ?>
                    <div class="reading-section"><?php echo esc_html($section); ?></div>
                <?php endif; ?>
                <h1 class="reading-title"><?php echo esc_html($chapter); ?></h1>
                <?php if ($page) : ?>
                    <div class="reading-page-ref">Big Book, Page <?php echo esc_html($page); ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Core Idea -->
            <?php if ($core_idea) : ?>
            <div class="core-idea">
                <h3>Today's Core Idea</h3>
                <p><?php echo esc_html($core_idea); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Original Passage -->
            <?php if ($passage) : ?>
            <div class="passage-section">
                <h3>From the Big Book</h3>
                <div class="passage-text">
                    <?php echo wp_kses_post($passage); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Modern Interpretation -->
            <?php if ($interpretation) : ?>
            <div class="interpretation-section">
                <h3>What This Means Today</h3>
                <div class="interpretation-text">
                    <?php echo wp_kses_post($interpretation); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Reflection Prompt -->
            <?php if ($reflection) : ?>
            <div class="reflection-section">
                <h3>Today's Reflection</h3>
                <p class="reflection-prompt"><?php echo esc_html($reflection); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Footer with Progress & Navigation -->
            <div class="reading-footer">
                <div class="journey-progress">
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo esc_attr(($current_day / 90) * 100); ?>%;"></div>
                    </div>
                    <p class="progress-text"><?php echo esc_html($current_day); ?> of 90 days completed</p>
                </div>
                
                <div class="reading-nav">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-reading btn-secondary">← Back to Home</a>
                    <a href="<?php echo esc_url(get_post_type_archive_link('rdr_provider')); ?>" class="btn-reading btn-primary">Find Treatment Providers</a>
                </div>
                
                <div class="restart-journey">
                    <a href="#" class="restart-link" onclick="if(confirm('Are you sure you want to restart your 90-day journey? This will reset your progress to Day 1.')) { document.cookie = 'rdrn_start_date=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'; location.reload(); } return false;">
                        Restart My 90-Day Journey
                    </a>
                </div>
            </div>
        </div>
        
        <?php endwhile; ?>
        
    <?php else : ?>
        <!-- No Reading Found -->
        <div class="reading-content no-reading">
            <h2>Reading Not Available</h2>
            <p>We're setting up the daily readings. Please check back soon!</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-reading btn-primary">Return to Homepage</a>
        </div>
    <?php endif; ?>
    
    <?php wp_reset_postdata(); ?>
    
    <!-- Attribution -->
    <div class="reading-attribution">
        <p><strong>About "Through the Red Door"</strong></p>
        <p>These daily readings are inspired by and reference Alcoholics Anonymous ("The Big Book"). 
        This content is intended for educational and recovery support purposes only. 
        Red Door Recovery Network is not affiliated with Alcoholics Anonymous World Services, Inc.</p>
    </div>
</div>

<?php get_footer(); ?>
