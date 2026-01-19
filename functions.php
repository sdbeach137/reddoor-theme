<?php
/**
 * Red Door Recovery Network - Theme Functions
 * Updated with Provider Directory functionality
 */

if (!defined('ABSPATH')) exit;

// Theme Setup
function reddoor_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    register_nav_menus(array(
        'primary' => __('Primary Navigation', 'reddoor'),
        'footer' => __('Footer Navigation', 'reddoor')
    ));
}
add_action('after_setup_theme', 'reddoor_setup');

// Enqueue Scripts
function reddoor_scripts() {
    wp_enqueue_style('reddoor-style', get_stylesheet_uri(), array(), '1.0.1');
    wp_enqueue_script('reddoor-readings', get_template_directory_uri() . '/assets/js/daily-readings.js', array('jquery'), '1.0.0', true);
    
    wp_localize_script('reddoor-readings', 'reddoorAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('reddoor-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'reddoor_scripts');

// ============================================================================
// DAILY READINGS CPT
// ============================================================================

function reddoor_register_reading_cpt() {
    register_post_type('rdr_reading', array(
        'labels' => array(
            'name' => 'Daily Readings',
            'singular_name' => 'Daily Reading',
            'add_new' => 'Add Reading',
            'edit_item' => 'Edit Reading'
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'through-the-big-book'),
        'supports' => array('title', 'editor', 'custom-fields'),
        'menu_icon' => 'dashicons-book',
        'show_in_rest' => true
    ));
}
add_action('init', 'reddoor_register_reading_cpt');

// Add meta boxes for readings
function reddoor_add_reading_meta() {
    add_meta_box('reading_details', 'Reading Details', 'reddoor_reading_meta_callback', 'rdr_reading', 'normal', 'high');
}
add_action('add_meta_boxes', 'reddoor_add_reading_meta');

function reddoor_reading_meta_callback($post) {
    wp_nonce_field('reddoor_reading_meta', 'reddoor_reading_nonce');
    
    $day = get_post_meta($post->ID, '_day_number', true);
    $section = get_post_meta($post->ID, '_section', true);
    $chapter = get_post_meta($post->ID, '_chapter', true);
    $excerpt_start = get_post_meta($post->ID, '_excerpt_start', true);
    $excerpt_end = get_post_meta($post->ID, '_excerpt_end', true);
    $core_idea = get_post_meta($post->ID, '_core_idea', true);
    $modern_interpretation = get_post_meta($post->ID, '_modern_interpretation', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label>Day Number (1-90)</label></th>
            <td><input type="number" name="day_number" value="<?php echo esc_attr($day); ?>" min="1" max="90" class="small-text"></td>
        </tr>
        <tr>
            <th><label>Section</label></th>
            <td><input type="text" name="section" value="<?php echo esc_attr($section); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Chapter</label></th>
            <td><input type="text" name="chapter" value="<?php echo esc_attr($chapter); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Core Idea</label></th>
            <td><textarea name="core_idea" rows="2" class="large-text"><?php echo esc_textarea($core_idea); ?></textarea></td>
        </tr>
        <tr>
            <th><label>Modern Clinical Interpretation</label></th>
            <td><textarea name="modern_interpretation" rows="8" class="large-text"><?php echo esc_textarea($modern_interpretation); ?></textarea></td>
        </tr>
    </table>
    <?php
}

// Save reading meta
function reddoor_save_reading_meta($post_id) {
    if (!isset($_POST['reddoor_reading_nonce']) || !wp_verify_nonce($_POST['reddoor_reading_nonce'], 'reddoor_reading_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $fields = array('day_number', 'section', 'chapter', 'core_idea', 'modern_interpretation');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_textarea_field($_POST[$field]));
        }
    }
}
add_action('save_post_rdr_reading', 'reddoor_save_reading_meta');

// ============================================================================
// PROVIDER CPT
// ============================================================================

function reddoor_register_provider_cpt() {
    register_post_type('rdr_provider', array(
        'labels' => array(
            'name' => 'Providers',
            'singular_name' => 'Provider',
            'add_new' => 'Add Provider',
            'edit_item' => 'Edit Provider',
            'view_item' => 'View Provider',
            'search_items' => 'Search Providers'
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'providers'),
        'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
        'menu_icon' => 'dashicons-location-alt',
        'show_in_rest' => true,
        'menu_position' => 5
    ));
}
add_action('init', 'reddoor_register_provider_cpt');

// Provider Taxonomies
function reddoor_register_provider_taxonomies() {
    // County
    register_taxonomy('rdr_county', 'rdr_provider', array(
        'labels' => array('name' => 'Counties', 'singular_name' => 'County'),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'county'),
        'show_in_rest' => true
    ));
    
    // City
    register_taxonomy('rdr_city', 'rdr_provider', array(
        'labels' => array('name' => 'Cities', 'singular_name' => 'City'),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'city'),
        'show_in_rest' => true
    ));
    
    // Level of Care
    register_taxonomy('rdr_level_of_care', 'rdr_provider', array(
        'labels' => array('name' => 'Levels of Care', 'singular_name' => 'Level of Care'),
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true
    ));
    
    // Services
    register_taxonomy('rdr_services', 'rdr_provider', array(
        'labels' => array('name' => 'Services', 'singular_name' => 'Service'),
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_rest' => true
    ));
}
add_action('init', 'reddoor_register_provider_taxonomies');

