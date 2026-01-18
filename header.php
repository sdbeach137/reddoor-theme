<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-container">
        <div class="site-logo">
            <a href="<?php echo home_url('/'); ?>">
                <h2 class="rdr-logo-text">
                    <span class="rdr-red">Red Door</span><br>
                    <span class="rdr-green">Recovery Network</span>
                </h2>
            </a>
        </div>
		  <?php
            // Check if pages exist and link to them, otherwise use fallback URLs
            $peer = get_page_by_path('peer-support');
            $stigma = get_page_by_path('stigma');
            $trauma = get_page_by_path('trauma-informed-care');
            $about = get_page_by_path('about-us');
			$claim = get_page_by_path('claim-your-listing');
			$treatment = get_page_by_path('find-treatment');
			$theman = get_page_by_path('the-man-in-the-arena');
            ?>
        
        <div class="header-right">
            <a href="#" class="header-login">Log In</a>
            <a href="<?php echo $claim ? get_permalink($claim->ID) : home_url('/claim-your-listing/'); ?>" class="btn-claim-listing">Claim your listing</a>
        </div>
    </div>
</header>

<nav class="main-navigation">
    <div class="nav-container">
        <ul class="nav-menu">
          
            <li><a href="<?php echo $peer ? get_permalink($peer->ID) : home_url('/peer-support/'); ?>">Peer Support</a></li>
            <li><a href="<?php echo $stigma ? get_permalink($stigma->ID) : home_url('/stigma/'); ?>">Stigma</a></li>
            <li><a href="<?php echo $trauma ? get_permalink($trauma->ID) : home_url('/trauma-informed-care/'); ?>">Trauma-informed care</a></li>
            <li><a href="<?php echo $about ? get_permalink($about->ID) : home_url('/about-us/'); ?>">About Us</a></li>
			<li><a href="<?php echo $treatment ? get_permalink($treatment->ID) : home_url('/find-treatment/'); ?>">Find Substance Abuse Treatment</a></li>
			<li><a href="<?php echo $theman ? get_permalink($theman->ID) : home_url('/the-man-in-the-arena/'); ?>">Words We Live By</a></li>
        </ul>
    </div>
</nav>
