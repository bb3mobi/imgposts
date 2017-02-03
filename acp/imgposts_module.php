<?php
/**
*
* @package Images from posts
* @copyright (c) 2014 Anvar (http://bb3.mobi), (c) 2015 Sheer(http://phpbbguru.net)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace bb3mobi\imgposts\acp;

class imgposts_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $config, $phpbb_root_path, $user, $template, $php_ext, $db, $auth, $request, $phpbb_log;

		$phpbb_ext_kb = new \bb3mobi\imgposts\core\helper($template, $phpbb_log, $config, $user, $auth, $db, $phpbb_root_path, $php_ext);

		$this->page_title = 'ACP_IMG_FROM_POSTS';
		$this->tpl_name = 'acp_imgposts';

		add_form_key('bb3mobi/imgposts');

		$mode = request_var('mode', '');

		if ($mode == 'manage_imgposts')
		{
			// Manage thumbs (c) Sheer
			$forum_id	= $request->variable('forum_id', 0);
			$num		= $request->variable('num', $config['last_images_attachment_count']);
			$forum_list	= make_forum_select(false, false, true, true, true, false, true);
			$s_forum_options = '';
			foreach ($forum_list as $key => $value)
			{
				$s_forum_options .='<option value="' . $value['forum_id'] . '" ' . (($value['disabled']) ? ' disabled="disabled" class="disabled-option"' : '') . '>' . $value['padding'] . $value['forum_name'] . '</option value>';
			}

			$template->assign_vars(array(
				'S_SELECT_FORUM'	=> true,
				'S_FORUM_OPTIONS'	=> $s_forum_options,
			));

			if ($request->is_set_post('create'))
			{
				if ($forum_id)
				{
					$result = $phpbb_ext_kb->last_images($forum_id, $num);
					$thumbs = $result['counts'][0];
					$created = $result['counts'][1];
					$thumb_names = implode('<br />', $result['thumbs']);

					if ($created)
					{
						$message = sprintf($user->lang['THUMB_CREATED'], $thumbs, $created, $thumb_names);
					}
					else
					{
						$message = sprintf($user->lang['THUMB_NOT_NEED_CREATE'], $thumbs);
					}
					meta_refresh(3, append_sid($this->u_action));
					trigger_error($message);
				}
				else
				{
					meta_refresh(3, append_sid($this->u_action));
					trigger_error($user->lang['NOT_FORUM_SELECTED'], E_USER_WARNING);
				}
			}

			if ($request->is_set_post('clear_all'))
			{
				if (!check_form_key('bb3mobi/imgposts'))
				{
					trigger_error('FORM_INVALID', E_USER_WARNING);
				}

				$handle = @opendir($phpbb_root_path . $config['images_new_path']);
				$files  = array();
				if ($handle)
				{
					while ($file = readdir($handle))
					{
						if ($file != '.' && $file != '..' && $file != '.htaccess' && $file != 'index.htm' && $file != 'index.html')
						{
							$files[] = $file;
						}
					}
					closedir($handle);
					if (!empty($files))
					{
						foreach ($files as $del_file)
						{
							@unlink($phpbb_root_path . $config['images_new_path'] . $del_file);
						}
						meta_refresh(3, append_sid($this->u_action));
						trigger_error($user->lang['CLEAR_ALL_SUCCESS'] . adm_back_link($this->u_action));
					}
					else
					{
						$message = $user->lang['CLEAR_ALL_EMPTY'];
					}
				}
				else
				{
					$message = $user->lang['CLEAR_ALL_ERROR'];
				}
				meta_refresh(3, append_sid($this->u_action));
				trigger_error($message . adm_back_link($this->u_action), E_USER_WARNING);
			}

			if ($request->is_set_post('clear_old'))
			{
				if (!check_form_key('bb3mobi/imgposts'))
				{
					trigger_error('FORM_INVALID', E_USER_WARNING);
				}

				$result = $phpbb_ext_kb->clear_cache();
				meta_refresh(3, append_sid($this->u_action));
				if (!$result)
				{
					trigger_error($user->lang['CLEAR_OLD_SUCCESS'] . adm_back_link($this->u_action));
				}
				else
				{
					trigger_error('' . $user->lang[$result] . '' . adm_back_link($this->u_action), E_USER_WARNING);
				}
			}

			$cron_enable	= $request->variable('cron', $config['imgposts_cron']);
			$cron_freq		= $request->variable('prune', $config['images_prune_gc']);
			if ($request->is_set_post('submit'))
			{
				if (!check_form_key('bb3mobi/imgposts'))
				{
					trigger_error('FORM_INVALID', E_USER_WARNING);
				}

				$config->set('images_prune_gc', $cron_freq * 86400);
				$config->set('imgposts_cron', $cron_enable);
				meta_refresh(3, append_sid($this->u_action));
				trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
			}

			$template->assign_vars(array(
				'S_MANAGE'			=> true,
				'L_TITLE'			=> $user->lang['ACP_IMG_MANAGE_POSTS'],
				'L_TITLE_EXPLAIN'	=> $user->lang['ACP_IMG_MANAGE_POSTS_EXPLAIN'],
				'PRUNE_DAYS'		=> round($config['images_prune_gc'] / 86400),
				'S_CHECKED_DISABLE'	=> (!$config['imgposts_cron']) ? ' checked="checked" ' : '',
				'S_CHECKED_ENABLE'	=> ($config['imgposts_cron']) ? ' checked="checked" ' : '',
				'NUM'				=> $num,
				'U_ACTION'			=> $this->u_action,
			));
		}
		else
		{
			$submit = $request->is_set_post('submit');

			$display_vars = array(
				'title'	=> 'ACP_IMG_POSTS',
				'vars'	=> array(
					'legend1'	=> 'LAST_IMAGES_ATACHMENT',
						'images_attachment'						=> array('lang' => 'IMAGES_ATACHMENT',		'validate' => 'select',		'type' => 'select', 'method' => 'select_display_mode', 'explain' => true),
						'last_images_attachment'				=> array('lang' => 'IMAGES_PLACE_TYPE',		'validate' => 'select',		'type' => 'select', 'method' => 'select_display_type', 'explain' => false),
						'last_images_attachment_ignore'			=> array('lang' => 'IMAGES_IGNORE_FORUM',	'validate' => 'string',		'type' => 'custom', 'method' => 'select_forums', 'explain' => true),
						'last_images_attachment_ignore_topic'	=> array('lang' => 'IMAGES_IGNORE_TOPIC',	'validate' => 'string',		'type' => 'text:40:40', 'explain' => true),
						'last_images_gallery'					=> array('lang' => 'IMAGES_PHPBB_GALLERY',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => true),
						'last_images_attachment_all'			=> array('lang' => 'IMAGES_ATTACHMENT_ALL',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => true),
						'last_images_attachment_bottom'			=> array('lang' => 'IMAGES_BOTTOM_TYPE',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => true),
						'last_images_attachment_top_invert'		=> array('lang' => 'IMAGES_TOP_INVERT',		'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => true),
						'last_images_attachment_carousel'		=> array('lang' => 'IMAGES_CAROUSEL_TYPE',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => true),
						'last_images_attachment_count'			=> array('lang' => 'IMAGES_COUNT_IMG',		'validate' => 'int:4:40',	'type' => 'number:4:40', 'explain' => true),
						'last_images_attachment_count_min'		=> array('lang' => 'IMAGES_COUNT_IMG_MIN',	'validate' => 'int:4:40',	'type' => 'number:4:10', 'explain' => true),
						'last_images_attachment_size'			=> array('lang' => 'IMAGES_SIZE_IMG',		'validate' => 'int:30:200',	'type' => 'number:30:200', 'explain' => false, 'append' => ' ' . $user->lang['PIXEL']),
						'images_copy_bottom'					=> array('lang' => 'WATERMARKS',			'validate' => 'string',		'type' => 'text:20:20', 'explain' => true),

					'legend2'	=> 'IMAGES_SETTINGS',
						'images_new_path'		=> array('lang' => 'IMAGES_NEW_PATH',		'validate' => 'string',	'type' => 'text:30:30', 'explain' => true),
						'images_height_width'	=> array('lang' => 'IMAGES_HEIGHT_WIDTH',	'validate' => 'bool',	'type' => 'radio:yes_no', 'explain' => true),

					'legend3'	=> 'FIRST_IMAGES_TOPIC',
						'first_images_from_topic'	=> array('lang' => 'FIRST_IMAGES_TOPIC_ON',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => false),
						'first_images_forum_ignore'	=> array('lang' => 'IMAGES_IGNORE_FORUM',	'validate' => 'string',		'type' => 'custom', 'method' => 'select_forums', 'explain' => true),
						'first_images_size'			=> array('lang' => 'IMAGES_SIZE_IMG',		'validate' => 'int:30:200',	'type' => 'number:30:200', 'explain' => false, 'append' => ' ' . $user->lang['PIXEL']),
						'first_images_float'		=> array('lang' => 'FIRST_IMAGES_FLOAT',	'validate' => 'bool',		'type' => 'radio:yes_no', 'explain' => false),
					'legend4'	=> 'ACP_SUBMIT_CHANGES',
				),
			);

			if (isset($display_vars['lang']))
			{
				$user->add_lang($display_vars['lang']);
			}

			$this->new_config = $config;
			$cfg_array = ($request->is_set('config')) ? utf8_normalize_nfc($request->variable('config', array('' => ''), true)) : $this->new_config;
			$error = array();

			// Forums Anvarable))
			if ($request->is_set('last_images_attachment_ignore'))
			{
				$cfg_array['last_images_attachment_ignore'] = implode(',', $request->variable('last_images_attachment_ignore', array(0 => '')));
			}

			if ($request->is_set('last_images_img_ignore'))
			{
				$cfg_array['last_images_img_ignore'] = implode(',', $request->variable('last_images_img_ignore', array(0 => '')));
			}

			if ($request->is_set('first_images_forum_ignore'))
			{
				$cfg_array['first_images_forum_ignore'] = implode(',', $request->variable('first_images_forum_ignore', array(0 => '')));
			}

			// We validate the complete config if wished
			validate_config_vars($display_vars['vars'], $cfg_array, $error);

			if ($submit && !check_form_key('bb3mobi/imgposts'))
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
					$config->set($config_name, $config_value);
				}
			}

			if ($submit)
			{
				$phpbb_log->add('admin', $user->data['user_id'], $user->data['session_ip'], 'LOG_IMG_FROM_POSTS_CONFIG', time(), false);
				meta_refresh(3, append_sid($this->u_action));
				trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
			}

			$this->page_title = $display_vars['title'];

			$template->assign_vars(array(
				'L_TITLE'				=> $user->lang[$display_vars['title']],
				'L_TITLE_EXPLAIN'		=> $user->lang[$display_vars['title'] . '_EXPLAIN'],
				'L_TITLE_DESCRIPTION'	=> $user->lang[$display_vars['title'] . '_DESCRIPTION'],
				'U_ACTION'			=> $this->u_action,
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
	}

	/**
	* Select forums multiple
	*/
	function select_forums($value, $key)
	{
		$forum_list = make_forum_select(false, false, true, true, true, false, true);

		// Build forum options
		$f_id_ary = explode(',', $value);

		$s_forum_options = '<select id="' . $key . '" name="' . $key . '[]" multiple="multiple">';
		foreach ($forum_list as $f_id => $f_row)
		{
			$selected = ((in_array($f_id, $f_id_ary)) ? ' selected="selected"' : '');
			$selected .= (($f_row['disabled']) ? ' disabled="disabled" class="disabled-option"' : '');
			$s_forum_options .= '<option value="' . $f_id . '"' . $selected . '>' . $f_row['padding'] . $f_row['forum_name'] . '</option>';
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

	/**
	* Select display mode
	*/
	function select_display_mode($selected_value, $value)
	{
		global $user;

		$act_ary = array(
			'IMAGES_MODE_ATT' => 1,
			'IMAGES_MODE_IMG' => 0,
			'IMAGES_MODE_ALL' => 2,
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
