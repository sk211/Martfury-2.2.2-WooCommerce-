<?php

if( ! class_exists('Martfury_Currencies_Widget') ) {
	/**
	 * Core class used to implement a Text widget.
	 *
	 * @since 2.8.0
	 *
	 * @see   WP_Widget
	 */
	class Martfury_Currencies_Widget extends WC_Widget {

		/**
		 * Sets up a new Text widget instance.
		 *
		 * @since  2.8.0
		 * @access public
		 */
		public function __construct() {
			$widget_ops  = array(
				'classname'   => 'widget_text mf-currency-widget',
				'description' => esc_html__( 'Shows currency list by WooCommerce Currency Switcher plugin', 'martfury' ),
			);
			$control_ops = array( 'width' => 400, 'height' => 350 );
			parent::__construct( 'martfury-currency', esc_html__( 'Martfury Currency Switcher', 'martfury' ), $widget_ops, $control_ops );

			$this->widget_cssclass    = 'mf-currency-widget';
			$this->widget_description = esc_html__( 'Shows currency list by WooCommerce Currency Switcher plugin', 'martfury' );
			$this->widget_id          = 'martfury_currency_widget';
			$this->widget_name        = esc_html__( 'Martfury - Currency Switcher', 'martfury' );
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Title', 'martfury' ),
				),
			);

			parent::__construct();
		}

		/**
		 * Outputs the content for the current Text widget instance.
		 *
		 * @since  2.8.0
		 * @access public
		 *
		 * @param array $args Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Text widget instance.
		 */
		public function widget( $args, $instance ) {


			echo $args['before_widget'];
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			?>
			<?php
			if ( isset( $instance['title'] ) && $instance['title'] ) {
				echo '<h4 class="widget-title">' . $instance['title'] . '</h4>';
			}
			?>
            <div class="widget-currency">
				<?php
				echo martfury_currency_switcher( true );
				?>
            </div>
			<?php
			echo $args['after_widget'];
		}
	}
}