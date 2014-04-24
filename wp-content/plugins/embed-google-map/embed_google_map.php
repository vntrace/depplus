<?php
/*
Plugin Name: Embed Google Map
Plugin URI: http://pkrete.com/wp/plugins/embed-google-map/v20.html
Description: Embed Google Map is a plugin for embedding one or more Google Maps to WordPress pages and posts.
Author: Petteri Kivim&auml;ki
Version: 2.0
Author URI: http://www.linkedin.com/in/pkivimaki
*/

/* Register WordPress hooks - Frontend */

// Run the function on post content prior to printing on the screen
add_filter('the_content', 'the_google_map_embedder');

/* Register WordPress hooks - Backend */

if ( is_admin() ) {
	// Add link to the Settings menu
	add_action('admin_menu', 'embed_google_map_create_menu');

	// Add link to settings in the Plugins page
	add_filter('plugin_action_links','embed_google_map_plugin_actions', 10, 2);
}

/* Frontend functions */	

function the_google_map_embedder($content) {

	// Regex for finding all the google_map tags
	$regex = "#{google_map}(.*?){/google_map}#s";
	// Read all the tags in an array
	$found = preg_match_all($regex, $content, $matches);
	// Get default options
	$options = get_option('embed_google_map_options');
	// Initialize options
	init_embed_google_map_options($options);
	
	// Check if any matches were found
	if ( $found ) {
	     // Loop through all the matches
		foreach ( $matches[0] as $value ) {			
			// Set default parameters
			$map_type = $options['map_type'];
			$zoom_level = $options['zoom_level'];
			$language = $options['language'];
			$add_link = $options['add_link'];
			$show_info = $options['show_info'];
			$link_label = $options['link_label'];
			$link_full = $options['link_full'];
			$height = $options['height'];
			$width = $options['width'];
			$border = $options['border'];
			$border_style =  $options['border_style'];
			$border_color =  $options['border_color'];
			$https = $options['https'];
			$protocol = "";
			$url = "maps.google.com/";
			$info_label = $options['info_label'];
						
			$map = $value;
			$map = str_replace('{google_map}','', $map);
			$map = str_replace('{/google_map}','', $map);
			$address = $map;
			$find = '|';

			// Check parameters
			if( strstr($map, $find) ) {
				$arr = explode('|',$map);
				$address = $arr[0];

				// Loop through all the parameters
				foreach ( $arr as $phrase ) {
					if ( strstr(strtolower($phrase), 'type:') ) {
						$tpm1 = explode(':',$phrase);
						$tmp1 = trim($tpm1[1], '"');
						if(strcmp(strtolower($tmp1),'normal') == 0) {
							$map_type = 'm';
						} else if(strcmp(strtolower($tmp1),'satellite') == 0) {
							$map_type = 'k';
						} else if(strcmp(strtolower($tmp1),'hybrid') == 0) {
							$map_type = 'h';
						} else if(strcmp(strtolower($tmp1),'terrain') == 0) {
							$map_type = 'p';
						} 
					}
					
					if ( strstr(strtolower($phrase), 'zoom:') )	 {       
						$tpm1 = explode(':',$phrase);
						$zoom_level = trim($tpm1[1], '"');
					}
						
					if ( strstr(strtolower($phrase), 'height:') ) {       
						$tpm1 = explode(':',$phrase);
						$height = trim($tpm1[1], '"');
					}
										
					if ( strstr(strtolower($phrase), 'width:') ) {
						$tpm1 = explode(':',$phrase);
						$width = trim($tpm1[1], '"');
					}
					
					if ( strstr(strtolower($phrase), 'border:') ) {       
						$tpm1 = explode(':',$phrase);
						$border = trim($tpm1[1], '"');
					}	
					if ( strstr(strtolower($phrase), 'border_style:') )	
					{       
						$tpm1 = explode(':',$phrase);							
						$border_style = trim($tpm1[1], '"');
						$border_style = ( preg_match('/^(none|hidden|dotted|dashed|solid|double)$/i', $border_style) ? $border_style : 'solid' );
					}	
						
					if ( strstr(strtolower($phrase), 'border_color:') )	
					{       
						$tpm1 = explode(':',$phrase);
						$border_color = trim($tpm1[1], '"');							
					}	
						
					if ( strstr(strtolower($phrase), 'lang:') )	{      
						$tpm1 = explode(':',$phrase);
						$language = trim($tpm1[1], '"');
					}							
						
					if ( strstr(strtolower($phrase), 'link:') ) {
						$tpm1 = explode(':',$phrase);
						$tmp1 = trim($tpm1[1], '"');
						if(strcmp(strtolower($tmp1),'yes') == 0) {
							$add_link = 1;
						} else {
							$add_link = 0;
						}
					}
						
					if ( strstr(strtolower($phrase), 'link_label:') ) {       
						$tpm1 = explode(':',$phrase);
						$link_label = trim($tpm1[1], '"');
					}
					
					if ( strstr(strtolower($phrase), 'link_full:') )
					{
						$tpm1 = explode(':',$phrase);
 						$tmp1 = trim($tpm1[1], '"');
						if(strcmp(strtolower($tmp1),'yes') == 0) {
							$link_full = 1;
						} else {
							$link_full = 0;
						}
					}
						
					if ( strstr(strtolower($phrase), 'show_info:') ) {
						$tpm1 = explode(':',$phrase);
						$tmp1 = trim($tpm1[1], '"');
						if(strcmp(strtolower($tmp1),'yes') == 0) {
							$show_info = 1;
						} else {
							$show_info = 0;
						}
					}	
					
					if ( strstr(strtolower($phrase), 'https:') )
					{
						$tpm1 = explode(':',$phrase);
 						$tmp1 = trim($tpm1[1], '"');
						if(strcmp(strtolower($tmp1),'yes') == 0) {
							$https = 1;
						} else {
							$https = 0;
						}
					}
					
					if ( strstr(strtolower($phrase), 'info_label:') )	
					{       
						$tpm1 = explode(':',$phrase);
						$info_label = trim($tpm1[1], '"');
					}					
				}
			}
			
			$protocol = $https ? "https://" : "http://";				
			$url = "$protocol$url";
				
			if(strcmp($language,'-') != 0) {
				$language = "&hl=$language";
			} else {
				$language = "";
			}
			if(preg_match('/^http(s|):\/\//i', $address)) {
				$url = $address;
			} else {
				$url .= "?q=$address";
				if(strlen($info_label) > 0) {
					$url .= "($info_label)";
				}				
			}	
			
			$info = $show_info ? "" : "&iwloc=near";
			
			// Unicode properties are available only if PCRE is compiled with "--enable-unicode-properties" 
			// '\pL' = any Unicode letter
			if (preg_match('/^[^\pL]+$/u', $address)) {
				$info = $show_info ? "&iwloc=near" : "";
			}
				
			// Build the html code	
			$replacement = "\n<iframe width='$width' height='$height' style='border: ".$border."px $border_style $border_color' ";
			$replacement .= "src='$url&z=$zoom_level&output=embed$language&t=$map_type$info'></iframe>\n";
			if($add_link) {
				$output = $link_full ? "&output=embed" : "";
				$replacement .= "<div><a href='$url&z=$zoom_level$language&t=$map_type$info$output' target='new'>$link_label</a></div>\n";
			}
			// Replace the tag with the html code that embeds the map
			$content = str_replace($value, $replacement, $content);
		}
	}
	return $content;
}

