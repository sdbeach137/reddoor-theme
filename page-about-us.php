<?php
/**
 * Template Name: About Us Page
 * Description: About Red Door Recovery Network with Founders
 */

get_header(); ?>

<main class="about-page">
    <section class="about-hero">
        <div class="about-container">
            <h1>Find Recovery, by People in Recovery</h1>
        </div>
    </section>
    
    <section class="founders-section">
        <div class="founders-container">
            <h2>Who We Are</h2>
            <p class="founders-intro">Red Door Recovery Network was built by people who have lived this work—not just studied it.</p>
            
            <div class="founders-grid">
                <!-- Scott - Co-Founder -->
                <div class="founder-card">
                    <div class="founder-photo">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/red_door_no_texts.png" alt="Scott - Co-Founder">
                    </div>
                    <div class="founder-info">
                        <h3>Scott</h3>
                        <p class="founder-title">Co-Founder | Clinical Leadership & Systems Development</p>
                        <div class="founder-bio">
                            <p>I am a person in long-term recovery and a Licensed Chemical Dependency Counselor II (LCDC II) with more than a decade of experience providing substance use disorder treatment and recovery support across detoxification, residential, outpatient, intensive outpatient, jail-based, and court-involved settings. My career spans direct clinical care, peer support, systems development, and public service—allowing me to understand recovery from the inside out and from every level of the system.</p>
                            
                            <p>I began my work in recovery as a Certified Peer Support Specialist, walking alongside people early in their journeys, helping them build hope, direction, and connection. Over time, I transitioned into clinical and leadership roles, facilitating evidence-based treatment using Motivational Interviewing (MI), CBT, DBT, relapse prevention, trauma-informed care, Thinking for a Change (T4C), Moral Reconation Therapy (MRT), and recovery-oriented systems of care (ROSC) principles. I have worked extensively with justice-involved populations, post-overdose engagement teams, and individuals with co-occurring mental health and substance use disorders.</p>
                            
                            <p>Beyond direct care, I served seven years on the Ohio Chemical Dependency Professionals Board Treatment Committee, contributing to licensure standards, education and training requirements, ethical oversight, and workforce policy. I have collaborated with ADAMHS Boards, participated in opioid task forces, served on nonprofit boards, and co-founded a court-approved diversion and education program in partnership with municipal court judges. I have also presented to the Ohio General Assembly on behavioral health workforce shortages and access to care.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Penny - Co-Founder -->
                <div class="founder-card">
                    <div class="founder-photo">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/red_door_no_texts.png" alt="Penny - Co-Founder">
                    </div>
                    <div class="founder-info">
                        <h3>Penny</h3>
                        <p class="founder-title">Co-Founder | Peer Recovery & Systems Leadership</p>
                        <div class="founder-bio">
                            <p>Our co-founder is a seasoned peer recovery leader whose work has shaped recovery access and outcomes across multiple systems of care. She served as the Opioid Quick Response Team (QRT) Director, leading post-overdose engagement efforts and coordinating rapid, compassionate outreach to individuals and families at critical moments of readiness for change. In addition, she spent many years as a jail-based case manager, working directly with incarcerated individuals to support re-entry, treatment linkage, and the early foundations of recovery—often engaging people at their most vulnerable and overlooked moments.</p>
                            
                            <p>As a Regional Peer Center Manager and CPRS Supervisor, she managed three peer recovery centers simultaneously and supervised approximately 15 certified peer supporters, ensuring program fidelity, ethical peer practice, and trauma-informed engagement. She is highly experienced in group facilitation and recovery education, with hands-on expertise in Matrix Model, Living in Balance, and SMART Recovery, and has facilitated a wide range of groups including yoga, art in recovery, life-skills development, and active recovery programming.</p>
                            
                            <p>Known for her ability to meet people where they are, she has provided direct peer support to hundreds of individuals impacted by substance use disorder, combining lived experience, structured recovery models, and human connection to help people move from crisis toward stability. Her leadership is grounded in service, dignity, and accountability, and her impact is measured not just in programs built—but in lives changed through consistent, person-centered peer support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="shared-mission-section">
        <div class="mission-statement-container">
            <div class="mission-text">
                <p>Red Door Recovery Network exists because the system is complex, fragmented, and often overwhelming—especially for people in crisis. Our mission is simple: reduce barriers, center lived experience, and connect people to care with dignity, accuracy, and respect. We believe recovery is not one-size-fits-all, and that people deserve guidance from those who understand both the clinical landscape and the human reality of recovery.</p>
                
                <p class="closing-statement"><strong>This is recovery guidance built on lived experience, clinical ethics, and an unwavering belief that people can and do recover—when the door is open and the path is clear.</strong></p>
            </div>
        </div>
    </section>
    
    <section class="mission-section">
        <div class="mission-container">
            <h2>Our Mission</h2>
            <p>To provide comprehensive, accessible information about substance use disorder treatment and recovery services, reducing barriers to care and supporting individuals on their journey to recovery.</p>
            
            <div class="values-grid">
                <div class="value-card">
                    <h3>Compassion</h3>
                    <p>We believe in treating everyone with dignity and respect</p>
                </div>
                <div class="value-card">
                    <h3>Accessibility</h3>
                    <p>Recovery resources should be available to all</p>
                </div>
                <div class="value-card">
                    <h3>Evidence-Based</h3>
                    <p>We promote approaches backed by research and lived experience</p>
                </div>
                <div class="value-card">
                    <h3>Hope</h3>
                    <p>Recovery is possible, and we celebrate every step forward</p>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.about-hero {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: white;
    padding: 60px 20px;
    text-align: center;
}
.about-container {
    max-width: 1200px;
    margin: 0 auto;
}
.about-hero h1 {
    font-size: 48px;
    font-weight: 700;
    margin: 0;
}

