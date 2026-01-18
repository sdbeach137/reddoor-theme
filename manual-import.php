<?php
/**
 * Manual Import Test
 * Upload to: /wp-content/themes/reddoor-theme/manual-import.php
 * Visit: http://your-site.com/wp-content/themes/reddoor-theme/manual-import.php
 * 
 * This will actually import the providers and show detailed results
 */

require_once('../../../../wp-load.php');

// Check if we have permission
if (!current_user_can('manage_options')) {
    die('You must be logged in as an administrator to run this test.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manual Import Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; margin: 10px 0; border-radius: 5px; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 10px; margin-top: 30px; }
        .progress { background: #f0f0f0; height: 30px; border-radius: 5px; overflow: hidden; margin: 20px 0; }
        .progress-bar { background: #0073aa; height: 100%; line-height: 30px; color: white; text-align: center; transition: width 0.3s; }
    </style>
</head>
<body>
    <h1>🚀 Manual Provider Import Test</h1>
    <p><strong>Started:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

    <?php
    $csv_file = __DIR__ . '/providers.csv';
    
    // Pre-flight checks
    echo "<h2>Pre-Flight Checks</h2>";
    
    if (!file_exists($csv_file)) {
        echo "<div class='error'><strong>❌ FATAL ERROR:</strong> CSV file not found at: $csv_file</div>";
        echo "<p>Upload providers.csv to the theme directory before running this import.</p>";
        die();
    }
    
    echo "<div class='success'>✅ CSV file found</div>";
    echo "<p><strong>Location:</strong> $csv_file</p>";
    echo "<p><strong>Size:</strong> " . number_format(filesize($csv_file)) . " bytes</p>";
    
    if (!function_exists('reddoor_import_providers_from_csv')) {
        echo "<div class='error'><strong>❌ FATAL ERROR:</strong> Import function not found</div>";
        echo "<p>Upload the updated functions.php file before running this import.</p>";
        die();
    }
    
    echo "<div class='success'>✅ Import function exists</div>";
    
    if (!post_type_exists('rdr_provider')) {
        echo "<div class='error'><strong>❌ FATAL ERROR:</strong> Provider post type not registered</div>";
        echo "<p>Check that functions.php was uploaded correctly.</p>";
        die();
    }
    
    echo "<div class='success'>✅ Provider post type registered</div>";
    
    // Count existing providers
    $existing_count = wp_count_posts('rdr_provider');
    $total_existing = $existing_count->publish + $existing_count->draft + $existing_count->pending;
    
    if ($total_existing > 0) {
        echo "<div class='warning'>⚠️ Found $total_existing existing providers. Import will update duplicates.</div>";
    }
    
    echo "<hr>";
    
    // Run the import
    echo "<h2>Running Import...</h2>";
    echo "<div class='info'>⏳ This may take 10-30 seconds. Please wait...</div>";
    flush();
    
    $start_time = microtime(true);
    
    $imported = reddoor_import_providers_from_csv($csv_file);
    
    $end_time = microtime(true);
    $duration = round($end_time - $start_time, 2);
    
    echo "<div class='progress'><div class='progress-bar' style='width: 100%;'>Complete!</div></div>";
    
    // Get detailed stats
    $stats = get_transient('rdr_last_import_stats');
    
    echo "<h2>Import Results</h2>";
    
    if ($imported > 0) {
        echo "<div class='success'>";
        echo "<h3>✅ Import Successful!</h3>";
        echo "<p><strong>Imported:</strong> $imported providers</p>";
        echo "<p><strong>Duration:</strong> $duration seconds</p>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<h3>❌ Import Failed</h3>";
        echo "<p><strong>Imported:</strong> 0 providers</p>";
        echo "<p>No providers were created. Check errors below.</p>";
        echo "</div>";
    }
    
    if ($stats) {
        if ($stats['skipped'] > 0) {
            echo "<div class='warning'>";
            echo "<p><strong>⚠️ Skipped:</strong> " . $stats['skipped'] . " rows (missing provider_name or empty rows)</p>";
            echo "</div>";
        }
        
        if (!empty($stats['errors'])) {
            echo "<div class='error'>";
            echo "<p><strong>❌ Errors encountered:</strong></p>";
            echo "<ul>";
            foreach (array_slice($stats['errors'], 0, 20) as $error) {
                echo "<li>" . esc_html($error) . "</li>";
            }
            if (count($stats['errors']) > 20) {
                echo "<li><em>... and " . (count($stats['errors']) - 20) . " more errors</em></li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    }
    
    // Show sample of imported providers
    echo "<h2>Sample Imported Providers</h2>";
    
    $sample_providers = get_posts(array(
        'post_type' => 'rdr_provider',
        'posts_per_page' => 10,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if (!empty($sample_providers)) {
        echo "<div class='info'>";
        echo "<p><strong>First 10 imported providers:</strong></p>";
        echo "<ol>";
        foreach ($sample_providers as $provider) {
            $county = wp_get_post_terms($provider->ID, 'rdr_county');
            $county_name = !empty($county) ? $county[0]->name : 'N/A';
            $phone = get_post_meta($provider->ID, '_phone', true);
            
            echo "<li>";
            echo "<strong>" . esc_html($provider->post_title) . "</strong><br>";
            echo "County: " . esc_html($county_name) . " | ";
            echo "Phone: " . esc_html($phone ? $phone : 'N/A') . "<br>";
            echo "<small>ID: " . $provider->ID . " | Date: " . $provider->post_date . "</small>";
            echo "</li>";
        }
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<div class='error'><p>No providers found in database after import.</p></div>";
    }
    
    // Taxonomy counts
    echo "<h2>Taxonomy Statistics</h2>";
    
    $counties = get_terms(array('taxonomy' => 'rdr_county', 'hide_empty' => false));
    $services = get_terms(array('taxonomy' => 'rdr_services', 'hide_empty' => false));
    $levels = get_terms(array('taxonomy' => 'rdr_level_of_care', 'hide_empty' => false));
    
    echo "<div class='info'>";
    echo "<p><strong>Counties:</strong> " . count($counties) . " unique counties</p>";
    echo "<p><strong>Services:</strong> " . count($services) . " unique services</p>";
    echo "<p><strong>Levels of Care:</strong> " . count($levels) . " unique levels</p>";
    echo "</div>";
    
    if (count($counties) > 0) {
        echo "<p><strong>Sample counties:</strong> ";
        echo implode(', ', array_slice(wp_list_pluck($counties, 'name'), 0, 10));
        if (count($counties) > 10) echo ", ...";
        echo "</p>";
    }
    
    // Final provider count
    $final_count = wp_count_posts('rdr_provider');
    $total_final = $final_count->publish + $final_count->draft + $final_count->pending;
    
    echo "<h2>Final Database Status</h2>";
    echo "<div class='success'>";
    echo "<p><strong>Total Providers in Database:</strong> $total_final</p>";
    echo "<p><strong>Published:</strong> {$final_count->publish}</p>";
    echo "<p><strong>Draft:</strong> {$final_count->draft}</p>";
    echo "<p><strong>Pending:</strong> {$final_count->pending}</p>";
    echo "</div>";
    
    ?>

    <hr>
    <h2>Next Steps</h2>
    <div class='info'>
        <p><a href="/wp-admin/edit.php?post_type=rdr_provider" style="background: #0073aa; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">📊 View All Providers in Admin</a></p>
        <p><a href="/wp-admin/edit-tags.php?taxonomy=rdr_county&post_type=rdr_provider" style="background: #0073aa; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🗺️ View Counties</a></p>
        <p><a href="/" style="background: #0073aa; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🏠 View Homepage</a></p>
    </div>

    <p><small><strong>Test completed:</strong> <?php echo date('Y-m-d H:i:s'); ?></small></p>

</body>
</html>
