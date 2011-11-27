<?php

/*
Plugin Name: Translucent image slideshow gallery
Plugin URI: http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/
Description: Translucent image slideshow gallery WordPress plugin will create a image slideshow. All images are animated with translucent effect. 
Author: Gopi.R
Version: 3.0
Author URI: http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/
Donate link: http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/
*/

/**
 *     Translucent image slideshow gallery
 *     Copyright (C) 2011  www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function TISG_slideshow() 
{
	
	$TISG_returnstr = "";
	
	$TISG_width = get_option('TISG_width');
	$TISG_height = get_option('TISG_height');
	$TISG_delay = get_option('TISG_delay');
	$TISG_speed = get_option('TISG_speed');
	$TISG_dir = get_option('TISG_dir');
	$TISG_link = get_option('TISG_link');
	$siteurl_link = get_option('siteurl') . "/";

	if(is_dir($TISG_dir))
	{
		if($TISG_link <> ""){
			$TISG_str_link = $TISG_link;
		}
		else{
			$TISG_str_link = "";
		}
		
		$TISG_dirHandle = opendir($TISG_dir);
		$TISG_count = 0;
		while ($TISG_file = readdir($TISG_dirHandle)) 
		{
		  if(!is_dir($TISG_file) && (strpos($TISG_file, '.jpg')>0 or strpos($TISG_file, '.gif')>0)) 
		  {
			 if($TISG_link == "" )
			 {
				$TISG_str =  '["' . $siteurl_link . $TISG_dir . $TISG_file . '", "", ""],';
			 }
			 else
			 {
				$TISG_str =  '["' . $siteurl_link . $TISG_dir . $TISG_file . '", "'. $TISG_link .'", "_new"],';
			 }
			 $TISG_returnstr = $TISG_returnstr . $TISG_str;
			 $TISG_count++;
		  }
		} 
		$TISG_returnstr = substr($TISG_returnstr,0,(strlen($TISG_returnstr)-1));
		closedir($TISG_dirHandle)
		?>
		<script type="text/javascript">
		var TISG=new TISG_translideshow({
			TISG_wrapperid: "myslideshow",
			TISG_dimensions: [<?php echo $TISG_width; ?>, <?php echo $TISG_height; ?>],
			TISG_imagearray: [<?php echo $TISG_returnstr; ?>],
			TISG_displaymode: {type:'auto', pause:<?php echo $TISG_delay; ?>, cycles:5, pauseonmouseover:true},
			TISG_orientation: "h", 
			TISG_persist: true, 
			TISG_slideduration: <?php echo $TISG_speed; ?>
		})
		</script>
		<?php
		echo "<div id='myslideshow'></div>";
	}
	else
	{
		echo $TISG_dir . " - Folder Does Not Exist";
	}
}

function TISG_install() 
{
	add_option('TISG_title', "Slide Show");
	add_option('TISG_width', "200");
	add_option('TISG_height', "155");
	add_option('TISG_delay', "3000");
	add_option('TISG_speed', "400");
	add_option('TISG_dir', "wp-content/plugins/translucent-image-slideshow-gallery/gallery/");
	add_option('TISG_link', "");
	
	add_option('TISG_dir_1', "");
	add_option('TISG_link_1', "");
	
	add_option('TISG_dir_2', "");
	add_option('TISG_link_2', "");
	
	add_option('TISG_dir_3', "");
	add_option('TISG_link_3', "");
	
	add_option('TISG_dir_4', "");
	add_option('TISG_link_4', "");
}

function TISG_widget($args) 
{
	$TISG_title = get_option('TISG_title');
	extract($args);
	if($TISG_title <> "")
	{
		echo $before_widget . $before_title;
		echo get_option('TISG_title');
		echo $after_title;
	}
	else
	{
		echo "<div id='TISG' style='padding-top:5px;padding-bottom:5px'>";
	}
	TISG_slideshow();
	if($TISG_title <> "")
	{
		echo $after_widget;
	}
	else
	{
		echo "</div>";
	}
}

function TISG_control() 
{
	echo '<p>Translucent image slideshow gallery.<br><br> To change the setting goto Translucent slideshow under setting menu. ';
	echo '<a href="options-general.php?page=translucent-image-slideshow-gallery/translucent-image-slideshow-gallery.php">click here</a>';
	echo '<br><br><a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/">';
	echo 'Click here for help and live demo</a></p>';
}

function TISG_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('Translucent-slideshow', 'Translucent slideshow', 'TISG_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('Translucent-slideshow', array('Translucent slideshow', 'widgets'), 'TISG_control');
	} 
}

function TISG_deactivation() 
{
//  delete_option('TISG_title');
//	delete_option('TISG_width');
//	delete_option('TISG_height');
//	delete_option('TISG_delay');
//	delete_option('TISG_speed');
//	delete_option('TISG_dir');
//	delete_option('TISG_link');
}

function ISG_admin_option()
{
	echo "<div class='wrap'>";
	echo "<h2>Translucent slideshow</h2>"; 
	
	$TISG_title = get_option('TISG_title');
	$TISG_width = get_option('TISG_width');
	$TISG_height = get_option('TISG_height');
	$TISG_delay = get_option('TISG_delay');
	$TISG_speed = get_option('TISG_speed');
	$TISG_dir = get_option('TISG_dir');
	$TISG_link = get_option('TISG_link');
	
	$TISG_dir_1 = get_option('TISG_dir_1');
	$TISG_link_1 = get_option('TISG_link_1');
	$TISG_dir_2 = get_option('TISG_dir_2');
	$TISG_link_2 = get_option('TISG_link_2');
	$TISG_dir_3 = get_option('TISG_dir_3');
	$TISG_link_3 = get_option('TISG_link_3');
	$TISG_dir_4 = get_option('TISG_dir_4');
	$TISG_link_4 = get_option('TISG_link_4');
	
	if (@$_POST['TISG_submit']) 
	{
		$TISG_title = stripslashes($_POST['TISG_title']);
		$TISG_width = stripslashes($_POST['TISG_width']);
		$TISG_height = stripslashes($_POST['TISG_height']);
		$TISG_delay = stripslashes($_POST['TISG_delay']);
		$TISG_speed = stripslashes($_POST['TISG_speed']);
		$TISG_dir = stripslashes($_POST['TISG_dir']);
		$TISG_link = stripslashes($_POST['TISG_link']);
		
		$TISG_dir_1 = stripslashes($_POST['TISG_dir_1']);
		$TISG_link_1 = stripslashes($_POST['TISG_link_1']);
		$TISG_dir_2 = stripslashes($_POST['TISG_dir_2']);
		$TISG_link_2 = stripslashes($_POST['TISG_link_2']);
		$TISG_dir_3 = stripslashes($_POST['TISG_dir_3']);
		$TISG_link_3 = stripslashes($_POST['TISG_link_3']);
		$TISG_dir_4 = stripslashes($_POST['TISG_dir_4']);
		$TISG_link_4 = stripslashes($_POST['TISG_link_4']);
		
		update_option('TISG_title', $TISG_title );
		update_option('TISG_width', $TISG_width );
		update_option('TISG_height', $TISG_height );
		update_option('TISG_delay', $TISG_delay );
		update_option('TISG_speed', $TISG_speed );
		update_option('TISG_dir', $TISG_dir );
		update_option('TISG_link', $TISG_link );
		
		update_option('TISG_dir_1', $TISG_dir_1 );
		update_option('TISG_link_1', $TISG_link_1 );
		update_option('TISG_dir_2', $TISG_dir_2 );
		update_option('TISG_link_2', $TISG_link_2 );
		update_option('TISG_dir_3', $TISG_dir_3 );
		update_option('TISG_link_3', $TISG_link_3 );
		update_option('TISG_dir_4', $TISG_dir_4 );
		update_option('TISG_link_4', $TISG_link_4 );
	}
	
	echo '<form name="form_etgwtlt" method="post" action="">';
	echo '<h3>Widget</h3>';
	echo "<div style='border: 1px solid #DDDDDD;padding-left:10px;'>";
	echo '<p>Title:<br><input  style="width: 650px;" maxlength="100" type="text" value="';
	echo $TISG_title . '" name="TISG_title" id="TISG_title" /></p>';
	echo '<table width="650" border="0" cellspacing="0" cellpadding="2">';
	echo '<tr><td colspan="4" style="color:#999900;">';
	echo '</td></tr>';
	echo '<tr>';
	echo '<td>Width:</td>';
	echo '<td>Height:</td>';
	echo '<td>Speed:</td>';
	echo '<td>Delay:</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><input  style="width: 75px;" maxlength="3" type="text" value="' . $TISG_width . '" name="TISG_width" id="TISG_width" />px</td>';
	echo '<td><input  style="width: 75px;" maxlength="3" type="text" value="' . $TISG_height . '" name="TISG_height" id="TISG_height" />px</td>';
	echo '<td><input  style="width: 75px;" maxlength="5" type="text" value="' . $TISG_speed . '" name="TISG_speed" id="TISG_speed" /></td>';
	echo '<td><input  style="width: 75px;" maxlength="5" type="text" value="' . $TISG_delay . '" name="TISG_delay" id="TISG_delay" />ms</td>';
	echo '</tr>';
	echo '</table>';
	echo '<p></p>';
	echo '<p>Image directory:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_dir . '" name="TISG_dir" id="TISG_dir" /> [TISG:CODE=SD0:W=200:H=150]</p>';
	echo '<p>Image hyper link:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_link . '" name="TISG_link" id="TISG_link" /></p>';
	echo "</div>";
	
	echo "<div style='padding-top:8px;'><h3>Short code 1</h3></div>";
	
	echo "<div style='border: 1px solid #DDDDDD;padding-left:10px;'>";
	echo '<p>Image directory:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_dir_1 . '" name="TISG_dir_1" id="TISG_dir_1" /> [TISG:CODE=SD1:W=200:H=150]</p>';
	echo '<p>Image hyper link:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_link_1 . '" name="TISG_link_1" id="TISG_link_1" /></p>';
	echo "</div>";
	
	echo "<div style='padding-top:8px;'><h3>Short code 2</h3></div>";

	echo "<div style='border: 1px solid #DDDDDD;padding-left:10px;'>";
	echo '<p>Image directory:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_dir_2 . '" name="TISG_dir_2" id="TISG_dir_2" /> [TISG:CODE=SD2:W=200:H=150]</p>';
	echo '<p>Image hyper link:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_link_2 . '" name="TISG_link_2" id="TISG_link_2" /></p>';
	echo "</div>";
	
	echo "<div style='padding-top:8px;'><h3>Short code 3</h3></div>";

	echo "<div style='border: 1px solid #DDDDDD;padding-left:10px;'>";
	echo '<p>Image directory:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_dir_3 . '" name="TISG_dir_3" id="TISG_dir_3" /> [TISG:CODE=SD3:W=200:H=150]</p>';
	echo '<p>Image hyper link:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_link_3 . '" name="TISG_link_3" id="TISG_link_3" /></p>';
	echo "</div>";
	
	echo "<div style='padding-top:8px;'><h3>Short code 4</h3></div>";

	echo "<div style='border: 1px solid #DDDDDD;padding-left:10px;'>";
	echo '<p>Image directory:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_dir_4 . '" name="TISG_dir_4" id="TISG_dir_4" /> [TISG:CODE=SD4:W=200:H=150]</p>';
	echo '<p>Image hyper link:<br><input  style="width: 650px;" type="text" value="';
	echo $TISG_link_4 . '" name="TISG_link_4" id="TISG_link_4" /></p>';
	echo "</div>";
	
	echo "<div style='padding-top:8px;'></div>";
	
	echo '<input type="submit" class="button-primary" id="TISG_submit" name="TISG_submit" value="submit" />';
	echo '</form>';
	
	echo "</div>";
	
	?>
	<h2>Plugin configuration</h2>
	Plug-in created by <a target="_blank" href='http://www.gopiplus.com/work/'>www.gopiplus.com</a><br>
	Check official website for Live demo and Video tutorial <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/'>click here</a><br>
    Note : You have to change the default folder location to your original location. (Best practice is don't upload your original image into this default folder instead you change this default path to original path). <a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/'>Click here</a> for more help.
    <br><br>
	<?php
}


add_filter('the_content','TISG_show_filter');

function TISG_show_filter($content)
{
	return 	preg_replace_callback('/\[TISG:(.*?)\]/sim','TISG_show_filter_callback',$content);
}

function TISG_show_filter_callback($matches) 
{
	global $wpdb;
	$TISG_returnstr = "";
	
	$scode = $matches[1];
	
	list($TISG_type_main, $TISG_sliderwidth_main, $TISG_sliderheight_main) = split("[:.-]", $scode);
	// [TISG:CODE=SD1:W=200:H=150]
	
	list($TISG_type_cap, $TISG_type) = split('[=.-]', $TISG_type_main);
	list($TISG_type_cap, $TISG_width) = split('[=.-]', $TISG_sliderwidth_main);
	list($TISG_type_cap, $TISG_height) = split('[=.-]', $TISG_sliderheight_main);
	
	$TISG_delay = get_option('TISG_delay');
	$TISG_speed = get_option('TISG_speed');
	
	if($TISG_type == "SD1")
	{
		$TISG_dir = get_option('TISG_dir_1');
		$TISG_link = get_option('TISG_link_1');
		$myslideshow = "SD1";
	}
	else if($TISG_type == "SD2")
	{
		$TISG_dir = get_option('TISG_dir_2');
		$TISG_link = get_option('TISG_link_2');	
		$myslideshow = "SD2";
	}
	else if($TISG_type == "SD3")
	{
		$TISG_dir = get_option('TISG_dir_3');
		$TISG_link = get_option('TISG_link_3');
		$myslideshow = "SD3";
	}
	else if($TISG_type == "SD4")
	{
		$TISG_dir = get_option('TISG_dir_4');
		$TISG_link = get_option('TISG_link_4');
		$myslideshow = "SD4";
	}
	else
	{
		$TISG_dir = get_option('TISG_dir');
		$TISG_link = get_option('TISG_link');
		$myslideshow = "SD0";
	}
	
	if(is_dir($TISG_dir))
	{
		$siteurl_link = get_option('siteurl') . "/";
		
		if(!is_numeric(@$TISG_width)) { @$TISG_width = 200; }
		if(!is_numeric(@$TISG_height)) { @$TISG_height = 150; }
		if(!is_numeric(@$TISG_delay)) { @$TISG_delay = 3000; }
		if(!is_numeric(@$TISG_speed)) { @$TISG_speed = 400; }
	
		$TISG_dirHandle = opendir($TISG_dir);
		$TISG_count = 0;
		while ($TISG_file = readdir($TISG_dirHandle)) 
		{
		  if(!is_dir($TISG_file) && (strpos($TISG_file, '.jpg')>0 or strpos($TISG_file, '.gif')>0 or strpos($TISG_file, '.png')>0)) 
		  {
			 if($TISG_link == "" )
			 {
				$TISG_str =  '["' . $siteurl_link . $TISG_dir . $TISG_file . '", "", ""],';
			 }
			 else
			 {
				$TISG_str =  '["' . $siteurl_link . $TISG_dir . $TISG_file . '", "'. $TISG_link .'", "_new"],';
			 }
			 $TISG_returnstr = $TISG_returnstr . $TISG_str;
			 $TISG_count++;
		  }
		} 
		$TISG_returnstr = substr($TISG_returnstr,0,(strlen($TISG_returnstr)-1));
		closedir($TISG_dirHandle);
	
		//$tiag = '<script type="text/javascript" src="'.$siteurl_link.'wp-content/plugins/translucent-image-slideshow-gallery/jquery.min.js"><script>';
		//$tiag = $tiag . '<script type="text/javascript" src="'.$siteurl_link.'wp-content/plugins/translucent-image-slideshow-gallery/translucent-image-slideshow-gallery.js"><script> ';
		$tiag = '<script type="text/javascript"> ';
		$tiag = $tiag . 'var TISG=new TISG_translideshow({ ';
			$tiag = $tiag . 'TISG_wrapperid: "'.$myslideshow.'", ';
			$tiag = $tiag . 'TISG_dimensions: ['.$TISG_width.','.$TISG_height.'], ';
			$tiag = $tiag . 'TISG_imagearray: ['.$TISG_returnstr.'], ';
			$tiag = $tiag . 'TISG_displaymode: {type:"auto", pause:'.$TISG_delay.', cycles:5, pauseonmouseover:true}, ';
			$tiag = $tiag . 'TISG_orientation: "h",  ';
			$tiag = $tiag . 'TISG_persist: true,  ';
			$tiag = $tiag . 'TISG_slideduration: '.$TISG_speed;
		$tiag = $tiag . '}) ';
		$tiag = $tiag . '</script> ';
	
		$tiag = $tiag . '<div id="'.$myslideshow.'"></div>';
	}
	else
	{
		$tiag = $TISG_dir . " - Folder Does Not Exist";
	}
	return $tiag;
	
}
function TISG_add_to_menu() 
{
	add_options_page('Translucent slideshow', 'Translucent slideshow', 'manage_options', __FILE__, 'ISG_admin_option' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'TISG_add_to_menu');
}

function TISG_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'jquery.min', get_option('siteurl').'/wp-content/plugins/translucent-image-slideshow-gallery/jquery.min.js');
		wp_enqueue_script( 'translucent-image-slideshow-gallery', get_option('siteurl').'/wp-content/plugins/translucent-image-slideshow-gallery/translucent-image-slideshow-gallery.js');
	}	
}

add_action('init', 'TISG_add_javascript_files');

add_action("plugins_loaded", "TISG_widget_init");
register_activation_hook(__FILE__, 'TISG_install');
register_deactivation_hook(__FILE__, 'TISG_deactivation');
add_action('init', 'TISG_widget_init');
?>
