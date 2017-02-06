<?php
/**
*
* @copyright (c) 2014 Anvar (http://bb3.mobi), (c) 2015 Sheer
* @package Images from posts v 1.0.3
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace bb3mobi\imgposts\core;

class helper
{
	protected $template;
	protected $phpbb_log;
	protected $config;
	protected $user;
	protected $phpbb_root_path;
	protected $php_ext;
	protected $db;
	protected $auth;

	public function __construct (
		\phpbb\template\template $template,
		\phpbb\log\log $phpbb_log,
		\phpbb\config\config $config,
		\phpbb\user $user,
		\phpbb\auth\auth $auth,
		\phpbb\db\driver\driver_interface $db,
		$phpbb_root_path,
		$php_ext
	)
	{
		$this->template = $template;
		$this->phpbb_log = $phpbb_log;
		$this->config = $config;
		$this->user = $user;
		$this->auth = $auth;
		$this->db = $db;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	public function exclude_forum($forum_id, $forum_ary)
	{
		if ($forum_ary)
		{
			$exclude = explode(',', $forum_ary);
		}
		else
		{
			$exclude = array();
		}
		return in_array($forum_id, $exclude);
	}

	// Create thumbs from [img][/img] (c) Sheer
	public function last_images($forum_id = false, $images_step = 0)
	{
		$thumbs = array();
		$pattern = array('.jpg', '.jpg' , '.jpg');
		$replacement = array('/source', '/mini' , '/medium');
		if ($images_step)
		{
			$this->config['last_images_attachment_count'] = $images_step;
		}
		$chars = '[img:';
		$sql_where = $sql_where_forbidden = $sql_where_topic = $sql_forum = '';

		if ($forum_id)
		{
			$sql_forum = ' AND p.forum_id = ' . $forum_id;
		}

		if ($this->config['last_images_attachment_ignore'])
		{
			$sql_where = ' AND p.forum_id NOT IN ('. $this->config['last_images_attachment_ignore'] .') ';
		}

		$forumz_ary = array();
		$forumz_read_ary = $this->auth->acl_getf('f_read');

		foreach ($forumz_read_ary as $forum_id => $allowed)
		{
			if (!$allowed['f_read'])
			{
				$forumz_ary[] = (int) $forum_id;
			}
		}

		if (sizeof($forumz_ary))
		{
			$sql_where_forbidden = ' AND ' . $this->db->sql_in_set('p.forum_id', $forumz_ary, true) . '';
		}

		if ($this->config['last_images_attachment_ignore_topic'])
		{
			$sql_where_topic = ' AND t.topic_id NOT IN ('. $this->config['last_images_attachment_ignore_topic'] .') ';
		}

		$sql = 'SELECT p.post_id, p.topic_id, p.forum_id, p.post_text, p.post_subject, p.post_time, t.topic_id, t.topic_title
			FROM ' . POSTS_TABLE . ' p
			LEFT JOIN ' . TOPICS_TABLE . ' t ON t.topic_id = p.topic_id
			WHERE post_text '. $this->db->sql_like_expression($this->db->get_any_char() . $chars . $this->db->get_any_char()) . '
			' . $sql_where . '
			' . $sql_where_forbidden . '
			' . $sql_where_topic . '
			' . $sql_forum . '
			AND p.post_visibility = 1
			AND p.post_id = t.topic_first_post_id
			ORDER BY p.post_time DESC';
		$result = $this->db->sql_query_limit($sql, $this->config['last_images_attachment_count']);
		$att_count = $create_count = 0;
		while ($attach = $this->db->sql_fetchrow($result))
		{
			$is_quoted = false;
			$attach['post_text'] = str_replace("\n", '', $attach['post_text']);
			if (preg_match_all('#\[quote(.*?)\](.*?)\[\/quote:(.*?)\]#iU', $attach['post_text'], $matches))
			{
				preg_match_all('#\[img:(.*?)\](.*?)\[\/img:(.*?)#i', $matches[1][0], $match);
				$is_quoted = (!empty($match[0])) ? true : false;
			}
			if (!$is_quoted)
			{
				if ($this->config['last_images_gallery'])
				{
					preg_match_all('#\[img:(.*?)\](.*?)\[\/img:(.*?)\]#i', $attach['post_text'], $current_posted_img);
				}
				else
				{
					preg_match_all('#\[img:(.*?)\]((.*?).jpg|(.*?).jpeg|(.*?).png|(.*?).gif)\[\/img:(.*?)\]#i', $attach['post_text'], $current_posted_img);
				}

				foreach ($current_posted_img[2] as $current_file_img)
				{
					$current_file_img	= str_replace('https', 'http', $current_file_img);
					$str = $last_x_img_ppp = preg_replace(array('#&\#46;#', '#&\#58;#', '/\[(.*?)\]/'), array('.',':',''), $current_file_img);
					$url = parse_url($last_x_img_ppp);
					if (isset($url['port']))
					{
						$str = $last_x_img_ppp = str_replace(':' . $url['port'], '', $last_x_img_ppp);
					}
					// Need for phpBB Gallery extension -->
					$last_x_img_ppp = str_replace($replacement, $pattern, $last_x_img_ppp);
					//<-- Need for phpBB Gallery extension
					$last_x_img_pre		= strrchr($last_x_img_ppp, "/");
					$last_x_img_pre		= substr($last_x_img_pre, 1);
					$last_x_img_pre		= strtolower(preg_replace('#[^a-zA-Z0-9_+.-]#', '', $last_x_img_pre));
					$last_x_img_pre_img	= substr($last_x_img_pre, 0, -4);
					$thumbnail_file = $this->config['images_new_path'] . 'attach-' . $attach['post_id'] . ''. $last_x_img_pre;

					// Need for phpBB Gallery extension -->
					if (preg_match_all('#app.php/gallery/image//*?(.*?)/(source|mini|medium)#sei', $str, $arr))
					{
						$last_x_img_ppp = str_replace($pattern, $replacement, $last_x_img_ppp);
					}
					//<-- Need for phpBB Gallery extension
					$res = true;
					if (!file_exists($this->phpbb_root_path.$thumbnail_file))
					{
						if ($this->url_exists($last_x_img_ppp))
						{
							$this->img_resize($last_x_img_ppp, $this->config['last_images_attachment_size'], $this->phpbb_root_path . $thumbnail_file, $this->config['images_copy_bottom']);
							$res = true;
							$create_count++;
							$thumbs[] = 'attach-' . $attach['post_id'] . ''. $last_x_img_pre;
						}
						else
						{
							$res = false;
						}
					}
					if ($res)
					{
						$this->template->assign_block_vars('attach_img', array(
							'ATTACH_POST'	=> append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", 'p=' . $attach['post_id']) . '#p' . $attach['post_id'],
							'ATTACH_IMG'	=> $thumbnail_file,
							'ATTACH_ALT'	=> $attach['post_subject'],
							'TOPIC_TITLE'	=> $attach['post_subject'],
							)
						);
						$att_count++;
					}
				}
			}
		}

		$this->db->sql_freeresult($result);
		$this->user->add_lang_ext('bb3mobi/imgposts', 'info_acp_imgposts');
		$this->template->assign_vars(array(
			'L_IMAGES_ATACHMENT'	=> $this->user->lang['LAST_IMAGES_ATACHMENT'],
			'IMAGES_BOTTOM_TYPE'	=> $this->config['last_images_attachment_bottom'],
			'IMAGES_TOP_INVERT'		=> $this->config['last_images_attachment_top_invert'],
			'IMAGES_ATT_CAROUSEL'	=> $this->config['last_images_attachment_carousel'],
			'IMAGES_ATTACH_SIZE'	=> $this->config['last_images_attachment_size'] + 10,
			'IMAGES_ATTACH_HEIGHT'	=> $this->config['last_images_attachment_size'],
			'IMAGES_ATTACHT_COUNT'	=> $att_count,
			)
		);

		if ($att_count >= $this->config['last_images_attachment_count_min'])
		{
			$this->template->assign_var('LAST_IMAGES_ATACHMENT', $this->config['last_images_attachment']);
		}

		$counts = array($att_count, $create_count);
		$create_thumbs['thumbs'] = $thumbs;
		$create_thumbs['counts'] = $counts;

		return $create_thumbs;
	}

	// Create thumbs from attachments
	public function last_images_attachment($forum_id = false)
	{
		$forum_ary = array();
		if (!$forum_id)
		{
			// All forums
			$forum_read_ary = $this->auth->acl_getf('f_read');
			foreach ($forum_read_ary as $forum_id => $allowed)
			{
				if ($allowed['f_read'])
				{
					$forum_ary[] = (int)$forum_id;
				}
			}
			$forum_ary = array_unique($forum_ary);
			$sql_where = 'WHERE f.forum_id = ' . $forum_id;
			$sql_where .= ' AND ' . $this->db->sql_in_set('t.forum_id', $forum_ary);
			if (!empty($this->config['last_images_attachment_ignore']))
			{
				$sql_where .= ' AND t.forum_id NOT IN (' . $this->config['last_images_attachment_ignore'] . ')';
			}
		}
		else
		{
			$sql_where = 'WHERE f.forum_id = ' . $forum_id;
			$sql_where .= ' AND t.forum_id = f.forum_id';
			$ignore_forums = (!empty($this->config['last_images_attachment_ignore'])) ? $this->config['last_images_attachment_ignore'] : 0;
			if (!$this->exclude_forum($forum_id, $ignore_forums))
			{
				$forum_ary[] = $forum_id;
			}
		}

		if (sizeof($forum_ary))
		{
			if (!empty($this->config['last_images_attachment_ignore_topic']))
			{
				$sql_where .= ' AND t.topic_id NOT IN (' . chop($this->config['last_images_attachment_ignore_topic'], ' ,') . ')';
			}
			$sql = 'SELECT a.attach_id, a.post_msg_id, a.physical_filename, a.real_filename, a.extension, a.filetime, t.topic_title
			FROM ' . ATTACHMENTS_TABLE . ' a, ' . FORUMS_TABLE . ' f, ' . TOPICS_TABLE . ' t
				' . $sql_where . '
				AND (mimetype = "image/jpeg" OR mimetype = "image/png" OR mimetype = "image/gif")
				AND a.topic_id = t.topic_id
				AND t.topic_first_post_id = a.post_msg_id
				GROUP BY a.post_msg_id DESC';
			$result = $this->db->sql_query_limit($sql, $this->config['last_images_attachment_count']);

			$att_count = 0;
			while($attach = $this->db->sql_fetchrow($result))
			{
				$thumbnail_file = $this->config['images_new_path'] . 'attach-' . $attach['attach_id'] . '.' . $attach['extension'];
				if (!empty($attach['physical_filename']) && !file_exists($this->phpbb_root_path . $thumbnail_file))
				{
					$file = $this->phpbb_root_path . $this->config['upload_path'] . '/' . utf8_basename($attach['physical_filename']);
					$this->img_resize($file, $this->config['last_images_attachment_size'], $this->phpbb_root_path . $thumbnail_file, $this->config['images_copy_bottom']);
				}
				if (file_exists($this->phpbb_root_path . $thumbnail_file))
				{
					$this->template->assign_block_vars('attach_img', array(
						'ATTACH_POST'	=> append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", 'p=' . $attach['post_msg_id']) . '#p' . $attach['post_msg_id'],
						'ATTACH_IMG'	=> generate_board_url() . '/' . $thumbnail_file,
						'ATTACH_ALT'	=> $attach['real_filename'],
						'TOPIC_TITLE'	=> censor_text($attach['topic_title'])
						)
					);
					$att_count++;
				}
			}
			$this->db->sql_freeresult($result);

			$this->user->add_lang_ext('bb3mobi/imgposts', 'info_acp_imgposts');
			$this->template->assign_vars(array(
				'L_IMAGES_ATACHMENT'	=> $this->user->lang['LAST_IMAGES_ATACHMENT'],
				'IMAGES_BOTTOM_TYPE'	=> $this->config['last_images_attachment_bottom'],
				'IMAGES_TOP_INVERT'		=> $this->config['last_images_attachment_top_invert'],
				'IMAGES_ATT_CAROUSEL'	=> $this->config['last_images_attachment_carousel'],
				'IMAGES_ATTACH_SIZE'	=> $this->config['last_images_attachment_size'] + 10,
				'IMAGES_ATTACH_HEIGHT'	=> $this->config['last_images_attachment_size'],
				)
			);
			if ($att_count >= $this->config['last_images_attachment_count_min'])
			{
				$this->template->assign_var('LAST_IMAGES_ATACHMENT', $this->config['last_images_attachment']);
			}
		}
	}

	public function img_resize($file, $resize, $thumbnail_file, $copy = false)
	{
		$size = @getimagesize($file);
		if (!count($size) || !isset($size[0]) || !isset($size[1]))
		{
			return;
		}
		$image_type = $size[2];

		if ($resize > $size[0] || $resize > $size[1])
		{
			return;
		}

		switch ($image_type)
		{
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($file);
			break;
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($file);
			break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($file);
			break;
			default:
			return;
		}

		$width = imagesx($image);
		$height = imagesy($image);

		$thumb_width = $thumb_height = $resize;
		$thumbnail_width = $thumb_width;
		$thumbnail_height = floor($height * ($thumbnail_width/$width));

		$new_left = 0;
		$new_top = floor(($thumbnail_height - $thumb_height)/2);

		if ($thumbnail_height < $thumb_height)
		{
			$thumbnail_height = $thumb_height;
			$thumbnail_width = floor($width * ($thumbnail_height/$height));

			$new_left = floor(($thumbnail_width - $thumb_width)/2);
			$new_top = 0;
		}

		$thumbnail2 = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
		imagecopyresampled($thumbnail2, $image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width, $height);

		if ($this->config['images_height_width'] || $thumbnail_height > $thumbnail_width)
		{
			$thumbnail = imagecreatetruecolor($thumb_width, $thumb_height);
			imagecopy($thumbnail, $thumbnail2, 0, 0, $new_left, $new_top, $thumb_width, $thumb_height);
			imagedestroy($thumbnail2);
		}
		else
		{
			$thumbnail = $thumbnail2;
		}

		if ($copy && ($thumb_height > 40 || $thumb_width > 40))
		{
			$color = imageColorAllocate($image, 140, 120, 90);
			imageString($thumbnail, 1, ($thumb_width/2)-(strlen($copy)*3-5), $thumb_height-10, $copy, $color);
		}

		switch ($image_type)
		{
			case IMAGETYPE_JPEG:
				imagejpeg($thumbnail, $thumbnail_file, 90);
			break;
			case IMAGETYPE_GIF:
				imagegif($thumbnail, $thumbnail_file);
			break;
			case IMAGETYPE_PNG:
				imagepng($thumbnail, $thumbnail_file, 0);
			break;
		}
		imagedestroy($thumbnail);
	}

	public function url_exists($url)
	{
		$handle = @fopen($url, 'r');
		if ($handle === false)
		{
			@fclose($handle);
			return false;
		}
		else
		{
			fclose($handle);
			return true;
		}
	}

	// Clear thumbs cache
	public function clear_cache()
	{
		// Search images
		$current_posted = array(); // ver 1.0.5

		$chars = '[img:';
		$pattern = array('.jpg', '.jpg' , '.jpg');
		$replacement = array('/source', '/mini' , '/medium');
		$sql = 'SELECT post_id, post_text, post_time
			FROM ' . POSTS_TABLE . ' p
			WHERE post_text '. $this->db->sql_like_expression($this->db->get_any_char() . $chars . $this->db->get_any_char()) . '
			AND post_visibility = 1
			ORDER BY post_time DESC';
		$result = $this->db->sql_query_limit($sql, (2 * $this->config['last_images_attachment_count']));
		while ($attach = $this->db->sql_fetchrow($result))
		{
			$is_quoted = false;
			$attach['post_text'] = str_replace("\n", '', $attach['post_text']);
			if (preg_match_all('#\[quote(.*?)\](.*?)\[\/quote:(.*?)\]#iU', $attach['post_text'], $matches))
			{
				preg_match_all('#\[img:(.*?)\](.*?)\[\/img:(.*?)#i', $matches[1][0], $match);
				$is_quoted = (!empty($match[0])) ? true : false;
			}
			if (!$is_quoted)
			{
				preg_match_all('#\[img:(.*?)\](.*?)\[\/img:(.*?)\]#i', $attach['post_text'], $current_posted_img);
				foreach ($current_posted_img[2] as $current_file_img)
				{
					$last_x_img_ppp = preg_replace(array('#&\#46;#', '#&\#58;#', '/\[(.*?)\]/'), array('.',':',''), $current_file_img);
					// Need for phpBB Gallery extension -->
					$last_x_img_ppp = str_replace($replacement, $pattern, $last_x_img_ppp);
					//<-- Need for phpBB Gallery extension
					$last_x_img_pre		= strrchr($last_x_img_ppp, "/");
					$last_x_img_pre		= substr($last_x_img_pre, 1);
					$last_x_img_pre		= strtolower(preg_replace('#[^a-zA-Z0-9_+.-]#', '', $last_x_img_pre));
					$last_x_img_pre_img	= substr($last_x_img_pre, 0, -4);
					$current_posted[] = 'attach-' . $attach['post_id'] . ''. $last_x_img_pre;
				}
			}
		}

		$this->db->sql_freeresult($result);
		array_unique($current_posted);
		array_map('trim', $current_posted); // Rest images

		$handle = @opendir($this->phpbb_root_path . $this->config['images_new_path']);
		if (!$handle)
		{
			return false;
		}

		$files = array();
		while ($file = readdir ($handle))
		{
			if ($file != '.' && $file != '..' && $file != '.htaccess' && $file != 'index.htm' && $file != 'index.html' && !preg_match('/topic/ui', $file))
			{
				$files[] = $file;
			}
		}
		closedir($handle);
		array_unique($files);
		array_map('trim', $files);
		sort($files);

		// Search attachments
		$attacments = array(); // ver 1.0.5

		$sql = 'SELECT a.attach_id, a.post_msg_id, a.extension, p.post_id, p.topic_id, p.post_time, p.post_visibility
			FROM ' . ATTACHMENTS_TABLE . ' a, ' . POSTS_TABLE . ' p
			WHERE a.post_msg_id = p.post_id
			AND (mimetype = "image/jpeg" OR mimetype = "image/png" OR mimetype = "image/gif")
			AND p.post_visibility = 1
			GROUP BY a.post_msg_id DESC';
		$result = $this->db->sql_query_limit($sql, $this->config['last_images_attachment_count']);
		while($attach = $this->db->sql_fetchrow($result))
		{
			$thumbnail_file = $this->config['images_new_path'] . 'attach-' . $attach['attach_id'] . '.' . $attach['extension'];
			$attacments[] = 'attach-' . $attach['attach_id'] . '.' . $attach['extension'];
		}
		$this->db->sql_freeresult($result);

		array_unique($attacments);
		array_map('trim', $attacments);
		$images_ary = array_diff($files, $current_posted);
		$deleted_images = array_diff($images_ary, $attacments);

		if (!empty($files) && sizeof($current_posted) >= $this->config['last_images_attachment_count'])
		{
			foreach ($deleted_images as $del_file)
			{
				@unlink($this->phpbb_root_path . $this->config['images_new_path'] . $del_file);
			}
		}

		// Delete old attachments
		$deleted_attacments = array_diff(array_diff($files, $attacments), $current_posted);
		if (!empty($files) && sizeof($attacments) >= $this->config['last_images_attachment_count'])
		{
			foreach($deleted_attacments as $del_file)
			{
				@unlink($this->phpbb_root_path . $this->config['images_new_path'] . $del_file);
			}
		}

		if (empty($deleted_images) && empty($deleted_attacments))
		{
			return 'NO_IMAGES_TO DELETE';
		}

		$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], 'LOG_CLEAR_IMG_CACHE', time(), false);
		return false;
	}
}
