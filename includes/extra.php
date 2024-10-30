<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

?>
	<div id="latest-themes" class="postbox">
		<h3 class="hndle"> Our latest free themes </h3>
		<div id="slider">
			<a href="#" class="control_next">&raquo;</a>
			<a href="#" class="control_prev">&laquo;</a>
			<ul>
				<?php 
				global $cryout_theme_settings;
				$themes = $cryout_theme_settings->get_suggested_themes();
				shuffle( $themes );
				foreach ( $themes as $theme ) { ?>
				<li>
					<?php printf( '<a href="https://www.cryoutcreations.eu/wordpress-themes/%1$s" target="_blank"><span class="item-title">%2$s WordPress Theme</span><img src="%3$s/%1$s.jpg"></a>', $theme, ucwords( $theme ), $url ) ?>
				</li>
				<?php } // foreach ?>
			</ul>
		</div>
	</div>
	
	<div id="priority-support" class="postbox priority-support">
		<h3 class="hndle"> Need help? </h3>
		<div class="inside">
			<a href="https://www.cryoutcreations.eu/pricing#extra-services" target="_blank"><img src="<?php echo $url ?>/priority-support.jpg"></a>
		</div><!--inside-->
	</div>
	
<?php
/* inline styling and scripting moved to code.js and style.css in 0.5.15 */
?>