<?php
/**
 * Template Name: Providers Archive with Search & Geolocation
 * Description: Displays all treatment providers with filtering
 */

get_header();

// Get search and filter parameters
$search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
// Support both querystring (?state=OH) and pretty URLs (/providers/oh/) via rewrite var rdrn_state
$state_filter = get_query_var('rdrn_state');
$state_filter = $state_filter ? strtoupper(sanitize_text_field($state_filter)) : '';
if (!$state_filter && isset($_GET['state'])) {
    $state_filter = strtoupper(sanitize_text_field($_GET['state']));
}

// Optional county slug (pretty URLs /providers/oh/adams/ -> rdrn_county=adams)
$county_filter = get_query_var('rdrn_county');
$county_filter = $county_filter ? sanitize_title($county_filter) : '';
if (!$county_filter && isset($_GET['county'])) {
    $county_filter = sanitize_title($_GET['county']);
}
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;
$near_me = isset($_GET['near']) && $_GET['near'] === 'me';

// Build query args
$args = array(
    'post_type' => 'rdr_provider',
    'posts_per_page' => 20,
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
    'orderby' => 'title',
    'order' => 'ASC'
);

// Add search
if ($search_query) {
    $args['s'] = $search_query;
}

// Add state filter
if ($state_filter) {
    $args['meta_query'] = array(
        array(
            'key' => '_state',
            'value' => $state_filter,
            'compare' => '='
        )
    );
}

// Add county filter (taxonomy)
if ($county_filter) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'rdr_county',
            'field'    => 'slug',
            'terms'    => $county_filter,
        )
    );
}

// NOTE: Provider listings are now rendered from the imported DB tables
// (wp_rdrn_providers + wp_rdrn_provider_services) so state/county pages
// work for all imported states (not just legacy CPT postmeta).
$providers_query = new WP_Query($args); // retained for legacy/compat; not the primary datasource

// Page title logic
$page_title = 'Treatment Providers Directory';
$page_subtitle = 'Find substance abuse treatment centers';

if ($search_query) {
    $page_title = 'Search Results for "' . esc_html($search_query) . '"';
} elseif ($near_me && $lat && $lng) {
    $page_title = 'Providers Near You';
    $page_subtitle = 'Based on your location';
} elseif ($state_filter) {
    // Full map so URLs don't depend on a hard-coded 5-state set
    $state_names = array(
        'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut',
        'DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana',
        'IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts',
        'MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada',
        'NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota',
        'OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota',
        'TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia',
        'WI'=>'Wisconsin','WY'=>'Wyoming','DC'=>'District of Columbia'
    );
    $state_label = isset($state_names[$state_filter]) ? $state_names[$state_filter] : $state_filter;
    $page_title = 'Providers in ' . $state_label;
    if ($county_filter) {
        $county_term = get_term_by('slug', $county_filter, 'rdr_county');
        if ($county_term && !is_wp_error($county_term)) {
            $page_title = $state_label . ' — ' . $county_term->name . ' County Providers';
        }
    }
}
?>

