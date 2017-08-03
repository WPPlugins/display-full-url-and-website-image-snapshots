<?php 
/*
Plugin Name: Advertise Me Full URL display in post and Image Snapshot
Plugin URI: http://www.advertiseme.com.au
Description: Display bit.ly/tinyurl/t.co full URL in the post and website image snapshot
Author URI: http://www.advertiseme.com.au
Version: Initial
*/

$converionLinkArray = array('http://bit.ly','http://tinyurl.com','http://ping.fm','http://post.ly','http://url4.eu','http://t.co');
function changeShortURL($content)
{
	global $post, $converionLinkArray;
	$post_id = $post;
	if (is_object($post_id)) 
	{
		$post_id = $post_id->ID;
	}
	if(is_single())
	{
		$tiny_url_expand = get_post_meta( $post_id, 'tiny_url_expand', true);
		
		if($tiny_url_expand != 'No') 
		{
			preg_match_all("/<a href=\"([^\"]*)\">(.*)<\/a>/iU",$content,$matches);
			if($matches) 
			{
				$y = 0;
				foreach($matches[1] as $url)
				{
					$i = false;
					
					foreach($converionLinkArray as $converionLink)
					{
						if(preg_match("|".$converionLink."|", $url)) {
							$i = true;
							break;
						}
					}
					if($i == true)
					{
						/*
						$surl = 'http://untiny.me/api/1.0/extract/?url='.$url."&format=json";
						$c = curl_init();  
						curl_setopt($c,CURLOPT_URL, $surl);  
						curl_setopt($c,CURLOPT_HEADER,false);  
						curl_setopt($c,CURLOPT_RETURNTRANSFER,1);  
						$result = curl_exec($c); 
						curl_close($c);  
						$obj = json_decode($result, true);
						if($obj['org_url'])
						{
							$content = preg_replace('|'.$url.'|', $obj['org_url'],$content);
						}
						*/
						/* In case of xml format
						*/	
						$surl = 'http://untiny.me/api/1.0/extract/?url='.$url;
						$xmlArray = my_xml2array($surl);
						if($xmlArray[0][0]['name'] == 'org_url')
						{
							$content = preg_replace('|'.$url.'|', $xmlArray[0][0]['value'],$content);
						}
						
					}
					$y++;
				}	 
			}
		}
	}	
	return $content;
}

function my_xml2array($__url)
{
    $xml_values = array();
    $contents = file_get_contents($__url);
    $parser = xml_parser_create('');
    if(!$parser)
        return false;

    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);
    if (!$xml_values)
        return array();
   
    $xml_array = array();
    $last_tag_ar =& $xml_array;
    $parents = array();
    $last_counter_in_tag = array(1=>0);
    foreach ($xml_values as $data)
    {
        switch($data['type'])
        {
            case 'open':
                $last_counter_in_tag[$data['level']+1] = 0;
                $new_tag = array('name' => $data['tag']);
                if(isset($data['attributes']))
                    $new_tag['attributes'] = $data['attributes'];
                if(isset($data['value']) && trim($data['value']))
                    $new_tag['value'] = trim($data['value']);
                $last_tag_ar[$last_counter_in_tag[$data['level']]] = $new_tag;
                $parents[$data['level']] =& $last_tag_ar;
                $last_tag_ar =& $last_tag_ar[$last_counter_in_tag[$data['level']]++];
                break;
            case 'complete':
                $new_tag = array('name' => $data['tag']);
                if(isset($data['attributes']))
                    $new_tag['attributes'] = $data['attributes'];
                if(isset($data['value']) && trim($data['value']))
                    $new_tag['value'] = trim($data['value']);

                $last_count = count($last_tag_ar)-1;
                $last_tag_ar[$last_counter_in_tag[$data['level']]++] = $new_tag;
                break;
            case 'close':
                $last_tag_ar =& $parents[$data['level']];
                break;
            default:
                break;
        };
    }
    return $xml_array;
} 

function templateTinyUrlImage($align = '', $class = '', $type = 0)
{
	global $post,$converionLinkArray;
	$pContent = get_the_content();
	
	$post_id = $post;
	if (is_object($post_id)) 
	{
		$post_id = $post_id->ID;
	}
	$firstUrl = '';
	$found = 0;
	
	$tiny_url_image = get_post_meta( $post_id, 'tiny_url_image', true);
	$tiny_url_image_size = get_post_meta( $post_id, 'tiny_url_image_size', true);
	if($tiny_url_image_size != '')
	{
		$s = $tiny_url_image_size;
	}
	else 
	{
		$s = 's';
	}
	if($type == 1)
	{
		$s = 't';
	}

	if($tiny_url_image != 'No') 
	{
		preg_match_all("/<a href=\"([^\"]*)\">(.*)<\/a>/iU",$pContent,$matches);
		if($matches) 
		{
			foreach($matches[1] as $url)
			{
				
				foreach($converionLinkArray as $converionLink)
				{
					if(preg_match("|".$converionLink."|", $url)) {
						$firstUrl = $url;
						break;
					}
				}
				if($firstUrl != '')
				{
					break;
				}
			}
			if($firstUrl != '')
			{
				/*$surl = 'http://untiny.me/api/1.0/extract/?url='.$firstUrl."&format=json";
				$c = curl_init();  
				curl_setopt($c,CURLOPT_URL, $surl);  
				curl_setopt($c,CURLOPT_HEADER,false);  
				curl_setopt($c,CURLOPT_RETURNTRANSFER,1);  
				$result = curl_exec($c); 
				curl_close($c);  
				$obj = json_decode($result, true);
				if($obj['org_url'])
				{
					if($tiny_url_image_size != '')
					{
						$s = $tiny_url_image_size;
					}
					else 
					{
						$s = 's';
					}
					$firstUrl = websnaper($obj['org_url'], $s, $align, $class);
				}
				*/
				/* In case of xml format
				*/
				$surl = 'http://untiny.me/api/1.0/extract/?url='.$firstUrl;
				$xmlArray = my_xml2array($surl);
				if($xmlArray[0][0]['name'] == 'org_url')
				{
					$found = 1;
					$firstUrl = websnaper($xmlArray[0][0]['value'], $s, $align, $class);
				}
			}
		}
		
		if($found == 0)
		{
			$wpo_sourcepermalink = get_post_meta( $post_id, 'wpo_sourcepermalink', true);
			if($wpo_sourcepermalink!='')
			{
				$firstUrl = websnaper($wpo_sourcepermalink, $s, $align, $class);
			}
		}
	}	
	echo $firstUrl;
}

