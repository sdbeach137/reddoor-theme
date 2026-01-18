# RED DOOR RECOVERY NETWORK - WordPress Theme

## 🎯 What This Theme Does

Complete WordPress theme for Red Door Recovery Network featuring:
- Multi-state substance use disorder provider directory
- "Through the Red Door" daily readings widget (90 days from AA Big Book)
- Cookie-based progress tracking (no login required)
- 5 virtual milestone coins with celebrations
- Professional, accessible design matching your brand
- Responsive layout for all devices

## 📦 What's Included

```
reddoor-theme/
├── style.css               # Complete CSS with brand colors
├── functions.php           # CPTs, CSV importer, AJAX handlers
├── header.php             # Site header with logo & navigation
├── footer.php             # Site footer
├── index.php              # Fallback template
├── front-page.php         # Homepage with full layout
├── daily-readings.csv     # 90-day readings data
├── assets/
│   ├── images/            # All logos and graphics
│   └── js/
│       └── daily-readings.js  # Cookie tracking & coins
└── README.md              # This file
```

## 🚀 Installation Instructions

### Step 1: Upload Theme

1. **Download** the `reddoor-theme.zip` file
2. **Login** to WordPress admin: `http://YOUR-SITE/wp-admin`
3. **Navigate** to: Appearance → Themes
4. **Click** "Add New" → "Upload Theme"
5. **Choose** `reddoor-theme.zip`
6. **Click** "Install Now"
7. **Activate** the theme

### Step 2: Import Daily Readings

1. **Navigate** to: Tools → Import Readings
2. **Click** "Import 90 Daily Readings" button
3. **Wait** for confirmation (imports all 90 days automatically)

That's it! The theme is now active and functional.

## 🎨 How The Widget Works

### Cookie-Based Tracking (No Login Required)

**First Visit:**
- Sets start date cookie
- Shows Day 1 reading
- User earns first coin (🚪 Day 1)

**Subsequent Visits:**
- Calculates days since start
- Automatically advances to current day
- Shows earned coins
- Celebration popup when reaching milestones

**Milestones:**
- 🚪 Day 1 - First Step
- 🥉 Day 7 - One Week
- 🥈 Day 30 - One Month
- 🥇 Day 60 - Two Months
- 🏆 Day 90 - Complete

**After 90 Days:**
- Automatically loops back to Day 1
- User can start the cycle again
- Coins reset for new journey

### Cookies Stored

- `rdr_journey_start` - Timestamp when user first visited
- `rdr_coins_earned` - Array of milestone days earned [1,7,30,60,90]
- `rdr_last_visit` - Last time user viewed a reading

## 🎯 Brand Colors

```css
--rdr-red: #B11226       /* Primary CTAs, logo */
--rdr-green: #2E7D32     /* Success, growth */
--rdr-charcoal: #1F2933  /* Text */
--rdr-soft-grey: #F3F4F6 /* Backgrounds */
--rdr-border-grey: #D1D5DB /* Borders */
--rdr-gold: #C9A227      /* Coins, accents */
```

## 📊 CSV File Format

The `daily-readings.csv` contains:
- day (1-90)
- section (Doctor's Opinion, Main text, etc.)
- chapter (Chapter name)
- excerpt_start_words (Opening of excerpt)
- excerpt_end_words (Closing of excerpt)
- core_idea (Summary)
- modern_clinical_interpretation (Evidence-based explanation)

## 🛠️ Customization

### Change Colors
Edit `style.css` and update the `:root` variables

### Modify Counties/Cities
Edit `front-page.php` and update the arrays:
```php
$ohio_counties = array(...);
$major_cities = array(...);
```

### Adjust Coin Milestones
Edit `assets/js/daily-readings.js`:
```javascript
const milestones = [1, 7, 30, 60, 90]; // Change these numbers
```

## 🔧 Troubleshooting

### Readings Not Loading
1. Verify import completed: Tools → Import Readings
2. Check browser console for JavaScript errors
3. Ensure cookies are enabled in browser

### Coins Not Appearing
1. Clear browser cache and cookies
2. Check that readings imported successfully
3. Verify JavaScript file loaded (View Page Source)

### Widget Not Displaying
1. Ensure you're on the homepage (front page)
2. Verify theme activated
3. Check that images uploaded correctly

## 🎓 For Developers

### Add More CPTs
Edit `functions.php` and add after existing CPT registration

### Modify AJAX Handler
The reading data comes from: `reddoor_ajax_get_reading()` in `functions.php`

### Custom Celebration Messages
Edit `daily-readings.js` in the `showCelebration()` function

## 📞 Support

For issues or questions:
- Check WordPress error logs
- Verify PHP version (8.0+)
- Ensure all files uploaded
- Test with default WordPress theme first

## 🎉 Features Coming Soon

- Provider directory functionality
- State/county filtering
- Search system
- WooCommerce integration
- User accounts
- OpenStreetMap integration

## 📄 License

GPL v2 or later

---

**Built with care for Red Door Recovery Network**
*Helping people find treatment and build recovery* 🚪