// Provider Meta Box
function reddoor_add_provider_meta() {
    add_meta_box('provider_details', 'Provider Details', 'reddoor_provider_meta_callback', 'rdr_provider', 'normal', 'high');
}
add_action('add_meta_boxes', 'reddoor_add_provider_meta');

function reddoor_provider_meta_callback($post) {
    wp_nonce_field('reddoor_provider_meta', 'reddoor_provider_nonce');
    
    $phone = get_post_meta($post->ID, '_phone', true);
    $email = get_post_meta($post->ID, '_email', true);
    $website = get_post_meta($post->ID, '_website', true);
    $street_1 = get_post_meta($post->ID, '_street_1', true);
    $street_2 = get_post_meta($post->ID, '_street_2', true);
    $city = get_post_meta($post->ID, '_city', true);
    $state = get_post_meta($post->ID, '_state', true);
    $zip = get_post_meta($post->ID, '_zip', true);
    $verified = get_post_meta($post->ID, '_verified_status', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label>Phone</label></th>
            <td><input type="text" name="phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Email</label></th>
            <td><input type="email" name="email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Website</label></th>
            <td><input type="url" name="website" value="<?php echo esc_attr($website); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Street Address</label></th>
            <td><input type="text" name="street_1" value="<?php echo esc_attr($street_1); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Address Line 2</label></th>
            <td><input type="text" name="street_2" value="<?php echo esc_attr($street_2); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>City</label></th>
            <td><input type="text" name="city" value="<?php echo esc_attr($city); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>State</label></th>
            <td><input type="text" name="state" value="<?php echo esc_attr($state); ?>" maxlength="2" class="small-text"></td>
        </tr>
        <tr>
            <th><label>ZIP Code</label></th>
            <td><input type="text" name="zip" value="<?php echo esc_attr($zip); ?>" class="small-text"></td>
        </tr>
        <tr>
            <th><label>Verified Status</label></th>
            <td>
                <select name="verified_status">
                    <option value="unverified" <?php selected($verified, 'unverified'); ?>>Unverified</option>
                    <option value="pending" <?php selected($verified, 'pending'); ?>>Pending</option>
                    <option value="likely_active" <?php selected($verified, 'likely_active'); ?>>Likely Active</option>
                    <option value="verified" <?php selected($verified, 'verified'); ?>>Verified</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

// Save provider meta
function reddoor_save_provider_meta($post_id) {
    if (!isset($_POST['reddoor_provider_nonce']) || !wp_verify_nonce($_POST['reddoor_provider_nonce'], 'reddoor_provider_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $fields = array('phone', 'email', 'website', 'street_1', 'street_2', 'city', 'state', 'zip', 'verified_status');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_rdr_provider', 'reddoor_save_provider_meta');

// ============================================================================
// CSV IMPORTERS - Admin Menu
// ============================================================================

function reddoor_importer_menu() {
    // Add to Tools menu
    add_management_page(
        'Import Data',
        'Import Data',
        'manage_options',
        'reddoor-import-data',
        'reddoor_importer_page'
    );
    
    // Add submenu under Providers for easy access
    add_submenu_page(
        'edit.php?post_type=rdr_provider',
        'Upload Providers',
        'Upload Providers',
        'manage_options',
        'reddoor-upload-providers',
        'reddoor_provider_upload_page'
    );
}
add_action('admin_menu', 'reddoor_importer_menu');

// Importer Page
function reddoor_importer_page() {
    ?>
    <div class="wrap">
        <h1>Import Red Door Data</h1>
        
        <!-- READINGS IMPORTER -->
        <div class="card" style="max-width: 800px; margin-bottom: 30px;">
            <h2>📖 Import Daily Readings (90 Days)</h2>
            
            <?php
            if (isset($_POST['import_readings']) && check_admin_referer('reddoor_import_readings')) {
                $csv_file = get_template_directory() . '/daily-readings.csv';
                
                if (!file_exists($csv_file)) {
                    echo '<div class="notice notice-error"><p>CSV file not found! Ensure daily-readings.csv is in theme root.</p></div>';
                } else {
                    $imported = reddoor_import_readings_from_csv($csv_file);
                    echo '<div class="notice notice-success"><p>✅ Successfully imported ' . $imported . ' daily readings!</p></div>';
                }
            }
            ?>
            
            <p>Imports all 90 daily readings from <code>/themes/reddoor-theme/daily-readings.csv</code></p>
            
            <form method="post" style="margin-top: 15px;">
                <?php wp_nonce_field('reddoor_import_readings'); ?>
                <input type="submit" name="import_readings" class="button button-primary" value="Import 90 Daily Readings">
            </form>
        </div>
        
        <!-- PROVIDERS IMPORTER -->
        <div class="card" style="max-width: 800px;">
            <h2>🏥 Import Providers (Treatment Agencies)</h2>
            
            <?php
            if (isset($_POST['import_providers']) && check_admin_referer('reddoor_import_providers')) {
                $csv_file = get_template_directory() . '/providers.csv';
                
                if (!file_exists($csv_file)) {
                    echo '<div class="notice notice-error"><p>CSV file not found! Ensure providers.csv is in theme root.</p></div>';
                } else {
                    $imported = reddoor_import_providers_from_csv($csv_file);
                    $stats = get_transient('rdr_last_import_stats');
                    
                    if ($imported > 0) {
                        echo '<div class="notice notice-success"><p>✅ Successfully imported ' . $imported . ' providers!</p></div>';
                        
                        if ($stats && $stats['skipped'] > 0) {
                            echo '<div class="notice notice-warning"><p>⚠️ Skipped ' . $stats['skipped'] . ' rows</p></div>';
                        }
                    } else {
                        echo '<div class="notice notice-error"><p>❌ No providers imported. Check CSV format.</p></div>';
                    }
                }
            }
            ?>
            
            <p>Imports treatment providers from <code>/themes/reddoor-theme/providers.csv</code></p>
            <p><strong>CSV Columns:</strong> provider_name, street_1, street_2, city, state, zip, county, phone, email, website, services_raw, level_of_care_raw, verified_status</p>
            
            <form method="post" style="margin-top: 15px;">
                <?php wp_nonce_field('reddoor_import_providers'); ?>
                <input type="submit" name="import_providers" class="button button-primary" value="Import Providers" onclick="return confirm('This will import all providers from the CSV. Continue?');">
            </form>
            
            <hr style="margin: 20px 0;">
            
            <h3>What This Does:</h3>
            <ul style="line-height: 1.8;">
                <li>Creates provider posts with all contact information</li>
                <li>Assigns counties as taxonomy terms</li>
                <li>Extracts levels of care and services</li>
                <li>Sets verification status</li>
                <li>Updates existing providers if they already exist (by name)</li>
            </ul>
        </div>
    </div>
    <?php
}

// Dedicated Provider Upload Page (under Providers menu)
function reddoor_provider_upload_page() {
    ?>
    <div class="wrap">
        <h1>📤 Upload Providers from CSV</h1>
        
        <?php
        if (isset($_POST['import_providers']) && check_admin_referer('reddoor_import_providers')) {
            $csv_file = get_template_directory() . '/providers.csv';
            
            if (!file_exists($csv_file)) {
                echo '<div class="notice notice-error"><p>❌ CSV file not found! Ensure providers.csv is in theme root: <code>/wp-content/themes/reddoor-theme/providers.csv</code></p></div>';
            } else {
                $imported = reddoor_import_providers_from_csv($csv_file);
                $stats = get_transient('rdr_last_import_stats');
                
                if ($imported > 0) {
                    echo '<div class="notice notice-success"><p>✅ <strong>Successfully imported ' . $imported . ' providers!</strong></p></div>';
                    
                    if ($stats) {
                        if ($stats['skipped'] > 0) {
                            echo '<div class="notice notice-warning"><p>⚠️ Skipped ' . $stats['skipped'] . ' rows (missing provider name or empty)</p></div>';
                        }
                        if (!empty($stats['errors'])) {
                            echo '<div class="notice notice-error"><p>❌ Errors: ' . implode('<br>', array_slice($stats['errors'], 0, 5)) . '</p></div>';
                        }
                    }
                    
                    echo '<div class="notice notice-info"><p>📊 Go to <a href="' . admin_url('edit.php?post_type=rdr_provider') . '"><strong>Providers</strong></a> to view all imported providers.</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>❌ No providers imported. Check CSV format and content.</p></div>';
                }
            }
        }
        ?>
        
        <div class="card" style="max-width: 800px; padding: 20px;">
            <h2>🏥 Import Treatment Providers</h2>
            
            <p style="font-size: 16px; line-height: 1.6; margin: 15px 0;">
                This will import all providers from <code>/wp-content/themes/reddoor-theme/providers.csv</code>
            </p>
            
            <div style="background: #f0f6fc; border-left: 4px solid #0969da; padding: 15px; margin: 20px 0;">
                <strong>📋 CSV Requirements:</strong>
                <ul style="margin: 10px 0 0 20px; line-height: 1.8;">
                    <li>File must be named: <code>providers.csv</code></li>
                    <li>Located in theme directory: <code>/wp-content/themes/reddoor-theme/</code></li>
                    <li>Contains columns: provider_name, street_1, city, state, county, phone, etc.</li>
                </ul>
            </div>
            
            <form method="post" style="margin-top: 25px;">
                <?php wp_nonce_field('reddoor_import_providers'); ?>
                <p>
                    <input type="submit" 
                           name="import_providers" 
                           class="button button-primary button-hero" 
                           value="🚀 Import All Providers from CSV"
                           onclick="return confirm('This will import all providers from providers.csv.\n\nExisting providers with the same name will be updated.\n\nContinue?');">
                </p>
            </form>
            
            <hr style="margin: 30px 0;">
            
            <h3>What This Does:</h3>
            <ul style="line-height: 1.8; margin-left: 20px;">
                <li>✅ Reads <code>providers.csv</code> from theme directory</li>
                <li>✅ Creates provider posts with all contact information</li>
                <li>✅ Assigns counties as taxonomy terms</li>
                <li>✅ Extracts levels of care and services</li>
                <li>✅ Sets verification status</li>
                <li>✅ Updates existing providers (by name match)</li>
            </ul>
            
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
                <strong>⚠️ Important:</strong> Make sure <code>providers.csv</code> is uploaded to the theme directory before clicking import.
            </div>
        </div>
    </div>
    <?php
}

// ============================================================================
// READINGS IMPORT FUNCTION
// ============================================================================

function reddoor_import_readings_from_csv($csv_file) {
    $imported = 0;
    
    if (($handle = fopen($csv_file, 'r')) !== FALSE) {
        $header = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            $day = $data[0];
            $section = $data[1];
            $chapter = $data[2];
            $excerpt_start = $data[3];
            $excerpt_end = $data[4];
            $core_idea = $data[5];
            $modern_interpretation = $data[6];
            
            $existing = get_posts(array(
                'post_type' => 'rdr_reading',
                'meta_query' => array(array('key' => '_day_number', 'value' => $day)),
                'posts_per_page' => 1
            ));
            
            if (!empty($existing)) {
                $post_id = $existing[0]->ID;
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => 'Day ' . $day . ' - ' . $section,
                    'post_content' => $modern_interpretation
                ));
            } else {
                $post_id = wp_insert_post(array(
                    'post_type' => 'rdr_reading',
                    'post_title' => 'Day ' . $day . ' - ' . $section,
                    'post_content' => $modern_interpretation,
                    'post_status' => 'publish'
                ));
            }
            
            if ($post_id) {
                update_post_meta($post_id, '_day_number', $day);
                update_post_meta($post_id, '_section', $section);
                update_post_meta($post_id, '_chapter', $chapter);
                update_post_meta($post_id, '_excerpt_start', $excerpt_start);
                update_post_meta($post_id, '_excerpt_end', $excerpt_end);
                update_post_meta($post_id, '_core_idea', $core_idea);
                update_post_meta($post_id, '_modern_interpretation', $modern_interpretation);
                $imported++;
            }
        }
        
        fclose($handle);
    }
    
    return $imported;
}

// ============================================================================
// PROVIDERS IMPORT FUNCTION
// ============================================================================

function reddoor_import_providers_from_csv($csv_file) {
    $imported = 0;
    $skipped = 0;
    $errors = array();
    
    if (($handle = fopen($csv_file, 'r')) !== FALSE) {
        // Skip any blank lines at the start
        $header = null;
        while (($line = fgetcsv($handle)) !== FALSE) {
            // Check if this line has content
            if (!empty($line[0])) {
                $header = $line;
                break;
            }
        }
        
        if (!$header) {
            fclose($handle);
            return 0;
        }
        
        // Create column index map
        $cols = array();
        foreach ($header as $index => $column) {
            $cols[trim($column)] = $index;
        }
        
        // Process each row
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Skip empty rows
            if (empty($data) || empty($data[0])) {
                continue;
            }
            
            // Extract provider name (required field)
            $provider_name = isset($data[$cols['provider_name']]) ? trim($data[$cols['provider_name']]) : '';
            
            if (empty($provider_name)) {
                $skipped++;
                continue;
            }
            
            // Extract all fields using column mapping
            $provider_legal_name = isset($data[$cols['provider_legal_name']]) ? trim($data[$cols['provider_legal_name']]) : '';
            $provider_type = isset($data[$cols['provider_type']]) ? trim($data[$cols['provider_type']]) : '';
            $description = isset($data[$cols['description']]) ? trim($data[$cols['description']]) : '';
            $street_1 = isset($data[$cols['street_1']]) ? trim($data[$cols['street_1']]) : '';
            $street_2 = isset($data[$cols['street_2']]) ? trim($data[$cols['street_2']]) : '';
            $city = isset($data[$cols['city']]) ? trim($data[$cols['city']]) : '';
            $state = isset($data[$cols['state']]) ? trim($data[$cols['state']]) : 'OH';
            $zip = isset($data[$cols['zip']]) ? trim($data[$cols['zip']]) : '';
            $county = isset($data[$cols['county']]) ? trim($data[$cols['county']]) : '';
            $phone = isset($data[$cols['phone']]) ? trim($data[$cols['phone']]) : '';
            $fax = isset($data[$cols['fax']]) ? trim($data[$cols['fax']]) : '';
            $email = isset($data[$cols['email']]) ? trim($data[$cols['email']]) : '';
            $website = isset($data[$cols['website']]) ? trim($data[$cols['website']]) : '';
            $intake_phone = isset($data[$cols['intake_phone']]) ? trim($data[$cols['intake_phone']]) : '';
            $hours = isset($data[$cols['hours']]) ? trim($data[$cols['hours']]) : '';
            $services_raw = isset($data[$cols['services_raw']]) ? trim($data[$cols['services_raw']]) : '';
            $payment_raw = isset($data[$cols['payment_raw']]) ? trim($data[$cols['payment_raw']]) : '';
            $telehealth = isset($data[$cols['telehealth_available']]) ? trim($data[$cols['telehealth_available']]) : '';
            $languages_raw = isset($data[$cols['languages_raw']]) ? trim($data[$cols['languages_raw']]) : '';
            $populations_raw = isset($data[$cols['populations_raw']]) ? trim($data[$cols['populations_raw']]) : '';
            $level_of_care_raw = isset($data[$cols['level_of_care_raw']]) ? trim($data[$cols['level_of_care_raw']]) : '';
            $mat_options = isset($data[$cols['mat_options_raw']]) ? trim($data[$cols['mat_options_raw']]) : '';
            $peer_support = isset($data[$cols['peer_support_available']]) ? trim($data[$cols['peer_support_available']]) : '';
            $peer_services = isset($data[$cols['peer_services_raw']]) ? trim($data[$cols['peer_services_raw']]) : '';
            $npi = isset($data[$cols['npi']]) ? trim($data[$cols['npi']]) : '';
            $license_raw = isset($data[$cols['license_raw']]) ? trim($data[$cols['license_raw']]) : '';
            $verified_status = isset($data[$cols['verified_status']]) ? trim($data[$cols['verified_status']]) : 'unverified';
            $source_board = isset($data[$cols['source_board_name']]) ? trim($data[$cols['source_board_name']]) : '';
            $source_url = isset($data[$cols['source_url']]) ? trim($data[$cols['source_url']]) : '';
            
            // Check if provider exists (by name)
            $existing = get_posts(array(
                'post_type' => 'rdr_provider',
                'title' => $provider_name,
                'posts_per_page' => 1,
                'post_status' => 'any'
            ));
            
            // Build description if empty
            if (empty($description) && !empty($services_raw)) {
                $description = 'Services: ' . $services_raw;
            }
            
            if (!empty($existing)) {
                // Update existing provider
                $post_id = $existing[0]->ID;
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_content' => $description
                ));
            } else {
                // Create new provider
                $post_id = wp_insert_post(array(
                    'post_type' => 'rdr_provider',
                    'post_title' => $provider_name,
                    'post_content' => $description,
                    'post_status' => 'publish'
                ));
            }
            
            if ($post_id && !is_wp_error($post_id)) {
                // Save all meta fields
                update_post_meta($post_id, '_provider_legal_name', $provider_legal_name);
                update_post_meta($post_id, '_provider_type', $provider_type);
                update_post_meta($post_id, '_phone', $phone);
                update_post_meta($post_id, '_fax', $fax);
                update_post_meta($post_id, '_email', $email);
                update_post_meta($post_id, '_website', $website);
                update_post_meta($post_id, '_intake_phone', $intake_phone);
                update_post_meta($post_id, '_hours', $hours);
                update_post_meta($post_id, '_street_1', $street_1);
                update_post_meta($post_id, '_street_2', $street_2);
                update_post_meta($post_id, '_city', $city);
                update_post_meta($post_id, '_state', $state);
                update_post_meta($post_id, '_zip', $zip);
                update_post_meta($post_id, '_verified_status', $verified_status);
                update_post_meta($post_id, '_payment_raw', $payment_raw);
                update_post_meta($post_id, '_telehealth_available', $telehealth);
                update_post_meta($post_id, '_languages_raw', $languages_raw);
                update_post_meta($post_id, '_populations_raw', $populations_raw);
                update_post_meta($post_id, '_mat_options', $mat_options);
                update_post_meta($post_id, '_peer_support_available', $peer_support);
                update_post_meta($post_id, '_peer_services_raw', $peer_services);
                update_post_meta($post_id, '_npi', $npi);
                update_post_meta($post_id, '_license_raw', $license_raw);
                update_post_meta($post_id, '_source_board_name', $source_board);
                update_post_meta($post_id, '_source_url', $source_url);
                
                // Assign County taxonomy
                if (!empty($county)) {
                    wp_set_object_terms($post_id, $county, 'rdr_county', false);
                }
                
                // Assign City taxonomy
                if (!empty($city)) {
                    wp_set_object_terms($post_id, $city, 'rdr_city', false);
                }
                
                // Assign Levels of Care taxonomy
                if (!empty($level_of_care_raw)) {
                    $levels = array_map('trim', explode(';', $level_of_care_raw));
                    $levels = array_filter($levels); // Remove empty values
                    if (!empty($levels)) {
                        wp_set_object_terms($post_id, $levels, 'rdr_level_of_care', false);
                    }
                }
                
                // Assign Services taxonomy
                if (!empty($services_raw)) {
                    $services = array_map('trim', explode(';', $services_raw));
                    $services = array_filter($services); // Remove empty values
                    if (!empty($services)) {
                        wp_set_object_terms($post_id, $services, 'rdr_services', false);
                    }
                }
                
                $imported++;
            } else {
                $errors[] = 'Failed to create: ' . $provider_name;
            }
        }
        
        fclose($handle);
    }
    
    // Store import stats for display
    set_transient('rdr_last_import_stats', array(
        'imported' => $imported,
        'skipped' => $skipped,
        'errors' => $errors
    ), 300); // 5 minutes
    
    return $imported;
}

