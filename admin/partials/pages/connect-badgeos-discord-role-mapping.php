<?php

$ets_badgeos_ranks = badgeos_get_ranks();


$connect_badgeos_default_role = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_default_role_id' ) ) );
?>
<div class="notice notice-warning ets-notice">
	<p><i class='fas fa-info'></i> <?php esc_html_e( 'Drag and Drop the Discord Roles over to the badgeos Ranks', 'connect-badgeos-to-discord' ); ?></p>
</div>
<div class="notice notice-warning ets-notice">
  <p><i class='fas fa-info'></i> <?php esc_html_e( 'Note: only published Ranks are displayed', 'connect-badgeos-to-discord' ); ?></p>
</div>

<div class="row-container">
  <div class="ets-column badgeos-discord-roles-col">
	<h2><?php esc_html_e( 'Discord Roles', 'connect-badgeos-to-discord' ); ?></h2>
	<hr>
	<div class="badgeos-discord-roles">
	  <span class="spinner"></span>
	</div>
  </div>
  <div class="ets-column">
	<h2><?php esc_html_e( 'Ranks', 'connect-badgeos-to-discord' ); ?></h2>
	<hr>
	<div class="badgeos-discord-rank-type">
	<?php
	if ( is_array( $ets_badgeos_ranks ) ) {
		foreach ( $ets_badgeos_ranks as $ets_badgeos_rank ) {

			?>
		  <div class="makeMeDroppable" data-badgeos_rank_type_id="<?php echo esc_attr( $ets_badgeos_rank->ID ); ?>" ><span><?php echo esc_html( $ets_badgeos_rank->post_title ); ?></span></div>
			<?php

		}
	}
	?>
	</div>
  </div>
</div>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
 <input type="hidden" name="action" value="badgeos_discord_save_role_mapping">
 <input type="hidden" name="current_url" value="<?php echo esc_url( ets_badgeos_discord_get_current_screen_url() ); ?>">   
  <table class="form-table" role="presentation">
	<tbody>
	  <tr>
		<th scope="row"><label for="badgeos-defaultRole"><?php esc_html_e( 'Default Role', 'connect-badgeos-to-discord' ); ?></label></th>
		<td>
		  <?php wp_nonce_field( 'badgeos_discord_role_mappings_nonce', 'ets_badgeos_discord_role_mappings_nonce' ); ?>
		  <input type="hidden" id="selected_default_role" value="<?php echo esc_attr( $connect_badgeos_default_role ); ?>">
		  <select id="badgeos-defaultRole" name="badgeos_defaultRole">
			<option value="none"><?php esc_html_e( '-None-', 'connect-badgeos-to-discord' ); ?></option>
		  </select>
		  <p class="description"><?php esc_html_e( 'This Role will be assigned to all Ranks', 'connect-badgeos-to-discord' ); ?></p>
		</td>
	  </tr>        

	</tbody>
  </table>
	<br>
  <div class="mapping-json">
	<textarea id="ets_badgeos_mapping_json_val" name="ets_badgeos_discord_role_mapping">
	<?php
	if ( isset( $ets_discord_roles ) ) {
		echo stripslashes( esc_html( $ets_discord_roles ) );}
	?>
	</textarea>
  </div>
  <div class="bottom-btn">
	<button type="submit" name="submit" value="ets_submit" class="ets-submit ets-btn-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'connect-badgeos-to-discord' ); ?>
	</button>
	<button id="revertMapping" name="flush" class="ets-submit ets-btn-submit ets-bg-red">
	  <?php esc_html_e( 'Flush Mappings', 'connect-badgeos-to-discord' ); ?>
	</button>
  </div>
</form>
