<?php

/*
Plugin Name: Super transition slideshow
Plugin URI: http://www.gopipulse.com/work/2010/07/18/super-transition-slideshow/
Description: Don't just display images, showcase them in style using this Super transition slideshow plugin. Randomly chosen 
Transitional effects in IE browsers.  
Author: Gopi.R
Version: 6.0
Author URI: http://www.gopipulse.com/work/2010/07/18/super-transition-slideshow/
Donate link: http://www.gopipulse.com/work/2010/07/18/super-transition-slideshow/
*/

function sts_show() 
{
	$sts = "";
	$sts_siteurl = get_option('siteurl');
	$sts_dir = get_option('sts_dir');
	$sts_pluginurl = $sts_siteurl . "/wp-content/plugins/super-transition-slideshow/";
	$sts_dirurl = $sts_siteurl . "/" . $sts_dir ;
	
	$sts_dirhandle = opendir($sts_dir);
	while ($sts_file = readdir($sts_dirhandle)) 
	{
	  if(!is_dir($sts_file) && (strpos($sts_file, '.jpg')>0 or strpos($sts_file, '.gif')>0 or strpos($sts_file, '.JPG')>0 or strpos($sts_file, '.GIF')>0))
	  {
		$sts = $sts . '["'.$sts_dirurl . $sts_file.'", "", "", ""],';
	  }
	}
	closedir($sts_dirhandle);
	$sts = substr($sts,0,(strlen($sts)-1));
	?>
	<link rel='stylesheet' href='<?php echo $sts_pluginurl; ?>super-transition-slideshow.css' type='text/css' />
    <script type="text/javascript">

	var flashyshow=new super_transition_slideshow({ 
		wrapperid: "sts_slideshow", 
		wrapperclass: "sts_class", 
		imagearray: [
			<?php echo $sts; ?>
		],
		pause: <?php echo get_option('sts_pause'); ?>, 
		transduration: <?php echo get_option('sts_transduration'); ?> 
	})
	
	</script>
    <?php
}


add_filter('the_content','sts_show_filter');

function sts_show_filter($content)
{
	return 	preg_replace_callback('/\[SUPER-SLIDESHOW:(.*?)\]/sim','sts_show_filter_callback',$content);
}

function sts_show_filter_callback($matches) 
{
	global $wpdb;
	$sts_return = "";
	$sts = "";
	
	$scode = $matches[1];
	//[SUPER-SLIDESHOW:TYPE=DIR1]
	
	$sts_pause = get_option('sts_pause');
	$sts_transduration = get_option('sts_transduration');
	
	if(!is_numeric($sts_pause)){ $sts_pause =2000;	}
	if(!is_numeric($sts_transduration)){ $sts_transduration = 3000; }
	
	list($sts_type_main) = split("[:.-]", $scode);
	list($sts_type_cap, $sts_type) = split('[=.-]', $sts_type_main);
	
	if($sts_type == "DIR1")
	{
		$sts_dir = get_option('sts_dir_1');
	}
	else if($sts_type == "DIR2")
	{
		$sts_dir = get_option('sts_dir_2');
	}
	else if($sts_type == "DIR3")
	{
		$sts_dir = get_option('sts_dir_3');
	}
	else if($sts_type == "DIR4")
	{
		$sts_dir = get_option('sts_dir_4');
	}
	else
	{
		$sts_dir = get_option('sts_dir');
	}
	
	$sts_siteurl = get_option('siteurl');
	$sts_pluginurl = $sts_siteurl . "/wp-content/plugins/super-transition-slideshow/";
	$sts_dirurl = $sts_siteurl . "/" . $sts_dir ;
	
	//echo $sts_dir;
	
	$sts_dirhandle = opendir($sts_dir);
	while ($sts_file = readdir($sts_dirhandle)) 
	{
	  if(!is_dir($sts_file) && (strpos($sts_file, '.jpg')>0 or strpos($sts_file, '.gif')>0 or strpos($sts_file, '.JPG')>0 or strpos($sts_file, '.GIF')>0))
	  {
		$sts = $sts . '["'.$sts_dirurl . $sts_file.'", "", "", ""],';
	  }
	}
	closedir($sts_dirhandle);
	$sts = substr($sts,0,(strlen($sts)-1));

	$sts_return = $sts_return . "<link rel='stylesheet' href='".$sts_pluginurl."super-transition-slideshow.css' type='text/css' />";
    $sts_return = $sts_return . "<script type='text/javascript' src='".$sts_pluginurl."super-transition-slideshow.js'></script>";
    $sts_return = $sts_return . "<script type='text/javascript'>";

	$sts_return = $sts_return . "var flashyshow=new super_transition_slideshow({ ";
		$sts_return = $sts_return . "wrapperid: 'sts_slideshow_".$sts_type."', ";
		$sts_return = $sts_return . "wrapperclass: 'sts_clas', ";
		$sts_return = $sts_return . "imagearray: [";
			$sts_return = $sts_return . $sts;
		$sts_return = $sts_return . "],";
		$sts_return = $sts_return . "pause: ".$sts_pause." , ";
		$sts_return = $sts_return . "transduration: ".$sts_transduration." ";
	$sts_return = $sts_return . "})";
	$sts_return = $sts_return . "</script>";
	return $sts_return;
}

