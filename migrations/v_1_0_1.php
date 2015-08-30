<?php
/**
*
* @package Images from posts
* @copyright (c) 2014 Anvar http://bb3.mobi
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace bb3mobi\imgposts\migrations;

class v_1_0_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['imgposts_version']) && version_compare($this->config['imgposts_version'], '1.0.1', '>=');
	}

	static public function depends_on()
	{
		return array('\bb3mobi\imgposts\migrations\v_1_0_0');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('images_attachment', '1')),
			// Current version
			array('config.update', array('imgposts_version', '1.0.1')),
		);
	}
}
