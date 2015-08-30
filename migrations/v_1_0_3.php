<?php
/**
*
* @package Images from posts
* @copyright (c) 2014 Anvar http://bb3.mobi
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace bb3mobi\imgposts\migrations;

class v_1_0_3 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['imgposts_version']) && version_compare($this->config['imgposts_version'], '1.0.3', '>=');
	}

	static public function depends_on()
	{
		return array('\bb3mobi\imgposts\migrations\v_1_0_2');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('imgposts_cron', '1')),
			array('config.add', array('images_prune_last_gc', '0', '1')),
			array('config.add', array('images_prune_gc', '172800')),
			// Current version
			array('config.update', array('imgposts_version', '1.0.3')),
			array('module.add', array('acp', 'ACP_IMG_FROM_POSTS', array(
				'module_basename'	=> '\bb3mobi\imgposts\acp\imgposts_module',
				'module_langname'	=> 'ACP_IMG_MANAGE_POSTS',
				'module_mode'		=> 'manage_imgposts',
				'module_auth'		=> 'ext_bb3mobi/imgposts && acl_a_board',
			))),
		);
	}
}
