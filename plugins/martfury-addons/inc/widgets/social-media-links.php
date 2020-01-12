<?php
if( ! class_exists('Martfury_Social_Links_Widget') ) {
	class Martfury_Social_Links_Widget extends WP_Widget {
		protected $default;
		protected $socials;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->socials = array(
				'facebook'   => esc_html__( 'Facebook', 'martfury' ),
				'twitter'    => esc_html__( 'Twitter', 'martfury' ),
				'googleplus' => esc_html__( 'Google Plus', 'martfury' ),
				'youtube'    => esc_html__( 'Youtube', 'martfury' ),
				'tumblr'     => esc_html__( 'Tumblr', 'martfury' ),
				'linkedin'   => esc_html__( 'Linkedin', 'martfury' ),
				'pinterest'  => esc_html__( 'Pinterest', 'martfury' ),
				'flickr'     => esc_html__( 'Flickr', 'martfury' ),
				'instagram'  => esc_html__( 'Instagram', 'martfury' ),
				'dribbble'   => esc_html__( 'Dribbble', 'martfury' ),
				'skype'      => esc_html__( 'Skype', 'martfury' ),
				'rss'        => esc_html__( 'RSS', 'martfury' )

			);
			$this->default = array(
				'title' => '',
			);
			foreach ( $this->socials as $k => $v ) {
				$this->default["{$k}_title"] = $v;
				$this->default["{$k}_url"]   = '';
			}

			parent::__construct(
				'social-links-widget',
				esc_html__( 'Martfury - Social Links', 'martfury' ),
				array(
					'classname'   => 'social-links-widget social-links',
					'description' => esc_html__( 'Display links to social media networks.', 'martfury' ),
				),
				array( 'width' => 600, 'height' => 350 )
			);
		}

		/**
		 * Outputs the HTML for this widget.
		 *
		 * @param array $args An array of standard parameters for widgets in this theme
		 * @param array $instance An array of settings for this widget instance
		 *
		 * @return void Echoes it's output
		 */
		function widget( $args, $instance ) {
			$instance = wp_parse_args( $instance, $this->default );

			extract( $args );
			echo $before_widget;

			if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
				echo $before_title . $title . $after_title;
			}

			echo '<div class="social-links-list">';

			foreach ( $this->socials as $social => $label ) {
				if ( ! empty( $instance[ $social . '_url' ] ) ) {
					printf(
						'<a href="%s" class="share-%s tooltip-enable share-social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank"><i class="social social_%s"></i></a>',
						esc_url( $instance[ $social . '_url' ] ),
						esc_attr( $social ),
						esc_attr( $instance[ $social . '_title' ] ),
						esc_attr( $social )
					);
				}
			}

			echo '</div>';

			echo $after_widget;
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @param array $instance
		 *
		 * @return array
		 */
		function form( $instance ) {
			$instance = wp_parse_args( $instance, $this->default );
			?>

            <p>
                <label
                        for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'martfury' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_attr( $instance['title'] ); ?>"/>
            </p>
			<?php
			foreach ( $this->socials as $social => $label ) {
				printf(
					'<div class="mr-recent-box">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
					$label,
					$this->get_field_name( $social . '_url' ),
					esc_html__( 'URL', 'martfury' ),
					$instance[ $social . '_url' ]
				);
			}
		}
	}
}