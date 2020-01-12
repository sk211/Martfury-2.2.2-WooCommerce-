<form method="post" class="search-date">
	<div class="from-date date-box">
		<label for="from">
			<?php esc_html_e( 'From:', 'martfury' ); ?>
		</label>
		<input class="date-pick" type="date" name="start_date" id="from"
			   value="<?php echo esc_attr( date( 'Y-m-d', $start_date ) ); ?>" />
	</div>
	<div class="to-date date-box">
		<label for="to"><?php esc_html_e( 'To:', 'martfury' ); ?></label>
		<input type="date" class="date-pick" name="end_date" id="to"
			   value="<?php echo esc_attr( date( 'Y-m-d', $end_date ) ); ?>" />
	</div>

	<input type="submit" class="btn btn-inverse btn-small"
		   value="<?php esc_html_e( 'Show', 'martfury' ); ?>" />
</form>