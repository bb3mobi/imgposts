<?php
/** 
*
* @author Anvar
* @version Images from posts
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

namespace bb3mobi\imgposts\core;

class helper
{
	protected $template;
	protected $config;
	protected $user;
	protected $phpbb_root_path;
	protected $php_ext;
	protected $db;

	public function __construct (\phpbb\template\template $template, \phpbb\config\config $config, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db, $phpbb_root_path, $php_ext)
	{
		$this->template = $template;
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
				GROUP BY a.post_msg_id DESC';
			$result = $this->db->sql_query_limit($sql, $this->config['last_images_attachment_count']);

			$att_count = 0;
			while($attach = $this->db->sql_fetchrow($result))
			{
				$thumbnail_file = $this->config['images_new_path'] . 'attach-' . $attach['attach_id'] . '.' . $attach['extension'];
				if (!empty($attach['physical_filename']) && !file_exists($this->phpbb_root_path . $thumbnail_file))
				{
					$file = $this->phpbb_root_path . $this->config['upload_path'] . '/' .  utf8_basename($attach['physical_filename']);
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
				'IMAGES_ATTACH_SIZE'	=> $this->config['last_images_attachment_size']+10)
			);
			if ($att_count >= $this->config['last_images_attachment_count_min'])
			{
				$this->template->assign_var('LAST_IMAGES_ATACHMENT', $this->config['last_images_attachment']);
			}
		}
	}

	public function img_resize($file, $resize, $thumbnail_file, $copy = false)
	{	/* resize images and width = height functions
		phpBB 2.0.23 album thumbnail mod 2008 Anvar, apwa.ru */

		$size = @getimagesize($file);

		if (!count($size) || !isset($size[0]) || !isset($size[1]))
		{
			return;
		}
		if ($resize >= $size[0] || $resize >= $size[1])
		{
			return;
		}

		$image_type = $size[2];
		if ($image_type == IMAGETYPE_JPEG)
		{
			$image = imagecreatefromjpeg($file);
		}
		else if ($image_type == IMAGETYPE_GIF)
		{
			$image = imagecreatefromgif($file);
		}
		else if ($image_type == IMAGETYPE_PNG)
		{
			$image = imagecreatefrompng($file);
		}
		else
		{
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

		if (!empty($this->config['images_height_width']) || ($thumbnail_height > $thumbnail_width))
		{
			$thumbnail = imagecreatetruecolor($thumb_width, $thumb_height);
			imagecopy($thumbnail, $thumbnail2, 0, 0, $new_left, $new_top, $thumb_width, $thumb_height);
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

		if($image_type == IMAGETYPE_JPEG)
		{
			imagejpeg($thumbnail, $thumbnail_file, 90);
		}
		else if($image_type == IMAGETYPE_GIF)
		{
			imagegif($thumbnail, $thumbnail_file);
		}
		else if ($image_type == IMAGETYPE_PNG)
		{
			imagepng($thumbnail, $thumbnail_file, 0);
		}

		imagedestroy($thumbnail);
	}
}