/* Backend functions */	

function embed_google_map_create_menu() {
	// Add link to the Settings menu
    add_options_page('Embed Google Maps Options', 'Embed Google Map', 'manage_options', 'embed_google_map.php', 'embed_google_map_page');

	//call add action function
	add_action( 'admin_init', 'register_embed_google_map_settings' );
}

function register_embed_google_map_settings() {
	//register settings
	register_setting( 'embed_google_map-settings-group', 'embed_google_map_options', 'embed_google_map_options_validate' );
}

function embed_google_map_page() {
	screen_icon();
	?>
	<div class="wrap">
    <h2>Embed Google Map Settings</h2>
	<p>
		Embed Google Map is a plugin for embedding one or more Google Maps to WordPress posts and pages. Adding maps is very simple, just add the address or the coordinates which location you want to show an a map inside google_map tags to a post or a page, and that's it!
	</p>
	<p>
		It's possible to define the type of the map (normal, satellite, hybrid, terrain), the size of the map, the language of the Google Maps interface, zoom level, border width, border color, border style, link to the full size map, custom labels and hide/show the info label. Both HTTP and HTTPS protocols are supported. The settings defined in this page are the default settings used for all the maps in the site, and they can be overridden for individual maps.
	</p>
	<form method="post" action="options.php">
		<?php 
			settings_fields('embed_google_map-settings-group');
			$options = get_option('embed_google_map_options');
			init_embed_google_map_options($options);
		?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><span title="Map type.">Map type:</span></th>
                <td>
					<select name="embed_google_map_options[map_type]">
						<option value="m" <? echo ($options['map_type'] == "m") ? 'selected="selected"' : ''; ?>>Normal map</option>
						<option value="k" <? echo ($options['map_type'] == "k") ? 'selected="selected"' : ''; ?>>Satellite</option>
						<option value="h" <? echo ($options['map_type'] == "h") ? 'selected="selected"' : ''; ?>>Hybrid</option>
						<option value="p" <? echo ($options['map_type'] == "p") ? 'selected="selected"' : ''; ?>>Terrain</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Zoom level.">Zoom level:</span></th>
                <td>
					<select name="embed_google_map_options[zoom_level]">
						<option value="0" <? echo ($options['zoom_level'] == "0") ? 'selected="selected"' : ''; ?>>0</option>
						<option value="1" <? echo ($options['zoom_level'] == "1") ? 'selected="selected"' : ''; ?>>1</option>
                        <option value="2" <? echo ($options['zoom_level'] == "2") ? 'selected="selected"' : ''; ?>>2</option>
                        <option value="3" <? echo ($options['zoom_level'] == "3") ? 'selected="selected"' : ''; ?>>3</option>
                        <option value="4" <? echo ($options['zoom_level'] == "4") ? 'selected="selected"' : ''; ?>>4</option>
                        <option value="5" <? echo ($options['zoom_level'] == "5") ? 'selected="selected"' : ''; ?>>5</option>
                        <option value="6" <? echo ($options['zoom_level'] == "6") ? 'selected="selected"' : ''; ?>>6</option>
                        <option value="7" <? echo ($options['zoom_level'] == "7") ? 'selected="selected"' : ''; ?>>7</option>
                        <option value="8" <? echo ($options['zoom_level'] == "8") ? 'selected="selected"' : ''; ?>>8</option>
                        <option value="9" <? echo ($options['zoom_level'] == "9") ? 'selected="selected"' : ''; ?>>9</option>
                        <option value="10" <? echo ($options['zoom_level'] == "10") ? 'selected="selected"' : ''; ?>>10</option>
                        <option value="11" <? echo ($options['zoom_level'] == "11") ? 'selected="selected"' : ''; ?>>11</option>
                        <option value="12" <? echo ($options['zoom_level'] == "12") ? 'selected="selected"' : ''; ?>>12</option>
                        <option value="13" <? echo ($options['zoom_level'] == "13") ? 'selected="selected"' : ''; ?>>13</option>
                        <option value="14" <? echo ($options['zoom_level'] == "14" || !isset($options['zoom_level'])) ? 'selected="selected"' : ''; ?>>14</option>
                        <option value="15" <? echo ($options['zoom_level'] == "15") ? 'selected="selected"' : ''; ?>>15</option>
                        <option value="16" <? echo ($options['zoom_level'] == "16") ? 'selected="selected"' : ''; ?>>16</option>
                        <option value="17" <? echo ($options['zoom_level'] == "17") ? 'selected="selected"' : ''; ?>>17</option>
                        <option value="18" <? echo ($options['zoom_level'] == "18") ? 'selected="selected"' : ''; ?>>18</option>
                        <option value="19" <? echo ($options['zoom_level'] == "29") ? 'selected="selected"' : ''; ?>>19</option>
                        <option value="20" <? echo ($options['zoom_level'] == "20") ? 'selected="selected"' : ''; ?>>20</option>
                        <option value="21" <? echo ($options['zoom_level'] == "21") ? 'selected="selected"' : ''; ?>>21</option>						
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Language.">Language:</span></th>
				<td>
					<select name="embed_google_map_options[language]">
						<option value="-">Undefined</option>
						<option value="ar"<? echo ($options['language'] == "ar") ? 'selected="selected"' : ''; ?>>Arabic</option>
						<option value="eu" <? echo ($options['language'] == "eu") ? 'selected="selected"' : ''; ?>>Basque</option>
						<option value="bn" <? echo ($options['language'] == "bn") ? 'selected="selected"' : ''; ?>>Bengali</option>
						<option value="bg" <? echo ($options['language'] == "bg") ? 'selected="selected"' : ''; ?>>Bulgarian</option>
						<option value="ca" <? echo ($options['language'] == "ca") ? 'selected="selected"' : ''; ?>>Catalan</option>
						<option value="zh-CN" <? echo ($options['language'] == "zh-CN") ? 'selected="selected"' : ''; ?>>Chinese (simplified)</option>
						<option value="zh-TW" <? echo ($options['language'] == "zh-TW") ? 'selected="selected"' : ''; ?>>Chinese (traditional)</option>
						<option value="hr" <? echo ($options['language'] == "hr") ? 'selected="selected"' : ''; ?>>Croatian</option>
						<option value="cs" <? echo ($options['language'] == "cs") ? 'selected="selected"' : ''; ?>>Czech</option>
						<option value="da" <? echo ($options['language'] == "da") ? 'selected="selected"' : ''; ?>>Danish</option>
						<option value="nl" <? echo ($options['language'] == "nl") ? 'selected="selected"' : ''; ?>>Dutch</option>
						<option value="en" <? echo ($options['language'] == "en") ? 'selected="selected"' : ''; ?>>English</option>
						<option value="en-AU" <? echo ($options['language'] == "en-AU") ? 'selected="selected"' : ''; ?>>English (Australian)</option>
						<option value="en-GB" <? echo ($options['language'] == "en-GB") ? 'selected="selected"' : ''; ?>>English (Great Britain)</option>
						<option value="fa" <? echo ($options['language'] == "fa") ? 'selected="selected"' : ''; ?>>Farsi</option>
						<option value="fil" <? echo ($options['language'] == "fil") ? 'selected="selected"' : ''; ?>>Filipino</option>
						<option value="fi" <? echo ($options['language'] == "fi") ? 'selected="selected"' : ''; ?>>Finnish</option>
						<option value="fr" <? echo ($options['language'] == "fr") ? 'selected="selected"' : ''; ?>>French</option>
						<option value="gl" <? echo ($options['language'] == "gl") ? 'selected="selected"' : ''; ?>>Galician</option>
						<option value="de" <? echo ($options['language'] == "de") ? 'selected="selected"' : ''; ?>>German</option>
						<option value="el" <? echo ($options['language'] == "el") ? 'selected="selected"' : ''; ?>>Greek</option>
						<option value="gu" <? echo ($options['language'] == "gu") ? 'selected="selected"' : ''; ?>>Gujarati</option>
						<option value="iw" <? echo ($options['language'] == "iw") ? 'selected="selected"' : ''; ?>>Hebrew</option>
						<option value="hi" <? echo ($options['language'] == "hi") ? 'selected="selected"' : ''; ?>>Hindi</option>
						<option value="hu" <? echo ($options['language'] == "hu") ? 'selected="selected"' : ''; ?>>Hungarian</option>
						<option value="id" <? echo ($options['language'] == "id") ? 'selected="selected"' : ''; ?>>Indonesian</option>
						<option value="it" <? echo ($options['language'] == "it") ? 'selected="selected"' : ''; ?>>Italian</option>
						<option value="ja" <? echo ($options['language'] == "ja") ? 'selected="selected"' : ''; ?>>Japanese</option>
						<option value="kn" <? echo ($options['language'] == "kn") ? 'selected="selected"' : ''; ?>>Kannada</option>
						<option value="ko" <? echo ($options['language'] == "ko") ? 'selected="selected"' : ''; ?>>Korean</option>
						<option value="lv" <? echo ($options['language'] == "lv") ? 'selected="selected"' : ''; ?>>Latvian</option>
						<option value="lt" <? echo ($options['language'] == "lt") ? 'selected="selected"' : ''; ?>>Lithuanian</option>
						<option value="ml" <? echo ($options['language'] == "ml") ? 'selected="selected"' : ''; ?>>Malayalam</option>
						<option value="mr" <? echo ($options['language'] == "mr") ? 'selected="selected"' : ''; ?>>Marathi</option>
						<option value="no" <? echo ($options['language'] == "no") ? 'selected="selected"' : ''; ?>>Norwegian</option>
						<option value="nn" <? echo ($options['language'] == "nn") ? 'selected="selected"' : ''; ?>>Norwegian Nynorsk</option>
						<option value="or" <? echo ($options['language'] == "or") ? 'selected="selected"' : ''; ?>>Oriya</option>
						<option value="pl" <? echo ($options['language'] == "pl") ? 'selected="selected"' : ''; ?>>Polish</option>
						<option value="pt" <? echo ($options['language'] == "pt") ? 'selected="selected"' : ''; ?>>Portuguese</option>
						<option value="pt-BR" <? echo ($options['language'] == "pt-BR") ? 'selected="selected"' : ''; ?>>Portuguese (Brazil)</option>
						<option value="pt-PT" <? echo ($options['language'] == "pt-PT") ? 'selected="selected"' : ''; ?>>Portuguese (Portugal)</option>
						<option value="ro" <? echo ($options['language'] == "ro") ? 'selected="selected"' : ''; ?>>Romanian</option>
						<option value="rm" <? echo ($options['language'] == "rm") ? 'selected="selected"' : ''; ?>>Romansch</option>
						<option value="ru" <? echo ($options['language'] == "ru") ? 'selected="selected"' : ''; ?>>Russian</option>
						<option value="sk" <? echo ($options['language'] == "sk") ? 'selected="selected"' : ''; ?>>Slovak</option>
						<option value="sl" <? echo ($options['language'] == "sl") ? 'selected="selected"' : ''; ?>>Slovenian</option>
						<option value="sr" <? echo ($options['language'] == "sr") ? 'selected="selected"' : ''; ?>>Serbian</option>
						<option value="es" <? echo ($options['language'] == "es") ? 'selected="selected"' : ''; ?>>Spanish</option>
						<option value="sv" <? echo ($options['language'] == "sv") ? 'selected="selected"' : ''; ?>>Swedish</option>
						<option value="tl" <? echo ($options['language'] == "tl") ? 'selected="selected"' : ''; ?>>Tagalog</option>
						<option value="ta" <? echo ($options['language'] == "ta") ? 'selected="selected"' : ''; ?>>Tamil</option>
						<option value="te" <? echo ($options['language'] == "te") ? 'selected="selected"' : ''; ?>>Telugu</option>
						<option value="th" <? echo ($options['language'] == "th") ? 'selected="selected"' : ''; ?>>Thai</option>
						<option value="tr" <? echo ($options['language'] == "tr") ? 'selected="selected"' : ''; ?>>Turkish</option>
						<option value="uk" <? echo ($options['language'] == "uk") ? 'selected="selected"' : ''; ?>>Ukrainian</option>
						<option value="vi" <? echo ($options['language'] == "vi") ? 'selected="selected"' : ''; ?>>Vietnamese</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Add link to Google Maps.">Add link:</span></th>
				<td><input name="embed_google_map_options[add_link]" type="checkbox" value="1" <?php checked('1', $options['add_link']); ?> /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Link label.">Link label:</span></th>
				<td><input type="text" name="embed_google_map_options[link_label]" value="<?php echo $options['link_label']; ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Open link in full screen mode.">Link full:</span></th>
				<td><input name="embed_google_map_options[link_full]" type="checkbox" value="1" <?php checked('1', $options['link_full']); ?> /></td>
			</tr>			
			<tr valign="top">
				<th scope="row"><span title="Show info label.">Show info:</span></th>
				<td><input name="embed_google_map_options[show_info]" type="checkbox" value="1" <?php checked('1', $options['show_info']); ?> /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Custom info label.">Info label:</span></th>
				<td><input type="text" name="embed_google_map_options[info_label]" value="<?php echo $options['info_label']; ?>" /></td>
			</tr>		
			<tr valign="top">
				<th scope="row"><span title="Default height.">Height:</span></th>
				<td><input type="text" name="embed_google_map_options[height]" value="<?php echo $options['height']; ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Default width.">Width:</span></th>
				<td><input type="text" name="embed_google_map_options[width]" value="<?php echo $options['width']; ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Frame border width.">Border:</span></th>
				<td>
					<select name="embed_google_map_options[border]">
						<option value="0" <? echo ($options['border'] == "0") ? 'selected="selected"' : ''; ?>>0</option>
						<option value="1" <? echo ($options['border'] == "1") ? 'selected="selected"' : ''; ?>>1</option>
						<option value="2" <? echo ($options['border'] == "2") ? 'selected="selected"' : ''; ?>>2</option>
						<option value="3" <? echo ($options['border'] == "3") ? 'selected="selected"' : ''; ?>>3</option>
						<option value="4" <? echo ($options['border'] == "4") ? 'selected="selected"' : ''; ?>>4</option>
						<option value="5" <? echo ($options['border'] == "5") ? 'selected="selected"' : ''; ?>>5</option>
						<option value="6" <? echo ($options['border'] == "6") ? 'selected="selected"' : ''; ?>>6</option>
						<option value="7" <? echo ($options['border'] == "7") ? 'selected="selected"' : ''; ?>>7</option>
						<option value="8" <? echo ($options['border'] == "8") ? 'selected="selected"' : ''; ?>>8</option>
						<option value="9" <? echo ($options['border'] == "9") ? 'selected="selected"' : ''; ?>>9</option>
						<option value="10" <? echo ($options['border'] == "10") ? 'selected="selected"' : ''; ?>>10</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><span title="Frame border style.">Border style:</span></th>
                <td>
					<select name="embed_google_map_options[border_style]">
						<option value="none" <? echo ($options['border_style'] == "none") ? 'selected="selected"' : ''; ?>>None</option>
						<option value="hidden" <? echo ($options['border_style'] == "hidden") ? 'selected="selected"' : ''; ?>>Hidden</option>
						<option value="dotted" <? echo ($options['border_style'] == "dotted") ? 'selected="selected"' : ''; ?>>Dotted</option>
						<option value="dashed" <? echo ($options['border_style'] == "dashed") ? 'selected="selected"' : ''; ?>>Dashed</option>
						<option value="solid" <? echo ($options['border_style'] == "solid") ? 'selected="selected"' : ''; ?>>Solid</option>
						<option value="double" <? echo ($options['border_style'] == "double") ? 'selected="selected"' : ''; ?>>Double</option>
					</select>
				</td>
			</tr>	
			<tr valign="top">
				<th scope="row"><span title="Frame border color in hexadecimal format.">Border color:</span></th>
				<td><input type="text" name="embed_google_map_options[border_color]" value="<?php echo $options['border_color']; ?>" /></td>
			</tr>	
			<tr valign="top">
				<th scope="row"><span title="Use HTTPS protocol.">HTTPS:</span></th>
				<td><input name="embed_google_map_options[https]" type="checkbox" value="1" <?php checked('1', $options['https']); ?> /></td>
			</tr>			
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
		
	<h3>Basic Usage</h3>
	<p>To embed a map in a post or a page use the following code:</p>
	<ul>
		<li>{google_map}address{/google_map}</li>
	</ul>
	<h3>Overriding default settings</h3>
	<p>To override one or more default settings use the following code:</p>
	<ul>	
		<li>{google_map}address{/google_map}</li>
		<li>{google_map}address|zoom:10{/google_map}</li>
		<li>{google_map}address|zoom:10|lang:it{/google_map}</li>
		<li>{google_map}address|width:200|height:200|border:1|border_style:solid|border_color:#000000{/google_map}</li>
		<li>{google_map}address|width:200|height:200|link:yes|link_label:Label{/google_map}</li>
		<li>{google_map}address|link:yes{/google_map}</li>
		<li>{google_map}address|type:satellite{/google_map}</li>
		<li>{google_map}address|show_info:yes|info_label:Label{/google_map}</li>
		<li>{google_map}address|link_full:yes{/google_map}</li>
		<li>{google_map}address|https:yes{/google_map}</li>		
		<li><b>*</b>{google_map}latitude,longitude{/google_map}</li>
	</ul>
	
	<p><b>*</b> latitude,longitude = coordinates in decimal degrees</p>
<?
}

