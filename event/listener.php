<?php
/**
*
* @package Images from posts v 1.0.4
* @copyright (c) 2014 Anvar (http://bb3.mobi)
* @copyright (c) 2015 Sheer (http://phpbbguru.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace bb3mobi\imgposts\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	protected $template;
	protected $config;
	protected $phpbb_root_path;
	protected $db;

	var $thumb_file;
	var $tds;

	public function __construct(\phpbb\template\template $template, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, $phpbb_root_path, $helper)
	{
		$this->template = $template;
		$this->config = $config;
		$this->db = $db;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->helper = $helper;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'		=> 'last_index_images',
			'core.viewforum_get_topic_data'		=> 'last_forum_images',
			'core.viewforum_modify_topicrow'	=> 'first_images_from_topic',
			'core.viewforum_modify_topics_data'	=> 'first_images_thumb_generate',
		);
	}

	public function last_index_images($event)
	{
		if ($this->config['last_images_attachment'] == 1 || $this->config['last_images_attachment'] == 3)
		{
			if ($this->config['images_attachment'] == 1 || $this->config['images_attachment'] == 2)
			{
				$this->helper->last_images_attachment();
			}

			if ($this->config['images_attachment'] == 0 || $this->config['images_attachment'] == 2)
			{
				$this->helper->last_images();
			}
		}
	}

	public function last_forum_images($event)
	{
		if ($this->config['last_images_attachment'] == 2 || $this->config['last_images_attachment'] == 3)
		{
			$forum_id = 0;
			$forum_data = $event['forum_data'];
			if (!$this->config['last_images_attachment_all'])
			{
				$forum_id = isset($forum_data['forum_id']) ? $forum_data['forum_id'] : 0;
			}

			if ($this->config['images_attachment'] == 1 || $this->config['images_attachment'] == 2)
			{
				$this->helper->last_images_attachment($forum_id);
			}

			if ($this->config['images_attachment'] == 0 || $this->config['images_attachment'] == 2)
			{
				$forum_id = isset($forum_data['forum_id']) ? $forum_data['forum_id'] : 0;
				$this->helper->last_images($forum_id);
			}
		}
	}

	public function first_images_thumb_generate($event)
	{
		if ($this->config['first_images_from_topic'])
		{
			$rowset = $event['rowset'];
			$ar = array();
			foreach ($event['topic_list'] as $topic_id)
			{
				$row = &$rowset[$topic_id];
				if (!isset($topic_ids))
				{
					$topic_ids = array();
				}

				if ($this->config['images_attachment'] == 0 || $this->config['images_attachment'] == 2)
				{
					$topic_ids[] = $topic_id;
				}

				if ($row['topic_attachment'] && ($this->config['images_attachment'] == 1 || $this->config['images_attachment'] == 2))
				{
					$topic_ids[] = $topic_id;
				}
				$forum_id = (!empty($row['forum_id'])) ? $row['forum_id'] : '';
				unset($rowset[$topic_id]);
			}

			if (isset($topic_ids))
			{
				$topic_ids = array_unique($topic_ids);
			}

			// Create thumbs from [img][/img] (c) Sheer
			if ($this->config['images_attachment'] == 0 || $this->config['images_attachment'] == 2)	// Image [img][/img] from first topis's post or all
			{
				$chars = '[img:';
				$pattern = array('.jpg', '.jpg' , '.jpg');
				$replacement = array('/source', '/mini' , '/medium');
				$sql_were = $sql_were_topic = '';
				if ($this->config['first_images_forum_ignore'])
				{
					$sql_were = ' AND p.forum_id NOT IN ('. $this->config['first_images_forum_ignore'] .') ';
				}

				if ($this->config['last_images_attachment_ignore_topic'])
				{
					$sql_were_topic = ' AND t.topic_id NOT IN ('. $this->config['last_images_attachment_ignore_topic'] .') ';
				}

				if (isset($topic_ids))
				{
					foreach($topic_ids as $tid)
					{
						$thumbnail_file = false;
						$sql = 'SELECT p.post_id, p.topic_id, p.forum_id, p.post_text, t.topic_id, t.topic_title
							FROM ' . POSTS_TABLE . ' p
							LEFT JOIN ' . TOPICS_TABLE . ' t ON t.topic_id = p.topic_id
							WHERE post_text '. $this->db->sql_like_expression($this->db->get_any_char() . $chars . $this->db->get_any_char()) . '
							AND t.topic_id = '. $tid . '
							' . $sql_were . '
							' . $sql_were_topic . '
							ORDER BY p.post_id ASC';
						$result = $this->db->sql_query_limit($sql, 1);
						while ($attach = $this->db->sql_fetchrow($result))
						{
							$is_quoted = $thumbnail_file = false;
							$attach['post_text'] = str_replace("\n", '', $attach['post_text']);
							if (preg_match_all('#\[quote(.*?)\](.*?)\[\/quote:(.*?)\]#iU', $attach['post_text'], $matches))
							{
								preg_match_all('#\[img:(.*?)\](.*?)\[\/img:(.*?)#i', $matches[1][0], $match);
								$is_quoted = (!empty($match[0])) ? true : false;
							}

							if (!$is_quoted)
							{
								if($this->config['last_images_gallery'])
								{
									preg_match('#\[img:(.*?)\](.*?)\[\/img:(.*?)\]#i', $attach['post_text'], $current_posted_img);
								}
								else
								{
									preg_match('#\[img:(.*?)\]((.*?).jpg|(.*?).jpeg|(.*?).png|(.*?).gif)\[\/img:(.*?)\]#i', $attach['post_text'], $current_posted_img);
								}

								$str = $last_x_img_ppp = preg_replace(array('#&\#46;#', '#&\#58;#', '/\[(.*?)\]/'), array('.',':',''), $current_posted_img[2]);
								$url = parse_url($last_x_img_ppp);
								if (isset($url['port']))
								{
									$str = $last_x_img_ppp = str_replace(':' . $url['port'], '', $last_x_img_ppp);
								}
								// Need for phpBB Gallery extension -->
								$last_x_img_ppp = str_replace($replacement, $pattern, $last_x_img_ppp);
								//<-- Need for phpBB Gallery extension
								$last_x_img_ppp		= str_replace('https', 'http', $last_x_img_ppp);
								$last_x_img_pre		= strrchr($last_x_img_ppp,"/");
								$last_x_img_pre		= substr($last_x_img_pre, 1);
								$last_x_img_pre		= strtolower(preg_replace('#[^a-zA-Z0-9_+.-]#', '', $last_x_img_pre));
								$last_x_img_pre_img	= substr($last_x_img_pre, 0, -4);
								$thumbnail_file = $this->config['images_new_path'] . 'topic-' . $attach['post_id'] . '-'. $last_x_img_pre;
							}
						}
						$this->db->sql_freeresult($result);

						if ($thumbnail_file)
						{
							if (!file_exists($thumbnail_file))
							{
								// Need for phpBB Gallery extension -->
								if (preg_match_all('#app.php/gallery/image//*?(.*?)/(source|mini|medium)#sei', $str, $arr))
								{
									$last_x_img_ppp = str_replace($pattern, $replacement, $last_x_img_ppp);
								}
								//<-- Need for phpBB Gallery extension
								if($this->helper->url_exists($last_x_img_ppp))
								{
									$this->helper->img_resize($last_x_img_ppp, $this->config['last_images_attachment_size'], $this->phpbb_root_path . $thumbnail_file, $this->config['images_copy_bottom']);
								}
							}

							if (file_exists($this->phpbb_root_path . $thumbnail_file))
							{
								$this->thumb_file[$row['topic_id']] = $thumbnail_file;
								$this->template->assign_vars(array(
									'FIRST_IMAGES_FLOAT' => $this->config['first_images_float'],
									'FIRST_IMAGES_TOPIC' => $this->config['first_images_size'],
								));

								if($thumbnail_file)
								{
									$ar[$tid] = $thumbnail_file;
								}
								// Reset thumb;
								$thumbnail_file = false;
							}
						}
					}
					$this->tds = $ar;
				}
			}

			if ($this->config['images_attachment'] == 1 || $this->config['images_attachment'] == 2)	// Attachment from first topis's post or all
			{
				if (!$this->helper->exclude_forum($forum_id, $this->config['first_images_forum_ignore']) && count($topic_ids))
				{
					$this->template->assign_vars(array(
						'FIRST_IMAGES_FLOAT' => $this->config['first_images_float'],
						'FIRST_IMAGES_TOPIC' => $this->config['first_images_size'],
					));

					$sql = 'SELECT physical_filename, extension, topic_id
						FROM ' . ATTACHMENTS_TABLE . '
						WHERE (mimetype = "image/jpeg" OR mimetype = "image/png" OR mimetype = "image/gif")
							AND ' . $this->db->sql_in_set('topic_id', $topic_ids) . '
						GROUP BY topic_id
						ORDER BY post_msg_id ASC';
					$result = $this->db->sql_query($sql);

					while($attach = $this->db->sql_fetchrow($result))
					{
						$thumbnail_file = $this->config['images_new_path'] . 'topic-' . $attach['topic_id'] . '.' . $attach['extension'];
						if (!file_exists($this->phpbb_root_path . $thumbnail_file) && !empty($attach['physical_filename']))
						{
							$file = $this->phpbb_root_path . $this->config['upload_path'] . '/' . utf8_basename($attach['physical_filename']);
							$this->helper->img_resize($file, $this->config['first_images_size'], $this->phpbb_root_path . $thumbnail_file);
						}
						if (file_exists($this->phpbb_root_path . $thumbnail_file))
						{
							$ar[$attach['topic_id']] = $thumbnail_file;
						}
					}
					$this->tds = $ar;
					$this->db->sql_freeresult($result);
				}
			}
		}
	}

	public function first_images_from_topic($event)
	{
		$row = $event['row'];
		$thumb = $this->tds;
		if (!empty($thumb[$row['topic_id']]))
		{
			$topic_row = array(
				'FILSE_EXIST'	=> true,
				'ATTACH_IMG'	=> generate_board_url() . '/' . $thumb[$row['topic_id']],
			);
			$event['topic_row'] += $topic_row;
		}
	}
}