// ============================================================================
// AJAX HANDLERS
// ============================================================================

add_action('wp_ajax_get_daily_reading', 'reddoor_ajax_get_reading');
add_action('wp_ajax_nopriv_get_daily_reading', 'reddoor_ajax_get_reading');

function reddoor_ajax_get_reading() {
    check_ajax_referer('reddoor-nonce', 'nonce');
    
    $day = isset($_POST['day']) ? intval($_POST['day']) : 1;
    
    $reading = get_posts(array(
        'post_type' => 'rdr_reading',
        'meta_query' => array(array('key' => '_day_number', 'value' => $day)),
        'posts_per_page' => 1
    ));
    
    if (empty($reading)) {
        wp_send_json_error('Reading not found');
    }
    
    $post = $reading[0];
    
    wp_send_json_success(array(
        'day' => get_post_meta($post->ID, '_day_number', true),
        'section' => get_post_meta($post->ID, '_section', true),
        'chapter' => get_post_meta($post->ID, '_chapter', true),
        'excerpt_start' => get_post_meta($post->ID, '_excerpt_start', true),
        'excerpt_end' => get_post_meta($post->ID, '_excerpt_end', true),
        'core_idea' => get_post_meta($post->ID, '_core_idea', true),
        'modern_interpretation' => get_post_meta($post->ID, '_modern_interpretation', true)
    ));
}

