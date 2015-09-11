<?php
/**
*
* Images from posts extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Anvar <http://bb3.mobi>
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_IMG_FROM_POSTS'			=> 'Images from posts',
	'ACP_IMG_MANAGE_POSTS'			=> 'Manage Thumbnails',
	'ACP_IMG_MANAGE_POSTS_EXPLAIN'	=> 'Here you can delete all or unused thumbnails, as well as configure the automatic deletion of the thumbnails cache.',
	'ACP_IMG_POSTS'					=> 'Display Settings',
	'ACP_IMG_POSTS_DESCRIPTION'		=> 'Here you can customize display of images posted in forum.',
	'ACP_IMG_POSTS_EXPLAIN'			=> 'Configure the image display settings<br />Extension <a href="http://bb3.mobi/forum/viewtopic.php?t=29">Images from Posts</a> (c) Anvar Stybaev',
	'CLEAR_ALL'						=> 'Remove all thumbnails',
	'CLEAR_ALL_EMPTY'				=> 'The folder thumbnails cache is empty',
	'CLEAR_ALL_ERROR'				=> 'Unable to clear the thumbnails cache (thumbnail folder may not exist)',
	'CLEAR_ALL_SUCCESS'				=> 'The thumbnails cache has been successfully cleared',
	'CLEAR_CACHE'					=> 'Clear the thumbnail cache',
	'CLEAR_CACHE_BY_CRON'			=> 'Configure automatic thumbnail deletion using CRON',
	'CLEAR_OLD'						=> 'Remove unused thumbnails',
	'CLEAR_OLD_ERROR'				=> 'Failed to remove unused thumbnails',
	'CLEAR_OLD_SUCCESS'				=> 'Unused thumbnails have been successfully removed',
	'CREATE_THUMBS'					=> 'Checking and creating thumbnails',
	'CRON_PRUNE'					=> 'Frequency of job',
	'CRON_SET'						=> 'Enable automatic thumbnail deletion',
	'FIRST_IMAGES_FLOAT'			=> 'Display the thumbnail on the left ?',
	'FIRST_IMAGES_TOPIC'			=> 'Display in Topic List',
	'FIRST_IMAGES_TOPIC_ON'			=> 'Enable Images in Topic List ?',
	'FORUMS_EXPLAIN'				=> 'Select a forum for which to create thumbnails',
	'IMAGES_ATACHMENT'				=> 'Image Type',
	'IMAGES_ATACHMENT_EXPLAIN'		=> 'To show thumbnails for attachments, select Attachments. To show thumbnails for [img] tags, select [img] Images. To show both, select All. <span class="error">(not recommended, as it will significantly increase the load).</span>',
	'IMAGES_ATTACHMENT_ALL'			=> 'Images from other forums',
	'IMAGES_ATTACHMENT_ALL_EXPLAIN'	=> 'Display images on this forum from other forums ?',
	'IMAGES_BOTTOM_TYPE'			=> 'Displayed at the bottom of the board index page',
	'IMAGES_BOTTOM_TYPE_EXPLAIN'		=> 'Display the thumbnail block at the bottom of page ?',
	'IMAGES_CAROUSEL_TYPE'			=> 'Scroll images',
	'IMAGES_CAROUSEL_TYPE_EXPLAIN'	=> 'Ability to scroll images by clicking on appropriate buttons.',
	'IMAGES_COUNT_IMG'				=> 'Image maximum',
	'IMAGES_COUNT_IMG_EXPLAIN'		=> 'Maximum number of images in block',
	'IMAGES_COUNT_IMG_MIN'			=> 'Image minimum',
	'IMAGES_COUNT_IMG_MIN_EXPLAIN'	=> 'Minimum number of images for display',
	'IMAGES_HEIGHT_WIDTH'			=> 'Create a square thumbnail ?',
	'IMAGES_HEIGHT_WIDTH_EXPLAIN'	=> 'If yes, thumbnails will be cropped on the left and right or top and bottom, otherwise it will be shown in proportion to the reduced image',
	'IMAGES_IGNORE_FORUM'			=> 'Ignored forums',
	'IMAGES_IGNORE_TOPIC'			=> 'Ignored topics',
	'IMAGES_IGNORE_TOPIC_EXPLAIN'	=> 'Comma separated topic ID to exclude certain topics from display.',
	'IMAGES_MODE_ALL'				=> 'All',
	'IMAGES_MODE_ATT'				=> 'Attachments',
	'IMAGES_MODE_IMG'				=> '[img] Images',
	'IMAGES_NEW_PATH'				=> 'Path for storing thumbnails',
	'IMAGES_NEW_PATH_EXPLAIN'		=> '<span class="error">Thumbnails folder should have permissions of CHMOD: 777.</span>',
	'IMAGES_PHPBB_GALLERY'			=> 'Images from Gallery',
	'IMAGES_PHPBB_GALLERY_EXPLAIN'	=> 'If enabled, images from the Gallery Extension will be available for thumbnails. See extension: &laquo;&nbsp;<a href="https://www.phpbb.com/community/viewtopic.php?f=456&t=2273501">phpBB Gallery</a>&nbsp;&raquo;.<br />This is optional, and not required in your installation.',
	'IMAGES_PLACE_TYPE'				=> 'Place display',
	'IMAGES_PLACE_TYPE_ALL'			=> 'Both: On index page/In list of topics',
	'IMAGES_PLACE_TYPE_INDEX'		=> 'On index page',
	'IMAGES_PLACE_TYPE_OFF'			=> 'Turned off',
	'IMAGES_PLACE_TYPE_VIEW'		=> 'In list of topics',
	'IMAGES_SETTINGS'				=> 'Thumbnail settings',
	'IMAGES_SIZE_IMG'				=> 'Image width maximum',
	'IMAGES_TOP_INVERT'				=> 'Display unit in top of forum',
	'IMAGES_TOP_INVERT_EXPLAIN'		=> 'Display the thumbnail block on the top of page ?',
	'LAST_IMAGES_ATACHMENT'			=> 'Last Images Attachment',
	'LOG_CLEAR_IMG_CACHE'			=> '<strong>Images from posts</strong> cleared thumbnails cache',
	'LOG_IMG_FROM_POSTS_CONFIG'		=> '<strong>Images from posts</strong> settings applied',
	'NOT_FORUM_SELECTED'			=> 'You must select the forum, please try again.',
	'NO_IMAGES_TO DELETE'			=> 'Abandoned or unused thumbnails not found',
	'NUM_THUMBS'					=> 'The number of messages being processed',
	'NUM_THUMBS_EXPLAIN'			=> 'Select the desired number of messages to process',
	'THUMB_CREATED'					=> '<strong>Total checked thumbnails: %d created: %d</strong><br />The following thumbnails were created<br />%s',
	'THUMB_NOT_NEED_CREATE'			=> '<strong>Total checked thumbnails: %d</strong>. Thumbnails queued for creation not found',
	'WATERMARKS'					=> '&laquo;&nbsp;Watermarks&nbsp;&raquo;',
	'WATERMARKS_EXPLAIN'			=> 'Enter the text that will be generated for the watermark, or leave it blank if the watermark is not needed.',
));
