<?php
/**
*
* @package Images from posts
* @copyright (c) 2014 Anvar http://bb3.mobi
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace bb3mobi\imgposts\acp;

class imgposts_info
{
	function module()
	{
		return array(
			'filename'	=> '\bb3mobi\imgposts\acp\imgposts_module',
			'title'		=> 'ACP_IMG_FROM_POSTS',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'config_imgposts'	=> array('title' => 'ACP_IMG_POSTS', 'auth' => 'ext_bb3mobi/imgposts && acl_a_board', 'cat' => array('ACP_IMG_FROM_POSTS')),
				'manage_imgposts'	=> array('title' => 'ACP_IMG_MANAGE_POSTS', 'auth' => 'ext_bb3mobi/imgposts && acl_a_board', 'cat' => array('ACP_IMG_FROM_POSTS')),
			),
		);
	}
}
