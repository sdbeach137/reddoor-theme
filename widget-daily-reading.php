<?php
/**
 * Daily Reading Widget - Updated Version
 * Changes: 
 * - Removed automatic welcome popup on first visit
 * - Split button into "Read Full Passage" + "Explain This Feature"
 */

// Add this to your functions.php or create as a separate widget file

class RDR_Daily_Reading_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'rdr_daily_reading',
            'Through the Red Door - Daily Reading',
            array('description' => 'Displays the daily reading from the 90-day program')
        );
    }
    
    public function widget($args, $instance) {
        // Get current day (from cookie or default to 1)
        $current_day = isset($_COOKIE['rdrn_journey_day']) ? intval($_COOKIE['rdrn_journey_day']) : 1;
        $coin_style = isset($_COOKIE['rdrn_coin_style']) ? sanitize_text_field($_COOKIE['rdrn_coin_style']) : 'heaven';
        $journey_started = isset($_COOKIE['rdrn_journey_started']) ? true : false;
        
        // Get the reading for this day
        $reading = get_posts(array(
            'post_type' => 'daily_reading',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => '_reading_day',
                    'value' => $current_day,
                    'compare' => '='
                )
            )
        ));
        
        if (empty($reading)) {
            // Fallback: get first reading
            $reading = get_posts(array(
                'post_type' => 'daily_reading',
                'posts_per_page' => 1,
                'orderby' => 'meta_value_num',
                'meta_key' => '_reading_day',
                'order' => 'ASC'
            ));
        }
        
        $reading_post = !empty($reading) ? $reading[0] : null;
        
        if ($reading_post) {
            $section = get_post_meta($reading_post->ID, '_section', true);
            $chapter = get_post_meta($reading_post->ID, '_chapter', true);
            $core_idea = get_post_meta($reading_post->ID, '_core_idea', true);
            $modern_interpretation = get_post_meta($reading_post->ID, '_modern_interpretation', true);
        }
        
        // Milestone days for coins
        $milestone_days = array(1, 7, 30, 60, 90);
        ?>
        
        <style>
        .rdr-daily-widget {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        
        .widget-header {
            background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        
        .widget-header h3 {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .day-counter {
            font-size: 1.5rem;
            font-weight: 700;
            color: #C9A227;
        }
        
        .widget-body {
            padding: 20px;
        }
        
        .reading-section {
            background: #f8f9fa;
            border-left: 4px solid #B11226;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 0 8px 8px 0;
        }
        
        .reading-section h4 {
            color: #B11226;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .reading-section p {
            color: #444;
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }
        
        .milestone-coins {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 20px 0;
        }
        
        .milestone-coin {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.4;
            transition: all 0.3s;
        }
        
        .milestone-coin.earned {
            opacity: 1;
            border-color: #C9A227;
            box-shadow: 0 0 10px rgba(201, 162, 39, 0.3);
        }
        
        .milestone-coin img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        
        /* Split Buttons */
        .widget-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-full-passage {
            display: block;
            background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
            color: #fff;
            text-align: center;
            padding: 14px 20px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-full-passage:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(177, 18, 38, 0.3);
            color: #fff;
        }
        
        .btn-explain {
            display: block;
            background: #f8f9fa;
            color: #1F2933;
            text-align: center;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .btn-explain:hover {
            background: #e9ecef;
            border-color: #B11226;
            color: #B11226;
        }
        
        .widget-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            text-align: center;
        }
        
        .widget-footer a {
            color: #666;
            font-size: 0.85rem;
            text-decoration: none;
        }
        
        .widget-footer a:hover {
            color: #B11226;
        }
        </style>
        
        <div class="rdr-daily-widget">
            <div class="widget-header">
                <h3>Through the Red Door</h3>
                <div class="day-counter">Day <?php echo $current_day; ?> of 90</div>
            </div>
            
            <div class="widget-body">
                <?php if ($reading_post) : ?>
                    <div class="reading-section">
                        <h4>Section & Chapter</h4>
                        <p><?php echo esc_html($section); ?> - Day <?php echo $current_day; ?> – <?php echo esc_html($chapter); ?></p>
                    </div>
                    
                    <div class="reading-section">
                        <h4>Core Idea</h4>
                        <p><?php echo esc_html($core_idea); ?></p>
                    </div>
                    
                    <div class="reading-section">
                        <h4>Modern Clinical Interpretation</h4>
                        <p><?php echo wp_trim_words(esc_html($modern_interpretation), 20, '...'); ?></p>
                    </div>
                <?php else : ?>
                    <p style="text-align: center; color: #666;">Loading your daily reading...</p>
                <?php endif; ?>
                
                <!-- Milestone Coins -->
                <div class="milestone-coins">
                    <?php 
                    $coin_path = get_template_directory_uri() . '/assets/images/coins/' . $coin_style . '.png';
                    foreach ($milestone_days as $day) : 
                        $earned = $current_day >= $day;
                    ?>
                    <div class="milestone-coin <?php echo $earned ? 'earned' : ''; ?>">
                        <?php if ($earned) : ?>
                            <img src="<?php echo esc_url($coin_path); ?>" alt="Day <?php echo $day; ?> Coin">
                        <?php else : ?>
                            <span style="color: #ccc; font-size: 0.7rem;">Day <?php echo $day; ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Split Buttons -->
                <div class="widget-buttons">
                    <a href="<?php echo home_url('/full-reading/'); ?>" class="btn-full-passage">
                        📖 Read Full Passage
                    </a>
                    <a href="<?php echo home_url('/begin-your-journey/'); ?>" class="btn-explain">
                        ❓ Explain This Feature
                    </a>
                </div>
            </div>
            
            <div class="widget-footer">
                <a href="#" onclick="openCoinStyleModal(); return false;">Change Coin Style</a>
                &nbsp;|&nbsp;
                <a href="#" onclick="resetJourney(); return false;">Reset Journey</a>
            </div>
        </div>
        
        <script>
        // NOTE: Welcome popup has been REMOVED
        // The journey now starts only when user clicks "Begin Your Journey" button
        // on the /begin-your-journey/ page
        
        function openCoinStyleModal() {
            // Redirect to begin-your-journey page with modal trigger
            window.location.href = '<?php echo home_url('/begin-your-journey/'); ?>?modal=coins';
        }
        
        function resetJourney() {
            if (confirm('Are you sure you want to reset your journey? This will clear your progress and coins.')) {
                document.cookie = 'rdrn_journey_day=1; path=/; max-age=31536000';
                document.cookie = 'rdrn_journey_started=; path=/; max-age=0';
                location.reload();
            }
        }
        </script>
        <?php
    }
    
    public function form($instance) {
        // Widget admin form
        ?>
        <p>This widget displays the daily reading from the "Through the Red Door" 90-day program.</p>
        <p>No configuration needed - it automatically shows the correct day based on user progress.</p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        return $new_instance;
    }
}

// Register widget
function rdr_register_daily_reading_widget() {
    register_widget('RDR_Daily_Reading_Widget');
}
add_action('widgets_init', 'rdr_register_daily_reading_widget');
?>