function sts_install() 
{
	add_option('sts_title', "Slideshow");
	add_option('sts_dir', "wp-content/plugins/super-transition-slideshow/images/");
	add_option('sts_title_yes', "YES");
	add_option('sts_pause', "2000");
	add_option('sts_transduration', "1000");
	add_option('sts_dir_1', "");
	add_option('sts_dir_2', "");
	add_option('sts_dir_3', "");
	add_option('sts_dir_4', "");
}

function sts_widget($args) 
{
	extract($args);
	if(get_option('sts_title_yes') == "YES") 
	{
		echo $before_widget . $before_title;
		echo get_option('sts_title');
		echo $after_title;
	}
	else
	{
		echo '<div style="padding-top:5px;padding-bottom:5px;">';
	}
	sts_show();
	if(get_option('sts_title_yes') == "YES") 
	{
		echo $after_widget;
	}
	else
	{
		echo '</div>';
	}
}

function sts_admin_option() 
{
	
	echo "<div class='wrap'>";
	echo "<h2>Super transition slideshow</h2>"; 
    
	$sts_title = get_option('sts_title');
	$sts_dir = get_option('sts_dir');
	$sts_title_yes = get_option('sts_title_yes');
	$sts_pause = get_option('sts_pause');
	$sts_transduration = get_option('sts_transduration');
	$sts_dir_1 = get_option('sts_dir_1');
	$sts_dir_2 = get_option('sts_dir_2');
	$sts_dir_3 = get_option('sts_dir_3');
	$sts_dir_4 = get_option('sts_dir_4');
	
	if (@$_POST['sts_submit']) 
	{
		$sts_title = stripslashes($_POST['sts_title']);
		$sts_dir = stripslashes($_POST['sts_dir']);
		$sts_title_yes = stripslashes($_POST['sts_title_yes']);
		$sts_pause = stripslashes($_POST['sts_pause']);
		$sts_transduration = stripslashes($_POST['sts_transduration']);
		$sts_dir_1 = stripslashes($_POST['sts_dir_1']);
		$sts_dir_2 = stripslashes($_POST['sts_dir_2']);
		$sts_dir_3 = stripslashes($_POST['sts_dir_3']);
		$sts_dir_4 = stripslashes($_POST['sts_dir_4']);
		
		update_option('sts_title', $sts_title );
		update_option('sts_dir', $sts_dir );
		update_option('sts_title_yes', $sts_title_yes );
		update_option('sts_pause', $sts_pause );
		update_option('sts_transduration', $sts_transduration );
		update_option('sts_dir_1', $sts_dir_1 );
		update_option('sts_dir_2', $sts_dir_2 );
		update_option('sts_dir_3', $sts_dir_3 );
		update_option('sts_dir_4', $sts_dir_4 );
		
	}
	?>
	<form name="form_sts" method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td align="left">
	<?php
	echo '<p>Title:<br><input  style="width: 450px;" maxlength="200" type="text" value="';
	echo $sts_title . '" name="sts_title" id="sts_title" /></p>';
	echo '<p>Pause:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $sts_pause . '" name="sts_pause" id="sts_pause" /> Only Number / Pause between content change (millisec)</p>';
	echo '<p>Transduration:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $sts_transduration . '" name="sts_transduration" id="sts_transduration" /> Only Number / Duration of transition (affects only IE users)</p>';
	echo '<p>Display Sidebar Title:<br><input maxlength="3" style="width: 100px;" type="text" value="';
	echo $sts_title_yes . '" name="sts_title_yes" id="sts_title_yes" /> (YES/NO)</p>';
	echo '<p>Image directory:<br><input  style="width: 550px;" type="text" value="';
	echo $sts_dir . '" name="sts_dir" id="sts_dir" /> [SUPER-SLIDESHOW:TYPE=DIR0]</p>';
	
	echo '<p>Image directory 1:<br><input  style="width: 550px;" type="text" value="';
	echo $sts_dir_1 . '" name="sts_dir_1" id="sts_dir_1" /> [SUPER-SLIDESHOW:TYPE=DIR1]</p>';
	
	echo '<p>Image directory 2:<br><input  style="width: 550px;" type="text" value="';
	echo $sts_dir_2 . '" name="sts_dir_2" id="sts_dir_2" /> [SUPER-SLIDESHOW:TYPE=DIR2]</p>';
	
	echo '<p>Image directory 3:<br><input  style="width: 550px;" type="text" value="';
	echo $sts_dir_3 . '" name="sts_dir_3" id="sts_dir_3" /> [SUPER-SLIDESHOW:TYPE=DIR3]</p>';
	
	echo '<p>Image directory 4:<br><input  style="width: 550px;" type="text" value="';
	echo $sts_dir_4 . '" name="sts_dir_4" id="sts_dir_4" /> [SUPER-SLIDESHOW:TYPE=DIR4]</p>';
	
	echo '<p>Default Image directory:<br>wp-content/plugins/super-transition-slideshow/images/ <br>';
	echo 'Dont upload your original images into this defult folder, instead you change this default path to original path from the above text box.</p>';
	
	echo '<input name="sts_submit" id="sts_submit" class="button-primary" value="Submit" type="submit" />';
	?>
	</td><td align="center" valign="middle"> </td></tr></table>
	</form>
    <h2>Plugin configuration option</h2>
	<ol>
	<li>Drag and drop the widget</li>
	<li>Short code for pages and posts</li>
	<li>Add directly in the theme</li>
	</ol>
	Note: check official website for live demo and more information <a target="_blank" href='http://www.gopipulse.com/work/2010/07/18/super-transition-slideshow/'>click here</a><br> 
	<h2>Note</h2>
	<p style="color:#990000;">
	1. This plug-in will not create any thumbnail of the image.<br>
	2. To change or use the fixed width take "super-transition-slideshow.js" file from plug-in directory and go to line 63 and fix the width, see below.<br>
	<code>
	slideHTML+='&lt;img src=&quot;'+this.imagearray[index][0]+'&quot; /&gt;'<br>
	to<br>
	slideHTML+='&lt;img width=&quot;200&quot; HEIGHT=&quot;150&quot; src=&quot;'+this.imagearray[index][0]+'&quot; /&gt;'</code><br>
	3. And take the "super-transition-slideshow.css" css file from same directory and set the width to "sts_class" class.<br>
	4. in default am using 200 X 150 size images.<br>
    <br></p>
	<?php
	echo "</div>";
}