.founders-section {
    padding: 80px 20px;
    background: white;
}
.founders-container {
    max-width: 1400px;
    margin: 0 auto;
}
.founders-container h2 {
    font-size: 42px;
    color: #B11226;
    text-align: center;
    margin-bottom: 20px;
}
.founders-intro {
    font-size: 20px;
    text-align: center;
    color: #666;
    margin-bottom: 60px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.founders-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    margin-top: 40px;
}

.founder-card {
    background: #f9f9f9;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.founder-photo {
    width: 200px;
    height: 200px;
    margin: 0 auto 30px;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid #B11226;
    background: white;
}
.founder-photo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 20px;
}

.founder-info h3 {
    font-size: 32px;
    color: #1F2933;
    margin-bottom: 10px;
    text-align: center;
}
.founder-title {
    font-size: 18px;
    color: #2E7D32;
    font-weight: 700;
    text-align: center;
    margin-bottom: 30px;
}

.founder-bio p {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
    margin-bottom: 20px;
}
.founder-bio p:last-child {
    margin-bottom: 0;
}

.shared-mission-section {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    padding: 60px 20px;
}
.mission-statement-container {
    max-width: 1200px;
    margin: 0 auto;
}
.mission-text {
    background: white;
    padding: 50px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}
.mission-text p {
    font-size: 19px;
    line-height: 1.9;
    color: #333;
    margin-bottom: 25px;
}
.mission-text p:last-child {
    margin-bottom: 0;
}
.closing-statement {
    font-size: 20px !important;
    color: #B11226 !important;
    font-weight: 600;
    text-align: center;
    padding-top: 20px;
    border-top: 3px solid #B11226;
}

.mission-section {
    background: #f5f5f5;
    padding: 80px 20px;
}
.mission-container {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
}
.mission-container h2 {
    font-size: 42px;
    color: #B11226;
    margin-bottom: 20px;
}
.mission-container > p {
    font-size: 20px;
    color: #666;
    max-width: 800px;
    margin: 0 auto 60px;
    line-height: 1.8;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 40px;
}
.value-card {
    background: white;
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.value-card h3 {
    font-size: 24px;
    color: #B11226;
    margin-bottom: 15px;
}
.value-card p {
    font-size: 16px;
    color: #666;
    line-height: 1.6;
}

/* Mobile Responsive */
@media (max-width: 968px) {
    .founders-grid {
        grid-template-columns: 1fr;
    }
    .about-hero h1 {
        font-size: 36px;
    }
}
</style>

<?php get_footer(); ?>
