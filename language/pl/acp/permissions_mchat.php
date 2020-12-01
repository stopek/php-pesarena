<?php
/**
* acp_permissions_mchat (phpBB Permission Set) [Polish]
*
* @package language
* @version $Id: permissions_phpbbmchat.php 
* @copyright (c) 2010 rmcgirr83.org
* @copyright (c) 2009 phpbb3bbcodes.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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

// Adding new category
$lang['permission_cat']['mchat'] = 'mChat';

// Adding the permissions
$lang = array_merge($lang, array(
	// User perms
	'acl_u_mchat_use'			=> array('lang' => 'Może używać mChat', 'cat' => 'mchat'),
	'acl_u_mchat_view'			=> array('lang' => 'Może widzieć mChat', 'cat' => 'mchat'),
	'acl_u_mchat_edit'			=> array('lang' => 'Może edytować wiadomości w mChat', 'cat' => 'mchat'),
	'acl_u_mchat_delete'		=> array('lang' => 'Może usuwać wiadomości w mChat', 'cat' => 'mchat'),
	'acl_u_mchat_ip'			=> array('lang' => 'Może widzieć adresy IP w mChat', 'cat' => 'mchat'),
	'acl_u_mchat_flood_ignore'	=> array('lang' => 'Może ignorować limit wysłania jednej wiadomości w określonym czasie w mChat', 'cat' => 'mchat'),
	'acl_u_mchat_archive'		=> array('lang' => 'Może przeglądać archiwum', 'cat' => 'mchat'),
	'acl_u_mchat_bbcode'		=> array('lang' => 'Może używać bbcode w mChat', 'cat' => 'mchat'),
	'acl_u_mchat_smilies'		=> array('lang' => 'Może używać uśmieszki w mChat', 'cat' => 'mchat'),
	'acl_u_mchat_urls'			=> array('lang' => 'Może wysyłać adresy url w mChat', 'cat' => 'mchat'),

	// Admin perms
	'acl_a_mchat'				=> array('lang' => 'Może zarządzać ustawieniami mChat', 'cat' => 'permissions'), // Using a phpBB category here
));

?>