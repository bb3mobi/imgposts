<?php
/**
*
* @package Images from posts
* @copyright (c) 2014 Anvar [http://bb3.mobi]
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace bb3mobi\imgposts\acp;

class imgposts_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $config, $user, $template;

		$this->page_title = 'ACP_IMG_FROM_POSTS';
		$this->tpl_name = 'acp_imgposts';

		$submit = (isset($_POST['submit'])) ? true : false;
		$form_key = 'config_exlinks';
		add_form_key($form_key);

		$display_vars = array(
			'title'	=> 'ACP_IMG_POSTS',
			'vars'	=> array(
				'legend1'	=> 'LAST_IMAGES_ATACHMENT',
					'last_images_attachment'				=> array('lang' => 'IMAGES_PLACE_TYPE',		'validate' => 'int',		'type' => 'select', 'method' => 'select_display_type', 'explain' => false),
					'last_images_attachment_ignore'			=> array('lang' => 'IMAGES_IGNORE_FORUM',	'validate' => 'string',		'type' => 'custom', 'method' => 'select_forums', 'explain' => true),
					'last_images_attachment_ignore_topic'	=> array('lang' => 'IMAGES_IGNORE_TOPIC',	'validate' => 'string',		'type' => 'text:40:40', 'explain' => true),
					'last_images_attachment_all'			=> array('lang' => 'IMAGES_ATTACHMENT_ALL',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => true),
					'last_images_attachment_bottom'			=> array('lang' => 'IMAGES_BOTTOM_TYPE',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => false),
					'last_images_attachment_top_invert'		=> array('lang' => 'IMAGES_TOP_INVERT',		'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => true),
					'last_images_attachment_carousel'		=> array('lang' => 'IMAGES_CAROUSEL_TYPE',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => true),
					'last_images_attachment_count'			=> array('lang' => 'IMAGES_COUNT_IMG',		'validate' => 'int:4:40',	'type' => 'number:4:40', 'explain' => false),
					'last_images_attachment_count_min'		=> array('lang' => 'IMAGES_COUNT_IMG_MIN',	'validate' => 'int:4:40',	'type' => 'number:4:10', 'explain' => true),
					'last_images_attachment_size'			=> array('lang' => 'IMAGES_SIZE_IMG',		'validate' => 'int:30:200',	'type' => 'number:30:200', 'explain' => false),
					'images_copy_bottom'					=> array('lang' => 'Watrmarks',				'validate' => 'string',		'type' => 'text:20:20', 'explain' => false),

				'legend2'	=> 'IMAGES_SETTINGS',
					'images_new_path'		=> array('lang' => 'IMAGES_NEW_PATH',		'validate' => 'string',	'type' => 'text:30:30', 'explain' => true),
					'images_height_width'	=> array('lang' => 'IMAGES_HEIGHT_WIDTH',	'validate' => 'bool',	'type' => 'radio:yes_no', 'explain' => false),

				'legend3'	=> 'FIRST_IMAGES_TOPIC',
					'first_images_from_topic'	=> array('lang' => 'FIRST_IMAGES_TOPIC_ON',		'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => false),
					'first_images_forum_ignore'	=> array('lang' => 'IMAGES_IGNORE_FORUM',		'validate' => 'string',		'type' => 'custom', 'method' => 'select_forums', 'explain' => true),
					'first_images_size'			=> array('lang' => 'IMAGES_SIZE_IMG',			'validate' => 'int:30:200',	'type' => 'number:30:200', 'explain' => false),
					'first_images_float'		=> array('lang' => 'FIRST_IMAGES_FLOAT',		'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => false),
				'legend4'	=> 'ACP_SUBMIT_CHANGES',
			),
		);

		if (isset($display_vars['lang']))
		{
			$user->add_lang($display_vars['lang']);
		}

		$this->new_config = $config;
		$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), true)) : $this->new_config;
		$error = array();

		// We validate the complete config if wished
		validate_config_vars($display_vars['vars'], $cfg_array, $error);

		if ($submit && !check_form_key($form_key))
		{
			$error[] = $user->lang['FORM_INVALID'];
		}
		// Do not write values if there is an error
		if (sizeof($error))
		{
			$submit = false;
		}
		
		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($display_vars['vars'] as $config_name => $null)
		{
			if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
			{
				continue;
			}

			$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($submit)
			{
				set_config($config_name, $config_value);
			}
		}

		if ($submit)
		{
			// POST Forums config && Anvar (bb3.mobi)
			$config_ignore = array('last_images_attachment_ignore', 'last_images_img_ignore', 'first_images_forum_ignore');
			foreach ($config_ignore as $forum_ignore)
			{
				$values = request_var($forum_ignore, array(0 => ''));
				$news = implode(',', $values);
				set_config($forum_ignore, $news);
			}

			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
		}

		$this->page_title = $display_vars['title'];

		$template->assign_vars(array(
			'L_TITLE'				=> $user->lang[$display_vars['title']],
			'L_TITLE_EXPLAIN'		=> $user->lang[$display_vars['title'] . '_EXPLAIN'],
			'L_TITLE_DESCRIPTION'	=> $user->lang[$display_vars['title'] . '_DESCRIPTION'],

			'S_ERROR'			=> (sizeof($error)) ? true : false,
			'ERROR_MSG'			=> implode('<br />', $error),
		));
		
		// Output relevant page
		foreach ($display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$template->assign_block_vars('options', array(
					'S_LEGEND'		=> true,
					'LEGEND'		=> (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
				);

				continue;
			}

			$type = explode(':', $vars['type']);

			$l_explain = '';
			if ($vars['explain'] && isset($vars['lang_explain']))
			{
				$l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
			}
			else if ($vars['explain'])
			{
				$l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
			}

			$l_description = (isset($user->lang[$vars['lang'] . '_DESCRIPTION'])) ? $user->lang[$vars['lang'] . '_DESCRIPTION'] : '';

			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

			if (empty($content))
			{
				continue;
			}

			$template->assign_block_vars('options', array(
				'KEY'					=> $config_key,
				'TITLE'					=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
				'S_EXPLAIN'				=> $vars['explain'],
				'TITLE_EXPLAIN'			=> $l_explain,
				'TITLE_DESCRIPTION'		=> $l_description,
				'CONTENT'				=> $content,
				)
			);

			unset($display_vars['vars'][$config_key]);
		}
	}
	function select_forums($value, $key)
	{// Select Forums function && Anvar (bb3.mobi)
		global $user, $config;

		$forum_list = make_forum_select(false, false, true, true, true, false, true);

		$selected = array();
		if(isset($config[$key]) && strlen($config[$key]) > 0)
		{
			$selected = explode(',', $config[$key]);
		}
		// Build forum options
		$s_forum_options = '<select id="' . $key . '" name="' . $key . '[]" multiple="multiple">';
		foreach ($forum_list as $f_id => $f_row)
		{
			$s_forum_options .= '<option value="' . $f_id . '"' . ((in_array($f_id, $selected)) ? ' selected="selected"' : '') . (($f_row['disabled']) ? ' disabled="disabled" class="disabled-option"' : '') . '>' . $f_row['padding'] . $f_row['forum_name'] . '</option>';
		}
		$s_forum_options .= '</select>';

		return $s_forum_options;
	}
	/**
	* Select display method
	*/
	function select_display_type($selected_value, $value)
	{
		global $user;

		$act_ary = array(
			'IMAGES_PLACE_TYPE_OFF' => 0,
			'IMAGES_PLACE_TYPE_INDEX' => 1,
			'IMAGES_PLACE_TYPE_VIEW' => 2,
			'IMAGES_PLACE_TYPE_ALL' => 3,
		);
		$act_options = '';

		foreach ($act_ary as $key => $value)
		{
			$selected = ($selected_value == $value) ? ' selected="selected"' : '';
			$act_options .= '<option value="' . $value . '"' . $selected . '>' . $user->lang[$key] . '</option>';
		}

		return $act_options;
	}
}

?>