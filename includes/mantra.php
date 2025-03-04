<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if (function_exists('mantra_init_fn')):
	add_action('admin_init', 'mantra_init_fn');
	add_action('mantra_before_righty', 'mantra_extra');
endif;

function mantra_theme_settings_restore($class='') {
	global $cryout_theme_settings;
?>
		<form name="mantra_form" action="options.php" method="post" enctype="multipart/form-data">
			<div id="accordion">
				<?php settings_fields('ma_options'); ?>
				<?php do_settings_sections('mantra-page'); ?>
			</div>
			<div id="submitDiv">
			    <br>
				<input class="button" name="ma_options[mantra_submit]" type="submit" style="float:right;"   value="<?php _e('Save Changes','mantra'); ?>" />
				<input class="button" name="ma_options[mantra_defaults]" id="mantra_defaults" type="submit" style="float:left;" value="<?php _e('Reset to Defaults','mantra'); ?>" />
				</div>
		</form>
<?php
} // mantra_theme_settings_buttons()

function mantra_extra() {
	$url = untrailingslashit( plugin_dir_url( dirname(__FILE__) ) ) . '/resources/media';
	include_once( plugin_dir_path( __FILE__ ) . 'extra.php' );
} // mantra_extra()


if ( version_compare( $this->current_theme['version'], '3.3.0', '>=' ) ) {
// all the functionality below is conditioned to running Mantra v3.3.0 or newer and is not needed in older versions

	/**
	 * Export Mantra settings to file
	 */
	function mantra_export_options(){
		
		if (! isset( $_POST['mantra_export'] ) ) return;

		if (ob_get_contents()) ob_clean();
		
		// Check nonce
		if ( ! wp_verify_nonce( $_POST['mantra-export'], 'mantra-export' ) ) return false;
		// Check permissions
		if ( ! current_user_can( 'edit_theme_options' ) ) return false;

		global $mantra_options;
		$name = sprintf( 'mantra-settings-%s-%s.txt', 
					preg_replace( '/[^a-z0-9-_]/i', '', preg_replace('/https?:\/\//i', '', get_option( 'siteurl' ) ) ),
					date('Ymd-His')
				);
		$data = $mantra_options;
		$data = json_encode( $data );
		$size = strlen( $data );

		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="'.$name.'"' );
		header( "Content-Transfer-Encoding: binary" );
		header( 'Accept-Ranges: bytes' );

		/* The three lines below basically make the download non-cacheable */
		header( "Cache-control: private" );
		header( 'Pragma: private' );
		header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );

		header( "Content-Length: " . $size);
		print( $data );
		
		die();
	} // mantra_export_options()
	add_action( 'admin_init', 'mantra_export_options' );

	/**
	 * This file manages the theme settings uploading and import operations.
	 * Uses the theme page to create a new form for uplaoding the settings
	 * Uses WP_Filesystem
	*/
	function mantra_import_form(){            
		
		$bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
		$size = size_format( $bytes );
		$upload_dir = wp_upload_dir();
		if ( ! empty( $upload_dir['error'] ) ) :
			?><div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:', 'mantra'); ?></p>
				<p><strong><?php echo $upload_dir['error']; ?></strong></p></div><?php
		else :
		?>

		<div class="wrap">
			<div style="width:400px;display:block;margin-left:30px;">
			<div id="icon-tools" class="icon32"><br></div>
			<h2><?php echo __( 'Import Mantra Settings', 'mantra' );?></h2>    
			<form enctype="multipart/form-data" id="import-upload-form" method="post" action="">
				<p><?php _e('Only files obtained from Mantra\'s export process are supported.', 'mantra'); ?></p>
				<p>
					<label for="upload"><strong><?php printf( __('Select an existing theme settings file: %s', 'mantra'), '(mantra-settings.txt)' ) ?> </strong><i></i></label> 
				   <input type="file" id="upload" name="import" size="25"  />
					<span style="font-size:10px;">(<?php  printf( __( 'Maximum size: %s', 'mantra' ), $size ); ?> )</span>
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
					<?php wp_nonce_field('mantra-import', 'mantra-import'); ?>
					<input type="hidden" name="mantra_import_confirmed" value="true" />
				</p>
				<input type="submit" class="button" value="<?php _e('Import and replace existing theme options', 'mantra'); ?>" />            
			</form>
		</div>
		</div> <!-- end wrap -->
		<?php
		endif;
	} // Closes the mantra_import_form() function definition 


	/**
	 * This actual import of the options from the file to the settings array.
	*/
	function mantra_import_file() {
		global $mantra_options;
		
		/* Check authorization */
		$authorized = true;
		// Check nonce
		if (!wp_verify_nonce($_POST['mantra-import'], 'mantra-import')) {$authorized = false;}
		// Check permissions
		if (!current_user_can('edit_theme_options')){ $authorized = false; }
		
		// If the user is authorized, import the theme's options to the database
		if ($authorized) {?>
			<?php
			// make sure there is an import file uploaded
			if ( (isset($_FILES["import"]["size"]) &&  ($_FILES["import"]["size"] > 0) ) ) {

				$form_fields = array('import');
				$method = '';
				
				$url = wp_nonce_url('themes.php?page=mantra-page', 'mantra-import');
				
				// Get file writing credentials
				if (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) {
					return true;
				}
				
				if ( ! WP_Filesystem($creds) ) {
					// our credentials were no good, ask the user for them again
					request_filesystem_credentials($url, $method, true, false, $form_fields);
					return true;
				}
				
				// Write the file if credentials are good
				$upload_dir = wp_upload_dir();
				$filename = trailingslashit($upload_dir['path']).'mantra_options.txt';
					 
				// by this point, the $wp_filesystem global should be working, so let's use it to create a file
				global $wp_filesystem;
				if ( ! $wp_filesystem->move($_FILES['import']['tmp_name'], $filename, true) ) {
					echo 'Error saving file!';
					return;
				}
				
				$file = $_FILES['import'];
				
				if ($file['type'] == 'text/plain') {
					$data = $wp_filesystem->get_contents($filename);
					// try to read the file
					if ($data !== FALSE){
						$settings = json_decode($data, true);
						// try to read the settings array
						if (isset($settings['mantra_db'])){ ?>
			<div class="wrap">
			<div id="icon-tools" class="icon32"><br></div>
			<h2><?php echo __( 'Import Mantra Theme Options ', 'mantra' );?></h2> <?php 
							$settings = array_merge($mantra_options, $settings);
							update_option('ma_options', $settings);
							echo '<div class="updated fade"><p>'. __('Success! The options have been imported.', 'mantra').'<br />';
							printf( '<a href="%s">%s<a></p></div>', admin_url( 'themes.php?page=mantra-page' ), __('Go back to the settings page and check them out!', 'mantra') );
						} 
						else { // else: try to read the settings array
							echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'mantra').'</strong><br />';
							echo __('The uploaded file does not contain valid Mantra settings. Make sure the file is exported from Mantra.', 'mantra').'</p></div>';
							mantra_import_form();
						}                    
					} 
					else { // else: try to read the file
						echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'mantra').'</strong><br />';
						echo __('The uploaded file could not be read.', 'mantra').'</p></div>';
						mantra_import_form();
					} 
				}
				else { // else: make sure the file uploaded was a plain text file
					echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'mantra').'</strong><br />';
					echo __('The uploaded file is not supported. Make sure the file was exported from Mantra and that it is a text file.', 'mantra').'</p></div>';
					mantra_import_form();
				}
				
				// Delete the file after we're done
				$wp_filesystem->delete($filename);
				
			}
			else { // else: make sure there is an import file uploaded           
				echo '<div class="error"><p>'.__( 'Oops! The file is empty or there was no file. This error could also be caused by uploads being disabled in your php.ini or by the file being bigger than the limits set in PHP.', 'mantra' ).'</p></div>';
				mantra_import_form();        
			}
			echo '</div> <!-- end wrap -->';
		}
		else {
			wp_die(__('ERROR: You are not authorized to perform this operation', 'mantra'));            
		}           
	} // Closes the mantra_import_file() function definition 

	function mantra_righty_below() {
	?>
			<div class="postbox export non-essential-option" style="overflow:hidden;">
			<div class="head-wrap">
				<h3 class="hndle"><?php _e( 'Import/Export Settings', 'mantra' ); ?></h3>
			</div><!-- head-wrap -->
			<div class="panel-wrap inside">
					<form action="" method="post">
						<?php wp_nonce_field('mantra-export', 'mantra-export'); ?>
						<input type="hidden" name="mantra_export" value="true" />
						<input type="submit" class="button" value="<?php _e('Export Theme options', 'mantra'); ?>" />
					</form>
					<form action="" method="post">
						<input type="hidden" name="mantra_import" value="true" />
						<input type="submit" class="button" value="<?php _e('Import Theme options', 'mantra'); ?>" />
					</form>
			</div><!-- inside -->
		</div><!-- export -->


		<div class="postbox news" >
				<div>
					<h3 class="hndle"><?php _e( 'Mantra Latest News', 'mantra' ); ?></h3>
				</div>
				<div class="panel-wrap inside" style="height:200px;overflow:auto;">
					<?php
					$mantra_news = fetch_feed( array( 'http://www.cryoutcreations.eu/cat/wordpress-themes/mantra/feed') );
					$maxitems = 0;
					if ( ! is_wp_error( $mantra_news ) ) {
						$maxitems = $mantra_news->get_item_quantity( 10 );
						$news_items = $mantra_news->get_items( 0, (int)$maxitems );
					}
					?>
					<ul class="news-list">
						<?php if ( $maxitems == 0 ) : echo '<li>' . __( 'No news items.', 'mantra' ) . '</li>'; else :
						foreach( $news_items as $news_item ) : ?>
							<li>
								<a class="news-header" href='<?php echo esc_url( $news_item->get_permalink() ); ?>'><?php echo esc_html( $news_item->get_title() ); ?></a><br />
					   <span class="news-item-date"><?php _e('Posted on','mantra');echo $news_item->get_date(' j F Y'); ?></span><br />
							   <a class="news-read" href='<?php echo esc_url( $news_item->get_permalink() ); ?>'>Read more &raquo;</a>
							</li>
						<?php endforeach; endif; ?>
					</ul>
				</div><!-- inside -->
		</div><!-- news -->
	<?php 
	} // mantra_righty_below()
	add_action( 'mantra_after_righty', 'mantra_righty_below' );

	// Truncate function for use in the Admin RSS feed 
	function mantra_truncate_words($string,$words=20, $ellipsis=' ...') {
	 $new = preg_replace('/((\w+\W+\'*){'.($words-1).'}(\w+))(.*)/', '${1}', $string);
	 return $new.$ellipsis;
	}

} // endif version_compare()

// FIN