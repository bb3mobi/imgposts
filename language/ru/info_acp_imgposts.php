<?php
/**
*
* Images from posts [Russian]
*
* @package info_acp_imgposts.php
* @copyright (c) 2014 Anvar http://apwa.ru
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
	'ACP_IMG_FROM_POSTS'			=> 'Изображения с постов',

	'ACP_IMG_POSTS'					=> 'Управление отображением',
	'ACP_IMG_POSTS_EXPLAIN'			=> 'Anvar (c) <a href="http://bb3.mobi">BB3.Mobi</a>',
	'ACP_IMG_POSTS_DESCRIPTION'		=> 'Тут вы можете настроить отображение изображений размещённых на форуме.',

	'LAST_IMAGES_ATACHMENT'			=> 'Последние изображения форума',
	'IMAGES_PLACE_TYPE'				=> 'Место отображения',
	'IMAGES_IGNORE_FORUM'			=> 'Игнорируемые форумы',
	'IMAGES_IGNORE_TOPIC'			=> 'Игнорируемые темы',
	'IMAGES_ATTACHMENT_ALL'			=> 'Не учитывать форумы',
	'IMAGES_ATTACHMENT_ALL_EXPLAIN'	=> 'Выводить на странице форума, изображения с других форумов?',
	'IMAGES_IGNORE_TOPIC_EXPLAIN'	=> 'Введите через запятую id тем, если хотите запретить их.',
	'IMAGES_BOTTOM_TYPE'			=> 'Отображать внизу страниц на главной',
	'IMAGES_TOP_INVERT'				=> 'Отображать блок в форуме сверху',
	'IMAGES_TOP_INVERT_EXPLAIN'		=> 'Блок с миниатюрами на странице со списком тем будет расположен сверху.',
	'IMAGES_CAROUSEL_TYPE'			=> 'Использовать прокрутку изображений',
	'IMAGES_CAROUSEL_TYPE_EXPLAIN'	=> 'Возможность прокручивать изображения пользователями, нажимая на соответствующие кнопки.<br />В другом случае будет использован crawl.js',
	'IMAGES_COUNT_IMG_MIN'			=> 'Минимум изображений для вывода',
	'IMAGES_COUNT_IMG_MIN_EXPLAIN'	=> 'Минимальное количество изображений для отображения блока',
	'IMAGES_COUNT_IMG'				=> 'Сколько выводить изображений',
	'IMAGES_SIZE_IMG'				=> 'Максимальная ширина изображений',

	'IMAGES_PLACE_TYPE_OFF'			=> 'Выключен',
	'IMAGES_PLACE_TYPE_INDEX'		=> 'На главной',
	'IMAGES_PLACE_TYPE_VIEW'		=> 'В списке тем',
	'IMAGES_PLACE_TYPE_ALL'			=> 'На главной и в списке тем',

	'IMAGES_SETTINGS'				=> 'Настройки миниатюр',
	'IMAGES_NEW_PATH'				=> 'Путь для хранения миатюр',
	'IMAGES_NEW_PATH_EXPLAIN'		=> '<span class="error">На папку с миниатюрами должны быть установлены права допускающие запись!</span>',
	'IMAGES_HEIGHT_WIDTH'			=> 'Делать изображения квадратными?',
	
	'FIRST_IMAGES_TOPIC'			=> 'Первое изображение темы',
	'FIRST_IMAGES_TOPIC_ON'			=> 'Включить отображение изображений?',
	'FIRST_IMAGES_FLOAT'			=> 'Отображение миниатюры слева?',
));