function sts_control()
{
	echo '<p>Super transition slideshow.<br> To change the setting goto Super transition slideshow link on Setting menu.';
	echo ' <a href="options-general.php?page=super-transition-slideshow/super-transition-slideshow.php">';
	echo 'click here</a></p>';
	?>
	check official website for live demo and more information  <a target="_blank" href='http://www.gopipulse.com/work/2010/07/18/super-transition-slideshow/'>click here</a><br> 
	<?php
}

function sts_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('Super-transition-slideshow', 'Super transition slideshow', 'sts_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('Super-transition-slideshow', array('Super transition slideshow', 'widgets'), 'sts_control');
	} 
}

function sts_deactivation() 
{
//	delete_option('sts_title');
//	delete_option('sts_dir');
//	delete_option('sts_title_yes');
//	delete_option('sts_pause');
//	delete_option('sts_transduration');
}

function sts_add_to_menu() 
{
	add_options_page('Super transition slideshow', 'Super transition slideshow', 'manage_options', __FILE__, 'sts_admin_option' );
}
if (is_admin()) 
{
	add_action('admin_menu', 'sts_add_to_menu');
}

function sts_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'super-transition-slideshow', get_option('siteurl').'/wp-content/plugins/super-transition-slideshow/super-transition-slideshow.js');
	}	
}

add_action('init', 'sts_add_javascript_files');

add_action("plugins_loaded", "sts_widget_init");
register_activation_hook(__FILE__, 'sts_install');
register_deactivation_hook(__FILE__, 'sts_deactivation');
add_action('init', 'sts_widget_init');
?>
