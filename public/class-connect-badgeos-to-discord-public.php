<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Badgeos_To_Discord
 * @subpackage Connect_Badgeos_To_Discord/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Connect_Badgeos_To_Discord
 * @subpackage Connect_Badgeos_To_Discord/public
 * @author     ExpressTech Softwares Solutions Pvt Ltd <contact@expresstechsoftwares.com>
 */
class Connect_Badgeos_To_Discord_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The single object Connect_Badgeos_To_Discord_Public
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var Connect_Badgeos_To_Discord_Public
	 */
	private static $instance;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Singleton pattern
	 *
	 * @since    1.0.0
	 * @param       string $plugin_name       The name of the plugin.
	 * @param       string $version    The version of this plugin.
	 * @return      object    $instance   The instance of the Connect_Badgeos_To_Discord_Public class
	 */
	public static function get_badgeos_discord_public_instance( $plugin_name, $version ) {

		if ( ! self::$instance ) {
			self::$instance = new Connect_Badgeos_To_Discord_Public( $plugin_name, $version );

		}
		return self::$instance;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Badgeos_To_Discord_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Badgeos_To_Discord_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$min_css = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/connect-badgeos-to-discord-public' . $min_css . '.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Badgeos_To_Discord_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Badgeos_To_Discord_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$min_js = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/connect-badgeos-to-discord-public' . $min_js . '.js', array( 'jquery' ), $this->version, false );
		$script_params = array(
			'admin_ajax'                => admin_url( 'admin-ajax.php' ),
			'permissions_const'         => CONNECT_BADGEOS_TO_DISCORD_OAUTH_SCOPES,
			'is_admin'                  => is_admin(),
			'ets_badgeos_discord_nonce' => wp_create_nonce( 'ets-badgeos-discord-ajax-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'etsBadgeOSParams', $script_params );

	}

	/**
	 * Add button to make connection in between user and discord
	 *
	 * @param NONE
	 * @return STRING
	 */
	public function ets_badgeos_discord_add_connect_discord_button() {
		$user_id = sanitize_text_field( trim( get_current_user_id() ) );

		$access_token                                   = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_access_token', true ) ) );
		$_ets_badgeos_discord_username                  = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_username', true ) ) );
		$ets_badgeos_discord_connect_button_bg_color    = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_connect_button_bg_color' ) ) );
		$ets_badgeos_discord_disconnect_button_bg_color = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_disconnect_button_bg_color' ) ) );
		$ets_badgeos_discord_disconnect_button_text     = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_disconnect_button_text' ) ) );
		$ets_badgeos_discord_loggedin_button_text       = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_loggedin_button_text' ) ) );
		$default_role                                   = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_default_role_id' ) ) );
		$ets_badgeos_discord_role_mapping               = json_decode( get_option( 'ets_badgeos_discord_role_mapping' ), true );
		$all_roles                                      = unserialize( get_option( 'ets_badgeos_discord_all_roles' ) );
		$roles_color                                    = unserialize( get_option( 'ets_badgeos_discord_roles_color' ) );
		$user_ranks                                     = ets_badgeos_discord_get_user_ranks_ids( $user_id );
		$mapped_role_name                               = '';
		if ( is_array( $user_ranks ) && is_array( $all_roles ) && is_array( $ets_badgeos_discord_role_mapping ) ) {
			foreach ( $user_ranks as $key => $user_rank_id ) {
				if ( array_key_exists( 'badgeos_rank_type_id_' . $user_rank_id, $ets_badgeos_discord_role_mapping ) ) {

					$mapped_role_id = $ets_badgeos_discord_role_mapping[ 'badgeos_rank_type_id_' . $user_rank_id ];

					if ( array_key_exists( $mapped_role_id, $all_roles ) ) {
						$mapped_role_name .= '<span> <i style="background-color:#' . dechex( $roles_color[ $mapped_role_id ] ) . '"></i>' . $all_roles[ $mapped_role_id ] . '</span>';
					}
				}
			}
		}

		$default_role_name = '';
		if ( is_array( $all_roles ) ) {
			if ( $default_role != 'none' && array_key_exists( $default_role, $all_roles ) ) {
				$default_role_name = '<span><i style="background-color:#' . dechex( $roles_color[ $default_role ] ) . '"></i> ' . $all_roles[ $default_role ] . '</span>';
			}
		}

			$restrictcontent_discord = '';
		if ( badgeos_discord_check_saved_settings_status() ) {

			if ( $access_token ) {
				$discord_user_id     = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_user_id', true ) ) );
				$discord_user_avatar = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_avatar', true ) ) );

				$disconnect_btn_bg_color  = 'style="background-color:' . $ets_badgeos_discord_disconnect_button_bg_color . '"';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<h2>' . esc_html__( 'Discord connection', 'connect-badgeos-and-discord' ) . '</h2>';
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<a href="#" class="ets-btn badgeos-discord-btn-disconnect" ' . $disconnect_btn_bg_color . ' id="badgeos-discord-disconnect-discord" data-user-id="' . esc_attr( $user_id ) . '">' . esc_html( $ets_badgeos_discord_disconnect_button_text ) . Connect_Badgeos_To_Discord::get_discord_logo_white() . '</a>';
				$restrictcontent_discord .= '<span class="ets-spinner"></span>';
				$restrictcontent_discord .= '<p>' . esc_html__( sprintf( 'Connected account: %s', $_ets_badgeos_discord_username ), 'connect-badgeos-to-discord' ) . '</p>';
				$restrictcontent_discord  = ets_badgeos_discord_get_user_avatar( $discord_user_id, $discord_user_avatar, $restrictcontent_discord );
				$restrictcontent_discord  = ets_badgeos_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord );
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord .= '</div>';

			} elseif ( ( ets_badgeos_discord_get_user_ranks_ids( $user_id ) && $mapped_role_name )
								|| ( ets_badgeos_discord_get_user_ranks_ids( $user_id ) && ! $mapped_role_name && $default_role_name )
								) {

				$connect_btn_bg_color     = 'style="background-color:' . $ets_badgeos_discord_connect_button_bg_color . '"';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<h3>' . esc_html__( 'Discord connection', 'connect-badgeos-to-discord' ) . '</h3>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<a href="?action=badgeos-discord-login" class="badgeos-discord-btn-connect ets-btn" ' . $connect_btn_bg_color . ' >' . esc_html( $ets_badgeos_discord_loggedin_button_text ) . Connect_Badgeos_To_Discord::get_discord_logo_white() . '</a>';
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord  = ets_badgeos_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord );

				$restrictcontent_discord .= '</div>';

			}
		}
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );

		return $restrictcontent_discord;
	}

	/**
	 * Allow data protocol.
	 *
	 * @since    1.0.0
	 *
	 * @param string[] $protocols Array of allowed protocols.
	 * @return array
	 */
	public function ets_badgeos_discord_allow_data_protocol( $protocols ) {

		$protocols[] = 'data';
		return $protocols;
	}

	/**
	 * For authorization process call discord API
	 *
	 * @param NONE
	 * @return OBJECT REST API response
	 */
	public function ets_badgeos_discord_api_callback() {
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'badgeos-discord-login' ) {
				$params                    = array(
					'client_id'     => sanitize_text_field( trim( get_option( 'ets_badgeos_discord_client_id' ) ) ),
					'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_badgeos_discord_redirect_url' ) ) ),
					'response_type' => 'code',
					'scope'         => 'identify email connections guilds guilds.join',
				);
				$discord_authorise_api_url = CONNECT_BADGEOS_TO_DISCORD_API_URL . 'oauth2/authorize?' . http_build_query( $params );

				wp_redirect( $discord_authorise_api_url, 302, get_site_url() );
				exit;
			}

			if ( isset( $_GET['code'] ) && isset( $_GET['via'] ) && $_GET['via'] == 'connect-badgeos-discord-addon' ) {
				$code     = sanitize_text_field( trim( $_GET['code'] ) );
				$response = $this->create_discord_auth_token( $code, $user_id );

				if ( ! empty( $response ) && ! is_wp_error( $response ) ) {
					$res_body              = json_decode( wp_remote_retrieve_body( $response ), true );
					$discord_exist_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_user_id', true ) ) );
					if ( is_array( $res_body ) ) {

						if ( array_key_exists( 'access_token', $res_body ) ) {

							$access_token = sanitize_text_field( trim( $res_body['access_token'] ) );
							update_user_meta( $user_id, '_ets_badgeos_discord_access_token', $access_token );
							if ( array_key_exists( 'refresh_token', $res_body ) ) {
								$refresh_token = sanitize_text_field( trim( $res_body['refresh_token'] ) );
								update_user_meta( $user_id, '_ets_badgeos_discord_refresh_token', $refresh_token );
							}
							if ( array_key_exists( 'expires_in', $res_body ) ) {
								$expires_in = $res_body['expires_in'];
								$date       = new DateTime();
								$date->add( DateInterval::createFromDateString( '' . $expires_in . ' seconds' ) );
								$token_expiry_time = $date->getTimestamp();
								update_user_meta( $user_id, '_ets_badgeos_discord_expires_in', $token_expiry_time );
							}
							$user_body = $this->get_discord_current_user( $access_token );

							if ( is_array( $user_body ) && array_key_exists( 'discriminator', $user_body ) ) {
								$discord_user_number           = $user_body['discriminator'];
								$discord_user_name             = $user_body['username'];
								$discord_user_name_with_number = $discord_user_name . '#' . $discord_user_number;
								$discord_user_avatar           = $user_body['avatar'];
								update_user_meta( $user_id, '_ets_badgeos_discord_username', $discord_user_name_with_number );
								update_user_meta( $user_id, '_ets_badgeos_discord_avatar', $discord_user_avatar );
							}
							if ( is_array( $user_body ) && array_key_exists( 'id', $user_body ) ) {
								$_ets_badgeos_discord_user_id = sanitize_text_field( trim( $user_body['id'] ) );
								if ( $discord_exist_user_id === $_ets_badgeos_discord_user_id ) {
									$user_ranks = map_deep( ets_badgeos_discord_get_user_ranks_ids( $user_id ), 'sanitize_text_field' );
									if ( is_array( $user_ranks ) ) {
										foreach ( $user_ranks as $rank_id ) {
											$_ets_badgeos_discord_role_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_role_id_for_' . $rank_id, true ) ) );
											if ( ! empty( $_ets_badgeos_discord_role_id ) && $_ets_badgeos_discord_role_id != 'none' ) {
												// $this->delete_discord_role( $user_id, $_ets_badgeos_discord_role_id );
											}
										}
									}
								}
								update_user_meta( $user_id, '_ets_badgeos_discord_user_id', $_ets_badgeos_discord_user_id );
								$this->add_discord_member_in_guild( $_ets_badgeos_discord_user_id, $user_id, $access_token );
							}
						} else {

						}
					} else {

					}
				}
			}
		}
	}

	/**
	 * Create authentication token for discord API
	 *
	 * @param STRING $code
	 * @param INT    $user_id
	 * @return OBJECT API response
	 */
	public function create_discord_auth_token( $code, $user_id ) {
		if ( ! is_user_logged_in() ) {

			wp_send_json_error( 'Unauthorized user', 401 );
			exit();

		}
		$user_ranks = sanitize_text_field( ets_badgeos_discord_get_user_ranks_ids( $user_id ) );
		if ( $user_ranks === null ) {
			return;
		}
		$response              = '';
		$refresh_token         = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_refresh_token', true ) ) );
		$token_expiry_time     = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_expires_in', true ) ) );
		$discord_token_api_url = CONNECT_BADGEOS_TO_DISCORD_API_URL . 'oauth2/token';
		if ( $refresh_token ) {
			$date              = new DateTime();
			$current_timestamp = $date->getTimestamp();
			if ( $current_timestamp > $token_expiry_time ) {
				$args     = array(
					'method'  => 'POST',
					'headers' => array(
						'Content-Type' => 'application/x-www-form-urlencoded',
					),
					'body'    => array(
						'client_id'     => sanitize_text_field( trim( get_option( 'ets_badgeos_discord_client_id' ) ) ),
						'client_secret' => sanitize_text_field( trim( get_option( 'ets_badgeos_discord_client_secret' ) ) ),
						'grant_type'    => 'refresh_token',
						'refresh_token' => $refresh_token,
						'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_badgeos_discord_redirect_url' ) ) ),
						'scope'         => CONNECT_BADGEOS_TO_DISCORD_OAUTH_SCOPES,
					),
				);
				$response = wp_remote_post( $discord_token_api_url, $args );
				ets_badgeos_discord_log_api_response( $user_id, $discord_token_api_url, $args, $response );
				if ( ets_badgeos_discord_check_api_errors( $response ) ) {
					$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
					Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				}
			}
		} else {
			$args     = array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/x-www-form-urlencoded',
				),
				'body'    => array(
					'client_id'     => sanitize_text_field( trim( get_option( 'ets_badgeos_discord_client_id' ) ) ),
					'client_secret' => sanitize_text_field( trim( get_option( 'ets_badgeos_discord_client_secret' ) ) ),
					'grant_type'    => 'authorization_code',
					'code'          => $code,
					'redirect_uri'  => sanitize_text_field( trim( get_option( 'ets_badgeos_discord_redirect_url' ) ) ),
					'scope'         => CONNECT_BADGEOS_TO_DISCORD_OAUTH_SCOPES,
				),
			);
			$response = wp_remote_post( $discord_token_api_url, $args );
			ets_badgeos_discord_log_api_response( $user_id, $discord_token_api_url, $args, $response );
			if ( ets_badgeos_discord_check_api_errors( $response ) ) {
				$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			}
		}
		return $response;
	}

	/**
	 * Get Discord user details from API
	 *
	 * @param STRING $access_token
	 * @return OBJECT REST API response
	 */
	public function get_discord_current_user( $access_token ) {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$user_id = get_current_user_id();

		$discord_cuser_api_url = CONNECT_BADGEOS_TO_DISCORD_API_URL . 'users/@me';
		$param                 = array(
			'headers' => array(
				'Content-Type'  => 'application/x-www-form-urlencoded',
				'Authorization' => 'Bearer ' . $access_token,
			),
		);
		$user_response         = wp_remote_get( $discord_cuser_api_url, $param );
		ets_badgeos_discord_log_api_response( $user_id, $discord_cuser_api_url, $param, $user_response );

		$response_arr = json_decode( wp_remote_retrieve_body( $user_response ), true );
		Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
		$user_body = json_decode( wp_remote_retrieve_body( $user_response ), true );
		return $user_body;

	}

	/**
	 * Add new member into discord guild
	 *
	 * @param INT    $_ets_badgeos_discord_user_id
	 * @param INT    $user_id
	 * @param STRING $access_token
	 * @return NONE
	 */
	public function add_discord_member_in_guild( $_ets_badgeos_discord_user_id, $user_id, $access_token ) {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$user_ranks = map_deep( ets_badgeos_discord_get_user_ranks_ids( $user_id ), 'sanitize_text_field' );
		if ( $user_ranks !== null ) {
			// It is possible that we may exhaust API rate limit while adding members to guild, so handling off the job to queue.
			as_schedule_single_action( ets_badgeos_discord_get_random_timestamp( ets_badgeos_discord_get_highest_last_attempt_timestamp() ), 'ets_badgeos_discord_as_handle_add_member_to_guild', array( $_ets_badgeos_discord_user_id, $user_id, $access_token ), BADGEOS_DISCORD_AS_GROUP_NAME );
		}
	}

	/**
	 * Method to add new members to discord guild.
	 *
	 * @param INT    $_ets_badgeos_discord_user_id
	 * @param INT    $user_id
	 * @param STRING $access_token
	 * @return NONE
	 */
	public function ets_badgeos_discord_as_handler_add_member_to_guild( $_ets_badgeos_discord_user_id, $user_id, $access_token ) {
		// Since we using a queue to delay the API call, there may be a condition when a member is delete from DB. so put a check.
		if ( get_userdata( $user_id ) === false ) {
			return;
		}
		$guild_id                         = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_server_id' ) ) );
		$discord_bot_token                = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_bot_token' ) ) );
		$default_role                     = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_default_role_id' ) ) );
		$ets_badgeos_discord_role_mapping = json_decode( get_option( 'ets_badgeos_discord_role_mapping' ), true );
		$discord_role                     = '';
		$discord_roles                    = array();
		$ranks_user                       = map_deep( ets_badgeos_discord_get_user_ranks_ids( $user_id ), 'sanitize_text_field' );

		$ets_badgeos_discord_send_welcome_dm = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_send_welcome_dm' ) ) );
		if ( is_array( $ranks_user ) ) {
			foreach ( $ranks_user as $rank_id ) {

				if ( is_array( $ets_badgeos_discord_role_mapping ) && array_key_exists( 'badgeos_rank_type_id_' . $rank_id, $ets_badgeos_discord_role_mapping ) ) {
					$discord_role = sanitize_text_field( trim( $ets_badgeos_discord_role_mapping[ 'badgeos_rank_type_id_' . $rank_id ] ) );
					array_push( $discord_roles, $discord_role );
					update_user_meta( $user_id, '_ets_badgeos_discord_role_id_for_' . $rank_id, $discord_role );
				}
			}
		}

		$guilds_memeber_api_url = CONNECT_BADGEOS_TO_DISCORD_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_badgeos_discord_user_id;
		$guild_args             = array(
			'method'  => 'PUT',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
			'body'    => json_encode(
				array(
					'access_token' => $access_token,
				)
			),
		);
		$guild_response         = wp_remote_post( $guilds_memeber_api_url, $guild_args );

		ets_badgeos_discord_log_api_response( $user_id, $guilds_memeber_api_url, $guild_args, $guild_response );
		if ( ets_badgeos_discord_check_api_errors( $guild_response ) ) {

			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );
			Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			// this should be catch by Action schedule failed action.
			throw new Exception( 'Failed in function ets_badgeos_discord_as_handler_add_member_to_guild' );
		}

		foreach ( $discord_roles as $discord_role ) {

			if ( $discord_role && $discord_role != 'none' && isset( $user_id ) ) {
				$this->put_discord_role_api( $user_id, $discord_role );

			}
		}

		if ( $default_role && $default_role != 'none' && isset( $user_id ) ) {
			update_user_meta( $user_id, '_ets_badgeos_discord_last_default_role', $default_role );
			$this->put_discord_role_api( $user_id, $default_role );
		}
		if ( empty( get_user_meta( $user_id, '_ets_badgeos_discord_join_date', true ) ) ) {
			update_user_meta( $user_id, '_ets_badgeos_discord_join_date', current_time( 'Y-m-d H:i:s' ) );
		}

		// Send welcome message.
		if ( $ets_badgeos_discord_send_welcome_dm == true ) {
			as_schedule_single_action( ets_badgeos_discord_get_random_timestamp( ets_badgeos_discord_get_highest_last_attempt_timestamp() ), 'ets_badgeos_discord_as_send_dm', array( $user_id, $ranks_user, 'welcome' ), BADGEOS_DISCORD_AS_GROUP_NAME );
		}
	}


	/**
	 * API call to change discord user role
	 *
	 * @param INT  $user_id
	 * @param INT  $role_id
	 * @param BOOL $is_schedule
	 * @return object API response
	 */
	public function put_discord_role_api( $user_id, $role_id, $is_schedule = true ) {
		if ( $is_schedule ) {
			as_schedule_single_action( ets_badgeos_discord_get_random_timestamp( ets_badgeos_discord_get_highest_last_attempt_timestamp() ), 'ets_badgeos_discord_as_schedule_member_put_role', array( $user_id, $role_id, $is_schedule ), BADGEOS_DISCORD_AS_GROUP_NAME );
		} else {
			$this->ets_badgeos_discord_as_handler_put_member_role( $user_id, $role_id, $is_schedule );
		}
	}

	/**
	 * Action Schedule handler for mmeber change role discord.
	 *
	 * @param INT  $user_id
	 * @param INT  $role_id
	 * @param BOOL $is_schedule
	 * @return object API response
	 */
	public function ets_badgeos_discord_as_handler_put_member_role( $user_id, $role_id, $is_schedule ) {
		$access_token                 = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_access_token', true ) ) );
		$guild_id                     = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_server_id' ) ) );
		$_ets_badgeos_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_user_id', true ) ) );
		$discord_bot_token            = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_bot_token' ) ) );
		$discord_change_role_api_url  = CONNECT_BADGEOS_TO_DISCORD_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_badgeos_discord_user_id . '/roles/' . $role_id;

		if ( $access_token && $_ets_badgeos_discord_user_id ) {
			$param = array(
				'method'  => 'PUT',
				'headers' => array(
					'Content-Type'   => 'application/json',
					'Authorization'  => 'Bot ' . $discord_bot_token,
					'Content-Length' => 0,
				),
			);

			$response = wp_remote_get( $discord_change_role_api_url, $param );

			ets_badgeos_discord_log_api_response( $user_id, $discord_change_role_api_url, $param, $response );
			if ( ets_badgeos_discord_check_api_errors( $response ) ) {
				$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( $is_schedule ) {
					// this exception should be catch by action scheduler.
					throw new Exception( 'Failed in function ets_badgeos_discord_as_handler_put_member_role' );
				}
			}
		}
	}

	/**
	 * Discord DM a member using bot.
	 *
	 * @param INT       $user_id
	 * @param STRING    $ranks_user
	 * @param ARRAY|INT $rank_user (Array of ranks | achievement_id).
	 * @param STRING    $type (warning|expired).
	 * @param INT       $points Achievement points awarded.
	 */
	public function ets_badgeos_discord_handler_send_dm( $user_id, $ranks_user, $type = 'warning', $points = '' ) {
		$discord_user_id   = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_user_id', true ) ) );
		$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_bot_token' ) ) );

		// Check if DM channel is already created for the user.
		$user_dm = get_user_meta( $user_id, '_ets_badgeos_discord_dm_channel', true );

		if ( ! isset( $user_dm['id'] ) || $user_dm === false || empty( $user_dm ) ) {
			$this->ets_badgeos_discord_create_member_dm_channel( $user_id );
			$user_dm       = get_user_meta( $user_id, '_ets_badgeos_discord_dm_channel', true );
			$dm_channel_id = $user_dm['id'];
		} else {
			$dm_channel_id = $user_dm['id'];
		}

		if ( $type == 'welcome' ) {
			$ets_badgeos_discord_welcome_message = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_welcome_message' ) ) );
			$message                             = ets_badgeos_discord_get_formatted_welcome_dm( $user_id, $ranks_user, $ets_badgeos_discord_welcome_message );
		}

		if ( $type == 'award_rank' ) {
			$ets_badgeos_discord_award_rank_message = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_award_rank_message' ) ) );
			$message                                = ets_badgeos_discord_get_formatted_award_rank_dm( $user_id, $ranks_user, $ets_badgeos_discord_award_rank_message );
		}

		if ( $type == 'earn_achievement' ) {
			$ets_badgeos_discord_earned_achievement_message = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_earned_achievement_message' ) ) );
			$message                                        = ets_badgeos_discord_get_formatted_earned_achievement_dm( $user_id, $ranks_user, $ets_badgeos_discord_earned_achievement_message );
		}
		if ( $type == 'earn_points' ) {
			$ets_badgeos_discord_award_user_points_message = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_award_user_points_message' ) ) );
			$message                                       = ets_badgeos_discord_get_formatted_earned_points_dm( $user_id, $ranks_user, $ets_badgeos_discord_award_user_points_message );
		}

		$creat_dm_url = CONNECT_BADGEOS_TO_DISCORD_API_URL . '/channels/' . $dm_channel_id . '/messages';

		$dm_args = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
			'body'    => json_encode(
				array(
					'content' => sanitize_text_field( trim( wp_unslash( $message ) ) ),
				)
			),
		);

			$dm_response = wp_remote_post( $creat_dm_url, $dm_args );
			ets_badgeos_discord_log_api_response( $user_id, $creat_dm_url, $dm_args, $dm_response );
			$dm_response_body = json_decode( wp_remote_retrieve_body( $dm_response ), true );
		if ( ets_badgeos_discord_check_api_errors( $dm_response ) ) {
				Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $dm_response_body, $user_id, debug_backtrace()[0] );
			// this should be catch by Action schedule failed action.
			throw new Exception( 'Failed in function ets_badgeos_discord_handler_send_dm' );
		}
	}

	/**
	 * Create DM channel for a give user_id
	 *
	 * @param INT $user_id
	 * @return MIXED
	 */
	public function ets_badgeos_discord_create_member_dm_channel( $user_id ) {
		$discord_user_id       = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_user_id', true ) ) );
		$discord_bot_token     = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_bot_token' ) ) );
		$create_channel_dm_url = CONNECT_BADGEOS_TO_DISCORD_API_URL . '/users/@me/channels';
		$dm_channel_args       = array(
			'method'  => 'POST',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
			'body'    => json_encode(
				array(
					'recipient_id' => $discord_user_id,
				)
			),
		);

		$created_dm_response = wp_remote_post( $create_channel_dm_url, $dm_channel_args );
		ets_badgeos_discord_log_api_response( $user_id, $create_channel_dm_url, $dm_channel_args, $created_dm_response );
		$response_arr = json_decode( wp_remote_retrieve_body( $created_dm_response ), true );

		if ( is_array( $response_arr ) && ! empty( $response_arr ) ) {
			// check if there is error in create dm response
			if ( array_key_exists( 'code', $response_arr ) || array_key_exists( 'error', $response_arr ) ) {
				Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( ets_badgeos_discord_check_api_errors( $created_dm_response ) ) {
					// this should be catch by Action schedule failed action.
					throw new Exception( 'Failed in function ets_badgeos_discord_create_member_dm_channel' );
				}
			} else {
				update_user_meta( $user_id, '_ets_badgeos_discord_dm_channel', $response_arr );
			}
		}
		return $response_arr;
	}

	/**
	 * Schedule delete discord role for a User
	 *
	 * @param INT  $user_id
	 * @param INT  $ets_badgeos_discord_role_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function delete_discord_role( $user_id, $ets_badgeos_discord_role_id, $is_schedule = true ) {
		if ( $is_schedule ) {
			as_schedule_single_action( ets_badgeos_discord_get_random_timestamp( ets_badgeos_discord_get_highest_last_attempt_timestamp() ), 'ets_badgeos_discord_as_schedule_delete_role', array( $user_id, $ets_badgeos_discord_role_id, $is_schedule ), BADGEOS_DISCORD_AS_GROUP_NAME );
		} else {
			$this->ets_badgeos_discord_as_handler_delete_memberrole( $user_id, $ets_badgeos_discord_role_id, $is_schedule );
		}
	}

	/**
	 * Action Schedule handler to process delete role of a User.
	 *
	 * @param INT  $user_id
	 * @param INT  $ets_badgeos_discord_role_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function ets_badgeos_discord_as_handler_delete_memberrole( $user_id, $ets_badgeos_discord_role_id, $is_schedule = true ) {

		$guild_id                     = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_server_id' ) ) );
		$_ets_badgeos_discord_user_id = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_user_id', true ) ) );
		$discord_bot_token            = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_bot_token' ) ) );
		$discord_delete_role_api_url  = CONNECT_BADGEOS_TO_DISCORD_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_badgeos_discord_user_id . '/roles/' . $ets_badgeos_discord_role_id;
		if ( $_ets_badgeos_discord_user_id ) {
			$param = array(
				'method'  => 'DELETE',
				'headers' => array(
					'Content-Type'   => 'application/json',
					'Authorization'  => 'Bot ' . $discord_bot_token,
					'Content-Length' => 0,
				),
			);

			$response = wp_remote_request( $discord_delete_role_api_url, $param );
			ets_badgeos_discord_log_api_response( $user_id, $discord_delete_role_api_url, $param, $response );
			if ( ets_badgeos_discord_check_api_errors( $response ) ) {
				$response_arr = json_decode( wp_remote_retrieve_body( $response ), true );
				Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
				if ( $is_schedule ) {
					// this exception should be catch by action scheduler.
					throw new Exception( 'Failed in function ets_badgeos_discord_as_handler_delete_memberrole' );
				}
			}
			return $response;
		}
	}

	/**
	 * Disconnect user from discord, and , if the case, kick Users on disconnect
	 *
	 * @param NONE
	 * @return OBJECT JSON response
	 */
	public function ets_badgeos_discord_disconnect_from_discord() {

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}

		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['ets_badgeos_discord_nonce'], 'ets-badgeos-discord-ajax-nonce' ) ) {
				wp_send_json_error( 'You do not have sufficient rights', 403 );
				exit();
		}
		$user_id              = sanitize_text_field( trim( $_POST['user_id'] ) );
		$kick_upon_disconnect = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_kick_upon_disconnect' ) ) );
		if ( $user_id ) {
			delete_user_meta( $user_id, '_ets_badgeos_discord_access_token' );
			delete_user_meta( $user_id, '_ets_badgeos_discord_refresh_token' );
			$user_roles = ets_badgeos_discord_get_user_roles( $user_id );
			if ( $kick_upon_disconnect ) {

				if ( is_array( $user_roles ) ) {
					foreach ( $user_roles as $user_role ) {
						$this->delete_discord_role( $user_id, $user_role );
					}
				}
			} else {
				$this->delete_member_from_guild( $user_id, false );
			}
		}
		$event_res = array(
			'status'  => 1,
			'message' => 'Successfully disconnected',
		);
		wp_send_json( $event_res );

		exit();
	}

	/**
	 * Schedule delete existing user from guild
	 *
	 * @param INT  $user_id
	 * @param BOOL $is_schedule
	 * @param NONE
	 */
	public function delete_member_from_guild( $user_id, $is_schedule = true ) {
		if ( $is_schedule && isset( $user_id ) ) {

			as_schedule_single_action( ets_badgeos_discord_get_random_timestamp( ets_badgeos_discord_get_highest_last_attempt_timestamp() ), 'ets_badgeos_discord_as_schedule_delete_member', array( $user_id, $is_schedule ), BADGEOS_DISCORD_AS_GROUP_NAME );
		} else {
			if ( isset( $user_id ) ) {
				$this->ets_badgeos_discord_as_handler_delete_member_from_guild( $user_id, $is_schedule );
			}
		}
	}

	/**
	 * AS Handling member delete from guild
	 *
	 * @param INT  $user_id
	 * @param BOOL $is_schedule
	 * @return OBJECT API response
	 */
	public function ets_badgeos_discord_as_handler_delete_member_from_guild( $user_id, $is_schedule ) {
		$guild_id                      = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_server_id' ) ) );
		$discord_bot_token             = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_bot_token' ) ) );
		$_ets_badgeos_discord_user_id  = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_user_id', true ) ) );
		$guilds_delete_memeber_api_url = CONNECT_BADGEOS_TO_DISCORD_API_URL . 'guilds/' . $guild_id . '/members/' . $_ets_badgeos_discord_user_id;
		$guild_args                    = array(
			'method'  => 'DELETE',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);
		$guild_response                = wp_remote_post( $guilds_delete_memeber_api_url, $guild_args );

		ets_badgeos_discord_log_api_response( $user_id, $guilds_delete_memeber_api_url, $guild_args, $guild_response );
		if ( ets_badgeos_discord_check_api_errors( $guild_response ) ) {
			$response_arr = json_decode( wp_remote_retrieve_body( $guild_response ), true );
			Connect_Badgeos_To_Discord_Logs::write_api_response_logs( $response_arr, $user_id, debug_backtrace()[0] );
			if ( $is_schedule ) {
				// this exception should be catch by action scheduler.
				throw new Exception( 'Failed in function ets_badgeos_discord_as_handler_delete_member_from_guild' );
			}
		}

		/*Delete all usermeta related to discord connection*/
		ets_badgeos_discord_remove_usermeta( $user_id );

	}

	/**
	 * Sends Discord message  when a rank is awarded.
	 *
	 * @param $user_id
	 * @param $achievement_id
	 * @param $this_trigger
	 * @param $site_id
	 * @param $args
	 * @param $entry_id
	 *
	 * @return none
	 */
	public function ets_badgeos_discord_badgeos_after_award_rank( $user_id, $rank_id, $rank_type, $credit_id, $credit_amount, $admin_id, $this_trigger, $rank_entry_id = 0 ) {

		$ets_badgeos_discord_send_award_rank_dm = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_send_award_rank_dm' ) ) );
		$access_token                           = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_access_token', true ) ) );
		// $refresh_token         = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_refresh_token', true ) ) );

		if ( $access_token && isset( $user_id ) && isset( $rank_id ) && $ets_badgeos_discord_send_award_rank_dm == true ) {
			as_schedule_single_action( ets_badgeos_discord_get_random_timestamp( ets_badgeos_discord_get_highest_last_attempt_timestamp() ), 'ets_badgeos_discord_as_send_dm', array( $user_id, $rank_id, 'award_rank' ), BADGEOS_DISCORD_AS_GROUP_NAME );
		}

	}

	/**
	 * Remove Rank's role.
	 *
	 * @param INT $user_id
	 * @param INT $rank_id
	 * @param INT $entry_id
	 */
	public function ets_badgeos_discord_badgeos_after_revoke_rank( $user_id, $rank_id, $entry_id ) {

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$access_token                     = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_access_token', true ) ) );
		$ets_badgeos_discord_role_mapping = json_decode( get_option( 'ets_badgeos_discord_role_mapping' ), true );
		$all_roles                        = unserialize( get_option( 'ets_badgeos_discord_all_roles' ) );

		if ( $access_token && $rank_id && is_array( $all_roles ) && is_array( $ets_badgeos_discord_role_mapping ) ) {

			if ( array_key_exists( 'badgeos_rank_type_id_' . $rank_id, $ets_badgeos_discord_role_mapping ) ) {

				$old_role_id = $ets_badgeos_discord_role_mapping[ 'badgeos_rank_type_id_' . $rank_id ];
				$this->delete_discord_role( $user_id, $old_role_id );
			}
		}
	}

	/**
	 * Send DM earned achievement.
	 *
	 * @param INT   $user_id
	 * @param INT   $achievement_id
	 * @param INT   $this_trigger
	 * @param INT   $site_id
	 * @param ARRAY $args
	 * @param INT   $entry_id
	 */
	public function ets_badgeos_discord_badgeos_award_achievement( $user_id, $achievement_id, $this_trigger, $site_id, $args, $entry_id ) {

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$access_token                                   = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_access_token', true ) ) );
		$ets_badgeos_discord_send_earned_achievement_dm = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_send_earned_achievement_dm' ) ) );
		if ( $access_token && isset( $user_id ) && isset( $achievement_id ) && $ets_badgeos_discord_send_earned_achievement_dm == true ) {
			as_schedule_single_action( ets_badgeos_discord_get_random_timestamp( ets_badgeos_discord_get_highest_last_attempt_timestamp() ), 'ets_badgeos_discord_as_send_dm', array( $user_id, $achievement_id, 'earn_achievement' ), BADGEOS_DISCORD_AS_GROUP_NAME );
		}

	}

