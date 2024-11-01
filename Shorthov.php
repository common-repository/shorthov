<?php
/*
Plugin Name: Shorthov more or less
Plugin URI: http://www.hetopenvizier.nl/shorthov/
Description: The very easy and simple &lt;!-- more --&gt; replacer. No more need to put the &lt;!--more--&gt; tag in your posts on the main blog page. Also works as an excerpt replacer. You can easily change the text of the 'more' link.  
	You can vary the abbreviation by the number of characters. 
	You can set the length of posts that should be abbreviated, if a post is shorter, then there will be no 'more' link.
	It is a blunt abbreviation, what means that words can be broken off. (What gives an extra teasing dimension.)
	Images and links are removed and therefor do not count by the abbreviation. 
	Other html-tags are kept intact.
	By using this plug-in, all your posts on your blog homepage are more or less the same length. 
Version: 1.0
Author: Stefan Schoonhoven
Author URI: http://www.hetopenvizier.nl/
License:     This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!is_plugin_page()) {
	function shorthov($text) {
		if (is_home()){
			$text = preg_replace("/<img[^>]+\>/i","",$text);
			$text = preg_replace("/<a[^>]+\>/i","",$text);
			if (strlen($text)>get_option('toolong')) {
				$text=substr($text,0, get_option('length'));
				$text = rtrim($text, '<');  
				if (strstr($text, "<i>") != null && strstr($text, "</i>") ==null) echo ($text.'</i><a href="'.get_permalink().'">'.get_option('text').'</a>');
				if (strstr($text, "<em>") != null && strstr($text, "</em>") ==null) echo ($text.'</i><a href="'.get_permalink().'">'.get_option('text').'</a>');
				if (strstr($text, "<b>") != null && strstr($text, "</b>") ==null) echo ($text.'</i><a href="'.get_permalink().'">'.get_option('text').'</a>');
				if (strstr($text, "<i>") == null && strstr($text, "<em>") == null && strstr($text, "<b>") == null) echo $text.'<a href="'.get_permalink().'">'.get_option('text').'</a>';
			}
			else return $text;
		}
		else return $text;
	}
	add_filter('the_content', 'shorthov');
}

add_action('admin_menu', 'shorthov_menu');

function shorthov_menu() {
	add_options_page('Shorthov more or less', Shorthov, 'manage_options',__file__, 'shorthov_opties');
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	add_option('text', '...more');
	add_option('length', '300');
	add_option('toolong', '450');
	register_setting( 'shorthov-settings', 'toolong' );
	register_setting( 'shorthov-settings', 'length' );
	register_setting( 'shorthov-settings', 'text' );
}

function shorthov_opties() {
?>
<div class="wrap">
<h2>Shorthov 1.0</h2>
The very easy and simple < more > replacer.
<hr>
<i>It is a blunt abbrevition: words can be broken off (what gives an extra teasing dimension). <br>
Images are removed before abbreviation. Links in the abbreviated post don't work. Other html-tags will be kept intact. The abbreviated posts will thus be the same length, more or less.</i>
<hr>

<p>
<form method="post" action="options.php">
	<?php settings_fields( 'shorthov-settings' ); ?>
	If the text is longer than this number of characters, it must be abbreviated:
	<input type="text" name="toolong" value="<?php echo get_option('toolong'); ?>" />
	<br>
    Text instead of 'more':
	<input type="text" name="text" value="<?php echo get_option('text'); ?>" />
	<br>
	Number of characters before the '<?php echo get_option('text'); ?>' link:
	<input type="text" name="length" value=" <?php echo get_option('length'); ?>" />
    <p>
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</form>
<hr>
<form method="post" action="options.php">
	<?php settings_fields( 'shorthov-settings' ); ?>
	<input type="hidden" name="text" value="...more" />
	<input type="hidden" name="length" value="300" />
	<input type="hidden" name="toolong" value="450" />
    <input type="submit" class="button-primary" value="<?php _e('Default Settings') ?>" />
</form>
</div>
<?php } ?>