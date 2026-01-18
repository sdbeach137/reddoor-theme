<?php
/**
 * Template Name: Claim Your Listing
 * Description: Provider upgrade/claim listing page
 */

get_header();
?>

<style>
/* Claim Your Listing Page */
.claim-page {
    background: #F3F4F6;
    min-height: 100vh;
}

.claim-hero {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: #fff;
    padding: 60px 20px;
    text-align: center;
}

.claim-hero h1 {
    font-size: 2.5rem;
    color: #fff;
    margin-bottom: 15px;
}

.claim-hero p {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto 25px;
}

.btn-hero {
    display: inline-block;
    background: #fff;
    color: #B11226;
    padding: 16px 40px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.1rem;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-hero:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.claim-hero .subtext {
    margin-top: 15px;
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Main Content */
.claim-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 50px 20px;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 50px;
    align-items: start;
}

/* Main Column */
.claim-main {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 40px;
}

.claim-main h2 {
    font-size: 2rem;
    color: #B11226;
    margin-bottom: 20px;
    line-height: 1.3;
}

.claim-main .intro-text {
    font-size: 1.1rem;
    color: #444;
    line-height: 1.8;
    margin-bottom: 30px;
}

/* Why Section */
.why-section {
    margin: 40px 0;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 12px;
}

.why-section h3 {
    color: #1F2933;
    font-size: 1.3rem;
    margin-bottom: 20px;
}

.why-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.why-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #e0e0e0;
}

.why-list li:last-child {
    border-bottom: none;
}

.why-list .icon {
    width: 24px;
    height: 24px;
    background: #2E7D32;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 14px;
}

.why-list span {
    color: #444;
    line-height: 1.5;
}

/* Listing Tiers */
.tier-section {
    margin: 40px 0;
}

.tier-section h3 {
    color: #1F2933;
    font-size: 1.5rem;
    margin-bottom: 25px;
    text-align: center;
}

.tier-card {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.tier-card:hover {
    border-color: #B11226;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.tier-card.featured {
    border-color: #C9A227;
    background: linear-gradient(135deg, #fffbf0 0%, #fff 100%);
}

.tier-card.featured::before {
    content: '★ MOST POPULAR';
    display: block;
    background: #C9A227;
    color: #fff;
    text-align: center;
    padding: 5px;
    margin: -25px -25px 20px;
    border-radius: 10px 10px 0 0;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
}

.tier-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.tier-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1F2933;
}

.tier-label {
    font-size: 0.75rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: block;
    margin-bottom: 3px;
}

.tier-price {
    text-align: right;
}

.tier-price .amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #B11226;
}

.tier-price .period {
    font-size: 0.85rem;
    color: #666;
}

.tier-tagline {
    color: #666;
    font-style: italic;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.tier-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tier-features li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 8px 0;
    color: #444;
    font-size: 0.95rem;
}

.tier-features .check {
    color: #2E7D32;
    font-weight: bold;
    flex-shrink: 0;
}