<style>
/* Providers archive card layout (match county cards) */
.providers-archive { background: #F3F4F6; min-height: 100vh; }
.page-header { background: linear-gradient(135deg, #1F2933 0%, #2d3a47 100%); color: #fff; padding: 40px 20px; }
.page-header h1 { color: #fff; margin: 0 0 8px 0; font-size: 2.2rem; }
.page-header p { margin: 0 0 16px 0; opacity: 0.9; }

.providers-container { max-width: 1400px; margin: 0 auto; padding: 30px 20px; display: grid; grid-template-columns: 280px 1fr; gap: 30px; align-items: start; }
.providers-sidebar { background: transparent; }
.filter-section { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 16px; }
.filter-section h3 { margin: 0 0 10px 0; font-size: 1rem; color: #1F2933; }
.filter-list { list-style: none; padding: 0; margin: 0; }
.filter-list li { margin: 0 0 6px 0; }
.filter-list a { color: #1F2933; text-decoration: none; font-size: 0.9rem; }
.filter-list a:hover { color: #B11226; text-decoration: underline; }
.filter-list .count { color: #6b7280; }

.providers-main { min-width: 0; }
.providers-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 14px; }
.results-count { color: #6b7280; margin: 0; }
.clear-filters { color: #B11226; text-decoration: none; font-weight: 600; }
.clear-filters:hover { text-decoration: underline; }

.providers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
.provider-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 2px solid transparent; display: flex; flex-direction: column; }
.provider-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.10); }
.provider-header { padding: 16px 16px 0 16px; }
.provider-card h3 { margin: 0 0 8px 0; font-size: 1.05rem; color: #1F2933; line-height: 1.25; }
.provider-address, .provider-phone, .provider-website { padding: 0 16px; margin: 0 0 8px 0; color: #4b5563; font-size: 0.9rem; }
.provider-phone a { color: #B11226; text-decoration: none; font-weight: 600; }
.provider-phone a:hover { text-decoration: underline; }
.provider-website a { color: #1F2933; text-decoration: none; }
.provider-website a:hover { text-decoration: underline; }
.provider-services { padding: 0 16px 10px 16px; display: flex; flex-wrap: wrap; gap: 6px; }
.service-tag { background: #F3F4F6; color: #1F2933; font-size: 0.75rem; padding: 4px 10px; border-radius: 999px; }
.provider-actions { margin-top: auto; display: grid; grid-template-columns: 1fr 1fr; }
.provider-call, .provider-details { display: block; text-align: center; padding: 12px 10px; text-decoration: none; font-weight: 700; font-size: 0.9rem; }
.provider-call { background: #B11226; color: #fff; }
.provider-call:hover { filter: brightness(0.95); }
.provider-details { background: #E5E7EB; color: #1F2933; }
.provider-details:hover { filter: brightness(0.98); }

.cta-box { background: #B11226; color: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.cta-box a { color: #fff; text-decoration: underline; }

@media (max-width: 980px) {
  .providers-container { grid-template-columns: 1fr; }
}
</style>

<main class="providers-archive">
    <div class="page-header">
        <div class="header-container">
            <h1><?php echo $page_title; ?></h1>
            <p><?php echo $page_subtitle; ?></p>
            
            <!-- Search Bar -->
            <form action="<?php echo esc_url(get_post_type_archive_link('rdr_provider')); ?>" method="GET" class="provider-search-form">
                <input type="text" 
                       name="s" 
                       placeholder="Search by city, ZIP, or provider name" 
                       value="<?php echo esc_attr($search_query); ?>"
                       class="provider-search-input">
                <select name="state" class="state-filter">
                    <option value="">All States</option>
                    <?php
                    // Option A: only states that exist in data (from imported provider table)
                    global $wpdb;
                    $prov_table = $wpdb->prefix . 'rdrn_providers';
                    $state_rows = $wpdb->get_results("SELECT UPPER(TRIM(state)) AS st, COUNT(*) AS cnt FROM $prov_table WHERE state IS NOT NULL AND state <> '' GROUP BY UPPER(TRIM(state)) ORDER BY st ASC");
                    $state_names = array(
                        'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut',
                        'DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana',
                        'IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts',
                        'MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada',
                        'NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota',
                        'OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota',
                        'TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia',
                        'WI'=>'Wisconsin','WY'=>'Wyoming','DC'=>'District of Columbia'
                    );
                    if ($state_rows) {
                        foreach ($state_rows as $row) {
                            $code = strtoupper($row->st);
                            if (!$code) continue;
                            $label = isset($state_names[$code]) ? $state_names[$code] : $code;
                            echo '<option value="' . esc_attr($code) . '" ' . selected($state_filter, $code, false) . '>' . esc_html($label) . '</option>';
                        }
                    }
                    ?>
                </select>
                <button type="submit" class="search-btn">Search</button>
                <button type="button" class="location-btn" onclick="useMyLocation()">📍 Near Me</button>
            </form>
        </div>
    </div>

    <div class="providers-container">
        <aside class="providers-sidebar">
            <div class="filter-section">
                <h3>Filter by State</h3>
                <ul class="filter-list">
                    <?php
                    // Option A: data-driven states (only states present in DB), with pretty URLs /providers/oh/
                    global $wpdb;
                    $prov_table = $wpdb->prefix . 'rdrn_providers';
                    $state_rows = $wpdb->get_results("SELECT UPPER(TRIM(state)) AS st, COUNT(*) AS cnt FROM $prov_table WHERE state IS NOT NULL AND state <> '' GROUP BY UPPER(TRIM(state)) ORDER BY st ASC");
                    $state_names = array(
                        'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut',
                        'DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana',
                        'IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts',
                        'MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada',
                        'NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota',
                        'OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota',
                        'TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia',
                        'WI'=>'Wisconsin','WY'=>'Wyoming','DC'=>'District of Columbia'
                    );
                    $archive_base = trailingslashit(home_url('/providers/'));
                    if ($state_rows) {
                        foreach ($state_rows as $row) {
                            $code = strtoupper($row->st);
                            if (!$code) continue;
                            $label = isset($state_names[$code]) ? $state_names[$code] : $code;
                            $pretty = $archive_base . strtolower($code) . '/';
                            if ($search_query) {
                                $pretty = add_query_arg('s', $search_query, $pretty);
                            }
                            echo '<li><a href="' . esc_url($pretty) . '">' . esc_html($label) . ' <span class="count">(' . intval($row->cnt) . ')</span></a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>

            <div class="filter-section">
                <h3>Filter by County</h3>
                <?php
                // If a state is selected, generate county list from the imported table for that state.
                // Otherwise, fall back to the legacy taxonomy list.
                global $wpdb;
                $prov_table = $wpdb->prefix . 'rdrn_providers';

                if ($state_filter) {
                    $county_rows = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT county, COUNT(*) AS cnt
                             FROM $prov_table
                             WHERE UPPER(TRIM(state)) = %s AND county IS NOT NULL AND county <> ''
                             GROUP BY county
                             ORDER BY cnt DESC
                             LIMIT 40",
                            $state_filter
                        )
                    );
                    if (!empty($county_rows)) {
                        echo '<ul class="filter-list">';
                        foreach ($county_rows as $cr) {
                            $county_name = trim((string)$cr->county);
                            $county_slug = sanitize_title($county_name);
                            $url = trailingslashit(home_url('/providers/' . strtolower($state_filter) . '/' . $county_slug));
                            echo '<li><a href="' . esc_url($url) . '">' . esc_html($county_name) . ' <span class="count">(' . intval($cr->cnt) . ')</span></a></li>';
                        }
                        echo '</ul>';
                    }
                } else {
                    $counties = get_terms(array(
                        'taxonomy' => 'rdr_county',
                        'hide_empty' => true,
                        'orderby' => 'name',
                        'number' => 20
                    ));

                    if ($counties && !is_wp_error($counties)) {
                        echo '<ul class="filter-list">';
                        foreach ($counties as $county) {
                            $url = get_term_link($county);
                            echo '<li><a href="' . esc_url($url) . '">' . esc_html($county->name) . ' <span class="count">(' . $county->count . ')</span></a></li>';
                        }
                        echo '</ul>';
                        echo '<p><a href="' . home_url('/providers/') . '">View all counties...</a></p>';
                    }
                }
                ?>
            </div>

            <div class="filter-section">
                <h3>Filter by City</h3>
                <?php
                // If a state is selected, show top cities for that state from the imported table.
                if ($state_filter) {
                    $city_rows = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT city, COUNT(*) AS cnt
                             FROM $prov_table
                             WHERE UPPER(TRIM(state)) = %s AND city IS NOT NULL AND city <> ''
                             GROUP BY city
                             ORDER BY cnt DESC
                             LIMIT 30",
                            $state_filter
                        )
                    );
                    if (!empty($city_rows)) {
                        echo '<ul class="filter-list">';
                        foreach ($city_rows as $cr) {
                            $city_name = trim((string)$cr->city);
                            // Keep existing search UX: clicking a city just applies a search query.
                            $url = add_query_arg(array('s' => $city_name), trailingslashit(home_url('/providers/' . strtolower($state_filter) . '/')));
                            echo '<li><a href="' . esc_url($url) . '">' . esc_html($city_name) . ' <span class="count">(' . intval($cr->cnt) . ')</span></a></li>';
                        }
                        echo '</ul>';
                    }
                } else {
                    $cities = get_terms(array(
                        'taxonomy' => 'rdr_city',
                        'hide_empty' => true,
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 15
                    ));

                    if ($cities && !is_wp_error($cities)) {
                        echo '<ul class="filter-list">';
                        foreach ($cities as $city) {
                            $url = get_term_link($city);
                            echo '<li><a href="' . esc_url($url) . '">' . esc_html($city->name) . ' <span class="count">(' . $city->count . ')</span></a></li>';
                        }
                        echo '</ul>';
                    }
                }
                ?>
            </div>

            <div class="cta-box">
                <h4>Looking for Help?</h4>
                <p>If you need immediate assistance, please call the 24/7 helpline:</p>
                <p class="hotline-number"><a href="tel:1-800-662-4357">1-800-662-HELP</a></p>
                <p style="font-size: 12px; opacity: 0.9; margin-top: 10px;">Free, confidential, 24/7</p>
            </div>
        </aside>

        <div class="providers-main">
            <?php
            // --- DB-driven listing (authoritative) ---
            global $wpdb;
            $prov_table = $wpdb->prefix . 'rdrn_providers';
            $svc_table  = $wpdb->prefix . 'rdrn_provider_services';

            $per_page = 12;
            $paged    = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
            if ($paged < 1) $paged = 1;
            $offset = ($paged - 1) * $per_page;

            // Helper: slugify for county matching
            $county_sql = '';
            $city_sql   = '';
            $search_sql = '';
            $params     = array();

            if ($state_filter) {
                $params[] = $state_filter;
            }

            if ($county_filter) {
                // Resolve slug -> exact county name (more reliable than trying to slugify in SQL).
                $resolved_county = null;
                if ($state_filter) {
                    $distinct = $wpdb->get_col(
                        $wpdb->prepare(
                            "SELECT DISTINCT county FROM $prov_table WHERE UPPER(TRIM(state)) = %s AND county IS NOT NULL AND county <> ''",
                            $state_filter
                        )
                    );
                    foreach ($distinct as $cn) {
                        if (sanitize_title($cn) === $county_filter) { $resolved_county = $cn; break; }
                    }
                }
                if ($resolved_county) {
                    $county_sql = " AND county = %s ";
                    $params[] = $resolved_county;
                } else {
                    // Fallback: best-effort slug compare.
                    $county_sql = " AND LOWER(TRIM(REPLACE(REPLACE(REPLACE(county, ' County', ''), 'county', ''), ' ', '-'))) = %s ";
                    $params[] = $county_filter;
                }
            }

            // If you later add a city pretty URL, wire it here. For now we keep search-based city filter only.

            if ($search_query) {
                $like = '%' . $wpdb->esc_like($search_query) . '%';
                $search_sql = " AND (name1 LIKE %s OR name2 LIKE %s OR city LIKE %s OR zip LIKE %s) ";
                array_push($params, $like, $like, $like, $like);
            }

            $where = "WHERE 1=1";
            if ($state_filter) {
                $where .= " AND UPPER(TRIM(state)) = %s ";
            }
            $where .= $county_sql;
            $where .= $search_sql;

            // Count
            $count_sql = "SELECT COUNT(*) FROM $prov_table $where";
            $total = intval($wpdb->get_var($wpdb->prepare($count_sql, $params)));

            // Fetch page
            $list_sql = "
                SELECT id, name1, name2, street1, street2, city, state, zip, phone, website, county
                FROM $prov_table
                $where
                ORDER BY name1 ASC
                LIMIT %d OFFSET %d
            ";
            $list_params = array_merge($params, array($per_page, $offset));
            $rows = $wpdb->get_results($wpdb->prepare($list_sql, $list_params));

            ?>

            <div class="providers-header">
                <p class="results-count">
                    <?php
                    if ($total > 0) {
                        echo 'Found <strong>' . number_format($total) . '</strong> provider' . ($total != 1 ? 's' : '');
                        if ($search_query) {
                            echo ' for "' . esc_html($search_query) . '"';
                        }
                    } else {
                        echo 'No providers found';
                    }
                    ?>
                </p>
                <?php if ($search_query || $state_filter || $county_filter): ?>
                    <p><a href="<?php echo home_url('/providers/'); ?>" class="clear-filters">Clear filters</a></p>
                <?php endif; ?>
            </div>

            <div class="providers-grid">
                <?php if (!empty($rows)) : ?>
                    <?php
                    // Load up to 3 service tags per provider from the services table.
                    $ids = wp_list_pluck($rows, 'id');
                    $svc_map = array();
                    if (!empty($ids)) {
                        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
                        $svc_rows = $wpdb->get_results($wpdb->prepare(
                            "SELECT provider_id, f1, f2, f3 FROM $svc_table WHERE provider_id IN ($placeholders) ORDER BY provider_id ASC, id ASC",
                            $ids
                        ));
                        foreach ($svc_rows as $sr) {
                            if (!isset($svc_map[$sr->provider_id])) $svc_map[$sr->provider_id] = array();
                            // Prefer f2 code if present, else f1 label
                            $tag = $sr->f2 ? $sr->f2 : $sr->f1;
                            if ($tag && count($svc_map[$sr->provider_id]) < 3) {
                                $svc_map[$sr->provider_id][] = $tag;
                            }
                        }
                    }

                    foreach ($rows as $r):
                        $title = $r->name1 ? $r->name1 : ($r->name2 ? $r->name2 : 'Provider');
                        $tel = preg_replace('/[^0-9]/', '', (string)$r->phone);
                        $address_line1 = trim((string)$r->street1);
                        $city_state_zip = trim((string)$r->city);
                        if ($r->state) $city_state_zip .= ($city_state_zip ? ', ' : '') . strtoupper(trim((string)$r->state));
                        if ($r->zip) $city_state_zip .= ' ' . trim((string)$r->zip);
                        $county_label = trim((string)$r->county);
                        $tags = isset($svc_map[$r->id]) ? $svc_map[$r->id] : array();
                    ?>

                    <div class="provider-card">
                        <div class="provider-header">
                            <h3><?php echo esc_html($title); ?></h3>
                        </div>

                        <?php if ($address_line1 || $city_state_zip): ?>
                            <p class="provider-address">
                                <span class="icon">📍</span>
                                <?php
                                if ($address_line1) echo esc_html($address_line1) . '<br>';
                                echo esc_html($city_state_zip);
                                if ($county_label) echo ' — ' . esc_html($county_label) . ' County';
                                ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($r->phone)): ?>
                            <p class="provider-phone">
                                <span class="icon">📞</span>
                                <?php if ($tel): ?>
                                    <a href="tel:<?php echo esc_attr($tel); ?>"><?php echo esc_html($r->phone); ?></a>
                                <?php else: ?>
                                    <?php echo esc_html($r->phone); ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($r->website)): ?>
                            <p class="provider-website">
                                <span class="icon">🌐</span>
                                <a href="<?php echo esc_url($r->website); ?>" target="_blank" rel="noopener">Visit Website</a>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($tags)): ?>
                            <div class="provider-services">
                                <?php foreach ($tags as $t): ?>
                                    <span class="service-tag"><?php echo esc_html($t); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="provider-actions">
                            <?php if ($tel): ?>
                                <a class="provider-call" href="tel:<?php echo esc_attr($tel); ?>">📞 Call Now</a>
                            <?php endif; ?>
                            <?php if (!empty($r->website)): ?>
                                <a class="provider-details" href="<?php echo esc_url($r->website); ?>" target="_blank" rel="noopener">View Details →</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php endforeach; ?>

                    <?php
                    // Pagination (pretty URL safe)
                    $max_pages = (int)ceil($total / $per_page);
                    if ($max_pages > 1):
                        $base_url = home_url('/providers/');
                        if ($state_filter) {
                            $base_url = trailingslashit(home_url('/providers/' . strtolower($state_filter) . '/'));
                            if ($county_filter) {
                                $base_url = trailingslashit(home_url('/providers/' . strtolower($state_filter) . '/' . $county_filter . '/'));
                            }
                        }
                        echo '<div class="providers-pagination">';
                        echo paginate_links(array(
                            'total' => $max_pages,
                            'current' => $paged,
                            'format' => 'page/%#%/',
                            'base' => trailingslashit($base_url) . '%_%',
                            'prev_text' => '← Previous',
                            'next_text' => 'Next →',
                            'add_args' => ($search_query ? array('s' => $search_query) : array()),
                        ));
                        echo '</div>';
                    endif;
                    ?>

                <?php else: ?>
                    <div class="no-results">
                        <h3>No providers found</h3>
                        <?php if ($state_filter): ?>
                            <p>No providers found in this state with the current filters.</p>
                        <?php else: ?>
                            <p>No providers found with the current filters.</p>
                        <?php endif; ?>
                        <p><a href="<?php echo home_url('/providers/'); ?>" class="btn-back">← Back to all providers</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
function useMyLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser');
        return;
    }

    const btn = event.target;
    btn.textContent = '📍 Finding...';
    btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            window.location.href = '<?php echo home_url('/providers/'); ?>?lat=' + lat + '&lng=' + lng + '&near=me';
        },
        function(error) {
            alert('Unable to get location: ' + error.message);
            btn.textContent = '📍 Near Me';
            btn.disabled = false;
        }
    );
}

// Convert state selection to pretty URLs (/providers/oh/) while preserving search
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.provider-search-form');
    const stateSel = document.querySelector('.provider-search-form select.state-filter');
    const searchInput = document.querySelector('.provider-search-form input[name="s"]');
    if (!form || !stateSel) return;

    form.addEventListener('submit', function (e) {
        const st = (stateSel.value || '').trim();
        if (!st) return; // no state selected -> normal submit

        e.preventDefault();
        const base = '<?php echo esc_js(trailingslashit(home_url('/providers/'))); ?>' + st.toLowerCase() + '/';
        const s = searchInput ? (searchInput.value || '').trim() : '';
        if (s) {
            window.location.href = base + '?s=' + encodeURIComponent(s);
        } else {
            window.location.href = base;
        }
    });
});
</script>

<?php get_footer(); ?>
