<?php
/**
 * Template Name: Stigma Page
 * Description: Understanding & Reducing Stigma
 */

get_header(); ?>

<main class="stigma-page">
    <!-- PROVIDER LISTINGS (Same as full reading page) -->
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
            <?php include(get_template_directory() . '/inc/provider-listings.php'); ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- STIGMA CONTENT -->
    <section class="stigma-content">
        <div class="content-container">
            <h1>Understanding & Reducing Stigma Around Substance Use Disorders</h1>
            <p class="intro">
                Stigma surrounding substance use disorders prevents people from seeking help,
                accessing care, and sustaining recovery. Red Door Recovery Network believes
                recovery should never be hidden or shamed.
            </p>
            
            <div class="stigma-section">
                <h2>What Is Stigma?</h2>
                <p>
                    Stigma is a social process that labels, stereotypes, and devalues individuals.
                    In addiction, stigma frames a treatable health condition as a moral failure.
                </p>
            </div>
            
            <div class="stigma-section">
                <h2>Why Stigma Exists</h2>
                <ul>
                    <li>Misunderstanding addiction as choice rather than disease</li>
                    <li>Historical moral narratives</li>
                    <li>Media portrayals focused on crime and relapse</li>
                    <li>Fear and lack of education</li>
                </ul>
            </div>
            
            <div class="stigma-section">
                <h2>How Stigma Hurts People</h2>
                <p>
                    Stigma leads to shame, delayed treatment, reduced access to care,
                    and systemic barriers that harm recovery outcomes.
                </p>
            </div>
            
            <div class="stigma-section">
                <h2>How We Can Counter Stigma</h2>
                <ul>
                    <li>Use person-first language</li>
                    <li>Share recovery stories</li>
                    <li>Support peer-led services</li>
                    <li>Educate healthcare and community leaders</li>
                </ul>
            </div>
            
            <div class="stigma-section">
                <h2>Ohio Beat the Stigma – Real Voices</h2>
                <div class="video-grid">
                    <iframe width="560" height="315"
                        src="https://www.youtube.com/embed/1rxaYYECevU"
                        title="Beat the Stigma – Recovery Ohio"
                        frameborder="0"
                        allowfullscreen></iframe>
                    <iframe width="560" height="315"
                        src="https://www.youtube.com/embed/WwBHwBWRaR4"
                        title="Beating the Stigma of Addiction and Mental Illness"
                        frameborder="0"
                        allowfullscreen></iframe>
                    <iframe width="560" height="315"
                        src="https://www.youtube.com/embed/EbwNDKHaYNQ"
                        title="Beat the Stigma – Recovery is Possible"
                        frameborder="0"
                        allowfullscreen></iframe>
                    <iframe width="560" height="315"
                        src="https://www.youtube.com/embed/NxiFxLyhJj8"
                        title="Beat the Stigma – Ohio Recovery Story"
                        frameborder="0"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
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
.stigma-section {
    margin: 30px 0;
}
.stigma-section h2 {
    font-size: 28px;
    color: var(--rdr-charcoal);
    margin-bottom: 15px;
}
.stigma-section p, .stigma-section li {
    font-size: 16px;
    line-height: 1.8;
    color: var(--rdr-charcoal);
}
.stigma-section ul {
    list-style: disc;
    margin-left: 30px;
}
.stigma-section li {
    margin: 10px 0;
}
.video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.video-grid iframe {
    width: 100%;
    height: 250px;
    border-radius: 10px;
}
</style>

<?php get_footer(); ?>
