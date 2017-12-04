<?php
	namespace noware;
	
	//require_once (__DIR__ . DIRECTORY_SEPARATOR . 'db.php');
	require_once ($_SERVER ['REQUEST_SCHEME'] . '://' . $_SERVER ['HTTP_HOST'] . '/lib/db.noware.php/www.db.php');
	//require_once ($db_src);
	
	class usr
	{
		protected $db;
		protected $id;
		//protected $id;
		//protected $name, $grp, $attr, $msg;
		//public $dsn, $usr, $key, $cfg;
		
		
		public function __construct ()
		{
			$this -> db = new db ();
			//$this -> dsn = '';
			//$this -> usr = '';
			//$this -> key = '';
			//$this -> cfg = array ();
			$this -> id = null;
		}
		
		public function __destruct ()
		{
			//$this -> deauthnt ();
			//$this -> db -> disconnect ();
		}
		
		/*
		public function __clone ()
		{
			$this -> database = new database ();
			
			$this -> name = new account\name ();
			$this -> group = new account\group ();
			$this -> attribute = new account\attribute ();
			$this -> message = new account\message ();
			
			$this -> name -> reinitialize ($this -> database);
			$this -> group -> reinitialize ($this -> database);
			$this -> attribute -> reinitialize ($this -> database);
			$this -> message -> reinitialize ($this -> database);
			
			//$this -> identify ('');
		}
		*/
		/*
		public function __set ($name, $value)
		{
			switch ($name)
			{
				case 'id':
				//	$this -> $name = $value;
					break;
				//case 'key':
				//case 'password':
				//	break;
				//case 'name':
				//	if (self::exists ($value))
				//		$this -> name = $value;
				//	
				//	break;
				//case 'enabled':
				//	if ($value)
				//		self::enable ($this -> name);
				//	else
				//		self::disable ($this -> name);
				//	
				//	//break;
				case 'database':
					if (gettype ($value) == 'object' && get_class ($value) == database::class)
					{
						$this -> $name = $value;
						
						$this -> name -> reinitialize ($this -> $name);
						$this -> group -> reinitialize ($this -> $name);
						$this -> attribute -> reinitialize ($this -> $name);
						$this -> message -> reinitialize ($this -> $name);
						
						//$this -> identify ('');
					}
			}
		}
		*/
		
		/*
		public function init ()
		{
			return $this -> db -> connect ($this -> dsn, $this -> usr, $this -> key, $this -> cfg);
		}
		
		public function inited ()
		{
			return $this -> db -> connected ();
		}
		
		public function fin ()
		{
			return $this -> db -> disconnect ();
		}
		*/
		
		public function __get ($name)
		{
			return $this -> $name;
		}
		
		public function __set ($name, $val)
		{
			switch ($name)
			{
				case 'name':
					if (rename ($this -> $name, $val))
						$this -> $name = $val;
					
					break;
				case 'db':
					if (gettype ($value) == 'object' && get_class ($value) == db::class)
					{
						$this -> $name = $value;
					}
			}
			
			return $this -> $name;
		}
		
		/*
		public function connect ()
		{
			if ($this -> database -> connected ())
				return true;
			
			$argc = func_num_args ();
			
			switch ($argc)
			{
				case 4:
					$configuration = func_get_arg (3);
				case 3:
					$key = func_get_arg (2);
				case 2:
					$name = func_get_arg (1);
				case 1;
					$dsn = func_get_arg (0);
					break;
				default:
					return false;
			}
			
			switch ($argc)
			{
				case 1:
					return $this -> database -> connect ($dsn);
				case 2:
					return $this -> database -> connect ($dsn, $name);
				case 3:
					return $this -> database -> connect ($dsn, $name, $key);
				default:
					return $this -> database -> connect ($dsn, $name, $key, $configuration);
			}
		}
		*/
		
		// Authorization.
		// Registration.
		
		//public static function all ()
		public function ls ()
		{
			//if (!$this -> identified () || !$this -> database -> query ('SELECT DISTINCT `ID` FROM `Permissions: Expanded: Accounts` WHERE `Account` = ? AND `Action: Name` = ? AND `Value` != ?', $result, array ($this -> id, 'r', false)/*, array ('fetch mode' => \PDO::FETCH_NAMED)*/))
			
			$result = array ();
			
			//if (!$this -> inited ())
			//	return $result;
			
			if (!$this -> authed ())
				return $result;
			
			$this -> db -> query ($exception, $result, 'select "enabled", "id", "id.grp", "name", "key", "cmd", "home", "gecos" from "sys.usr.norm"', array ($this -> id, '*'));
			
			return $result;
		}
		
		public function exist ($usr)
		{
			//if (!$this -> identified () || !$this -> database -> query ('SELECT DISTINCT `ID` FROM `Permissions: Expanded: Accounts` WHERE `Account` = ? AND `Action: Name` = ? AND `Value` != ?', $result, array ($this -> id, 'r', false)/*, array ('fetch mode' => \PDO::FETCH_NAMED)*/))
			
			//$result = array ();
			
			//if (!$this -> inited ())
			//	return false;
			
			if (!$this -> authed ())
				return false;
			
			if (!$this -> db -> query ($exception, $result, 'select "ntt.id" from "sys.usr.norm" where "name" = ?'))
				return false;
			
			if (empty ($result))
				return false;
			
			 foreach ($result as $usr)
				 if (may ($usr [0], 'access'))
					 return true;
			 
			 return false;
		}
		
		
		// auth[orize]
		// reg[ister]
		// add
		//public static function add ($name/*, $key = null*/)
		public function add ($name, $key = '')
		{
			//if (!$this -> inited ())
			//	return false;
			
			if (!$this -> authed ())
				return false;
			
			return $this -> db -> query ($exception, $result, 'insert or ignore into "sys.usr.norm" ("name", "key") values (?, ?)', array ($name, $key));
		}
		
		// deauth[orize]
		// dereg[ister]
		// del[ete]
		//public static function delete ($name)
		public function del ($name, $key = '')
		{
			if (!$this -> inited ())
				return false;
			
			if (!$this -> authed ())
				return false;
			
			return $this -> db -> query ($exception, $result, 'delete from "sys.usr.norm" where "name" = ? and "key" = ?', array ($name, $key));
		}
		
		
		// Authentication.
		// Identification.
		
		// authenticated
		// identified
		
		// Signed in?
		
		public function authned ()
		{
			return $this -> id != null;
		}
		
		
		// authenticate
		// identify
		
		// Sign in.
		
		//public static function identify ($name, $key = null)
		//public function identify ($name, $key = '')
		public function authnt ($name, $key = '')
		{
			//if (!$this -> db -> query ($exception, $result, 'SELECT COUNT (DISTINCT "ID") AS \'nr\', "ID" FROM `Accounts` WHERE `Name` = ? AND `Password` = ?', array ($name, $key)))
			if (!$this -> db -> query ($exception, $result, 'select "ntt.id" from "sys.usr.norm" where "name" = ? and "key" = ?', array ($name, $key)))
			{
				//var_dump ('Failed to query to identify.');
				return false;
			}
			
			if (empty ($result))
				return false;
			else
			{
				if (count ($result) > 1)
					return false;
				
				//$this -> id = $result [0] [1];
				$this -> id = $result [0] [0];
				//$this -> name = $name;
				//var_dump ($this -> id);
				return true;
			}
		}
		
		/*
		// Verify identity.
		//public static function verify ($name, $key)
		//public function validate ($key)
		//public function verify ($key)
		//public function verify_identity ($key)
		public function reauth ($key)
		{
			if (!$this -> authed ())
				return false;
			
			return $this -> auth ($this -> name, $key);
			
			if (!$this -> database -> query ('SELECT COUNT (DISTINCT `ID`) FROM `Accounts` WHERE `ID` = ? AND `Password` = ?', $result, array ($this -> id, $key)))
				return false;
			
			return $result ['data'] [0] [0] > 0;
		}
		*/
		
		public function authed ()
		{
			return $this -> id != null;
		}
		
		// deauthenticate
		// anonymize
		
		// Sign out.
		
		public function deauthnt ()
		{
			//if (!$this -> identified ())
			//	return true;
			
			//if ($this -> authed ())
				$this -> id = null;
			
			return true;
		}
		
		/*
		// State: Active/Inactive
		public function state ()
		{
			switch (func_num_args ())
			{
				// Get the state.
				case 0:
					if (!$this -> query ('SELECT `State` FROM `Accounts` WHERE `Name` = ?', array ($id)))
						return;
					
					$result = $this -> statement -> fetchAll (\PDO::FETCH_NUM);
					
					if ($result === false || count ($result) <= 0)
						return;
					
					return !($result [0] [0]);
				
				// Set the state.
				default:
					if (gettype (func_get_arg (0)) != 'boolean')
						return;
					
					return false;
			}
		}
		*/
		
		
		// Enabled state.
		
		// Get the state.
		//public static function enabled ($name)
		public function enabled ($name)
		{
			//if (!$this -> inited ())
			//	return false;
			
			if (!$this -> authed ())
				return false;
			
			if (!$this -> db -> query ($exception, $result, 'select "enabled" from "sys.usr.norm" where "name" = ?', array ($name)))
				return false;
			
			return (bool) $result [0] [0];
		}
		
		//public static function enable ($name)
		public function enable ($name)
		{
			if (!$this -> inited ())
				return false;
			
			if (!$this -> authed ())
				return false;
			
			return $this -> db -> query ($exception, $result, 'update "sys.usr.norm" set "enabled" = ? where "name" = ?', array (1, $name));
		}
		
		//public static function disable ($name)
		public function disable ($name)
		{
			//if (!$this -> inited ())
			//	return false;
			
			if (!$this -> authed ())
				return false;
			
			return $this -> db -> query ($exception, $result, 'update "sys.usr.norm" set "enabled" = ? where "name" = ?', array (0, $name));
		}
		
		/*
		public static function rename ($old, $new)
		{
			return false;
		}
		*/
		
		// Set the name.
		// Rename.
		
		protected function rename ($old_name, $new_name)
		{
			//if (!$this -> inited ())
			//	return false;
			
			if (!$this -> authed ())
				return false;
			
			if ($new_name == $old_name)
				// No change
				return true;
			
			//if (!defined ('users'))
			//	return false;
			
			if (!$this -> exist ($old_name))
				return false;
			
			//if ($this -> reserved ($name) || $this -> group -> exist ($name) || $this -> exist ($name))
			if ($this -> exist ($new_name))
				return false;
			
			// Verify if the user is allowed to change the name.
			if (!$this -> database -> query ('', $result, array ($this -> name)) || $result ['data'] === false || count ($result ['data']) <= 0 || $result ['data'] [0] [0] <= 0)
				return false;
			
			// Verify if the user is allowed to change the main group.
			if (!$this -> database -> query ('', $result, array ($this -> name)) || $result ['data'] === false || count ($result ['data']) <= 0 || $result ['data'] [0] [0] <= 0)
				return false;
			
			//if (!$this -> rename_folder ($this -> name, $name))
			//	return false;
			
			//if (!$this -> rename_text (users . DIRECTORY_SEPARATOR . $name, $this -> name, $name))
			//{
			//	// Change everything back.
			//	$this -> rename_folder ($name, $this -> name);
			//	
			//	return false;
			//}
			
			return $this -> database -> query ($exception, $result, 'update "sys.usr.norm" set "name" = ? where "name" = ?', array ($new_name, $old_name));
		}
		
		
		// Groups.
		// Main group.
		/*
		public function group ()
		{
			//return '';
		}
		*/
		
		// Make a new group.
		public function grp_add ($name)
		{
			return false;
		}
		
		public function grp_del ($name)
		{
			return false;
		}
		
		// All joined groups.
		public function grps_joined ()
		{
			return array ();
		}
		
		// Join a group.
		public function join ($grp)
		{
			return false;
		}
		
		// Leave a group.
		public function leave ($grp)
		{
			return false;
		}
		
		// Groups
		// System (Attributes)
		// Messages
		
		public function __sleep ()
		{
			/*
			echo '<pre>';
			echo 'serializing noware::usr' . PHP_EOL;
			var_dump ($this -> id);
			echo '</pre>';
			*/
			return array ('db', 'id');
		}
		
		/*
		public function __wakeup ()
		{
			//echo '<pre>';
			//echo 'deserializing noware::usr' . PHP_EOL;
			//var_dump ($this);
			//if ($this -> db != null)
			//$this -> message = $this -> error = "";
			//var_dump ($this -> id);
			//var_dump ($this -> link -> getAttribute(PDO::ATTR_SERVER_INFO));
			//var_dump ('noware::usr::__wakeup()', $this -> db);
			//$this -> db -> reconnect ();
			//$this -> database = new database ();
			//$this -> db -> wakeup ();
			//$this -> db = new db ();
			//$this -> db -> connect ($exception, 'sqlite:/mnt/data/sys.db', 'root');
			//echo '</pre>';
		}
		*/
		
		/*
		public static function types ()
		{
			return array ('literal', 'group');
		}
		*/
		/*
		public function published ()
		{
			if (!$this -> identified ())
				return false;
			
			if (!defined ('www'))
				// We do not have the requirements to work with.
				return false;
			
			return is_link (www . DIRECTORY_SEPARATOR . $this -> id);
		}
		
		public function publish ($value = true)
		{
			if (!$this -> identified ())
				return false;
			
			if (!defined ('www') || !defined ('users'))
				// We do not have the requirements to work with.
				return false;
			
			if ($value)
			{
				if (is_link (www . DIRECTORY_SEPARATOR . $this -> id))
				{
					return true;
				}
				else
				{
					return file_exists (users . DIRECTORY_SEPARATOR . $this -> id . DIRECTORY_SEPARATOR . 'Public') && symlink ('..' . DIRECTORY_SEPARATOR . 'Home' . DIRECTORY_SEPARATOR . $this -> id . DIRECTORY_SEPARATOR . 'Public', www . DIRECTORY_SEPARATOR . $this -> id);
				}
			}
			else if (is_link (www . DIRECTORY_SEPARATOR . $this -> id))
			// We are being asked to remove the link.
			{
				return unlink (www . DIRECTORY_SEPARATOR . $this -> id);
			}
			else
			{
				return true;
			}
		}
		*/
		
		public function may ()
		{
			//echo '<pre>';
			
			switch (func_num_args ())
			{
				case 4:
					$actor = func_get_arg (0);
					$target_id = func_get_arg (1);
					$target_key = func_get_arg (2);
					$action = func_get_arg (3);
					break;
				case 3:
					$actor = $this -> id;
					$target_id = func_get_arg (0);
					$target_key = func_get_arg (1);
					$action = func_get_arg (2);
					break;
				default:
					//echo 'noware::usr::may()::case::default' . PHP_EOL;
					return false;
			}
			
			//var_dump ($actor, $target_id, $target_key, $action);
			
			//return false;
			
			
			//$result = array ();
			
			/*
			if (!$this -> inited ())
			{
				echo 'noware::usr::may()::!inited()' . PHP_EOL;
				return false;
			}
			*/
			
			if (!$this -> authed ())
			{
				//echo 'noware::usr::may()::!authed()' . PHP_EOL;
				return false;
			}
			
			if (!$this -> may_sql ($sql))
			{
				//echo 'noware::usr::may()::!may_sql()' . PHP_EOL;
				return false;
			}
			
			//var_dump ($sql);
			$sql = $sql [0] [0];
			
			/*
			// First, check if the user is allowed to see the aked permission.
			if (!$this -> db -> query ($exception, $result, $sql, array (':actor' => $this -> id, ':target_id' => $target_id, ':target_key' => $target_key), $this -> may_type () ['access']))
				return false;
			
			if ($result [0] [0] == 0)
				return false;
			*/
			
			if (!$this -> db -> query ($exception, $result, $sql, array (':actor' => $actor, ':target_id' => $target_id, ':target_key' => $target_key, ':action' => $action, ':any' => '*')))
			{
				//echo 'noware::usr::may()::!query()' . PHP_EOL;
				return false;
			}
			
			//var_dump ($exception, $result);
			//echo '</pre>';
			return $result [0] [0] != 0;
		}
		
		public function may_type ()
		{
			return array ('access' => 15, 'change' => 16);
		}
		
		protected function may_sql (&$result)
		{
			//if (!$this -> inited ())
			//	return false;
			
			if (!$this -> authed ())
				return false;
			
			return $this -> db -> query
			(
				$exception,
				$result,
				'select
					"value"
				from
					"ntt"
				where
					"id" =
					(
						select
							"dest"
						from
							"ntt.path"
						where
							"src" = 0
							and
							"path" = :sys
							||
							(
								select
									"value"
								from
									"sys.dft"
								where
									"id" = :sys
									and
									"key" = :path_delim
							)
							||
							:fn
					)
				and
					"key" = :may',
				array
				(
					':sys' => 'sys',
					':path_delim' => 'path.delim',
					':fn' => 'fn',
					':may' => 'may'
				)
			);
		}
		
		/*
		public function inited ()
		{
			return true;
		}
		*/
	}
