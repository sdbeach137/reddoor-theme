<?php
/**
 * Template Name: State Directory Page
 */

get_header();

global $wpdb;

/**
 * Read state from URL:
 * /state/california/ → rdrn_state_slug = california
 */
$slug = get_query_var('rdrn_state_slug');
$slug = strtolower(trim((string)$slug));

/**
 * Map state slug → state code
 */
$slug_to_code = [
  'alabama'=>'AL','alaska'=>'AK','arizona'=>'AZ','arkansas'=>'AR','california'=>'CA','colorado'=>'CO','connecticut'=>'CT',
  'delaware'=>'DE','florida'=>'FL','georgia'=>'GA','hawaii'=>'HI','idaho'=>'ID','illinois'=>'IL','indiana'=>'IN','iowa'=>'IA',
  'kansas'=>'KS','kentucky'=>'KY','louisiana'=>'LA','maine'=>'ME','maryland'=>'MD','massachusetts'=>'MA','michigan'=>'MI',
  'minnesota'=>'MN','mississippi'=>'MS','missouri'=>'MO','montana'=>'MT','nebraska'=>'NE','nevada'=>'NV','new-hampshire'=>'NH',
  'new-jersey'=>'NJ','new-mexico'=>'NM','new-york'=>'NY','north-carolina'=>'NC','north-dakota'=>'ND','ohio'=>'OH','oklahoma'=>'OK',
  'oregon'=>'OR','pennsylvania'=>'PA','rhode-island'=>'RI','south-carolina'=>'SC','south-dakota'=>'SD','tennessee'=>'TN','texas'=>'TX',
  'utah'=>'UT','vermont'=>'VT','virginia'=>'VA','washington'=>'WA','west-virginia'=>'WV','wisconsin'=>'WI','wyoming'=>'WY',
  'district-of-columbia'=>'DC'
];

$state_code = $slug_to_code[$slug] ?? '';

$providers_table = $wpdb->prefix . 'rdrn_providers';

/**
 * Helper: slugify
 */
function rdrn_slugify_local($s) {
  $s = strtolower(trim($s));
  $s = preg_replace('/[^a-z0-9]+/', '-', $s);
  return trim($s, '-');
}

/**
 * /state/  (no state selected)
 * Show state selector only
 */
if ($slug === '') {
  ?>
  <main class="main-content">
    <section class="hero-search-section">
      <div class="container">
        <h1>Choose a State</h1>

        <div class="state-dropdown">
          <label for="stateSelect">Choose a state:</label>
          <select id="stateSelect" onchange="if(this.value){window.location.href=this.value;}">
            <option value="">Select a state</option>
            <?php
            $rows = $wpdb->get_results("
              SELECT UPPER(TRIM(state)) AS st, COUNT(*) AS cnt
              FROM {$providers_table}
              WHERE state IS NOT NULL AND state <> ''
              GROUP BY UPPER(TRIM(state))
              ORDER BY st ASC
            ", ARRAY_A);

            $code_to_slug = array_flip($slug_to_code);

            foreach ($rows as $r) {
              $code = strtoupper(trim($r['st']));
              $st_slug = $code_to_slug[$code] ?? '';
              if (!$st_slug) continue;
              $url = home_url('/state/' . $st_slug . '/');
              echo '<option value="'.esc_url($url).'">'.esc_html(ucwords(str_replace('-', ' ', $st_slug))).'</option>';
            }
            ?>
          </select>
        </div>
      </div>
    </section>
  </main>
  <?php
  get_footer();
  return;
}

/**
 * Invalid state slug
 */
if (!$state_code) {
  ?>
  <main class="content">
    <div class="container">
      <h1>State not recognized</h1>
      <p>Use URLs like <code>/state/california/</code> or <code>/state/new-york/</code>.</p>
    </div>
  </main>
  <?php
  get_footer();
  return;
}

/**
 * Query counties + cities for this state
 */
$counties = $wpdb->get_results($wpdb->prepare("
  SELECT county, COUNT(*) AS cnt
  FROM $providers_table
  WHERE state = %s AND county IS NOT NULL AND county <> ''
  GROUP BY county
  ORDER BY county ASC
", $state_code), ARRAY_A);

$cities = $wpdb->get_results($wpdb->prepare("
  SELECT city, COUNT(*) AS cnt
  FROM $providers_table
  WHERE state = %s AND city IS NOT NULL AND city <> ''
  GROUP BY city
  ORDER BY cnt DESC
  LIMIT 50
", $state_code), ARRAY_A);

$providers_base = home_url('/providers/' . strtolower($state_code) . '/');
?>

<main class="main-content">

  <section class="hero-search-section">
    <div class="container">
      <h1><?php echo esc_html(ucwords(str_replace('-', ' ', $slug))); ?></h1>

      <div class="state-dropdown">
        <label for="stateSelect">Choose a state:</label>
        <select id="stateSelect" onchange="if(this.value){window.location.href=this.value;}">
          <?php
          $rows = $wpdb->get_results("
            SELECT UPPER(TRIM(state)) AS st, COUNT(*) AS cnt
            FROM {$providers_table}
            WHERE state IS NOT NULL AND state <> ''
            GROUP BY UPPER(TRIM(state))
            ORDER BY st ASC
          ", ARRAY_A);

          $code_to_slug = array_flip($slug_to_code);

          foreach ($rows as $r) {
            $code = strtoupper(trim($r['st']));
            $st_slug = $code_to_slug[$code] ?? '';
            if (!$st_slug) continue;
            $url = home_url('/state/' . $st_slug . '/');
            $selected = ($code === $state_code) ? ' selected' : '';
            echo '<option value="'.esc_url($url).'"'.$selected.'>'.esc_html(ucwords(str_replace('-', ' ', $st_slug))).'</option>';
          }
          ?>
        </select>
      </div>
    </div>
  </section>

  <section class="directory-section">
    <div class="container directory-layout">

      <div class="directory-lists">

        <div class="directory-block">
          <h2>Counties:</h2>
          <div class="directory-grid">
            <?php foreach ($counties as $c):
              $county_slug = rdrn_slugify_local($c['county']);
              $url = $providers_base . $county_slug . '/';
            ?>
              <a class="directory-link" href="<?php echo esc_url($url); ?>">
                <?php echo esc_html($c['county']); ?> (<?php echo intval($c['cnt']); ?>)
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="directory-block">
          <h2>Major Cities:</h2>
          <div class="directory-grid">
            <?php foreach ($cities as $c):
              $url = $providers_base . '?city=' . rawurlencode($c['city']);
            ?>
              <a class="directory-link" href="<?php echo esc_url($url); ?>">
                <?php echo esc_html($c['city']); ?> (<?php echo intval($c['cnt']); ?>)
              </a>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <aside class="directory-widget">
        <?php
        $widget_path = get_stylesheet_directory() . '/widget-daily-reading.php';
        if (file_exists($widget_path)) {
          include $widget_path;
        }
        ?>
      </aside>

    </div>
  </section>

</main>

<?php get_footer(); ?>
