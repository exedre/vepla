<?php
	require_once('Constants.php');	

	class Course {
	
		public static function getCurrentCourse() {			
			if (isset($_SESSION['course'])) {
				return $_SESSION['course'];
			} else {
				return NULL;
			}		
		}
	
		public static function getCourseForId($id = NULL) {
			if(isset($id)) {				
				$course = Factory::getCourseForId($id);
				$_SESSION['course'] = $course;
				return $course;				
			} elseif(isset($_REQUEST['courseid'])) { 
				$course = Factory::getCourseForId($_REQUEST['courseid']);
				$_SESSION['course'] = $course;
				return $course;
			} elseif (isset($_SESSION['course'])) {
				return $_SESSION['course'];
			} else {
				return NULL;
			}
		}		
		
		public static function getAllPublishedCourses($subject) {		
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
		
			$courses = array();
		
			// create statement object
			$stmt = $mysql->stmt_init();
		
			if($stmt->prepare(
				"select 
					c.id, c.title, c.description, c.url, c.role_id, r.description
				from 
					course as c 
				inner join 
						role as r 
					on 
						c.role_id = r.id 
				where 
					c.published = 1
				and
					c.subject_id = ?
				order by 
					c.id asc")) {
				$stmt->bind_param('i', $subject);
				$stmt->execute();			
				$course_id = 0;
				$course_title = "";
				$course_description = "";
				$course_url = "";
				$course_role_id = 0;
				$course_role_description = "";
				$stmt->bind_result($course_id, $course_title, $course_description, $course_url, $course_role_id, $course_role_description);
				while($stmt->fetch()) {
					$course = new Course($course_id, $course_title, $course_description, $course_url, $course_role_id, $course_role_description);
					array_push($courses, $course);
				}
				$stmt->close();
			}				
			return $courses;			
		}
	
		private $id;
		private $title;
		private $description;
		private $url;
		private $role_id;
		private $role_description;
		private $elements;
		
		function Course($id, $title, $description, $url, $role_id, $role_description) {
			$this->id = $id;
			$this->title = $title;	
			$this->description = $description;	
			$this->url = $url;	
			$this->role_id = $role_id;	
			$this->role_description = $role_description;	
		}
		
		public function getId() {
			return $this->id;
		}

		public function getTitle() {
			return $this->title;
		}
		
		public function getDescription() {
			return $this->description;
		}
		
		public function getRoleId() {
			return $this->role_id;
		}		

		public function getRoleDescription() {
			return $this->role_description;
		}	
		
		public function getElements() {
			
			if(!isset($this->elements))	{
				//$mysql = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_SCHEMA);
				$mysql = MySQL::getInstance();
			
				if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
			
				// get the course elements
				$stmt = $mysql->stmt_init();
				if($stmt->prepare("select id, ordinal, title, duration, url, course_id, content_id from element where course_id = ? order by ordinal asc")) {
					$stmt->bind_param('i', $this->id);
					$stmt->execute();				
					$this->elements = array();
					$element_id = 0;
					$element_ordinal = "";
					$element_title = "";
					$element_duration = 0;
					$element_url = "";
					$course_id = 0;
					$content_id = 0;
					$stmt->bind_result($element_id, $element_ordinal, $element_title, $element_duration, $element_url, $course_id, $content_id);
					while($stmt->fetch()) {
						$element = new Element($element_id, $element_ordinal, $element_title, $element_duration, $element_url, $course_id, $content_id);						
						$this->elements[$element->getId()] = $element;
					}
					$stmt->close();
				}
				
				$stmt = $mysql->stmt_init();
				if($stmt->prepare("select element_id, duration from participation where person_id = ? order by element_id asc")) {				
					$userid = User::getCurrentUser()->getId();					
					$stmt->bind_param('i', $userid);
					$stmt->execute();				
					$element_id = 0;
					$duration = 0;
					$stmt->bind_result($element_id, $duration);
					while($stmt->fetch()) {
						if(isset($this->elements[$element_id])) {
							$element = $this->elements[$element_id];
							if(isset($element)) {
								$element->markAsViewed();
								$element->setViewDuration($duration);
							}
						}
					}
					$stmt->close();
				}
			}
			return $this->elements;
		}
		
		/*
		public function getElementsWithStatus($user) {			
						
			$mysql = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_SCHEMA);
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
		
			// get the course elements
			$stmt = $mysql->stmt_init();
			if($stmt->prepare("
				select 
					p.element_id, p.duration 
				from 
					participation as p
				inner join 
					element as e
				on 
					p.element_id = e.id
				where
					e.course_id = ? 
				and	
					p.person_id = ?
				order by 
					e.ordinal asc")) {
				$stmt->bind_param('ii', $this->id, $user->getId());
				$stmt->execute();
				$this->elements = array();
				$element_id = 0;
				$participation_duration = 0;
				$stmt->bind_result($element_id, $participation_duration);
				$elements = this->getElements();
				$elements_with_status = array();
				while($stmt->fetch()) {
					foreach($elements as $element) {
						if($element->getId() == $element_id) {
							$element_with_status = new ElementWithStatus($element, 'viewed', $participation_duration);
							array_push($elements_with_status, $element_with_status);
							break;
						}
					}
				}
				$stmt->close();
			}				

			return $this->elements;
		}
		*/
		
		
		public function getElement($id) {
			if(isset($this->elements) && isset($this->elements[$id])) {
				return $this->elements[$id];
			}
			return NULL;
		}
		
		public function getPreviousElementId($element) {
			$id = NULL;
			$max_ordinal = -1;
			foreach(array_keys($this->elements) as $key) {
				if($this->elements[$key]->getOrdinal() > $max_ordinal && $this->elements[$key]->getOrdinal() < $element->getOrdinal()) {
					$id = $key;
					$max_ordinal = $this->elements[$key]->getOrdinal();
				}
			}
			return $id;
		}
		
		public function getNextElementId($element) {
			$id = NULL;
			$min_ordinal = 100000000;
			foreach(array_keys($this->elements) as $key) {
				if($this->elements[$key]->getOrdinal() < $min_ordinal && $this->elements[$key]->getOrdinal() > $element->getOrdinal()) {
					$id = $key;
					$min_ordinal = $this->elements[$key]->getOrdinal();
				}
			}
			return $id;
		}
		
	}
?>