/**
 * Sends discord mesage  when points are awarded.
 *
 * @param $user_id
 * @param $credit_id
 * @param $achievement_id
 * @param $type
 * @param $new_points
 * @param $this_trigger
 * @param $step_id
 * @param $point_rec_id
 *
 * @return none
 */
	public function ets_badgeos_after_award_points( $user_id, $credit_id, $achievement_id, $type, $new_points, $this_trigger, $step_id, $point_rec_id ) {

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( 'Unauthorized user', 401 );
			exit();
		}
		$access_token                                  = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_badgeos_discord_access_token', true ) ) );
		$ets_badgeos_discord_send_award_user_points_dm = sanitize_text_field( trim( get_option( 'ets_badgeos_discord_send_award_user_points_dm' ) ) );
		if ( $access_token && isset( $user_id ) && $ets_badgeos_discord_send_award_user_points_dm == true ) {

			as_schedule_single_action( ets_badgeos_discord_get_random_timestamp( ets_badgeos_discord_get_highest_last_attempt_timestamp() ), 'ets_badgeos_discord_as_send_dm', array( $user_id, $credit_id, 'earn_points' ), BADGEOS_DISCORD_AS_GROUP_NAME );
		}

	}


	/**
	 * Display connect to discord button for a user on their profile screen
	 *
	 * @since  1.0.0
	 * @param  object $user The current user's $user object
	 * @return void
	 */
	public function ets_badgeos_discord_display_connect_discord_button( $user = null ) {

		if ( is_user_logged_in() ) {
			_e( wp_kses( $this->ets_badgeos_discord_add_connect_discord_button(), ets_badgeos_discord_allowed_html() ) );
		}

	}

}