// Register Widget Areas
function reddoor_register_widgets() {
    register_sidebar(array(
        'name' => 'Homepage Sidebar',
        'id' => 'homepage-sidebar',
        'description' => 'Sidebar for homepage - Through the Red Door widget',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
}
add_action('widgets_init', 'reddoor_register_widgets');

/**
 * Pretty URLs for Providers directory
 *  - /providers/oh/            -> providers archive filtered to OH
 *  - /providers/oh/adams/      -> providers archive filtered to OH + county slug
 *
 * NOTE: After deploying this change, flush permalinks once:
 * WP Admin -> Settings -> Permalinks -> Save Changes
 */
add_action('init', function () {
    // /providers/oh/
    add_rewrite_rule(
        '^providers/([a-z]{2})/?$',
        'index.php?post_type=rdr_provider&rdrn_state=$matches[1]',
        'top'
    );

    // /providers/oh/adams/
    add_rewrite_rule(
        '^providers/([a-z]{2})/([^/]+)/?$',
        'index.php?post_type=rdr_provider&rdrn_state=$matches[1]&rdrn_county=$matches[2]',
        'top'
    );
}, 20);

add_filter('query_vars', function ($vars) {
    $vars[] = 'rdrn_state';
    $vars[] = 'rdrn_county';
    return $vars;
});
