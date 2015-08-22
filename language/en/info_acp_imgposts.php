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
	'ACP_IMG_POSTS'					=> 'Display Settings',
	'ACP_IMG_POSTS_EXPLAIN'			=> 'Configure the image display settings<br /><a href="http://bb3.mobi">Img from Posts</a> (c) <a href="http://apwa.ru">Anvar</a>',
	'ACP_IMG_POSTS_DESCRIPTION'		=> 'Here you can customize display of images posted in forum.',
	'ACP_IMG_MANAGE_POSTS'			=> 'Manage Thumbnails',
	'ACP_IMG_MANAGE_POSTS_EXPLAIN'	=> 'Here you can delete all or unused thumbnails, as well as configure the automatic deletion of the thumbnails cache.',
	'LAST_IMAGES_ATACHMENT'			=> 'Last Images Attachment',
	'IMAGES_MODE_IMG'				=> '[img] Images',
	'IMAGES_MODE_ATT'				=> 'Attachments',
	'IMAGES_MODE_ALL'				=> 'All',
	'IMAGES_PHPBB_GALLERY'			=> 'Images from Gallery',
	'IMAGES_PHPBB_GALLERY_EXPLAIN'	=> 'If enabled, images from the Gallery Extension will be available for thumbnails. <a href="https://www.phpbb.com/customise/db/mod/phpbb_gallery/">&laquo;phpBB Gallery 3.1 Extension&raquo;</a><br />This is optional, and not required in your installation.',
	'IMAGES_PLACE_TYPE'				=> 'Place display',
	'IMAGES_IGNORE_FORUM'			=> 'Ignored forums',
	'IMAGES_IGNORE_TOPIC'			=> 'Ignored topics',
	'IMAGES_ATTACHMENT_ALL'			=> 'Images from other forums',
	'IMAGES_ATTACHMENT_ALL_EXPLAIN'	=> 'Display images on this forum from other forums ?',
	'IMAGES_IGNORE_TOPIC_EXPLAIN'	=> 'Comma separated topic ID to exclude certain topics from display.',
	'IMAGES_BOTTOM_TYPE'			=> 'Displayed at the bottom of the board index page',
	'IMAGES_TOP_INVERT'				=> 'Display unit in top of forum',
	'IMAGES_TOP_INVERT_EXPLAIN'		=> 'Display the thumbnail block on the top of page ?',
	'IMAGES_CAROUSEL_TYPE'			=> 'Scroll images',
	'IMAGES_CAROUSEL_TYPE_EXPLAIN'	=> 'Ability to scroll images by clicking on appropriate buttons.',
	'IMAGES_COUNT_IMG_MIN'			=> 'Image minimum',
	'IMAGES_COUNT_IMG_MIN_EXPLAIN'	=> 'Minimum number of images for display',
	'IMAGES_COUNT_IMG'				=> 'Image maximum',
	'IMAGES_COUNT_IMG_EXPLAIN'		=> 'Maximum number of images in block',
	'IMAGES_SIZE_IMG'				=> 'Image width maximum',
	'IMAGES_PLACE_TYPE_OFF'			=> 'Turned off',
	'IMAGES_PLACE_TYPE_INDEX'		=> 'On index page',
	'IMAGES_PLACE_TYPE_VIEW'		=> 'In list of topics',
	'IMAGES_PLACE_TYPE_ALL'			=> 'Both: On index page/In list of topics',
	'IMAGES_SETTINGS'				=> 'Thumbnail settings',
	'IMAGES_NEW_PATH'				=> 'Path for storing thumbnails',
	'IMAGES_NEW_PATH_EXPLAIN'		=> '<span class="error">Thumbnails folder should have permissions of CHMOD: 777</span>',
	'IMAGES_HEIGHT_WIDTH'			=> 'Create a square thumbnail ?',
	'IMAGES_HEIGHT_WIDTH_EXPLAIN'	=> 'If yes, thumbnails will be cropped on the left and right or top and bottom, otherwise it will be shown in proportion to the reduced image',
	'IMAGES_ATACHMENT'				=> 'Image Type',
	'IMAGES_ATACHMENT_EXPLAIN'		=> 'To show thumbnails for attachments, select Attachments. To show thumbnails for [img] tags, select [img] Images. To show both, select All. <span class="error">(not recommended, as it will significantly increase the load)</span>',
	'FIRST_IMAGES_TOPIC'			=> 'Display in Topic List',
	'FIRST_IMAGES_TOPIC_ON'			=> 'Enable Images in Topic List ?',
	'FIRST_IMAGES_FLOAT'			=> 'Display the thumbnail on the left ?',
	'WATERMARKS'					=> '&laquo;Watermarks&raquo;',
	'WATERMARKS_EXPLAIN'			=> 'Enter the text that will be generated for the watermark, or leave it blank if the watermark is not needed.',
	'CLEAR_CACHE'					=> 'Clear the thumbnail cache',
	'CLEAR_ALL_SUCCESS'				=> 'The thumbnails cache has been successfully cleared',
	'CLEAR_OLD_SUCCESS'				=> 'Unused thumbnails have been successfully removed',
	'CLEAR_OLD'						=> 'Remove unused thumbnails',
	'CLEAR_ALL'						=> 'Remove all thumbnails',
	'CLEAR_ALL_ERROR'				=> 'Unable to clear the thumbnails cache (thumbnail folder may not exist)',
	'CLEAR_ALL_EMPTY'				=> 'Thumbnails cache is empty',
	'CLEAR_OLD_ERROR'				=> 'Failed to remove unused thumbnails',
	'CLEAR_CACHE_BY_CRON'			=> 'Configure automatic thumbnail deletion using CRON',
	'CRON_SET'						=> 'Enable automatic thumbnail deletion',
	'CRON_PRUNE'					=> 'Frequency of job',
	'CREATE_THUMBS'					=> 'Checking and creating thumbnails',
	'FORUMS_EXPLAIN'				=> 'Select a forum for which to create thumbnails',
	'NO_IMAGES_TO DELETE'			=> 'Abandoned or unused thumbnails found',
	'NUM_THUMBS'					=> 'The number of messages being processed',
	'NUM_THUMBS_EXPLAIN'			=> 'Select the desired number of messages to process',
	'THUMB_CREATED'					=> '<strong>Total checked thumbnails: %d created: %d</strong><br />The following thumbnails were created<br />%s',
	'THUMB_NOT_NEED_CREATE'			=> '<strong>Total checked thumbnails: %d</strong>. Thumbnails queued for creation not found',
	'NOT_FORUM_SELECTED'			=> 'You must select the forum, please try again.',
	'LOG_IMG_FROM_POSTS_CONFIG'		=> '<strong>Images from posts</strong> settings applied',
	'LOG_CLEAR_IMG_CACHE'			=> '<strong>Images from posts</strong> cleared thumbnails cache',
));
