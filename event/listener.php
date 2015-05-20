<?php
/**
*
* @package Images from posts v 1.0.2
* @copyright (c) 2014 Anvar (http://bb3.mobi)
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
			'core.index_modify_page_title' => 'last_index_images',
			'core.viewforum_get_topic_data' => 'last_forum_images',
			'core.viewforum_modify_topicrow' => 'first_images_from_topic',
			'core.viewforum_modify_topics_data' => 'first_images_thumb_generate',
		);
	}

	public function last_index_images($event)
	{
		if ($this->config['last_images_attachment'] == 1 || $this->config['last_images_attachment'] == 3)
		{
			$this->helper->last_images_attachment();
		}
	}

	public function last_forum_images($event)
	{
		$forum_id = '';
		if (!$this->config['last_images_attachment_all'])
		{
			$forum_data = $event['forum_data'];
			$forum_id = isset($forum_data['forum_id']) ? $forum_data['forum_id'] : '';
		}
		if ($this->config['last_images_attachment'] == 2 || $this->config['last_images_attachment'] == 3)
		{
			$this->helper->last_images_attachment($forum_id);
		}
	}

	public function first_images_thumb_generate($event)
	{
		if (!empty($this->config['first_images_from_topic']))
		{
			$rowset = $event['rowset'];
			$forum_id = 0;
			$topic_ids = array();
			foreach ($event['topic_list'] as $topic_id)
			{
				$row = &$rowset[$topic_id];
				$topic_ids[] = ($row['topic_attachment']) ? $topic_id : '';
				$forum_id = (!empty($row['forum_id'])) ? $row['forum_id'] : '';
				unset($rowset[$topic_id]);
			}

			if (!$this->helper->exclude_forum($forum_id, $this->config['first_images_forum_ignore']) && count($topic_ids))
			{
				$this->template->assign_vars(array(
					'FIRST_IMAGES_FLOAT' => $this->config['first_images_float'],
					'FIRST_IMAGES_TOPIC' => $this->config['first_images_size'])
				);

				$sql = 'SELECT physical_filename, extension, topic_id
					FROM ' . ATTACHMENTS_TABLE . '
					WHERE (mimetype = "image/jpeg" OR mimetype = "image/png" OR mimetype = "image/gif")
						AND ' . $this->db->sql_in_set('topic_id', $topic_ids) . '
					GROUP BY post_msg_id ASC';
				$result = $this->db->sql_query($sql);

				while($attach = $this->db->sql_fetchrow($result))
				{
					$thumbnail_file = $this->config['images_new_path'] . 'topic-' . $attach['topic_id'] . '.' . $attach['extension'];
					if (!file_exists($this->phpbb_root_path . $thumbnail_file) && !empty($attach['physical_filename']))
					{
						$file = $this->phpbb_root_path . $this->config['upload_path'] . '/' .  utf8_basename($attach['physical_filename']);
						$this->helper->img_resize($file, $this->config['first_images_size'], $this->phpbb_root_path . $thumbnail_file);
					}
					if (file_exists($this->phpbb_root_path . $thumbnail_file))
					{
						$this->thumb_file[$attach['topic_id']] = $thumbnail_file;
					}
				}
				$this->db->sql_freeresult($result);
			}
		}
	}

	public function first_images_from_topic($event)
	{
		$row = $event['row'];
		if (!empty($this->thumb_file[$row['topic_id']]))
		{
			$topic_row = array(
				'FILSE_EXIST'	=> 'http://bb3.mobi',
				'ATTACH_IMG'	=> generate_board_url() . '/' . $this->thumb_file[$row['topic_id']],
			);
			$event['topic_row'] += $topic_row;
		}
	}
}

?>