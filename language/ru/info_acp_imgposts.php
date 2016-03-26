<?php
/**
*
* Images from posts extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Anvar <http://apwa.ru>
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
	'ACP_IMG_FROM_POSTS'			=> 'Изображения из сообщений',
	'ACP_IMG_POSTS'					=> 'Управление отображением',
	'ACP_IMG_POSTS_EXPLAIN'			=> '<a href="http://bb3.mobi/forum/viewtopic.php?t=29" title="Последние изображения форума">Images from Posts</a> (c) Anvar - 2015',
	'ACP_IMG_POSTS_DESCRIPTION'		=> 'Здесь вы можете настроить отображение изображений, размещённых на форуме.',
	'ACP_IMG_MANAGE_POSTS'			=> 'Управление миниатюрами',
	'ACP_IMG_MANAGE_POSTS_EXPLAIN'	=> 'Здесь вы можете удалить все или только неиспользуемые миниатюры, а также настроить параметры автоматической очистки кеша миниатюр.',
	'LAST_IMAGES_ATACHMENT'			=> 'Последние изображения форума',
	'IMAGES_MODE_IMG'				=> 'Изображения',
	'IMAGES_MODE_ATT'				=> 'Вложения',
	'IMAGES_MODE_ALL'				=> 'Все',
	'IMAGES_PHPBB_GALLERY'			=> 'Учитывать изображения из галереи',
	'IMAGES_PHPBB_GALLERY_EXPLAIN'	=> 'Если включено, то будут учитыватся изображения из галереи расширения <a href="https://www.phpbb.com/customise/db/mod/phpbb_gallery/">&laquo;phpBB Gallery 3.1 Extension&raquo;</a><br />(Это не означает, что расширение должно быть установлено на вашей конференции)',
	'IMAGES_PLACE_TYPE'				=> 'Место отображения',
	'IMAGES_IGNORE_FORUM'			=> 'Игнорируемые форумы',
	'IMAGES_IGNORE_TOPIC'			=> 'Игнорируемые темы',
	'IMAGES_ATTACHMENT_ALL'			=> 'Не учитывать форумы',
	'IMAGES_ATTACHMENT_ALL_EXPLAIN'	=> 'Выводить на странице форума, изображения с других форумов',
	'IMAGES_IGNORE_TOPIC_EXPLAIN'	=> 'Введите через запятую id тех тем, которые будут проигнорированы при выводе изображений',
	'IMAGES_BOTTOM_TYPE'			=> 'Отображать на главной станице (в списке фрумов) внизу',
	'IMAGES_BOTTOM_TYPE_EXPLAIN'		=> 'Display the thumbnail block at the bottom of page ?',
	'IMAGES_TOP_INVERT'				=> 'Отображать блок в форуме сверху',
	'IMAGES_TOP_INVERT_EXPLAIN'		=> 'Блок с миниатюрами на странице со списком тем будет расположен сверху',
	'IMAGES_CAROUSEL_TYPE'			=> 'Использовать автопрокрутку изображений',
	'IMAGES_CAROUSEL_TYPE_EXPLAIN'	=> 'Возможность прокрутки изображений пользователями нажимая на соответствующие кнопки.',
	'IMAGES_COUNT_IMG_MIN'			=> 'Минимум изображений для вывода',
	'IMAGES_COUNT_IMG_MIN_EXPLAIN'	=> 'Минимальное количество изображений для отображения блока',
	'IMAGES_COUNT_IMG'				=> 'Количество изображений',
	'IMAGES_COUNT_IMG_EXPLAIN'		=> 'Максимальное количество изображений в блоке',
	'IMAGES_SIZE_IMG'				=> 'Максимальная ширина изображений',
	'IMAGES_PLACE_TYPE_OFF'			=> 'Выключен',
	'IMAGES_PLACE_TYPE_INDEX'		=> 'На главной',
	'IMAGES_PLACE_TYPE_VIEW'		=> 'В списке тем',
	'IMAGES_PLACE_TYPE_ALL'			=> 'На главной и в списке тем',
	'IMAGES_SETTINGS'				=> 'Настройки миниатюр',
	'IMAGES_NEW_PATH'				=> 'Путь для хранения миатюр',
	'IMAGES_NEW_PATH_EXPLAIN'		=> '<span class="error">На папку с миниатюрами должны быть установлены права допускающие запись!</span>',
	'IMAGES_HEIGHT_WIDTH'			=> 'Обрезать изображения до квадрата',
	'IMAGES_HEIGHT_WIDTH_EXPLAIN'	=> 'Если да, миниатюры будут обрезаны слева и справа или сверху и снизу, иначе будет показано пропорционально уменьшенное изображение',
	'IMAGES_ATACHMENT'				=> 'Тип изображений',
	'IMAGES_ATACHMENT_EXPLAIN'		=> 'Если выбрано &laquo;Вложения&raquo;, будут показаны миниатюры вложений, если выбрано &laquo;Изображения&raquo;, будут показаны миниатюры изображений со строрниих ресурсов ([img][\img]).<br />Если выбрано &laquo;Все&raquo;, будут показаны миниатюры обоих типов <span class="error">(Использование изображений из [img][/img] значительно увеличивает нагрузку)</span>',
	'FIRST_IMAGES_TOPIC'			=> 'Первое изображение темы',
	'FIRST_IMAGES_TOPIC_ON'			=> 'Включить отображение изображений',
	'FIRST_IMAGES_FLOAT'			=> 'Отображение миниатюры слева',
	'WATERMARKS'					=> '&laquo;Водяной&raquo; знак',
	'WATERMARKS_EXPLAIN'			=> 'Введите здесь текст, который будет сгенерирован для водяного знака или оставьте это поле пустым, если водяной знак не нужен',
	'CLEAR_CACHE'					=> 'Очистка кеша миниатюр',
	'CLEAR_ALL_SUCCESS'				=> 'Кеш миниатюр был успешно очищен',
	'CLEAR_OLD_SUCCESS'				=> 'Неиспользуемые миниатюры были успешно удалены',
	'CLEAR_OLD'						=> 'Удалить неиспользуемые миниатюры',
	'CLEAR_ALL'						=> 'Очистить кеш миниатюр',
	'CLEAR_ALL_ERROR'				=> 'Не удалось очистить кеш миниатюр (возможно папка с миниатюрами не существует)',
	'CLEAR_ALL_EMPTY'				=> 'Кеш миниатюр пуст',
	'CLEAR_OLD_ERROR'				=> 'Не удалось удалить неиспользуемые миниатюры',
	'CLEAR_CACHE_BY_CRON'			=> 'Настрйка автоматической очистки',
	'CRON_SET'						=> 'Включить автоматическую очистку',
	'CRON_PRUNE'					=> 'Частота запуска очистки',
	'CREATE_THUMBS'					=> 'Проверка и создание минатюр',
	'FORUMS_EXPLAIN'				=> 'Выберите форум, для которого создавать миниатюры',
	'NO_IMAGES_TO DELETE'			=> 'Неиспользумых миниатюр не найдено',
	'NUM_THUMBS'					=> 'Количество обрабатываемых сообщений',
	'NUM_THUMBS_EXPLAIN'			=> 'Выберите необходимое количество обрабатываемых сообщений.<br />По умолчанию установлено значение, равное установке &laquo;Максимальное количество изображений в блоке&raquo;',
	'THUMB_CREATED'					=> '<strong>Всего проверено миниатюр: %d создано: %d</strong><br />Следующие миниатюры были созданы:<br />%s',
	'THUMB_NOT_NEED_CREATE'			=> '<strong>Всего проверено миниатюр: %d</strong>. Миниатюр, которые необходимо создать, необнаружено.',
	'NOT_FORUM_SELECTED'			=> 'Необходимо выбрать форум',
	'LOG_IMG_FROM_POSTS_CONFIG'		=> 'Изменены настройки расширения <strong>Images from posts</strong>',
	'LOG_CLEAR_IMG_CACHE'			=> 'Очищен кеш миниатюр расширения <strong>Images from posts</strong>',
));