function embed_google_map_options_validate($input) {
	// map type is 'm', 'k', 'h' or 'p'
	$input['map_type'] = ( preg_match('/^(m|k|h|p)$/', $input['map_type']) ? $input['map_type'] : 'm' );
	// zoom level is between 0-21
	$input['zoom_level'] = ( $input['zoom_level'] >= 0 && $input['zoom_level'] <= 21 ? $input['zoom_level'] : 14 );
    // add_link is either 0 or 1
    $input['add_link'] = ( $input['add_link'] == 1 ? 1 : 0 );
	// link_full is either 0 or 1
    $input['link_full'] = ( $input['link_full'] == 1 ? 1 : 0 );
	// height can contain only digits, whitespaces are stripped from the beginning and end of the value
	$input['height'] = ( preg_match('/^\d+$/', trim($input['height'])) ? trim($input['height']) : 400 );
	// show_info is either 0 or 1
    $input['show_info'] = ( $input['show_info'] == 1 ? 1 : 0 );
	// width can contain only digits, whitespaces are stripped from the beginning and end of the value
	$input['width'] = ( preg_match('/^\d+$/', trim($input['width'])) ? trim($input['width']) : 300 );
	// border is between 0-10
	$input['border'] = ( $input['border'] >= 0 && $input['border'] <= 10 ? $input['border'] : 0 );
	// border style is 'none', 'hidden', 'dotted', 'dashed', 'solid' or 'double'
	$input['border_style'] = ( preg_match('/^(none|hidden|dotted|dashed|solid|double)$/i', $input['border_style']) ? $input['border_style'] : 'solid' );
	// border color is a hex color
	$input['border_color'] = ( preg_match('/^#[a-f0-9]{6}$/i', $input['border_color']) ? $input['border_color'] : '#000000' );
    // https is either 0 or 1
    $input['https'] = ( $input['https'] == 1 ? 1 : 0 );	
	
	return $input;
}