/* Premium Section */
.premium-highlight {
    background: linear-gradient(135deg, #1F2933 0%, #2d3a47 100%);
    color: #fff;
    border-radius: 12px;
    padding: 30px;
    margin: 30px 0;
}

.premium-highlight h4 {
    color: #C9A227;
    font-size: 1.2rem;
    margin-bottom: 15px;
}

.premium-highlight p {
    color: rgba(255,255,255,0.9);
    line-height: 1.7;
    margin-bottom: 15px;
}

.premium-highlight ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.premium-highlight li {
    padding: 8px 0;
    color: rgba(255,255,255,0.9);
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.premium-highlight .check {
    color: #C9A227;
}

/* SEO Table */
.seo-table {
    width: 100%;
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9rem;
}

.seo-table th,
.seo-table td {
    padding: 12px 10px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
}

.seo-table th {
    background: #f8f9fa;
    color: #1F2933;
    font-weight: 600;
}

.seo-table th:first-child,
.seo-table td:first-child {
    text-align: left;
}

.seo-table .check-cell {
    color: #2E7D32;
}

.seo-table .dash-cell {
    color: #ccc;
}

/* Disclaimer */
.disclaimer-box {
    background: #fef2f2;
    border-left: 4px solid #B11226;
    padding: 20px;
    margin: 30px 0;
    font-size: 0.9rem;
    color: #666;
    border-radius: 0 8px 8px 0;
}

.disclaimer-box strong {
    color: #B11226;
}

/* Sidebar */
.claim-sidebar {
    position: sticky;
    top: 20px;
}

.pricing-widget {
    background: #1F2933;
    color: #fff;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 25px;
}

.pricing-widget h3 {
    color: #fff;
    font-size: 1.2rem;
    margin-bottom: 25px;
    text-align: center;
}

.pricing-tier {
    padding: 15px 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.pricing-tier:last-child {
    border-bottom: none;
}

.pricing-tier .tier-label {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.pricing-tier .tier-name {
    color: #fff;
    font-size: 1rem;
    margin: 3px 0;
}

.pricing-tier .tier-price {
    color: #C9A227;
    font-weight: 700;
}

.pricing-tier ul {
    list-style: none;
    padding: 0;
    margin: 10px 0 0;
    font-size: 0.85rem;
}

.pricing-tier li {
    color: rgba(255,255,255,0.7);
    padding: 3px 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.pricing-tier .check {
    color: #2E7D32;
}

.btn-claim-sidebar {
    display: block;
    background: #B11226;
    color: #fff;
    text-align: center;
    padding: 16px 20px;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    margin-top: 25px;
    transition: all 0.3s;
}

.btn-claim-sidebar:hover {
    background: #8B0F1F;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 1024px) {
    .claim-content {
        grid-template-columns: 1fr;
    }
    
    .claim-sidebar {
        position: static;
    }
}

@media (max-width: 600px) {
    .claim-hero h1 {
        font-size: 1.8rem;
    }
    
    .claim-main {
        padding: 25px;
    }
    
    .tier-header {
        flex-direction: column;
    }
    
    .tier-price {
        text-align: left;
    }
}
</style>

<div class="claim-page">
    <!-- Hero Section - DO NOT CHANGE -->
    <section class="claim-hero">
        <h1>Claim Your Listing</h1>
        <p>Ready to Get Started?</p>
        <p>Join hundreds of providers connecting with people seeking help.</p>
        <a href="#claim-form" class="btn-hero">Claim Your Listing</a>
        <p class="subtext">Free access always available.</p>
    </section>
    
    <!-- Main Content -->
    <div class="claim-content">
        <!-- Main Column -->
        <main class="claim-main">
            <h2>Turn Your Free Listing Into a Recovery-Focused Referral Engine</h2>
            
            <p class="intro-text">
                Every provider on Red Door Recovery Network is listed for free—because access to care should never be hidden behind a paywall.
            </p>
            
            <p class="intro-text">
                Claiming your listing allows you to control how your organization is presented, improve visibility, and connect with people who are actively seeking help.
            </p>
            
            <p class="intro-text">
                Built by people in recovery, Red Door is designed to support ethical engagement, transparency, and meaningful referrals—not gimmicks, lead farms, or exploitative advertising.
            </p>
            
            <!-- Why Section -->
            <div class="why-section">
                <h3>Why Providers List With Red Door</h3>
                <p style="margin-bottom: 20px;">Providers choose Red Door because we:</p>
                <ul class="why-list">
                    <li>
                        <span class="icon">✓</span>
                        <span>Never hide contact information</span>
                    </li>
                    <li>
                        <span class="icon">✓</span>
                        <span>Prioritize recovery-centered, trauma-informed presentation</span>
                    </li>
                    <li>
                        <span class="icon">✓</span>
                        <span>Match people to care based on services, level of care, and geography</span>
                    </li>
                    <li>
                        <span class="icon">✓</span>
                        <span>Provide search visibility without selling leads</span>
                    </li>
                    <li>
                        <span class="icon">✓</span>
                        <span>Offer upgrade paths that add clarity, not pressure</span>
                    </li>
                </ul>
                <p style="margin-top: 20px; font-style: italic; color: #666;">You choose how visible and interactive your listing becomes.</p>
            </div>
            
            <!-- Listing Tiers -->
            <div class="tier-section">
                <h3>Listing Options & Benefits</h3>
                
                <!-- Free Tier -->
                <div class="tier-card">
                    <div class="tier-header">
                        <div>
                            <span class="tier-label">Always Free</span>
                            <span class="tier-name">Free Listing</span>
                        </div>
                        <div class="tier-price">
                            <span class="amount">$0</span>
                        </div>
                    </div>
                    <p class="tier-tagline">Ethical access, always included</p>
                    <p style="color: #444; margin-bottom: 15px;">Every provider receives a free listing.</p>
                    <ul class="tier-features">
                        <li><span class="check">✓</span> Provider or agency name</li>
                        <li><span class="check">✓</span> Address and service area</li>
                        <li><span class="check">✓</span> Phone number</li>
                        <li><span class="check">✓</span> County listing</li>
                    </ul>
                    <p style="margin-top: 15px; font-size: 0.9rem; color: #666;">This ensures people seeking help can always find basic contact information.</p>
                </div>
                
                <!-- Basic Tier -->
                <div class="tier-card">
                    <div class="tier-header">
                        <div>
                            <span class="tier-label">Grow Your Visibility</span>
                            <span class="tier-name">Basic Listing</span>
                        </div>
                        <div class="tier-price">
                            <span class="amount">$29.95</span>
                            <span class="period">/ month</span>
                        </div>
                    </div>
                    <p class="tier-tagline">A professional presence</p>
                    <p style="color: #444; margin-bottom: 15px;">Everything in Free, plus:</p>
                    <ul class="tier-features">
                        <li><span class="check">✓</span> One featured photo (logo or facility)</li>
                        <li><span class="check">✓</span> Short written description</li>
                        <li><span class="check">✓</span> Services and level-of-care tags</li>
                        <li><span class="check">✓</span> Improved placement above free listings</li>
                    </ul>
                    <p style="margin-top: 15px; font-size: 0.9rem; color: #666;"><strong>Best for:</strong> individual providers, peer supporters, and small programs.</p>
                </div>
                
                <!-- Enhanced Tier -->
                <div class="tier-card">
                    <div class="tier-header">
                        <div>
                            <span class="tier-label">Build Credibility</span>
                            <span class="tier-name">Enhanced Listing</span>
                        </div>
                        <div class="tier-price">
                            <span class="amount">$99.95</span>
                            <span class="period">/ month</span>
                        </div>
                    </div>
                    <p class="tier-tagline">Visibility and credibility</p>
                    <p style="color: #444; margin-bottom: 15px;">Everything in Basic, plus:</p>
                    <ul class="tier-features">
                        <li><span class="check">✓</span> Full photo gallery</li>
                        <li><span class="check">✓</span> Expanded narrative description</li>
                        <li><span class="check">✓</span> Accreditations displayed (CARF, Joint Commission, OhioMHAS, etc.)</li>
                        <li><span class="check">✓</span> Insurance and payment methods highlighted</li>
                        <li><span class="check">✓</span> Priority placement in county and service searches</li>
                    </ul>
                    <p style="margin-top: 15px; font-size: 0.9rem; color: #666;"><strong>Best for:</strong> outpatient programs, peer centers, and multi-service agencies.</p>
                </div>
                
                <!-- Featured Tier -->
                <div class="tier-card featured">
                    <div class="tier-header">
                        <div>
                            <span class="tier-label">Stand Out</span>
                            <span class="tier-name">Featured Listing</span>
                        </div>
                        <div class="tier-price">
                            <span class="amount">$199.95</span>
                            <span class="period">/ month</span>
                        </div>
                    </div>
                    <p class="tier-tagline">Designed to stand out—and be found</p>
                    <p style="color: #444; margin-bottom: 15px;">Everything in Enhanced, plus:</p>
                    <ul class="tier-features">
                        <li><span class="check">✓</span> Featured badge in search results</li>
                        <li><span class="check">✓</span> Top placement within county and service filters</li>
                        <li><span class="check">✓</span> Highlighted services and populations served</li>
                        <li><span class="check">✓</span> Choice of 2 professional page themes</li>
                        <li><span class="check">✓</span> Expanded visual layout</li>
                    </ul>
                    
                    <div class="premium-highlight" style="margin-top: 20px;">
                        <h4>🔍 Google Placement Advantage</h4>
                        <p>Featured listings are structured and optimized to improve Google visibility, increasing the likelihood that your listing appears in local and service-based searches—not just inside the directory.</p>
                        <p style="margin-bottom: 0;">At this level, your listing begins functioning as a <strong>search-optimized landing page</strong>, not simply a directory entry.</p>
                    </div>
                </div>
                
                <!-- Premium Tier -->
                <div class="tier-card">
                    <div class="tier-header">
                        <div>
                            <span class="tier-label">Maximum Impact</span>
                            <span class="tier-name">Premium / Exclusive Listing</span>
                        </div>
                        <div class="tier-price">
                            <span class="amount">$499.95–$899.95</span>
                            <span class="period">/ month</span>
                        </div>
                    </div>
                    <p class="tier-tagline">A custom recovery website with active SEO oversight</p>
                    
                    <div class="premium-highlight" style="margin: 20px 0;">
                        <h4>🎯 Professional SEO Monitoring (Premium Only)</h4>
                        <p>Premium listings receive ongoing SEO monitoring by Red Door's professional SEO team. Your listing:</p>
                        <ul>
                            <li><span class="check">✓</span> Is designed to rank as its own Google-indexed webpage</li>
                            <li><span class="check">✓</span> Can be found directly through Google, not only via the directory</li>
                            <li><span class="check">✓</span> Is monitored for performance, indexing health, and visibility trends</li>
                        </ul>
                        <p style="margin-top: 15px; margin-bottom: 0;">This provides long-term search equity without hiring a separate marketing firm.</p>
                    </div>
                    
                    <p style="color: #444; margin-bottom: 15px;">Everything in Featured, plus:</p>
                    <ul class="tier-features">
                        <li><span class="check">✓</span> <strong>Tracked Phone Number</strong> — Unique number routed to your existing line with call volume tracking</li>
                        <li><span class="check">✓</span> <strong>Secure Contact & Intake Form</strong> — Customizable fields, email notifications, HIPAA-aware disclaimers</li>
                        <li><span class="check">✓</span> <strong>Custom Single-Page Website (WYSIWYG)</strong> — Your listing becomes a fully branded, SEO-indexed page</li>
                        <li><span class="check">✓</span> Choice of 5 professional themes</li>
                        <li><span class="check">✓</span> <strong>Priority Placement</strong> — Maximum directory visibility</li>
                        <li><span class="check">✓</span> <strong>Limited Exclusivity</strong> — By county or service type</li>
                    </ul>
                </div>
            </div>
            
            <!-- Theme Options -->
            <div style="background: #f8f9fa; padding: 25px; border-radius: 12px; margin: 30px 0;">
                <h4 style="color: #1F2933; margin-bottom: 15px;">Available Page Themes (Featured & Premium)</h4>
                <p style="color: #666; margin-bottom: 15px;">Each theme includes: Hero section, About your program, Services, Populations served, Accreditations, Gallery, Contact form, and Clear calls-to-action.</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
                    <span style="background: #fff; padding: 10px 15px; border-radius: 6px; text-align: center; font-size: 0.9rem;">Clinical & Professional</span>
                    <span style="background: #fff; padding: 10px 15px; border-radius: 6px; text-align: center; font-size: 0.9rem;">Peer-Led Recovery</span>
                    <span style="background: #fff; padding: 10px 15px; border-radius: 6px; text-align: center; font-size: 0.9rem;">Community & Family</span>
                    <span style="background: #fff; padding: 10px 15px; border-radius: 6px; text-align: center; font-size: 0.9rem;">Residential / Healing</span>
                    <span style="background: #fff; padding: 10px 15px; border-radius: 6px; text-align: center; font-size: 0.9rem;">Modern Minimal</span>
                </div>
            </div>
            
            <!-- SEO Explanation -->
            <div style="margin: 40px 0;">
                <h3 style="color: #1F2933; margin-bottom: 15px;">What "SEO Monitoring" Means (Plain Language)</h3>
                <p style="color: #444; line-height: 1.7; margin-bottom: 15px;">SEO monitoring does <strong>not</strong> mean buying ads or gaming Google. It means:</p>
                <ul style="color: #444; line-height: 1.8; padding-left: 20px;">
                    <li>Making sure your page is indexed correctly</li>
                    <li>Monitoring how your page appears in search</li>
                    <li>Adjusting structure, metadata, and content signals over time</li>
                    <li>Ensuring your listing remains visible as search algorithms evolve</li>
                </ul>
                <p style="color: #666; font-style: italic; margin-top: 15px;">Premium listings receive active attention, not "set-it-and-forget-it" placement.</p>
            </div>
            
            <!-- SEO Comparison Table -->
            <div style="margin: 40px 0;">
                <h3 style="color: #1F2933; margin-bottom: 20px;">SEO Feature Comparison</h3>
                <div style="overflow-x: auto;">
                    <table class="seo-table">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th>Free</th>
                                <th>Basic</th>
                                <th>Enhanced</th>
                                <th>Featured</th>
                                <th>Premium</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Indexed by Google</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓</td>
                            </tr>
                            <tr>
                                <td>Appears in directory searches</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓</td>
                            </tr>
                            <tr>
                                <td>Priority internal placement</td>
                                <td class="dash-cell">—</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓✓</td>
                                <td class="check-cell">✓✓✓</td>
                                <td class="check-cell">✓✓✓✓</td>
                            </tr>
                            <tr>
                                <td>Google-optimized structure</td>
                                <td class="dash-cell">—</td>
                                <td class="dash-cell">—</td>
                                <td class="check-cell">✓</td>
                                <td class="check-cell">✓✓</td>
                                <td class="check-cell">✓✓✓</td>
                            </tr>
                            <tr>
                                <td>Page theme options</td>
                                <td class="dash-cell">—</td>
                                <td class="dash-cell">—</td>
                                <td class="dash-cell">—</td>
                                <td>2</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Standalone Google ranking</td>
                                <td class="dash-cell">—</td>
                                <td class="dash-cell">—</td>
                                <td class="dash-cell">—</td>
                                <td>Limited</td>
                                <td><strong>Primary focus</strong></td>
                            </tr>
                            <tr>
                                <td>Active SEO monitoring</td>
                                <td class="dash-cell">—</td>
                                <td class="dash-cell">—</td>
                                <td class="dash-cell">—</td>
                                <td class="dash-cell">—</td>
                                <td class="check-cell">✓</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Disclaimer -->
            <div class="disclaimer-box">
                <strong>SEO Compliance Disclaimer</strong><br><br>
                Red Door Recovery Network does not guarantee specific Google rankings, traffic volume, or call counts. Search performance depends on multiple factors including location, competition, and user behavior.<br><br>
                SEO monitoring is intended to support visibility and discoverability, not replace clinical quality, availability, or appropriateness of care.
            </div>
            
            <!-- CTA -->
            <div id="claim-form" style="text-align: center; margin-top: 40px; padding: 40px; background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%); border-radius: 12px; color: #fff;">
                <h3 style="color: #fff; font-size: 1.5rem; margin-bottom: 15px;">Ready to Claim Your Listing?</h3>
                <p style="opacity: 0.9; margin-bottom: 20px;">Get started today and connect with people seeking help.</p>
                <a href="<?php echo home_url('/register/'); ?>" style="display: inline-block; background: #fff; color: #B11226; padding: 16px 40px; border-radius: 50px; font-weight: 700; text-decoration: none;">👉 Claim Your Listing</a>
            </div>
        </main>
        
        <!-- Sidebar - DO NOT CHANGE -->
        <aside class="claim-sidebar">
            <div class="pricing-widget">
                <h3>Listing Options at a Glance</h3>
                
                <div class="pricing-tier">
                    <span class="tier-label">Always Free</span>
                    <div class="tier-name">Free Listing</div>
                    <div class="tier-price">$0</div>
                    <ul>
                        <li><span class="check">✓</span> Name, address, phone</li>
                        <li><span class="check">✓</span> County & service area</li>
                        <li><span class="check">✓</span> Basic discoverability</li>
                        <li><span class="check">✓</span> Access to care is never restricted</li>
                    </ul>
                </div>
                
                <div class="pricing-tier">
                    <span class="tier-label">Grow Your Visibility</span>
                    <div class="tier-name">Basic</div>
                    <div class="tier-price">$29.95 / mo</div>
                    <ul>
                        <li><span class="check">✓</span> 1 featured photo</li>
                        <li><span class="check">✓</span> Short description</li>
                        <li><span class="check">✓</span> Service tags</li>
                        <li><span class="check">✓</span> Better search placement</li>
                    </ul>
                </div>
                
                <div class="pricing-tier">
                    <span class="tier-label">Build Credibility</span>
                    <div class="tier-name">Enhanced</div>
                    <div class="tier-price">$99.95 / mo</div>
                    <ul>
                        <li><span class="check">✓</span> Photo gallery</li>
                        <li><span class="check">✓</span> Expanded description</li>
                        <li><span class="check">✓</span> Accreditations displayed</li>
                        <li><span class="check">✓</span> Insurance & payment info</li>
                        <li><span class="check">✓</span> Priority search placement</li>
                    </ul>
                </div>
                
                <div class="pricing-tier" style="background: rgba(201,162,39,0.1); margin: 15px -30px; padding: 15px 30px;">
                    <span class="tier-label">Stand Out</span>
                    <div class="tier-name">Featured</div>
                    <div class="tier-price">$199.95 / mo</div>
                    <ul>
                        <li><span class="check">✓</span> Featured badge</li>
                        <li><span class="check">✓</span> Top placement</li>
                        <li><span class="check">✓</span> 2 page themes</li>
                        <li><span class="check">✓</span> Google-optimized</li>
                    </ul>
                </div>
                
                <div class="pricing-tier">
                    <span class="tier-label">Maximum Impact</span>
                    <div class="tier-name">Premium / Exclusive</div>
                    <div class="tier-price">$499.95–$899.95 / mo</div>
                    <ul>
                        <li><span class="check">✓</span> Custom website</li>
                        <li><span class="check">✓</span> Active SEO monitoring</li>
                        <li><span class="check">✓</span> Tracked phone number</li>
                        <li><span class="check">✓</span> Contact/intake form</li>
                        <li><span class="check">✓</span> 5 page themes</li>
                    </ul>
                </div>
                
                <a href="#claim-form" class="btn-claim-sidebar">👉 Claim Your Listing</a>
            </div>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
