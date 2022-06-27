<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//======================================================================

// Custom Alpha color control

//======================================================================

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Customize_Alpha_Color_Control' ) ):
	class Customize_Alpha_Color_Control extends WP_Customize_Control {

		/**
		 * Official control name.
		 */
		public $type = 'alpha-color';

		/**
		 * Add support for palettes to be passed in.
		 *
		 * Supported palette values are true, false, or an array of RGBa and Hex colors.
		 */
		public $palette;

		/**
		 * Add support for showing the opacity value on the slider handle.
		 */
		public $show_opacity;

		/**
		 * Enqueue scripts and styles.
		 *
		 * Ideally these would get registered and given proper paths before this control object
		 * gets initialized, then we could simply enqueue them here, but for completeness as a
		 * stand alone class we'll register and enqueue them here.
		 */
		public function enqueue() {
			wp_enqueue_script( 'alpha-color-picker', WPOP_URL . 'assets/js/alpha-color-picker.js', [
					'jquery',
					'wp-color-picker',
				], '1.0.0', true );
			wp_enqueue_style( 'alpha-color-picker', WPOP_URL . 'assets/css/alpha-color-picker.css', [ 'wp-color-picker' ], '1.0.0' );
		}

		/**
		 * Render the control.
		 */
		public function render_content() {

			// Process the palette
			if ( is_array( $this->palette ) ) {
				$palette = implode( '|', $this->palette );
			} else {
				// Default to true.
				$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
			}

			// Support passing show_opacity as string or boolean. Default to true.
			$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';
			if ( isset( $this->label ) && '' !== $this->label ) {
				echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
			}
			// Output the label and description if they were passed in.

			if ( isset( $this->description ) && '' !== $this->description ) {
				echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
			}
			// Begin the output. ?>
            <label>

                <input class="alpha-color-control" type="text"
                       data-show-opacity="<?php echo $show_opacity; ?>"
                       data-palette="<?php echo esc_attr( $palette ); ?>"
                       data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?> />
            </label>
			<?php
		}
	}
endif;


if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WPOPTIN_Background_Image_Upgrade' ) ) :
	class WPOPTIN_Background_Image_Upgrade extends WP_Customize_Control {

		public $type = 'popup';

		public $popup_id = null;

		public $icon = null;


		public function render_content() {
			?>

            <label class="customize-control-title">   <?php echo $this->label; ?></label>
            <div class="attachment-media-view">
                <button type="button"
                        class="wpop-bg-image-upload button-add-media"><?php echo __( 'Select image', 'wpoptin' ) ?></button>
            </div>

            <div class="wpop-bg-image-upgrade-wrap">

                <p><?php echo $this->description; ?></p>

                <p><?php esc_html_e("Use following coupon code to get 27% discount for limited time", 'wpoptin');?><br><code>BFCMOP</code></p>'
                <a href="<?php echo wpop_fs()->get_upgrade_url() ?>"
                   class="wpoptin-upgrade-btn"><?php echo __( 'Upgrade to pro', 'wpoptin' ) ?></a>
            </div>


			<?php
		}
	}
endif;

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WPOPTIN_Position_Upgrade' ) ) :
	class WPOPTIN_Position_Upgrade extends WP_Customize_Control {

		public $type = 'popup';

		public $popup_id = null;

		public $icon = null;


		public function render_content() {
			?>


            <label class="customize-control-title">   <?php echo $this->label; ?></label>
            <span class="customize-inside-control-row customize-inside-positon-control-row">
                <input id="wopop-position-left"
                       class="wopop-position-upgrade-input"
                       name="wopop-position-upgrade-input" value="left"
                       type="radio">
                <label for="wopop-position-left"><?php echo __( 'Left', 'wpoptin' ) ?></label>

                 <div class="wpop-position-upgrade-wrap">
               
                    <p><?php echo $this->description; ?></p>

                    <p><?php esc_html_e("Use following coupon code to get 27% discount for limited time", 'wpoptin');?><br><code>BFCMOP</code></p>'
                    <a href="<?php echo wpop_fs()->get_upgrade_url() ?>"
                       class="wpoptin-upgrade-btn"><?php echo __( 'Upgrade to pro', 'wpoptin' ) ?></a>
                 </div> 
            </span>

            <span class="customize-inside-control-row customize-inside-positon-control-row">
                <input id="wopop-position-right"
                       class="wopop-position-upgrade-input"
                       name="wopop-position-upgrade-input" value="right"
                       type="radio">
                <label for="wopop-position-right"><?php echo __( 'Right', 'wpoptin' ) ?></label>
                 <div class="wpop-position-upgrade-wrap">
               
                    <p><?php echo $this->description; ?></p>

                    <p><?php esc_html_e("Use following coupon code to get 27% discount for limited time", 'wpoptin');?><br><code>BFCMOP</code></p>'
                    <a href="<?php echo wpop_fs()->get_upgrade_url() ?>"
                       class="wpoptin-upgrade-btn"><?php echo __( 'Upgrade to pro', 'wpoptin' ) ?></a>
                 </div> 
            </span>


            <span class="customize-inside-control-row customize-inside-positon-control-row">
                <input id="wopop-position-center"
                       class="wopop-position-upgrade-input"
                       name="wopop-position-upgrade-input" value="bottom_center"
                       type="radio">
                <label for="wopop-position-center"><?php echo __( 'Bottom Center', 'wpoptin' ) ?></label>

                 <div class="wpop-position-upgrade-wrap">
               
                    <p><?php echo $this->description; ?></p>

                    <p><?php esc_html_e("Use following coupon code to get 27% discount for limited time", 'wpoptin');?><br><code>BFCMOP</code></p>'
                    <a href="<?php echo wpop_fs()->get_upgrade_url() ?>"
                       class="wpoptin-upgrade-btn"><?php echo __( 'Upgrade to pro', 'wpoptin' ) ?></a>
                 </div> 
            </span>

			<?php
		}
	}
endif;

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Customize_WPOPTIN_PopUp' ) ) :
	class Customize_WPOPTIN_PopUp extends WP_Customize_Control {

		public $type = 'popup';

		public $popup_id = null;

		public $icon = null;


		public function render_content() {
			?>
            <label class="customize-control-title">   <?php echo $this->label; ?></label>
            <p><?php echo $this->description; ?></p>

            <p><?php esc_html_e("Use following coupon code to get 27% discount for limited time", 'wpoptin');?><br><code>BFCMOP</code></p>'
            <a href="<?php echo wpop_fs()->get_upgrade_url() ?>"
               class="wpoptin-upgrade-btn"><?php echo __( 'Upgrade to pro', 'wpoptin' ) ?></a>


			<?php
		}
	}
endif;