function init_embed_google_map_options(&$options) {
	if(!isset($options['map_type'])) { $options['map_type'] = 'm'; }
	if(!isset($options['zoom_level'])) { $options['zoom_level'] = 14; }
	if(!isset($options['language'])) { $options['language'] = ''; }
	if(!isset($options['add_link'])) { $options['add_link'] = 0; }
	if(!isset($options['link_label'])) { $options['link_label'] = 'View Larger Map'; } 
	if(!isset($options['link_full'])) { $options['link_full'] = 0; }
	if(!isset($options['show_info'])) { $options['show_info'] = 1; }
	if(!isset($options['info_label'])) { $options['info_label'] = ''; } 
	if(!isset($options['height'])) { $options['height'] = 400; }
	if(!isset($options['width'])) { $options['width'] = 300; }
	if(!isset($options['border'])) { $options['border'] = 0; }
	if(!isset($options['border_style'])) { $options['border_style'] = 'solid'; }
	if(!isset($options['border_color'])) { $options['border_color'] = '#000000'; }
	if(!isset($options['https'])) { $options['https'] = 0; }
}

function embed_google_map_plugin_actions($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if ($file == $this_plugin){
		$my_link = '<a href="admin.php?page=embed_google_map.php">' . __('Settings') . '</a>';
		array_unshift($links, $my_link);
	}
	return $links;
}
?>
