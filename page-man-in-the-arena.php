<?php
/**
 * Template Name: Man in the Arena
 * Description: Theodore Roosevelt's "Man in the Arena" + Tecumseh's "A Warrior's Prayer" - Red Door Recovery Network's life mantras
 */

get_header();

// Get 3 random providers for the top section
$providers = new WP_Query(array(
    'post_type' => 'rdr_provider',
    'posts_per_page' => 3,
    'orderby' => 'rand'
));
?>

<style>
/* Man in the Arena Page */
.arena-page {
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
    max-width: 1200px;
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
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
    font-size: 1rem;
    margin-bottom: 8px;
}

.provider-mini-card .location {
    color: #666;
    font-size: 0.85rem;
    margin-bottom: 8px;
}

.provider-mini-card .phone {
    color: #B11226;
    font-weight: 600;
    font-size: 0.9rem;
}

.provider-mini-card .phone a {
    color: inherit;
    text-decoration: none;
}

.provider-mini-card .btn-details {
    display: inline-block;
    margin-top: 10px;
    color: #B11226;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.85rem;
}

/* Main Content */
.arena-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 20px;
}

/* Header */
.arena-header {
    text-align: center;
    margin-bottom: 50px;
}

.arena-header .overline {
    font-size: 0.85rem;
    color: #B11226;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 15px;
}

.arena-header h1 {
    font-size: 2.5rem;
    color: #1F2933;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.2;
}

.arena-header .intro {
    font-size: 1.1rem;
    color: #666;
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.7;
}

.decorative-line {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #B11226, #C9A227);
    margin: 0 auto 30px;
    border-radius: 2px;
}

/* Two Column Layout for Quotes */
.quotes-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

