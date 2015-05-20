<?php
/**
*
* Images from posts [English]
*
* @package info_acp_imgposts.php
* @copyright (c) 2014 Anvar http://bb3.mobi
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_IMG_FROM_POSTS'			=> 'Images from posts',

	'ACP_IMG_POSTS'					=> 'Controls display',
	'ACP_IMG_POSTS_EXPLAIN'			=> 'Anvar (c) <a href="http://bb3.mobi">BB3.Mobi</a>',
	'ACP_IMG_POSTS_DESCRIPTION'		=> 'Here you can customize display of images posted in forum.',

	'LAST_IMAGES_ATACHMENT'			=> 'Latest Images Forums',
	'IMAGES_PLACE_TYPE'				=> 'Place display',
	'IMAGES_IGNORE_FORUM'			=> 'Ignored forums',
	'IMAGES_IGNORE_TOPIC'			=> 'Ignored topics',
	'IMAGES_ATTACHMENT_ALL'			=> 'Do not include forums',
	'IMAGES_ATTACHMENT_ALL_EXPLAIN'	=> 'Is displayed on page of forum, images from other forums?',
	'IMAGES_IGNORE_TOPIC_EXPLAIN'	=> 'Enter a comma-separated id so if you want to ban them.',
	'IMAGES_BOTTOM_TYPE'			=> 'Displayed at the bottom of page index',
	'IMAGES_TOP_INVERT'				=> 'Display unit in top of forum',
	'IMAGES_TOP_INVERT_EXPLAIN'		=> 'Block thumbnail on page with a list of topics to be located on top.',
	'IMAGES_CAROUSEL_TYPE'			=> 'To scroll images',
	'IMAGES_CAROUSEL_TYPE_EXPLAIN'	=> 'Ability to scroll members by clicking on appropriate buttons.<br />In other case will be used crawl.zhs',
	'IMAGES_COUNT_IMG_MIN'			=> 'Minimum image to display',
	'IMAGES_COUNT_IMG_MIN_EXPLAIN'	=> 'Minimum number of images for display unit',
	'IMAGES_COUNT_IMG'				=> 'How to display images',
	'IMAGES_SIZE_IMG'				=> 'Maximum width of images',

	'IMAGES_PLACE_TYPE_OFF'			=> 'Turned off',
	'IMAGES_PLACE_TYPE_INDEX'		=> 'On index page',
	'IMAGES_PLACE_TYPE_VIEW'		=> 'In list of topics',
	'IMAGES_PLACE_TYPE_ALL'			=> 'On index page/In list of topics',

	'IMAGES_SETTINGS'				=> 'Thumbnail settings',
	'IMAGES_NEW_PATH'				=> 'Path for storing miatyur',
	'IMAGES_NEW_PATH_EXPLAIN'		=> '<span class="error">Folder on thumbnail should be set rules allow entry!</span> CHMOD:777',
	'IMAGES_HEIGHT_WIDTH'			=> 'Making square image?',
	
	'FIRST_IMAGES_TOPIC'			=> 'First Image Topics',
	'FIRST_IMAGES_TOPIC_ON'			=> 'Enable display images?',
	'FIRST_IMAGES_FLOAT'			=> 'Displays thumbnail on left?',
));
