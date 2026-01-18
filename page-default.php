<?php
/**
 * Template Name: Default Page
 * Default page template with sidebar
 * Upload to: /wp-content/themes/reddoor-theme/page.php (or rename existing)
 */

get_header();
?>

<style>
/* Default Page Styles */
.default-page {
    max-width: 1400px;
    margin: 40px auto;
    padding: 0 30px;
}

.default-page.with-sidebar {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 50px;
    align-items: start;
}

.page-content {
    font-family: 'Merriweather', Georgia, serif;
}

.page-content h1 {
    font-size: 42px;
    color: #B11226;
    margin-bottom: 25px;
    line-height: 1.2;
}

.page-content h2 {
    font-size: 28px;
    color: #1F2933;
    margin: 30px 0 15px;
}

.page-content h3 {
    font-size: 22px;
    color: #1F2933;
    margin: 25px 0 12px;
}

.page-content p {
    font-size: 17px;
    line-height: 1.8;
    color: #333;
    margin-bottom: 18px;
}

.page-content ul,
.page-content ol {
    margin: 20px 0;
    padding-left: 25px;
}

.page-content li {
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 8px;
}

.page-content a {
    color: #B11226;
    text-decoration: underline;
}

.page-content a:hover {
    color: #2E7D32;
}

.page-content blockquote {
    border-left: 4px solid #B11226;
    padding: 20px 25px;
    margin: 30px 0;
    background: #F3F4F6;
    border-radius: 0 10px 10px 0;
    font-style: italic;
}

.page-content img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 20px 0;
}

/* Page Sidebar */
.page-sidebar {
    position: sticky;
    top: 30px;
}

.sidebar-widget {
    background: #fff;
    border: 2px solid #E0E0E0;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
}

.sidebar-widget h3 {
    font-size: 18px;
    color: #1F2933;
    margin: 0 0 15px;
    padding-bottom: 12px;
    border-bottom: 2px solid #F3F4F6;
    font-family: 'Merriweather', Georgia, serif;
}

.sidebar-widget ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-widget ul li {
    padding: 8px 0;
    border-bottom: 1px solid #F3F4F6;
}

.sidebar-widget ul li:last-child {
    border-bottom: none;
}

.sidebar-widget ul li a {
    color: #1F2933;
    text-decoration: none;
    font-size: 15px;
}

.sidebar-widget ul li a:hover {
    color: #B11226;
}

/* CTA Box */
.sidebar-cta-box {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
}

.sidebar-cta-box h3 {
    color: white;
    border: none;
    padding: 0;
    margin: 0 0 10px;
}

.sidebar-cta-box p {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 15px;
}

.sidebar-cta-box a {
    display: inline-block;
    background: white;
    color: #B11226;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
}

.sidebar-cta-box a:hover {
    background: #2E7D32;
    color: white;
}

/* Responsive */
@media (max-width: 900px) {
    .default-page.with-sidebar {
        grid-template-columns: 1fr;
    }
    
    .page-sidebar {
        position: static;
    }
}
</style>

<main class="default-page with-sidebar">
    
    <!-- Main Content -->
    <article class="page-content">
        <?php 
        if (have_posts()) : 
            while (have_posts()) : the_post();
        ?>
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        <?php 
            endwhile;
        endif;
        ?>
    </article>
    
    <!-- Sidebar -->
    <aside class="page-sidebar">
        
        <!-- Quick Links Widget -->
        <div class="sidebar-widget">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="<?php echo home_url('/'); ?>">Find Treatment</a></li>
                <li><a href="<?php echo home_url('/peer-support/'); ?>">Peer Support</a></li>
                <li><a href="<?php echo home_url('/trauma-informed-care/'); ?>">Trauma-Informed Care</a></li>
                <li><a href="<?php echo home_url('/stigma/'); ?>">Understanding Stigma</a></li>
                <li><a href="<?php echo home_url('/about-us/'); ?>">About Us</a></li>
            </ul>
        </div>
        
        <!-- CTA Box -->
        <div class="sidebar-cta-box">
            <h3>Are You a Provider?</h3>
            <p>Get your facility listed and help people find treatment.</p>
            <a href="<?php echo home_url('/claim-your-listing/'); ?>">Claim Your Listing</a>
        </div>
        
        <?php 
        // Display dynamic sidebar if registered
        if (is_active_sidebar('page-sidebar')) {
            dynamic_sidebar('page-sidebar');
        }
        ?>
        
    </aside>
    
</main>

<?php get_footer(); ?>
