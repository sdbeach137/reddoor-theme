<?php
/**
 * COMPREHENSIVE DATABASE DIAGNOSTIC
 * Upload to: /wp-content/themes/reddoor-theme/check-database.php  
 * Visit: http://64.225.63.218/wp-content/themes/reddoor-theme/check-database.php
 */

require_once('../../../../wp-load.php');

if (!current_user_can('manage_options')) {
    wp_die('You must be logged in as administrator.');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #0073aa; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 14px; }
        th { background: #0073aa; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .section { margin: 30px 0; padding: 20px; background: #f8f9fa; border-left: 4px solid #0073aa; }
        .missing { background: #ffdddd; }
        .present { background: #ddffdd; }
        pre { background: #f5f5f5; padding: 15px; overflow-x: auto; border-radius: 4px; }
        .btn { display: inline-block; background: #0073aa; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn:hover { background: #005a87; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Complete Database Diagnostic</h1>
    <p><strong>Scan Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

    <!-- SECTION 1: Provider Count -->
    <div class="section">
        <h2>1. Total Providers in Database</h2>
        <?php
        $counts = wp_count_posts('rdr_provider');
        $total = $counts->publish + $counts->draft;
        
        echo "<table>";
        echo "<tr><th>Status</th><th>Count</th></tr>";
        echo "<tr><td>Published</td><td><strong>{$counts->publish}</strong></td></tr>";
        echo "<tr><td>Draft</td><td>{$counts->draft}</td></tr>";
        echo "<tr><td>Trash</td><td>{$counts->trash}</td></tr>";
        echo "<tr><td><strong>TOTAL</strong></td><td><strong>$total</strong></td></tr>";
        echo "</table>";
        
        if ($total == 0) {
            echo "<p class='error'>❌ CRITICAL: No providers in database! Import has not run.</p>";
        } elseif ($total < 300) {
            echo "<p class='warning'>⚠️ WARNING: Only $total providers (expected ~365)</p>";
        } else {
            echo "<p class='success'>✅ Good provider count</p>";
        }
        ?>
    </div>

    <!-- SECTION 2: Sample Data -->
    <div class="section">
        <h2>2. Sample Provider Data (First 10)</h2>
        <?php
        $providers = get_posts(array(
            'post_type' => 'rdr_provider',
            'posts_per_page' => 10,
            'orderby' => 'ID',
            'order' => 'ASC'
        ));

        if (empty($providers)) {
            echo "<p class='error'>❌ No providers to display</p>";
        } else {
            echo "<table>";
            echo "<tr>";
            echo "<th>Provider Name</th>";
            echo "<th>Phone</th>";
            echo "<th>Street Address</th>";
            echo "<th>City</th>";
            echo "<th>State</th>";
            echo "<th>ZIP</th>";
            echo "<th>County</th>";
            echo "<th>Website</th>";
            echo "</tr>";
            
            $stats = array(
                'phone' => 0,
                'street' => 0,
                'city' => 0,
                'state' => 0,
                'zip' => 0,
                'county' => 0,
                'website' => 0
            );
            
            foreach ($providers as $provider) {
                $phone = get_post_meta($provider->ID, '_phone', true);
                $street = get_post_meta($provider->ID, '_street_1', true);
                $city = get_post_meta($provider->ID, '_city', true);
                $state = get_post_meta($provider->ID, '_state', true);
                $zip = get_post_meta($provider->ID, '_zip', true);
                $website = get_post_meta($provider->ID, '_website', true);
                $counties = wp_get_post_terms($provider->ID, 'rdr_county');
                $county = !empty($counties) ? $counties[0]->name : '';
                
                if ($phone) $stats['phone']++;
                if ($street) $stats['street']++;
                if ($city) $stats['city']++;
                if ($state) $stats['state']++;
                if ($zip) $stats['zip']++;
                if ($county) $stats['county']++;
                if ($website) $stats['website']++;
                
                echo "<tr>";
                echo "<td><strong>" . esc_html($provider->post_title) . "</strong></td>";
                echo "<td class='" . ($phone ? "present" : "missing") . "'>" . ($phone ? esc_html($phone) : "EMPTY") . "</td>";
                echo "<td class='" . ($street ? "present" : "missing") . "'>" . ($street ? esc_html($street) : "EMPTY") . "</td>";
                echo "<td class='" . ($city ? "present" : "missing") . "'>" . ($city ? esc_html($city) : "EMPTY") . "</td>";
                echo "<td class='" . ($state ? "present" : "missing") . "'>" . ($state ? esc_html($state) : "EMPTY") . "</td>";
                echo "<td class='" . ($zip ? "present" : "missing") . "'>" . ($zip ? esc_html($zip) : "EMPTY") . "</td>";
                echo "<td class='" . ($county ? "present" : "missing") . "'>" . ($county ? esc_html($county) : "EMPTY") . "</td>";
                echo "<td class='" . ($website ? "present" : "missing") . "'>" . ($website ? '<a href="' . esc_url($website) . '" target="_blank">Link</a>' : "EMPTY") . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<p><strong>Data Completeness (out of 10 samples):</strong></p>";
            echo "<ul>";
            foreach ($stats as $field => $count) {
                $percent = ($count / 10) * 100;
                $class = $percent >= 50 ? 'success' : 'error';
                echo "<li class='$class'>" . ucfirst($field) . ": $count/10 ({$percent}%)</li>";
            }
            echo "</ul>";
        }
        ?>
    </div>

    <!-- SECTION 3: All Meta Fields for One Provider -->
    <div class="section">
        <h2>3. Complete Meta Field Check (Provider #1)</h2>
        <?php
        if (!empty($providers)) {
            $provider = $providers[0];
            echo "<p><strong>Provider:</strong> " . esc_html($provider->post_title) . " (ID: {$provider->ID})</p>";
            
            $all_meta = get_post_meta($provider->ID);
            
            echo "<p><strong>Total Meta Fields:</strong> " . count($all_meta) . "</p>";
            
            echo "<table>";
            echo "<tr><th>Meta Key</th><th>Value</th></tr>";
            
            foreach ($all_meta as $key => $values) {
                if (strpos($key, '_') === 0) { // Only our custom fields
                    $value = $values[0];
                    echo "<tr>";
                    echo "<td><code>" . esc_html($key) . "</code></td>";
                    echo "<td>" . (empty($value) ? '<span class="error">EMPTY</span>' : esc_html(substr($value, 0, 100))) . "</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
        ?>
    </div>

    <!-- SECTION 4: Taxonomies -->
    <div class="section">
        <h2>4. Taxonomy Counts</h2>
        <?php
        $taxonomies = array(
            'rdr_county' => 'Counties',
            'rdr_city' => 'Cities',
            'rdr_services' => 'Services',
            'rdr_level_of_care' => 'Levels of Care'
        );
        
        echo "<table>";
        echo "<tr><th>Taxonomy</th><th>Total Terms</th><th>With Providers</th><th>Sample Terms</th></tr>";
        
        foreach ($taxonomies as $tax => $label) {
            $all_terms = get_terms(array('taxonomy' => $tax, 'hide_empty' => false));
            $used_terms = get_terms(array('taxonomy' => $tax, 'hide_empty' => true));
            
            echo "<tr>";
            echo "<td><strong>$label</strong></td>";
            echo "<td>" . count($all_terms) . "</td>";
            echo "<td>" . count($used_terms) . "</td>";
            echo "<td>";
            if (!empty($used_terms)) {
                $sample = array_slice($used_terms, 0, 5);
                echo implode(', ', wp_list_pluck($sample, 'name'));
                if (count($used_terms) > 5) {
                    echo "...";
                }
            } else {
                echo "<span class='error'>None!</span>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>

    <!-- SECTION 5: States Distribution -->
    <div class="section">
        <h2>5. Provider Distribution by State</h2>
        <?php
        global $wpdb;
        $state_counts = $wpdb->get_results("
            SELECT meta_value as state, COUNT(*) as count 
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = '_state' 
            AND p.post_type = 'rdr_provider' 
            AND p.post_status = 'publish'
            GROUP BY meta_value
            ORDER BY count DESC
        ");
        
        if (!empty($state_counts)) {
            echo "<table>";
            echo "<tr><th>State</th><th>Providers</th><th>%</th></tr>";
            $total_with_state = array_sum(wp_list_pluck($state_counts, 'count'));
            
            foreach ($state_counts as $row) {
                $percent = round(($row->count / $total_with_state) * 100, 1);
                $state_name = $row->state ? $row->state : '<span class="error">BLANK</span>';
                echo "<tr><td><strong>$state_name</strong></td><td>{$row->count}</td><td>{$percent}%</td></tr>";
            }
            echo "</table>";
            
            if ($total_with_state < $total * 0.9) {
                echo "<p class='warning'>⚠️ " . ($total - $total_with_state) . " providers have no state assigned</p>";
            }
        } else {
            echo "<p class='error'>❌ No state data found in database</p>";
        }
        ?>
    </div>

    <!-- SECTION 6: CSV File Check -->
    <div class="section">
        <h2>6. CSV Source File Analysis</h2>
        <?php
        $csv_path = __DIR__ . '/providers.csv';
        
        if (file_exists($csv_path)) {
            echo "<p class='success'>✅ CSV file found</p>";
            echo "<p><strong>File Path:</strong> <code>$csv_path</code></p>";
            echo "<p><strong>File Size:</strong> " . number_format(filesize($csv_path)) . " bytes</p>";
            echo "<p><strong>Last Modified:</strong> " . date('Y-m-d H:i:s', filemtime($csv_path)) . "</p>";
            
            $handle = fopen($csv_path, 'r');
            
            // Check for blank line
            $line1 = fgetcsv($handle);
            if (empty($line1[0])) {
                echo "<p class='success'>✅ Blank first line detected (will be skipped)</p>";
                $header = fgetcsv($handle);
            } else {
                $header = $line1;
                echo "<p class='warning'>⚠️ No blank first line (first row is header)</p>";
            }
            
            echo "<p><strong>Column Count:</strong> " . count($header) . "</p>";
            
            // Read first actual data row
            $first_data = fgetcsv($handle);
            
            // Count total rows
            $row_count = 2; // Already read 2
            while (fgetcsv($handle) !== false) {
                $row_count++;
            }
            fclose($handle);
            
            echo "<p><strong>Total Rows (including header):</strong> $row_count</p>";
            echo "<p><strong>Expected Providers:</strong> " . ($row_count - 2) . "</p>";
            
            // Show header columns
            echo "<h3>CSV Columns:</h3>";
            echo "<ol>";
            foreach ($header as $col) {
                echo "<li><code>" . esc_html($col) . "</code></li>";
            }
            echo "</ol>";
            
            // Show first row data for key fields
            echo "<h3>First Provider Data Sample:</h3>";
            echo "<table>";
            echo "<tr><th>Field</th><th>Value from CSV</th></tr>";
            
            $key_fields = array(2 => 'provider_name', 6 => 'street_1', 8 => 'city', 9 => 'state', 10 => 'zip', 11 => 'county', 12 => 'phone', 14 => 'email', 15 => 'website');
            
            foreach ($key_fields as $index => $field_name) {
                $value = isset($first_data[$index]) ? $first_data[$index] : '';
                $class = empty($value) ? 'missing' : 'present';
                echo "<tr class='$class'>";
                echo "<td><strong>" . esc_html($field_name) . "</strong></td>";
                echo "<td>" . ($value ? esc_html($value) : '<span class="error">EMPTY IN CSV</span>') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
        } else {
            echo "<p class='error'>❌ CSV file NOT FOUND at: <code>$csv_path</code></p>";
            echo "<p>Expected location: <code>/wp-content/themes/reddoor-theme/providers.csv</code></p>";
        }
        ?>
    </div>

    <!-- SECTION 7: Diagnosis & Recommendations -->
    <div class="section">
        <h2>7. Diagnosis & Recommendations</h2>
        <?php
        $issues = array();
        $warnings = array();
        
        // Check for critical issues
        if ($total == 0) {
            $issues[] = "NO PROVIDERS IN DATABASE - Import has never run or completely failed";
        }
        
        if (!empty($providers)) {
            $sample = $providers[0];
            $has_phone = !empty(get_post_meta($sample->ID, '_phone', true));
            $has_address = !empty(get_post_meta($sample->ID, '_street_1', true));
            $has_city = !empty(get_post_meta($sample->ID, '_city', true));
            
            if (!$has_phone) {
                $issues[] = "Phone numbers NOT importing from CSV";
            }
            if (!$has_address) {
                $issues[] = "Street addresses NOT importing from CSV";
            }
            if (!$has_city) {
                $issues[] = "Cities NOT importing from CSV";
            }
        }
        
        $counties = get_terms(array('taxonomy' => 'rdr_county', 'hide_empty' => true));
        $cities = get_terms(array('taxonomy' => 'rdr_city', 'hide_empty' => true));
        
        if (count($counties) == 0) {
            $issues[] = "NO COUNTIES - Taxonomy assignment not working";
        }
        if (count($cities) == 0) {
            $issues[] = "NO CITIES - Taxonomy assignment not working";
        }
        
        if ($total > 0 && $total < 300) {
            $warnings[] = "Only $total providers imported (expected ~365)";
        }
        
        // Display results
        if (empty($issues) && empty($warnings)) {
            echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
            echo "<h3 class='success'>✅ ALL CHECKS PASSED!</h3>";
            echo "<p>Database appears to be properly populated with provider data.</p>";
            echo "</div>";
        } else {
            if (!empty($issues)) {
                echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px; border: 1px solid #f5c6cb; margin-bottom: 20px;'>";
                echo "<h3 class='error'>❌ CRITICAL ISSUES FOUND:</h3>";
                echo "<ul>";
                foreach ($issues as $issue) {
                    echo "<li class='error'>" . esc_html($issue) . "</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
            
            if (!empty($warnings)) {
                echo "<div style='background: #fff3cd; padding: 20px; border-radius: 5px; border: 1px solid #ffeaa7;'>";
                echo "<h3 class='warning'>⚠️ WARNINGS:</h3>";
                echo "<ul>";
                foreach ($warnings as $warning) {
                    echo "<li class='warning'>" . esc_html($warning) . "</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
        }
        ?>
        
        <h3>Recommended Actions:</h3>
        <ol style="font-size: 16px; line-height: 1.8;">
            <?php if ($total == 0): ?>
                <li><strong>RUN IMPORT NOW</strong> - Go to Providers → Upload Providers and click import button</li>
            <?php elseif (!empty($issues)): ?>
                <li><strong>DELETE ALL PROVIDERS</strong> - Go to Providers → All Providers, select all, move to trash</li>
                <li><strong>UPLOAD LATEST functions.php</strong> - Make sure you have the updated import function</li>
                <li><strong>RE-IMPORT</strong> - Go to Providers → Upload Providers and import fresh</li>
            <?php else: ?>
                <li><strong>VERIFY PAGES WORK</strong> - Visit /providers/ and test county/city links</li>
                <li><strong>TEST SEARCH</strong> - Try searching for providers by name or city</li>
            <?php endif; ?>
            <li><strong>RE-RUN THIS DIAGNOSTIC</strong> - Come back here after changes to verify fix</li>
        </ol>
    </div>

    <!-- Action Buttons -->
    <div style="text-align: center; margin: 40px 0;">
        <a href="/wp-admin/edit.php?post_type=rdr_provider" class="btn">View All Providers</a>
        <a href="/wp-admin/edit.php?post_type=rdr_provider&page=reddoor-upload-providers" class="btn">Go to Import Page</a>
        <a href="<?php echo home_url('/providers/'); ?>" class="btn">View Providers Page</a>
        <a href="javascript:location.reload()" class="btn">Refresh Diagnostic</a>
    </div>

</div>
</body>
</html>
