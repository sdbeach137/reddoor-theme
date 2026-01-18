<?php
/**
 * Template Name: Begin Your Journey
 * Description: Explains the "Through the Red Door" daily readings and triggers the journey start
 */

get_header();

// Get 3 random providers for the top section
$providers = new WP_Query(array(
    'post_type' => 'rdr_provider',
    'posts_per_page' => 3,
    'orderby' => 'rand'
));

// Get saved coin style from cookie
$selected_coin = isset($_COOKIE['rdrn_coin_style']) ? sanitize_text_field($_COOKIE['rdrn_coin_style']) : '';
$journey_started = isset($_COOKIE['rdrn_journey_started']) ? true : false;
?>

<style>
/* Begin Your Journey Page */
.journey-page {
    background: #F3F4F6;
    min-height: 100vh;
}

/* Nearby Providers Section */
.nearby-providers {
    background: #fff;
    padding: 40px 20px;
    border-bottom: 1px solid #e0e0e0;
}

.nearby-providers .container {
    max-width: 1100px;
    margin: 0 auto;
}

.nearby-providers h2 {
    text-align: center;
    color: #1F2933;
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.nearby-providers .subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}

.providers-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.provider-mini-card {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s;
}

.provider-mini-card:hover {
    border-color: #B11226;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.provider-mini-card h4 {
    color: #1F2933;
    font-size: 1.1rem;
    margin-bottom: 8px;
}

.provider-mini-card .location {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.provider-mini-card .phone a {
    color: #B11226;
    font-weight: 600;
    text-decoration: none;
}

.provider-mini-card .btn-link {
    display: inline-block;
    margin-top: 12px;
    color: #B11226;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.9rem;
}

/* Main Content */
.journey-content {
    max-width: 900px;
    margin: 0 auto;
    padding: 60px 20px;
}

/* Header */
.journey-header {
    text-align: center;
    margin-bottom: 50px;
}

.journey-header .overline {
    font-size: 0.85rem;
    color: #B11226;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 15px;
}

.journey-header h1 {
    font-size: 2.5rem;
    color: #1F2933;
    margin-bottom: 20px;
    line-height: 1.2;
}

.journey-header .intro {
    font-size: 1.1rem;
    color: #666;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.8;
}

.decorative-line {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #B11226, #C9A227);
    margin: 0 auto 30px;
    border-radius: 2px;
}

/* Feature Cards */
.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin: 40px 0;
}

.feature-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    text-align: center;
}

.feature-card .icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 1.5rem;
}

.feature-card h3 {
    color: #1F2933;
    font-size: 1.1rem;
    margin-bottom: 10px;
}

.feature-card p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Explanation Sections */
.explain-section {
    background: #fff;
    border-radius: 16px;
    padding: 40px;
    margin: 40px 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}

.explain-section h2 {
    color: #B11226;
    font-size: 1.5rem;
    margin-bottom: 20px;
}

.explain-section p {
    color: #444;
    line-height: 1.8;
    margin-bottom: 15px;
}

.explain-section ul {
    padding-left: 20px;
    margin: 20px 0;
}

.explain-section li {
    color: #444;
    line-height: 1.8;
    margin-bottom: 10px;
}

.highlight-box {
    background: #fef2f2;
    border-left: 4px solid #B11226;
    padding: 20px;
    border-radius: 0 8px 8px 0;
    margin: 20px 0;
}

.highlight-box p {
    margin: 0;
    color: #1F2933;
    font-weight: 500;
}

