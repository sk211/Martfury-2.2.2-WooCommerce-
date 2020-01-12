<# if ( data.depth == 0 ) { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Mega Menu Content', 'martfury' ) ?>"
   data-panel="mega"><?php esc_html_e( 'Mega Menu', 'martfury' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Mega Menu Background', 'martfury' ) ?>"
   data-panel="background"><?php esc_html_e( 'Background', 'martfury' ) ?></a>
<div class="separator"></div>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Icon', 'martfury' ) ?>"
   data-panel="icon"><?php esc_html_e( 'Icon', 'martfury' ) ?></a>
<# } else if ( data.depth == 1 ) { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Content', 'martfury' ) ?>"
   data-panel="content"><?php esc_html_e( 'Menu Content', 'martfury' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu General', 'martfury' ) ?>"
   data-panel="general"><?php esc_html_e( 'General', 'martfury' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Icon', 'martfury' ) ?>"
   data-panel="icon"><?php esc_html_e( 'Icon', 'martfury' ) ?></a>
<# } else { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Icon', 'martfury' ) ?>"
   data-panel="icon"><?php esc_html_e( 'Icon', 'martfury' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu General', 'martfury' ) ?>"
   data-panel="general_2"><?php esc_html_e( 'General', 'martfury' ) ?></a>
<# } #>
