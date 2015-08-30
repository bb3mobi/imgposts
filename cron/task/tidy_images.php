<?php
/**
*
* @package Images from posts v 1.0.3
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace bb3mobi\imgposts\cron\task;

/**
* Tidy cron task.
*
*/

class tidy_images extends \phpbb\cron\task\base
{
	protected $config;
	protected $phpbb_log;
	protected $user;
	protected $template;
	protected $auth;
	protected $db;
	protected $phpbb_root_path;
	protected $php_ext;

	/**
	* Constructor.
	*/
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\log\log $phpbb_log,
		\phpbb\user $user,
		\phpbb\template\template $template,
		\phpbb\auth\auth $auth,
		\phpbb\db\driver\driver_interface $db,
		$phpbb_root_path,
		$php_ext
	)
	{
		$this->config = $config;
		$this->phpbb_log = $phpbb_log;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->template = $template;
		$this->auth = $auth;
		$this->php_ext = $php_ext;
		$this->db = $db;
	}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		$this->cron_tidy_images();
	}

	/**
	* Returns whether this cron task should run now, because enough time
	* has passed since it was last.
	* @return bool
	*/
	public function should_run()
	{
		if (($this->config['images_prune_last_gc'] < time() - $this->config['images_prune_gc']) && $this->config['imgposts_cron'])
		{
			$this->cron_tidy_images();
		}
	}

	public function cron_tidy_images()
	{
		$phpbb_ext_kb = new \bb3mobi\imgposts\core\helper($this->template, $this->phpbb_log, $this->config, $this->user, $this->auth, $this->db, $this->phpbb_root_path, $this->php_ext);
		$phpbb_ext_kb->clear_cache();
		$this->config->set('images_prune_last_gc', time(), true);
	}
}