/* Coin Preview Section */
.coin-preview-section {
    background: linear-gradient(135deg, #1F2933 0%, #2d3a47 100%);
    border-radius: 16px;
    padding: 40px;
    margin: 40px 0;
    text-align: center;
    color: #fff;
}

.coin-preview-section h2 {
    color: #C9A227;
    margin-bottom: 15px;
}

.coin-preview-section p {
    color: rgba(255,255,255,0.9);
    margin-bottom: 25px;
}

.coin-samples {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.coin-sample {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #fff;
    padding: 5px;
    transition: transform 0.3s;
    cursor: pointer;
}

.coin-sample:hover {
    transform: scale(1.1);
}

.coin-sample img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.btn-choose-coin {
    display: inline-block;
    background: #C9A227;
    color: #1F2933;
    padding: 14px 30px;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    border: none;
    font-size: 1rem;
    transition: all 0.3s;
}

.btn-choose-coin:hover {
    background: #dbb84d;
    transform: translateY(-2px);
}

/* CTA Section */
.journey-cta {
    text-align: center;
    margin: 50px 0;
    padding: 50px;
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    border-radius: 16px;
    color: #fff;
}

.journey-cta h2 {
    color: #fff;
    font-size: 2rem;
    margin-bottom: 15px;
}

.journey-cta p {
    color: rgba(255,255,255,0.9);
    margin-bottom: 30px;
    font-size: 1.1rem;
}

.btn-begin-journey {
    display: inline-block;
    background: #fff;
    color: #B11226;
    padding: 18px 45px;
    border-radius: 50px;
    font-size: 1.2rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-begin-journey:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Coin Selection Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: #fff;
    border-radius: 20px;
    padding: 40px;
    max-width: 500px;
    width: 90%;
    text-align: center;
    position: relative;
    animation: modalSlide 0.3s ease;
}

@keyframes modalSlide {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #999;
}

.modal-content h3 {
    color: #1F2933;
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.modal-content p {
    color: #666;
    margin-bottom: 25px;
}

.coin-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.coin-option {
    padding: 20px;
    border: 3px solid #e0e0e0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.coin-option:hover {
    border-color: #C9A227;
}

.coin-option.selected {
    border-color: #C9A227;
    background: #fffbf0;
}

.coin-option img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.coin-option .coin-name {
    font-weight: 600;
    color: #1F2933;
}

.btn-confirm-coin {
    background: #B11226;
    color: #fff;
    border: none;
    padding: 14px 40px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-confirm-coin:hover {
    background: #8B0F1F;
}

/* Celebration Modal */
.celebration-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
    z-index: 10000;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

.celebration-overlay.active {
    display: flex;
}

.celebration-content {
    text-align: center;
    color: #fff;
    max-width: 600px;
    padding: 40px;
    position: relative;
    z-index: 2;
}

.celebration-coin {
    width: 80px;
    height: 80px;
    margin: 0 auto 30px;
    position: relative;
}

.celebration-coin img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    box-shadow: 0 0 30px rgba(201, 162, 39, 0.5);
}

.celebration-content h2 {
    color: #C9A227;
    font-size: 2rem;
    margin-bottom: 10px;
}

.celebration-content .day-badge {
    background: #C9A227;
    color: #1F2933;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 700;
    display: inline-block;
    margin-bottom: 30px;
}

.celebration-content .message {
    color: rgba(255,255,255,0.9);
    line-height: 1.8;
    font-size: 1.05rem;
    margin-bottom: 25px;
}

.celebration-content .dare-greatly {
    font-family: 'Georgia', serif;
    font-style: italic;
    color: #C9A227;
    font-size: 1.2rem;
    text-decoration: none;
    display: inline-block;
    margin: 10px 0;
    transition: all 0.3s;
}

.celebration-content .dare-greatly:hover {
    color: #dbb84d;
}

.celebration-content .signature {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.celebration-content .signature p {
    margin: 5px 0;
    font-style: italic;
    color: rgba(255,255,255,0.7);
}

.celebration-content .signature .names {
    color: #fff;
    font-weight: 600;
    font-style: normal;
}

.btn-close-celebration {
    background: #B11226;
    color: #fff;
    border: none;
    padding: 14px 40px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    margin-top: 30px;
    transition: all 0.3s;
}

.btn-close-celebration:hover {
    background: #8B0F1F;
}

/* Fireworks Canvas */
#fireworks-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 10001;
}

/* Responsive */
@media (max-width: 768px) {
    .journey-header h1 {
        font-size: 1.8rem;
    }
    
    .explain-section {
        padding: 25px;
    }
    
    .coin-options {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        padding: 25px;
    }
}
</style>

<div class="journey-page">
    
    <!-- Nearby Providers -->
    <?php if ($providers->have_posts()) : ?>
    <section class="nearby-providers">
        <div class="container">
            <h2>Find Help Near You</h2>
            <p class="subtitle">Treatment providers ready to support your recovery journey</p>
            
            <div class="providers-row">
                <?php while ($providers->have_posts()) : $providers->the_post(); 
                    $city = get_post_meta(get_the_ID(), '_city', true);
                    $state = get_post_meta(get_the_ID(), '_state', true);
                    $phone = get_post_meta(get_the_ID(), '_phone', true);
                ?>
                <div class="provider-mini-card">
                    <h4><?php the_title(); ?></h4>
                    <p class="location"><?php if ($city) echo esc_html($city); if ($city && $state) echo ', '; if ($state) echo esc_html($state); ?></p>
                    <?php if ($phone) : ?>
                    <p class="phone"><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>">📞 <?php echo esc_html($phone); ?></a></p>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="btn-link">View Details →</a>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div class="journey-content">
        
        <!-- Header -->
        <header class="journey-header">
            <p class="overline">Through the Red Door</p>
            <h1>Begin Your 90-Day Journey</h1>
            <div class="decorative-line"></div>
            <p class="intro">
                The "Through the Red Door" daily reading program breaks the core wisdom of the Big Book 
                into 90 digestible readings — one for each day of your first 90 days in recovery.
            </p>
        </header>
        
        <!-- Feature Grid -->
        <div class="feature-grid">
            <div class="feature-card">
                <div class="icon">📖</div>
                <h3>Daily Big Book Excerpts</h3>
                <p>Each day presents a focused passage from the AA Big Book, making the text more accessible and meaningful.</p>
            </div>
            <div class="feature-card">
                <div class="icon">💡</div>
                <h3>Modern Clinical Interpretation</h3>
                <p>Evidence-based insights translate timeless wisdom into practical, actionable guidance for today.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🏆</div>
                <h3>Achievement Coins</h3>
                <p>Earn collectible milestone coins as you progress, celebrating your commitment to recovery.</p>
            </div>
        </div>
        
        <!-- 90 in 90 Section -->
        <div class="explain-section">
            <h2>90 Readings in 90 Days</h2>
            <p>
                There's a saying in AA: <strong>"90 meetings in 90 days."</strong> This time-tested approach helps 
                newcomers build a solid foundation in their recovery by immersing themselves in the program.
            </p>
            <p>
                Our daily readings are designed to supplement — not replace — this practice. While you attend meetings, 
                work with a sponsor, and engage with your recovery community, these readings provide an additional 
                touchpoint with the core principles of recovery.
            </p>
            
            <div class="highlight-box">
                <p>
                    💡 Each reading takes just 5-10 minutes — perfect for your morning routine, a lunch break, 
                    or quiet reflection before bed.
                </p>
            </div>
        </div>
        
        <!-- Modern Clinical Interpretation -->
        <div class="explain-section">
            <h2>Evidence-Based Modern Interpretation</h2>
            <p>
                While the Big Book's wisdom is timeless, we've enhanced each reading with modern clinical perspective. 
                Our interpretations incorporate:
            </p>
            <ul>
                <li><strong>Cognitive Behavioral Techniques</strong> — Identify and counter cognitive distortions that fuel addictive thinking</li>
                <li><strong>Motivational Interviewing Principles</strong> — Build intrinsic motivation for lasting change</li>
                <li><strong>Trauma-Informed Care</strong> — Recognize how past experiences shape present behaviors</li>
                <li><strong>Mindfulness Practices</strong> — Stay grounded in the present moment</li>
                <li><strong>Relapse Prevention Strategies</strong> — Build practical skills for maintaining sobriety</li>
            </ul>
            <p>
                Each reading identifies the core principle being taught and explains which therapeutic techniques 
                can help you internalize and apply that principle in your daily life.
            </p>
        </div>
        
        <!-- Coin System -->
        <div class="coin-preview-section">
            <h2>🏆 Milestone Achievement Coins</h2>
            <p>
                As you progress through your journey, you'll earn achievement coins at key milestones: 
                Day 1, Day 7, Day 30, Day 60, and Day 90. Choose a coin style that resonates with your personal journey.
            </p>
            
            <div class="coin-samples">
                <div class="coin-sample">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/coins/heaven.png" alt="Heaven Style Coin">
                </div>
                <div class="coin-sample">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/coins/skulls.png" alt="Skulls Style Coin">
                </div>
            </div>
            
            <button class="btn-choose-coin" onclick="openCoinModal()">Choose Your Coin Style</button>
        </div>
        
        <!-- CTA -->
        <div class="journey-cta">
            <h2>Ready to Begin?</h2>
            <p>Take your first step through the Red Door and start Day 1 of your 90-day journey.</p>
            <button class="btn-begin-journey" onclick="beginJourney()">
                🚪 Begin Your Journey Through the Red Door
            </button>
        </div>
        
    </div>
</div>

<!-- Coin Selection Modal -->
<div class="modal-overlay" id="coin-modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeCoinModal()">×</button>
        <h3>Choose Your Achievement Coin</h3>
        <p>Select the coin style that represents your recovery journey.</p>
        
        <div class="coin-options">
            <div class="coin-option" data-coin="heaven" onclick="selectCoin('heaven')">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/coins/heaven.png" alt="Heaven Style">
                <div class="coin-name">Heaven</div>
            </div>
            <div class="coin-option" data-coin="skulls" onclick="selectCoin('skulls')">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/coins/skulls.png" alt="Skulls Style">
                <div class="coin-name">Skulls</div>
            </div>
        </div>
        
        <button class="btn-confirm-coin" onclick="confirmCoinSelection()">Confirm Selection</button>
    </div>
</div>

<!-- Celebration Modal -->
<div class="celebration-overlay" id="celebration-modal">
    <canvas id="fireworks-canvas"></canvas>
    <div class="celebration-content">
        <div class="celebration-coin" id="animated-coin">
            <img src="" alt="Day 1 Coin" id="celebration-coin-img">
        </div>
        
        <h2>Congratulations on Beginning Your Recovery Journey Through the Red Door</h2>
        <div class="day-badge">Day 1 Achievement Awarded</div>
        
        <p class="message">
            This humble token represents the beginning of the recovery journey of struggling people who are battling 
            a Substance Use Disorder or an Alcohol Use Disorder. Anyone that has suffered from substance abuse, 
            be it alcohol abuse or drug abuse, knows that the first step can sometimes be the hardest one.
        </p>
        
        <p class="message">
            Accomplishing the start of any recovery-based journey is impressive and deserves acknowledgment. 
            The founders of Red Door Recovery Network have sat in the very seats that you sit now. Face the uncertainty 
            of what this new life will look like. Felt the fear and uncertainty that is associated with the start of this journey.
        </p>
        
        <p class="message">
            Know one thing: <strong>it gets easier.</strong> The cravings will subside, the uncertainty of life will diminish, 
            the legal ramifications will be resolved...and you WILL begin to flourish.
        </p>
        
        <p class="message">
            The beginning of your recovery path is one of the bravest and most courageous actions that a person seeking 
            to change their life into a life of recovery can do. Be proud of these little things in your recovery. 
            These little things add up to major accomplishments.
        </p>
        
        <p class="message">
            Remember, it is NOT the critic that counts, it is those who 
            <a href="<?php echo home_url('/the-man-in-the-arena/'); ?>" class="dare-greatly">dare greatly</a> 
            and do the work.
        </p>
        
        <p class="message">
            Receiving this Day One token serves as a reminder of the promise you made to reclaim your life and make 
            better choices, one day at a time. And if you have accomplished that, even for a short period of time, 
            for now, you should know you have what it takes to keep going.
        </p>
        
        <div class="signature">
            <p>Yours in Recovery,</p>
            <p class="names">Penny and Scott</p>
            <p>Founders of Red Door Recovery Network</p>
        </div>
        
        <button class="btn-close-celebration" onclick="closeCelebration()">Continue to Day 1 Reading →</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Coin selection state
let selectedCoinStyle = '<?php echo esc_js($selected_coin); ?>';
let journeyStarted = <?php echo $journey_started ? 'true' : 'false'; ?>;

// Open coin selection modal
function openCoinModal() {
    $('#coin-modal').addClass('active');
    if (selectedCoinStyle) {
        $(`.coin-option[data-coin="${selectedCoinStyle}"]`).addClass('selected');
    }
}

// Close coin modal
function closeCoinModal() {
    $('#coin-modal').removeClass('active');
}

// Select a coin style
function selectCoin(style) {
    selectedCoinStyle = style;
    $('.coin-option').removeClass('selected');
    $(`.coin-option[data-coin="${style}"]`).addClass('selected');
}

// Confirm coin selection
function confirmCoinSelection() {
    if (!selectedCoinStyle) {
        alert('Please select a coin style first.');
        return;
    }
    
    // Save to cookie (365 days)
    document.cookie = `rdrn_coin_style=${selectedCoinStyle}; path=/; max-age=31536000`;
    closeCoinModal();
}

// Begin the journey
function beginJourney() {
    // Check if coin is selected
    if (!selectedCoinStyle) {
        openCoinModal();
        return;
    }
    
    // Mark journey as started
    document.cookie = 'rdrn_journey_started=1; path=/; max-age=31536000';
    document.cookie = 'rdrn_journey_day=1; path=/; max-age=31536000';
    
    // Show celebration
    showCelebration();
}

// Show celebration modal with animation
function showCelebration() {
    const coinImg = `<?php echo get_template_directory_uri(); ?>/assets/images/coins/${selectedCoinStyle}.png`;
    $('#celebration-coin-img').attr('src', coinImg);
    $('#celebration-modal').addClass('active');
    
    // Start fireworks
    startFireworks();
    
    // Animate the coin
    animateCoin();
}

// Animate the coin with spinning and enlarging effect
function animateCoin() {
    const coin = $('#animated-coin');
    const coinImg = coin.find('img');
    
    // Start small
    coin.css({
        width: '40px',
        height: '40px',
        opacity: 0
    });
    
    // Animate to large with spinning
    coin.animate({
        opacity: 1
    }, 500);
    
    // CSS animation for spin and grow
    let size = 40;
    let rotation = 0;
    
    const growInterval = setInterval(() => {
        size += 8;
        rotation += 30;
        
        coin.css({
            width: size + 'px',
            height: size + 'px',
            transform: `rotateY(${rotation}deg)`
        });
        
        if (size >= 200) {
            clearInterval(growInterval);
            coin.css({
                width: '200px',
                height: '200px',
                transform: 'rotateY(0deg)'
            });
            
            // Add glow effect
            coinImg.css({
                boxShadow: '0 0 50px rgba(201, 162, 39, 0.8), 0 0 100px rgba(201, 162, 39, 0.4)'
            });
        }
    }, 50);
}

// Fireworks effect using canvas
function startFireworks() {
    const canvas = document.getElementById('fireworks-canvas');
    const ctx = canvas.getContext('2d');
    
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    // Red Door brand colors for sparks
    const colors = ['#C9A227', '#dbb84d', '#2E7D32', '#4CAF50', '#B11226'];
    
    const particles = [];
    
    // Create particles from center
    function createBurst(x, y) {
        for (let i = 0; i < 50; i++) {
            const angle = (Math.PI * 2 / 50) * i;
            const velocity = 3 + Math.random() * 5;
            
            particles.push({
                x: x,
                y: y,
                vx: Math.cos(angle) * velocity,
                vy: Math.sin(angle) * velocity,
                color: colors[Math.floor(Math.random() * colors.length)],
                life: 100,
                size: 2 + Math.random() * 3
            });
        }
    }
    
    // Initial bursts
    setTimeout(() => createBurst(canvas.width / 2, canvas.height / 2), 500);
    setTimeout(() => createBurst(canvas.width / 3, canvas.height / 3), 800);
    setTimeout(() => createBurst(canvas.width * 2 / 3, canvas.height / 3), 1100);
    setTimeout(() => createBurst(canvas.width / 4, canvas.height / 2), 1400);
    setTimeout(() => createBurst(canvas.width * 3 / 4, canvas.height / 2), 1700);
    
    // Animation loop
    function animate() {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.1)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        for (let i = particles.length - 1; i >= 0; i--) {
            const p = particles[i];
            
            p.x += p.vx;
            p.y += p.vy;
            p.vy += 0.1; // gravity
            p.life -= 1;
            
            if (p.life <= 0) {
                particles.splice(i, 1);
                continue;
            }
            
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fillStyle = p.color;
            ctx.globalAlpha = p.life / 100;
            ctx.fill();
            ctx.globalAlpha = 1;
            
            // Draw trail
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p.x - p.vx * 3, p.y - p.vy * 3);
            ctx.strokeStyle = p.color;
            ctx.lineWidth = p.size / 2;
            ctx.globalAlpha = p.life / 200;
            ctx.stroke();
            ctx.globalAlpha = 1;
        }
        
        if (particles.length > 0 || document.getElementById('celebration-modal').classList.contains('active')) {
            requestAnimationFrame(animate);
        }
    }
    
    animate();
}

// Close celebration and redirect to day 1
function closeCelebration() {
    $('#celebration-modal').removeClass('active');
    // Redirect to the daily reading page or homepage
    window.location.href = '<?php echo home_url('/'); ?>';
}

// Close modals on escape key
$(document).keyup(function(e) {
    if (e.key === 'Escape') {
        closeCoinModal();
    }
});

// Close modal on overlay click
$('.modal-overlay').on('click', function(e) {
    if (e.target === this) {
        closeCoinModal();
    }
});
</script>

<?php get_footer(); ?>
