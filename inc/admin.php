<?php

/* Adapted from code in Rookie Lite (c) 2018, MH Themes. */

if (!defined('ABSPATH')) {
  exit;
}

/***** Show Welcome Screen After Theme Activation *****/

if (!function_exists('rookie_welcome_admin')) {
  function rookie_welcome_admin() {
    global $pagenow;
    if (is_admin() && isset($_GET['activated']) && $pagenow == "themes.php") {
      wp_redirect('themes.php?page=rookie');
      exit;
    }
  }
}
add_action('admin_init', 'rookie_welcome_admin', 1);

/***** Theme Info Page *****/

if (!function_exists('rookie_theme_info_page')) {
  function rookie_theme_info_page() {
    add_theme_page(esc_html__('Welcome to Rookie', 'rookie'), esc_html__('Theme Info', 'rookie'), 'edit_theme_options', 'rookie', 'rookie_display_theme_page');
  }
}
add_action('admin_menu', 'rookie_theme_info_page');

if (!function_exists('rookie_display_theme_page')) {
  function rookie_display_theme_page() {
    $rookie_data = wp_get_theme(); ?>
    <div class="theme-info-wrap">
      <h1>
        <?php printf(esc_html__('Welcome to %1$1s %2$2s', 'rookie'), 'Rookie', $rookie_data['Version']); ?>
      </h1>
      <div class="rookie-row theme-intro rookie-clearfix">
        <div class="rookie-col-1-4">
          <img class="theme-screenshot" src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="<?php esc_html_e('Theme Screenshot', 'rookie'); ?>" />
        </div>
        <div class="rookie-col-3-4 theme-description">
          <?php echo esc_html($rookie_data['Description']); ?>
        </div>
      </div>
      <hr>
      <div class="theme-links rookie-clearfix">
        <p>
          <strong><?php esc_html_e('Important Links:', 'rookie'); ?></strong>
          <a href="<?php echo esc_url('https://www.themeboy.com/rookie/'); ?>" target="_blank">
            <?php esc_html_e('Theme Info Page', 'rookie'); ?>
          </a>
          <a href="<?php echo esc_url('https://support.themeboy.com/'); ?>" target="_blank">
            <?php esc_html_e('Support Center', 'rookie'); ?>
          </a>
          <a href="<?php echo esc_url('https://wordpress.org/support/theme/rookie'); ?>" target="_blank">
            <?php esc_html_e('Support Forum', 'rookie'); ?>
          </a>
          <a href="<?php echo esc_url('https://www.themeboy.com/showcase/'); ?>" target="_blank">
            <?php esc_html_e('ThemeBoy Showcase', 'rookie'); ?>
          </a>
        </p>
      </div>
      <hr>
      <div id="getting-started">
        <h3>
          <?php printf(esc_html__('Get Started with %s', 'rookie'), $rookie_data['Name']); ?>
        </h3>
        <div class="rookie-row rookie-clearfix">
          <div class="rookie-col-1-2">
            <div class="section">
              <h4>
                <span class="dashicons dashicons-welcome-learn-more"></span>
                <?php esc_html_e('Theme Documentation', 'rookie'); ?>
              </h4>
              <p class="about">
                <?php printf(esc_html__('Need any help with configuring %s? The documentation for this theme includes all theme related information that is needed to get your site up and running in no time. In case you have any additional questions, feel free to reach out in the theme support forums on WordPress.org.', 'rookie'), $rookie_data['Name']); ?>
              </p>
              <p>
                <a href="<?php echo esc_url('https://support.themeboy.com/category/29-rookie'); ?>" target="_blank" class="button button-secondary">
                  <?php esc_html_e('Theme Documentation', 'rookie'); ?>
                </a>
                <a href="<?php echo esc_url('https://wordpress.org/support/theme/rookie'); ?>" target="_blank" class="button button-secondary">
                  <?php esc_html_e('Support Forum', 'rookie'); ?>
                </a>
              </p>
            </div>
            <div class="section">
              <h4>
                <span class="dashicons dashicons-admin-appearance"></span>
                <?php esc_html_e('Theme Options', 'rookie'); ?>
              </h4>
              <p class="about">
                <?php printf(esc_html__('%s supports the Theme Customizer for all theme settings. Click "Customize Theme" to open the Customizer now.',  'rookie'), $rookie_data['Name']); ?>
              </p>
              <p>
                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-secondary">
                  <?php esc_html_e('Customize Theme', 'rookie'); ?>
                </a>
              </p>
            </div>
          </div>
          <div class="rookie-col-1-2">
            <div class="section">
              <h4>
                <span class="dashicons dashicons-cart"></span>
                <?php esc_html_e('Rookie Plus', 'rookie'); ?>
              </h4>
              <p class="about">
                <?php esc_html_e('If you like the free version of this theme, you will LOVE the pro version of Rookie which includes unique custom widgets, additional features and more useful options to customize your website.', 'rookie'); ?>
              </p>
              <p>
                <a href="<?php echo esc_url('https://www.themeboy.com/rookie-plus/'); ?>" target="_blank" class="button button-primary">
                  <?php esc_html_e('Upgrade to Rookie Plus', 'rookie'); ?>
                </a>
              </p>
            </div>
            <div class="section">
              <h4>
                <span class="dashicons dashicons-images-alt"></span>
                <?php esc_html_e('Rookie Theme Demos', 'rookie'); ?>
              </h4>
              <p class="about">
                <?php esc_html_e('The premium version of Rookie includes lots of additional features and options to customize your website. We have created several theme demos as examples in order to show what is possible with this flexible magazine theme.', 'rookie'); ?>
              </p>
              <p>
                <a href="<?php echo esc_url('http://demo.themeboy.com/sportspress/rookie-plus/soccer'); ?>" target="_blank" class="button button-secondary">
                  <?php esc_html_e('Theme Demos', 'rookie'); ?>
                </a>
                <a href="<?php echo esc_url('https://www.themeboy.com/showcase/'); ?>" target="_blank" class="button button-secondary">
                  <?php esc_html_e('ThemeBoy Showcase', 'rookie'); ?>
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="theme-comparison">
        <h3 class="theme-comparison-intro">
          <?php esc_html_e('Upgrade to Rookie Plus for more awesome features:', 'rookie'); ?>
        </h3>
        <table>
          <thead class="theme-comparison-header">
            <tr>
              <th class="table-feature-title"><h3><?php esc_html_e('Features', 'rookie'); ?></h3></th>
              <th><h3><?php esc_html_e('Rookie', 'rookie'); ?></h3></th>
              <th><h3><?php esc_html_e('Rookie Plus', 'rookie'); ?></h3></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><h3><?php esc_html_e('Theme price', 'rookie'); ?></h3></td>
              <td><?php esc_html_e('Free', 'rookie'); ?></td>
              <td>
                <a href="<?php echo esc_url('https://www.themeboy.com/rookie-plus/pricing/'); ?>" target="_blank">
                  <?php esc_html_e('View pricing', 'rookie'); ?>
                </a>
              </td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Responsive layout', 'rookie'); ?></h3></td>
              <td><span class="dashicons dashicons-yes"></span></td>
              <td><span class="dashicons dashicons-yes"></span></td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Extended layout options', 'rookie'); ?></h3></td>
              <td><span class="dashicons dashicons-yes"></span></td>
              <td><span class="dashicons dashicons-yes"></span></td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Second sidebar', 'rookie'); ?></h3></td>
              <td><span class="dashicons dashicons-yes"></span></td>
              <td><span class="dashicons dashicons-yes"></span></td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Homepage template', 'rookie'); ?></h3></td>
              <td><span class="dashicons dashicons-yes"></span></td>
              <td><span class="dashicons dashicons-yes"></span></td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Social sidebar buttons', 'rookie'); ?></h3></td>
              <td><span class="dashicons dashicons-no"></span></td>
              <td><span class="dashicons dashicons-yes"></span></td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Animated image slider', 'rookie'); ?></h3></td>
              <td><span class="dashicons dashicons-no"></span></td>
              <td><span class="dashicons dashicons-yes"></span></td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Multi-column news widget', 'rookie'); ?></h3></td>
              <td><span class="dashicons dashicons-no"></span></td>
              <td><span class="dashicons dashicons-yes"></span></td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Customizable footer copyright', 'rookie'); ?></h3></td>
              <td><span class="dashicons dashicons-no"></span></td>
              <td><span class="dashicons dashicons-yes"></span></td>
            </tr>
            <tr>
              <td><h3><?php esc_html_e('Tech support', 'rookie'); ?></h3></td>
              <td><?php esc_html_e('Support forum', 'rookie'); ?></td>
              <td><?php esc_html_e('Access to private support', 'rookie'); ?></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td>
                <a href="<?php echo esc_url('https://www.themeboy.com/rookie-plus/'); ?>" target="_blank" class="upgrade-button">
                  <?php esc_html_e('Upgrade to Rookie Plus', 'rookie'); ?>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <hr>
      <div id="theme-author">
        <p>
          <?php printf(esc_html__('%1$1s is proudly brought to you by %2$2s. If you like %3$3s: %4$4s.', 'rookie'), $rookie_data['Name'], '<a target="_blank" href="https://www.themeboy.com/" title="ThemeBoy">ThemeBoy</a>', $rookie_data['Name'], '<a target="_blank" href="https://wordpress.org/support/view/theme-reviews/rookie?filter=5" title="Rookie Review">' . esc_html__('Rate this theme', 'rookie') . '</a>'); ?>
        </p>
      </div>
    </div><?php
  }
}

?>