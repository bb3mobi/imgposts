<?php
/**
*
* @package Images from posts
* @copyright (c) 2014 Anvar http://bb3.mobi
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace bb3mobi\imgposts\migrations;

class v_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return;
	}

	static public function depends_on()
	{
			return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('last_images_attachment', '0')),
			array('config.add', array('last_images_attachment_all', '1')),
			array('config.add', array('last_images_attachment_ignore', '')),
			array('config.add', array('last_images_attachment_ignore_topic', '')),
			array('config.add', array('last_images_attachment_bottom', '0')),
			array('config.add', array('last_images_attachment_top_invert', '1')),
			array('config.add', array('last_images_attachment_carousel', '1')),
			array('config.add', array('last_images_attachment_count_min', '6')),
			array('config.add', array('last_images_attachment_count', '9')),
			array('config.add', array('last_images_attachment_size', '120')),
			array('config.add', array('images_new_path', 'ext/bb3mobi/imgposts/images/')),
			array('config.add', array('images_copy_bottom', 'bb3.mobi')),
			array('config.add', array('images_height_width', '1')),
			array('config.add', array('first_images_from_topic', '0')),
			array('config.add', array('first_images_forum_ignore', '')),
			array('config.add', array('first_images_size', '50')),
			array('config.add', array('first_images_float', '0')),
			array('config.add', array('last_images_img_ignore', '0')),
			// Current version
			array('config.add', array('imgposts_version', '1.0.0')),
			// Add ACP modules
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_IMG_FROM_POSTS')),
			array('module.add', array('acp', 'ACP_IMG_FROM_POSTS', array(
				'module_basename'	=> '\bb3mobi\imgposts\acp\imgposts_module',
				'module_langname'	=> 'ACP_IMG_POSTS',
				'module_mode'		=> 'config_imgposts',
				'module_auth'		=> 'ext_bb3mobi/imgposts && acl_a_board',
			))),
		);
	}
}