function websnaper($url , $s = 's', $align = 'right', $class = '')
{
	global $wpdb;
	$websnaprKey = get_option('websnapr_developer_key');
	/*return '<a href="'.$url.'"><img align="'.$align.'" class="'.$class.'" src="http://images.websnapr.com?url='.$url.'&size='.$s.'&key='.$websnaprKey.'"></a>';*/
	return '<script type="text/javascript">wsr_snapshot(\''.$url.'\', \''.$websnaprKey.'\', \''.$s.'\');</script>';
}

function custom_tiny_url_image_post_options()
{
	global $post;
	$post_id = $post;
	if (is_object($post_id)) 
	{
		$post_id = $post_id->ID;
	}
	$tiny_url_expand = get_post_meta( $post_id, 'tiny_url_expand', true );
	$tiny_url_image = get_post_meta( $post_id, 'tiny_url_image', true );
	$tiny_url_image_size = get_post_meta( $post_id, 'tiny_url_image_size', true );
	
?>
	<div class="postbox closed">
		<h3>Tiny Options</h3>
		<div class="inside">
			<?php custom_tiny_url_image_form($tiny_url_expand, $tiny_url_image, $tiny_url_image_size, $post_id); ?>
		</div>
	</div>
	<?php
}

function custom_tiny_url_image_form($tiny_url_expand, $tiny_url_image, $tiny_url_image_size, $post_id) {
?>
	<input value="<?php echo $tiny_url_expand; ?>" type="hidden" name="tiny_url_expand" id="tiny_url_expand" />
	<input value="<?php echo $tiny_url_image; ?>" type="hidden" name="tiny_url_image" id="tiny_url_image" />
	<input value="<?php echo $post_id; ?>" type="hidden" name="tiny_url_options" id="tiny_url_options" />
	
	<table class="form-table" id="tiny_url_options">
		<tr>
			<th scope="row">Tiny URL expand</th>
			<td>
				<select name="new_tiny_url_expand" style="width:55px">
					<option value="Yes" <?php if($tiny_url_expand == '' || $tiny_url_expand == 'Yes') echo "selected"; ?>>Yes</option>
					<option value="No" <?php if($tiny_url_expand == 'No') echo "selected"; ?>>No</option>
				</select>	
			</td>
		</tr>
		<tr>
			<th scope="row">Show Image</th>
			<td>
				<select name="new_tiny_url_image" style="width:55px">
					<option value="Yes" <?php if($tiny_url_image == '' || $tiny_url_image == 'Yes') echo "selected"; ?>>Yes</option>
					<option value="No" <?php if($tiny_url_image == 'No') echo "selected"; ?>>No</option>
				</select>	
			</td>
		</tr>
		<tr>
			<th scope="row">Image size</th>
			<td>
				<select name="new_tiny_url_image_size" style="width:150px">
					<option value="t" <?php if($tiny_url_image_size == 't') echo "selected"; ?>>Tiny(90x70)</option>
					<option value="s" <?php if($tiny_url_image_size == '' || $tiny_url_image_size == 's') echo "selected"; ?>>Small(200x150)</option>
					<option value="m" <?php if($tiny_url_image_size == 'm') echo "selected"; ?>>Medium(400x300, Not supported in free version)</option>
					<option value="l" <?php if($tiny_url_image_size == 'l') echo "selected"; ?>>Large(640x480, Not supported in free version)</option>
				</select>	
			</td>
		</tr>
	</table>
<?php
}

function custom_tiny_url_image_save_post($id) {
	if ( !isset($_REQUEST['tiny_url_options']) &&  $_REQUEST['tiny_url_options'] != $id) return;
	
	update_post_meta($id, 'tiny_url_expand', $_REQUEST['new_tiny_url_expand']);
	update_post_meta($id, 'tiny_url_image', $_REQUEST['new_tiny_url_image']);
	update_post_meta($id, 'tiny_url_image_size', $_REQUEST['new_tiny_url_image_size']);
}

function websnaprSettings()
{
	global $wpdb;
	include("websnaprsettings.php");
}
 
function admin_action_websnapr_setting()
{
	add_options_page("Websnapr Settings", "Websnapr Settings", 10 , "websnapr_settings", "websnaprSettings");
}

add_action('admin_menu', 'admin_action_websnapr_setting');
add_action('edit_form_advanced', 'custom_tiny_url_image_post_options' );
add_action('save_post', 'custom_tiny_url_image_save_post' );
add_filter('the_content', 'changeShortURL',1);
?>