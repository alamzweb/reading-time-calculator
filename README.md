=== Reading Time Calculator === \
Contributors: Saad \
Plugin URI: [https://sitecroftdev.vercel.app/plugins/reading-time-calculator](https://sitecroftdev.vercel.app/plugins/reading-time-calculator) \
Tags: reading time, estimated reading time, read time, reading time calculator, word count \
Requires at least: 6.0 \
Tested up to: 6.5 \
Requires PHP: 8.0 \
Stable tag: 1.0.0 \
License: GPLv2 or later \
License URI: [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html) 

Automatically calculates and displays clean, estimated reading time metrics for your WordPress posts, pages, and custom post types.

== Description ==

Reading Time Calculator is a lightweight, secure, and production-ready WordPress plugin designed to improve user engagement by setting clear content expectations. By providing a clean visual cue regarding content depth before a visitor begins scrolling, you drastically improve time-on-site metrics and reduce bounce rates.

The plugin works seamlessly right out of the box with optimal default configurations, meaning zero setup stress for standard installations.

= Core Features =

* **Automatic Reading Time Calculation:** Generates reading duration calculations based entirely on content word density.
* **Precise Math Conversions:** Evaluates content depth against adjustable reading speeds (defaulting to 200 words per minute) and intelligently rounds fractions up to the nearest clean minute.
* **Smart Content Thresholds:** Enforces a minimum display threshold of "1 min read" to keep small micro-posts looking uniform and professional.
* **Flexible Display Injection:** Choose whether your reading time indicators render automatically above your content layout, beneath it, in both positions, or altogether hidden (giving you full manual control).
* **Deep Architectural Integration:** Fully supported across Posts, Pages, and custom public post types native to your site hierarchy.
* **Translation-Ready Core:** Prepared fully for localization with strict text domain bindings across all string variations.
* **Lightweight Custom Styling:** Features zero bulky CSS grid framework dependencies. Employs crisp modern CSS variables that adapt effortlessly into your active layout theme context.

= Performance & Architecture Optimization = \
This plugin is built with a strict performance-first mentality for enterprise scale. Instead of running expensive string calculations and regex evaluations on every single raw database query page load, Reading Time Calculator integrates cleanly with the WordPress Transients API. Calculations are processed exactly once and securely cached for up to 24 hours. The transient cache is selectively flushed only when a specific post layout is updated or when your site administrator alters global plugin calculations from the settings backend.

= Global Configurations & Options API = \
Manage complete control over global plugin behaviors from a central interface panel nested under **Settings -> Reading Time Calculator**. Available customization hooks include:

* **Enable Plugin:** A universal global checkbox master switch to instantly toggle automatic display injections across your entire ecosystem.
* **Reading Speed (WPM):** A validated numerical boundary control enabling you to adapt calculation models to match your specific target audience's processing speeds.
* **Prefix Text:** Custom text input to let you localize or stylize your visual indicators (e.g., "📖 Reading Time:", "Estimated Scale:", or "Duración:").
* **Display Position:** Direct dropdown navigation mapping execution hooks to Before Content, After Content, Both Before and After, or Disabled (Manual Injection Mode).
* **Supported Post Types:** A dynamic list of checkboxes scanning your entire active theme structure to selectively activate reading metrics only where they make strategic sense.

= Manual Shortcode Injections = \
For complex design frameworks, template layouts, or theme structures where automatic content hooks do not fit, take control manually using our highly optimized shortcode ecosystem:

* `[reading_time]` - Instantly renders the styled reading time metric for the current post loop context it resides within.
* `[reading_time post_id="123"]` - Queries, calculates, and outputs the unique localized reading calculation for a specific designated Post ID outside of the active page loop.

= Core Gutenberg Block Support = \
Reading Time Calculator includes a dedicated, zero-configuration Gutenberg block entitled **Reading Time**.
To use the block:

1. Open up any Gutenberg block editor screen.
2. Click the block inserter (+) icon tool.
3. Search for "Reading Time" and place it dynamically anywhere into your nested grid layout.
4. The editor preview utilizes an internal server-side render framework to reflect live calculated outputs right inside your editor workspace without breaking your structural flow.

== Installation ==

1. Log in to your WordPress dashboard.
2. Navigate to **Plugins -> Add New Plugin**.
3. Search for "Reading Time Calculator" using the search bar on the top right.
4. Click **Install Now** next to the plugin, then click **Activate** once installation completes.
5. Alternatively, upload a downloaded `.zip` file of the plugin via the **Upload Plugin** button at the top of the page, then activate it.
6. Go to **Settings -> Reading Time Calculator** to configure your options.

== Frequently Asked Questions ==

= Does this plugin slow down massive blogs with thousands of long-form articles? = \
Absolutely not. The plugin code utilizes modern PHP object structures and implements rigorous transient caching. When an article is requested, it checks the database cache first. The actual word-counting loop runs only when a post is completely modified or viewed for the first time in 24 hours, keeping database performance crisp.

= What happens to shortcodes or embedded HTML layout elements inside my posts? = \
Our underlying engine sanitizes raw post contents with `strip_shortcodes()` and `wp_strip_all_tags()` before counting words. This means layout code, script blocks, shortcode text handles, and design markup tags are completely scrubbed from calculation totals, leaving only accurate text metrics.

= If I select "Disabled" under the Display Position settings, will shortcodes still work? = \
Yes. Setting the Display Position setting to "Disabled" completely halts automatic content filter overrides across theme layouts, but leaves your shortcode functions and Gutenberg blocks fully operational. This is the ideal setup for advanced builders wanting manual control over where metrics appear.

= Does this plugin leave old orphan options inside my database if I remove it? = \
No. We adhere to official cleanup guidelines. When you click **Delete** from your WordPress backend, our standalone `uninstall.php` routine executes automatically. This safely drops global option settings arrays and deletes all cached reading time transients from your database.

== Changelog ==

= 1.0.0 =

* Complete initial production release.
* Implemented clean object-oriented plugin architecture conforming to WP Coding Standards.
* Integrated Transients API to optimize server database performance across enterprise architectures.
* Built full dynamic Options API settings panel tracking validated custom speeds, post types, and prefixes.
* Rolled out semantic shortcode attributes and responsive, block-native Gutenberg asset configurations.

== Upgrade Notice ==

= 1.0.0 = \
Initial deployment. Install this version to immediately enable clean, production-grade reading time indicators across your site structure.
