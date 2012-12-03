<?php

	class User {
		
		public static function getCurrentUser() {
			// if a userid is specified, reload the user
			if(isset($_REQUEST['userid'])) { 				
				$user = User::getUserForLogin($_REQUEST['userid']);
				$_SESSION['user'] = $user;
				return $user;
			} else if(isset($_SERVER['REMOTE_USER'])) { 
				$user = User::getUserForLogin($_SERVER['REMOTE_USER']);
				$_SESSION['user'] = $user;
				return $user;
			} else if(isset($_SERVER['PHP_AUTH_USER'])) { 
				$user = User::getUserForLogin($_SERVER['PHP_AUTH_USER']);
				$_SESSION['user'] = $user;
				return $user;
			} else if(isset($_SESSION['user'])) {
				// if a user is stored in the session use it
				return $_SESSION['user'];
			} else {
				// urgh, no way!				
				echo '<table>';
				foreach(array_keys($_SERVER) as $key) {
					echo '<tr><td>' . $key . '</td><td>' . $_SERVER[$key] . '</td></tr>';
				}
				echo '</table>';
				die("no userid defined");
			}
		}	
		
		public static function getUserForLogin($login) {		
						
			$mysql = MySQL::getInstance();
			
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
			
			//echo 'getting user for login \'' . $login . '\'<br>';
					
			$user = null;
			
			// load the person data
			$stmt = $mysql->stmt_init();			
			if($stmt->prepare("select 
				p.id, 
				p.login, 
				p.surname, 
				p.name, 
				p.sex, 
				p.rank_id, 
				r.short_description, 
				r.long_description 
			from 
				person as p 
			inner join 
				rank as r 
			on 
				p.rank_id = r.id 
			where login = ?")) {
				$stmt->bind_param('s', $login);
				
				$u_id = 0;
				$u_login = "";				
				$u_surname = '';
				$u_name = '';
				$u_sex = 0;
				$u_rank_id = 0;	
				$u_rank_short = '';	
				$u_rank_long = '';				
				$stmt->bind_result($u_id, $u_login, $u_surname, $u_name, $u_sex, $u_rank_id, $u_rank_short, $u_rank_long);				
				$stmt->execute();								
				$stmt->fetch();
				$user = new User($u_id, $u_login, $u_surname, $u_name, $u_sex, $u_rank_id, $u_rank_short, $u_rank_long);
				$stmt->close();				
			}
			
			if(isset($user)) {
				// load the user roles
				$stmt = $mysql->stmt_init();
				if($stmt->prepare("select role_id from authorisations where user_id = ?")) {
					$id = $user->getId();
					$stmt->bind_param('i', $id);
					$stmt->execute();
					$role = 0;
					$stmt->bind_result($role);
					$user->roles = array();
					while($stmt->fetch()) {
						array_push($user->roles, $role);
					}
					$stmt->close();
				}
			}
			return $user;
		}	
		
	
		private $login;
		private $id;
		private $surname;
		private $name;
		private $sex;
		private $rank_id;	
		private $rank_short;	
		private $rank_long;	
		private $roles;
				

		public function __construct($id, $login, $surname, $name, $sex, $rank_id, $rank_short, $rank_long) {
			$this->id = $id;
			$this->login = $login;
			$this->surname = $surname;
			$this->name = $name;
			$this->sex = $sex;
			$this->rank_id = $rank_id;
			$this->rank_short = $rank_short;
			$this->rank_long = $rank_long;
		}
		
		public function getLogin() {
			return $this->login;
		}

		public function getId() {
			return $this->id;
		}

		public function getSurname() {
			return $this->surname;
		}

		public function getName() {
			return $this->name;
		}

		public function getSex() {
			return $this->sex;
		}
		
		public function getRankId() {
			return $this->rank_id;
		}

		public function getShortRankDescription() {
			return $this->rank_short;
		}		
		
		public function getLongRankDescription() {
			return $this->rank_long;
		}				
		
		public function getRoles() {
			return $this->roles;
		}		
		
		public function hasRole($role) {
			return in_array($role, $this->roles);
		}				
		
		public function isMale() {
			return $this->sex == 1;
		}
		
		public function isFemale() {
			return $this->sex == 2;
		}		
		
		public function __toString() {
			return $this->name . ' ' . $this->surname . ' (' . $this->login . ')';
		}		
	}
?>
