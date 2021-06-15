<?php

/**
*
* @package AllowedExt
* @copyright (c) 2020 DeaDRoMeO ; hello-vitebsk.ru
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace deadromeo\allowedext\event;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;
	
public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		
}
	static public function getSubscribedEvents()
	{
		return array(
		'core.posting_modify_template_vars'			=> 'modify_posting',								
		);
	}

	
	public function modify_posting($event)
	{
	$this->user->add_lang_ext('deadromeo/allowedext', 'allowedext');
			
		$sql = 'SELECT extension
			FROM ' . EXTENSIONS_TABLE . '			
			WHERE group_id IN (SELECT group_id FROM ' . EXTENSION_GROUPS_TABLE . ' WHERE allow_group = 1 )';	
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('row_ae', array(
				'EXT' 			=> $row['extension'],
			));
		}

	}
	
}