<?php
/**
 * Template Name: Peer Support Page
 * Description: Peer Recovery Support Information
 */

get_header(); ?>

<main class="peer-support-page">
    <!-- PROVIDER LISTINGS -->
    <?php
    $user_lat = isset($_GET['lat']) ? floatval($_GET['lat']) : (isset($_COOKIE['user_lat']) ? floatval($_COOKIE['user_lat']) : null);
    $user_lng = isset($_GET['lng']) ? floatval($_GET['lng']) : (isset($_COOKIE['user_lng']) ? floatval($_COOKIE['user_lng']) : null);
    
    if ($user_lat && $user_lng) {
        setcookie('user_lat', $user_lat, time() + (30 * 24 * 60 * 60), '/');
        setcookie('user_lng', $user_lng, time() + (30 * 24 * 60 * 60), '/');
    }
    ?>
    
    <?php if ($user_lat && $user_lng): ?>
    <section class="nearby-providers">
        <div class="providers-container">
            <h2>Treatment Providers Near You</h2>
            
            <?php
            // Query for paid/premium providers
            $premium_args = array(
                'post_type' => 'rdr_provider',
                'posts_per_page' => 5,
                'meta_query' => array(
                    array(
                        'key' => '_is_premium',
                        'value' => '1',
                        'compare' => '='
                    )
                )
            );
            
            $premium_query = new WP_Query($premium_args);
            $has_premium = $premium_query->have_posts();
            
            if ($has_premium) {
                echo '<div class="premium-providers">';
                while ($premium_query->have_posts()) {
                    $premium_query->the_post();
                    ?>
                    <div class="provider-card premium">
                        <span class="premium-badge">⭐ Premium Provider</span>
                        <h3><?php the_title(); ?></h3>
                        <p class="provider-address">
                            <?php 
                            echo esc_html(get_post_meta(get_the_ID(), '_street_address', true)) . '<br>';
                            echo esc_html(get_post_meta(get_the_ID(), '_city', true)) . ', ';
                            echo esc_html(get_post_meta(get_the_ID(), '_state', true)) . ' ';
                            echo esc_html(get_post_meta(get_the_ID(), '_zip', true));
                            ?>
                        </p>
                        <p class="provider-phone">📞 <?php echo esc_html(get_post_meta(get_the_ID(), '_phone', true)); ?></p>
                        <?php if (get_post_meta(get_the_ID(), '_website', true)): ?>
                            <a href="<?php echo esc_url(get_post_meta(get_the_ID(), '_website', true)); ?>" class="provider-website" target="_blank">Visit Website</a>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="provider-details">View Full Details →</a>
                    </div>
                    <?php
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                // No premium providers, show free listings
                $free_args = array(
                    'post_type' => 'rdr_provider',
                    'posts_per_page' => 10
                );
                
                $free_query = new WP_Query($free_args);
                
                if ($free_query->have_posts()) {
                    echo '<div class="free-providers">';
                    while ($free_query->have_posts()) {
                        $free_query->the_post();
                        ?>
                        <div class="provider-mini-card">
                            <h4><?php the_title(); ?></h4>
                            <p class="mini-address">
                                <?php 
                                echo esc_html(get_post_meta(get_the_ID(), '_street_address', true)) . ', ';
                                echo esc_html(get_post_meta(get_the_ID(), '_city', true)) . ', ';
                                echo esc_html(get_post_meta(get_the_ID(), '_state', true));
                                ?>
                            </p>
                            <p class="mini-phone">📞 <?php echo esc_html(get_post_meta(get_the_ID(), '_phone', true)); ?></p>
                        </div>
                        <?php
                    }
                    echo '</div>';
                    wp_reset_postdata();
                }
            }
            ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- PEER SUPPORT CONTENT -->
    <section class="peer-support-content">
        <div class="content-container">
            <h1>Peer Recovery Support Services</h1>
            <p class="intro">
                Peer support is a proven approach where people with lived experience of recovery 
                provide guidance, encouragement, and connection to others facing similar challenges.
            </p>
            
            <div class="content-section">
                <h2>What is Peer Support?</h2>
                <p>
                    Peer recovery support services are non-clinical services provided by individuals 
                    who have personal experience with recovery from substance use disorders. These 
                    trained peers help others navigate their own recovery journey through shared 
                    understanding and practical guidance.
                </p>
            </div>
            
            <div class="content-section">
                <h2>Benefits of Peer Support</h2>
                <ul>
                    <li>Shared lived experience creates understanding and reduces isolation</li>
                    <li>Practical guidance from someone who has walked the path</li>
                    <li>Hope through witnessing successful recovery</li>
                    <li>Connection to recovery community and resources</li>
                    <li>Increased engagement in treatment and recovery services</li>
                    <li>Improved long-term recovery outcomes</li>
                </ul>
            </div>
            
            <div class="content-section">
                <h2>Types of Peer Support Services</h2>
                <ul>
                    <li><strong>One-on-One Support:</strong> Individual meetings with a trained peer supporter</li>
                    <li><strong>Group Support:</strong> Peer-led recovery groups and meetings</li>
                    <li><strong>Recovery Coaching:</strong> Goal-setting and accountability support</li>
                    <li><strong>Family Support:</strong> Guidance for families affected by substance use</li>
                    <li><strong>Recovery Community Centers:</strong> Safe spaces for connection and activities</li>
                </ul>
            </div>
            
            <div class="content-section">
                <h2>Finding Peer Support in Ohio</h2>
                <p>
                    Ohio has invested heavily in peer recovery support services. Many treatment 
                    facilities, recovery community organizations, and health departments offer peer 
                    support services. Use the provider directory above to find services near you.
                </p>
            </div>
            
            <div class="content-section">
                <h2>Becoming a Peer Supporter</h2>
                <p>
                    If you're in stable recovery and interested in helping others, you can become 
                    a certified peer supporter. Ohio offers training and certification through the 
                    Ohio Department of Mental Health and Addiction Services (ODMHAS).
                </p>
                <p>
                    <strong>Requirements typically include:</strong>
                </p>
                <ul>
                    <li>At least one year of stable recovery</li>
                    <li>Completion of approved training program (75-100 hours)</li>
                    <li>Passing certification exam</li>
                    <li>Commitment to ethical practice and ongoing education</li>
                </ul>
            </div>
            
            <div class="cta-section">
                <h2>Connect with Recovery Community</h2>
                <p>Whether you're seeking support or want to give back, peer support is a powerful 
                tool for lasting recovery.</p>
                <a href="<?php echo home_url('/providers/'); ?>" class="btn-cta">Find Peer Support Services</a>
            </div>
        </div>
    </section>
</main>

<style>
.peer-support-content,
.stigma-content {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}
.content-container {
    background: var(--rdr-white);
    padding: 40px;
    border-radius: 10px;
}
.peer-support-content h1,
.stigma-content h1 {
    font-size: 36px;
    color: var(--rdr-red);
    margin-bottom: 20px;
}
.intro {
    font-size: 18px;
    line-height: 1.8;
    margin-bottom: 40px;
    color: #666;
}
.content-section {
    margin: 30px 0;
}
.content-section h2 {
    font-size: 28px;
    color: var(--rdr-charcoal);
    margin-bottom: 15px;
}
.content-section p,
.content-section li {
    font-size: 16px;
    line-height: 1.8;
    color: var(--rdr-charcoal);
    margin: 10px 0;
}
.content-section ul {
    list-style: disc;
    margin-left: 30px;
}
.content-section li {
    margin: 10px 0;
}
.cta-section {
    background: linear-gradient(135deg, var(--rdr-red) 0%, #8B0F1F 100%);
    color: var(--rdr-white);
    padding: 40px;
    border-radius: 10px;
    text-align: center;
    margin-top: 40px;
}
.cta-section h2 {
    color: var(--rdr-white);
    font-size: 32px;
    margin-bottom: 15px;
}
.cta-section p {
    font-size: 18px;
    margin-bottom: 20px;
    color: rgba(255,255,255,0.9);
}
.btn-cta {
    display: inline-block;
    background: var(--rdr-white);
    color: var(--rdr-red);
    padding: 15px 40px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 700;
    font-size: 18px;
    transition: all 0.3s;
}
.btn-cta:hover {
    background: var(--rdr-green);
    color: var(--rdr-white);
    transform: translateY(-2px);
}

/* Provider sections styling (reused) */
.nearby-providers {
    background: #f9f9f9;
    padding: 40px 20px;
    margin-bottom: 40px;
}
.providers-container {
    max-width: 1200px;
    margin: 0 auto;
}
.providers-container h2 {
    font-size: 32px;
    color: var(--rdr-red);
    margin-bottom: 30px;
    text-align: center;
}
.premium-providers {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}
.provider-card {
    background: var(--rdr-white);
    border: 2px solid var(--rdr-border-grey);
    border-radius: 10px;
    padding: 20px;
}
.provider-card.premium {
    border-color: var(--rdr-gold);
    box-shadow: 0 4px 12px rgba(201, 162, 39, 0.2);
}
.premium-badge {
    display: inline-block;
    background: var(--rdr-gold);
    color: var(--rdr-charcoal);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 10px;
}
.provider-card h3 {
    font-size: 20px;
    color: var(--rdr-charcoal);
    margin-bottom: 10px;
}
.provider-address,
.provider-phone {
    font-size: 14px;
    color: #666;
    margin: 5px 0;
}
.provider-website,
.provider-details {
    display: inline-block;
    margin-top: 10px;
    color: var(--rdr-red);
    text-decoration: none;
    font-weight: 600;
}
.provider-website:hover,
.provider-details:hover {
    text-decoration: underline;
}
.free-providers {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}
.provider-mini-card {
    background: var(--rdr-white);
    border: 1px solid var(--rdr-border-grey);
    border-radius: 8px;
    padding: 15px;
}
.provider-mini-card h4 {
    font-size: 16px;
    color: var(--rdr-charcoal);
    margin-bottom: 8px;
}
.mini-address,
.mini-phone {
    font-size: 13px;
    color: #666;
    margin: 4px 0;
}
</style>

<?php get_footer(); ?>