/* Quote Container Shared Styles */
.quote-container {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.quote-white-box {
    padding: 40px;
    position: relative;
    flex: 1;
}

.quote-white-box::before {
    content: '"';
    position: absolute;
    top: 15px;
    left: 25px;
    font-size: 6rem;
    color: #B11226;
    opacity: 0.1;
    font-family: Georgia, serif;
    line-height: 1;
}

.quote-source {
    text-align: center;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.quote-source .speech-title {
    font-style: italic;
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 5px;
}

.quote-source .speech-details {
    color: #888;
    font-size: 0.85rem;
}

.quote-text {
    font-size: 1.05rem;
    line-height: 1.9;
    color: #1F2933;
    text-align: justify;
    position: relative;
    z-index: 1;
}

.quote-text .highlight {
    background: linear-gradient(180deg, transparent 60%, rgba(177, 18, 38, 0.15) 60%);
    padding: 0 2px;
}

.quote-text .emphasis {
    color: #B11226;
    font-weight: 600;
}

/* Attribution */
.quote-attribution {
    text-align: right;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.attribution-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1F2933;
    margin-bottom: 3px;
}

.attribution-title {
    color: #666;
    font-size: 0.9rem;
    font-style: italic;
}

/* Personal Note - Dark Box */
.quote-dark-box {
    background: linear-gradient(135deg, #1F2933 0%, #2d3a47 100%);
    color: #fff;
    padding: 30px;
}

.quote-dark-box h3 {
    color: #C9A227;
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.quote-dark-box p {
    color: rgba(255,255,255,0.9);
    line-height: 1.8;
    font-size: 0.95rem;
    margin-bottom: 12px;
}

.quote-dark-box p:last-of-type {
    margin-bottom: 0;
}

.quote-dark-box strong {
    color: #fff;
}

.quote-dark-box .signature {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid rgba(255,255,255,0.2);
    text-align: center;
}

.quote-dark-box .signature-text {
    font-style: italic;
    color: rgba(255,255,255,0.7);
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.quote-dark-box .signature-names {
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
}

.quote-dark-box .signature-title {
    color: #C9A227;
    font-size: 0.85rem;
    margin-top: 3px;
}

/* Full Width Philosophy Section */
.philosophy-section {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    padding: 50px;
    margin-top: 40px;
}

.philosophy-section h2 {
    color: #B11226;
    font-size: 1.8rem;
    text-align: center;
    margin-bottom: 30px;
}

.philosophy-content {
    max-width: 900px;
    margin: 0 auto;
}

.philosophy-content p {
    color: #444;
    line-height: 1.9;
    margin-bottom: 20px;
    font-size: 1.05rem;
}

.philosophy-content .founders-quote {
    background: linear-gradient(135deg, #fef2f2 0%, #fff 100%);
    border-left: 4px solid #B11226;
    padding: 25px 30px;
    margin: 30px 0;
    border-radius: 0 12px 12px 0;
}

.philosophy-content .founders-quote p {
    font-size: 1.1rem;
    color: #1F2933;
    margin-bottom: 15px;
    font-style: italic;
}

.philosophy-content .founders-quote .quote-author {
    font-style: normal;
    font-weight: 600;
    color: #B11226;
    text-align: right;
}

.philosophy-content h3 {
    color: #1F2933;
    font-size: 1.4rem;
    margin: 35px 0 20px;
}

.philosophy-content ul {
    list-style: none;
    padding: 0;
    margin: 25px 0;
}

.philosophy-content ul li {
    padding: 12px 0 12px 35px;
    position: relative;
    color: #444;
    font-size: 1.05rem;
    border-bottom: 1px solid #f0f0f0;
}

.philosophy-content ul li:last-child {
    border-bottom: none;
}

.philosophy-content ul li::before {
    content: '✦';
    position: absolute;
    left: 0;
    color: #C9A227;
    font-size: 1rem;
}

.philosophy-content .final-signature {
    text-align: center;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
}

.philosophy-content .final-signature p {
    margin-bottom: 5px;
}

.philosophy-content .final-signature .names {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1F2933;
}

.philosophy-content .final-signature .title {
    color: #B11226;
    font-style: italic;
}

/* Call to Action */
.arena-cta {
    text-align: center;
    margin-top: 50px;
}

.arena-cta p {
    color: #666;
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.btn-journey {
    display: inline-block;
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: #fff;
    padding: 18px 40px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.1rem;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(177, 18, 38, 0.3);
}

.btn-journey:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(177, 18, 38, 0.4);
}

/* Responsive */
@media (max-width: 1024px) {
    .quotes-grid {
        grid-template-columns: 1fr;
    }
    
    .philosophy-section {
        padding: 35px 25px;
    }
}

@media (max-width: 768px) {
    .arena-header h1 {
        font-size: 1.8rem;
    }
    
    .quote-white-box {
        padding: 25px;
    }
    
    .quote-white-box::before {
        font-size: 4rem;
        top: 10px;
        left: 15px;
    }
    
    .quote-text {
        font-size: 0.95rem;
        text-align: left;
    }
    
    .quote-dark-box {
        padding: 25px;
    }
    
    .providers-row {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="arena-page">
    
    <!-- Nearby Providers Section -->
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
                    $counties = wp_get_post_terms(get_the_ID(), 'rdr_county');
                ?>
                <div class="provider-mini-card">
                    <h4><?php the_title(); ?></h4>
                    <p class="location">
                        <?php 
                        if ($city) echo esc_html($city);
                        if ($city && $state) echo ', ';
                        if ($state) echo esc_html($state);
                        if ($counties && !is_wp_error($counties)) {
                            echo ' · ' . esc_html($counties[0]->name) . ' County';
                        }
                        ?>
                    </p>
                    <?php if ($phone) : ?>
                    <p class="phone">
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>">
                            📞 <?php echo esc_html($phone); ?>
                        </a>
                    </p>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="btn-details">View Details →</a>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div class="arena-content">
        
        <!-- Header -->
        <header class="arena-header">
            <p class="overline">Our Guiding Philosophy</p>
            <h1>Words That Shape Our Mission</h1>
            <div class="decorative-line"></div>
            <p class="intro">
                These passages have shaped our approach to recovery, to life, and to building Red Door Recovery Network. 
                They remind us that the path forward belongs to those who dare to live fully—with courage, purpose, and authenticity.
            </p>
        </header>
        
        <!-- Two Column Quotes -->
        <div class="quotes-grid">
            
            <!-- LEFT: Man in the Arena (Roosevelt) -->
            <div class="quote-container">
                <div class="quote-white-box">
                    <div class="quote-source">
                        <p class="speech-title">Excerpt from "Citizenship In A Republic"</p>
                        <p class="speech-details">Delivered at the Sorbonne, Paris, France · April 23, 1910</p>
                    </div>
                    
                    <div class="quote-text">
                        It is not the critic who counts; not the man who points out how the strong man stumbles, 
                        or where the doer of deeds could have done them better. The credit belongs to 
                        <span class="highlight">the man who is actually in the arena</span>, whose face is marred by dust 
                        and sweat and blood; who strives valiantly; who errs, who comes short again and again, 
                        because there is no effort without error and shortcoming; but who does actually strive 
                        to do the deeds; who knows great enthusiasms, the great devotions; who spends himself 
                        in a worthy cause; who at the best knows in the end the triumph of high achievement, 
                        and who at the worst, if he fails, <span class="emphasis">at least fails while daring greatly</span>, 
                        so that his place shall never be with those cold and timid souls who neither know victory nor defeat.<br/><br/><br/><br/><br/><br/><br/><br/><br/>
                    </div>
                    
                    <div class="quote-attribution">
                        <p class="attribution-name">Theodore Roosevelt</p>
                        <p class="attribution-title">26th President of the United States</p>
                    </div>
                </div>
                
                <div class="quote-dark-box">
                    <h3>Why This Matters to Us</h3>
                    <p>
                        Recovery is not a spectator sport. It requires stepping into the arena—facing uncertainty, 
                        risking failure, and choosing to fight for a better life even when the outcome isn't guaranteed.
                    </p>
                    <p>
                        We have sat where you sit now. We have felt the fear, the doubt, and the weight of starting over. 
                        But we also know the triumph that comes from daring greatly—from refusing to let addiction 
                        write the final chapter of our story.
                    </p>
                    <p>
                        This speech has been our compass through the hardest days. We share it with you now as a reminder: 
                        <strong>you are not a critic on the sidelines. You are in the arena.</strong> And that takes courage.
                    </p>
                    
                    <div class="signature">
                        <p class="signature-text">Yours in Recovery,</p>
                        <p class="signature-names">Penny &amp; Scott</p>
                        <p class="signature-title">Founders, Red Door Recovery Network</p>
                    </div>
                </div>
            </div>
            
            <!-- RIGHT: A Warrior's Prayer (Tecumseh) -->
            <div class="quote-container">
                <div class="quote-white-box">
                    <div class="quote-source">
                        <p class="speech-title">Chief Tecumseh: A Poem on Living</p>
                        <p class="speech-details">"A Warrior's Prayer"</p>
                    </div>
                    
                    <div class="quote-text">
                        So live your life that the fear of death can never enter your heart. Trouble no one about their religion; 
                        respect others in their view, and demand that they respect yours. Love your life, perfect your life, 
                        beautify all things in your life. Seek to make your life long and its purpose in the service of your people.
                        <br><br>
                        Prepare a noble death song for the day when you go over the great divide. Always give a word or a sign 
                        of salute when meeting or passing a friend, even a stranger, when in a lonely place. Show respect to all 
                        people and grovel to none.
                        <br><br>
                        When you arise in the morning give thanks for the food and for the joy of living. If you see no reason 
                        for giving thanks, the fault lies only in yourself. Abuse no one and no thing, for abuse turns the wise 
                        ones to fools and robs the spirit of its vision.
                        <br><br>
                        When it comes your time to die, be not like those whose hearts are filled with the fear of death, so that 
                        when their time comes they weep and pray for a little more time to live their lives over again in a different way. 
                        <span class="emphasis">Sing your death song and die like a hero going home.</span>
                    </div>
                    
                    <div class="quote-attribution">
                        <p class="attribution-name">Chief Tecumseh</p>
                        <p class="attribution-title">Shawnee Leader (1768–1813)</p>
                    </div>
					
                   
                </div>
                
                <div class="quote-dark-box">
                    <h3>The Wisdom of Living Fully</h3>
                    <p>
                        The words attributed to Tecumseh speak to a way of living that transcends time, culture, and circumstance. 
                        They are not about death in a literal sense, but about how we live—with integrity, purpose, courage, 
                        gratitude, and love—so that fear no longer governs our choices.
                    </p>
                    <p>
                        In recovery, this philosophy becomes profoundly relevant.
                    </p>
                    <p>
                        Substance use disorder is not simply about substances. It is often about disconnection—from purpose, 
                        from self, from community, and from meaning. Tecumseh's words call us back to a life that is lived 
                        fully present, where respect replaces shame, gratitude replaces resentment, and purpose replaces despair.
                    </p>
                    <p>
                        <strong>Recovery, at its core, is not about abstinence alone. It is about learning how to live a life worth staying present for.</strong>
                    </p>
                    <p>
                        When Tecumseh speaks of loving your life, perfecting it, beautifying it, and serving others, he is describing 
                        the very foundation of sustainable recovery: a life anchored in meaning rather than escape. A life where we 
                        no longer need to run from ourselves.
                    </p> 
					
					<div class="signature">
                        <p class="signature-text">Yours in Recovery,</p>
                        <p class="signature-names">Penny &amp; Scott</p>
                        <p class="signature-title">Founders, Red Door Recovery Network</p>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Full Width Philosophy Section -->
        <div class="philosophy-section">
            <h2>Purpose as the Antidote to Escape</h2>
            
            <div class="philosophy-content">
                <p>
                    The founders of Red Door Recovery Network believe that recovery is strongest when it is purpose-driven, 
                    values-based, and rooted in authentic connection.
                </p>
                
                <p>As we often say:</p>
                
                <div class="founders-quote">
                    <p>
                        "Drugs and alcohol are a coping skill that is used to escape from a life that you cannot bear to be present in. 
                        By living an authentic, vulnerable and purpose-driven life that you love, there will be no need for this coping skill. 
                        Suddenly you will find yourself running towards a life of recovery, and the need for drugs and alcohol to cope 
                        will silently slip away from your life.
                    </p>
                    <p>
                        Everyone in recovery deserves a great life. Not to be blessed by, or earned, or given, but it is <em>deserved</em>. 
                        Go find the life you deserve — <strong>Decide what to Be and Go Be It.</strong>
                    </p>
                    <p>
                        But above all — Live your life fiercely and unapologetically for good.'"</em>
                    </p>
                    <p class="quote-author">— Scott &amp; Penny,<br/ > Founders of Red Door Recovery Network</p>
                </div>
                
                <h3>This Belief Reframes Recovery Entirely</h3>
                
                <p>
                    Substances are not the enemy—they are a signal. A signal that something in life feels unbearable, unlivable, 
                    or disconnected from meaning. When a person builds a life rooted in love, service, belonging, creativity, 
                    and self-respect, the need for escape begins to dissolve.
                </p>
                
                <p>
                    Not through force. Not through fear. <strong>But through fulfillment.</strong>
                </p>
                
                <h3>Recovery as a Fierce Commitment to Life</h3>
                
                <p>
                    Tecumseh urges us not to grovel, not to abuse ourselves or others, not to live small or afraid. 
                    He speaks of respect, gratitude, courage, and service—values that mirror what long-term recovery demands.
                </p>
                
                <p>
                    <strong>Recovery is not passive. It is an act of bravery.</strong>
                </p>
                
                <p>
                    It is choosing to live honestly. Choosing to face pain without numbing. Choosing to build something 
                    meaningful from what was once broken.
                </p>
                
                <p>
                    And when recovery is built on that foundation, it becomes not a burden—but a calling.
                </p>
                
					<div class="scott_centered">
						<p>
						<strong>At Red Door Recovery Network, we believe:</strong></p>
					</div>
						<ul>
							<li>Everyone in recovery deserves a great life</li>
							<li>Purpose is not optional—it is essential</li>
							<li>Healing happens when people are seen, respected, and empowered</li>
							<li>Recovery is not about shrinking your life—it is about expanding it</li>
						</ul>
					<div>
						<p>
							To live fiercely. To live unapologetically for good. To live in such a way that fear no longer drives our choices.
						</p>

						<p>
							And one day—far in the future—to look back and know we truly lived.
						</p>
						</div>
					
                
                <div class="final-signature">
                    <p class="names">~Scott &amp; Penny</p><br />
                    <p class="title">Founders of Red Door Recovery Network</p>
                </div>
            </div>
        </div>
        
        <!-- Call to Action -->
        <div class="arena-cta">
            <p>Ready to begin your own journey through the Red Door?</p>
            <a href="<?php echo home_url('/begin-your-journey/'); ?>" class="btn-journey">
                Begin Your 90-Day Journey →
            </a>
        </div>
        
    </div>
</div>

<?php get_footer(); ?>
