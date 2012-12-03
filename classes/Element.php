<?php

	class Element {
		
		private $id;
		private $ordinal;
		private $title;
		private $element_duration;
		private $url;
		private $course_id;
		private $viewed;
		private $view_duration;
		private $content_type;
							
		public function Element($id, $ordinal, $title, $element_duration, $url, $course_id, $content_type) {
			$this->id = $id;
			$this->ordinal = $ordinal;
			$this->title = $title;
			$this->element_duration = $element_duration;
			$this->url = $url;
			$this->course_id = $course_id;
			$this->viewed = false;
			$this->view_duration = 0;
			$this->content_type = $content_type;
		}
		
		public function getId() {
			return $this->id;
		}

		public function getOrdinal() {
			return $this->ordinal;
		}

		public function getTitle() {
			return $this->title;
		}

		public function getElementDuration() {
			return $this->element_duration;
		}
		
		public function getCourseId() {
			return $this->course_id;
		}		
		
		public function getContentType() {
			return $this->content_type;
		}

		public function isViewed() {
			return $this->viewed;
		}
						
		public function isNotViewed() {
			return !$this->viewed;
		}		
		
		public function markAsViewed() {
			$this->viewed = true;
		}		
		
		public function getViewDuration() {
			return $this->view_duration;
		}
		
		public function setViewDuration($duration) {
			$this->view_duration = $duration;
		}
		
		public function getFormattedViewDuration() {
			return TimeUtils::format($this->view_duration);
		}
				
		public function addToViewDuration($duration, $user) {			
			$error = '';
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
			
			// try to update an existing row, if it exists
			$stmt = $mysql->stmt_init();
			
			if($stmt->prepare('update participation set duration = duration + ? where person_id = ? and element_id = ?')) {
				$userid = $user->getId();
				$elemid = $this->id;
				$stmt->bind_param('iii', $duration, $userid, $elemid);
				$stmt->execute();							
				if($mysql->affected_rows == 1) {
					echo 'OK';
					$this->view_duration = $this->view_duration + $duration;
					return;
				} else {
					$error = $mysql->error;
				}
				
			}
			$stmt->close();
			
			// try to insert a new row
			$stmt = $mysql->stmt_init();
			if($stmt->prepare('insert into participation (person_id, element_id, duration) values (?, ?, ?)')) {
				$userid = $user->getId();
				$elemid = $this->id;
				$stmt->bind_param('iii', $userid, $elemid, $duration);
				$stmt->execute();
				if($mysql->affected_rows == 1) {
					echo 'OK';
					$this->view_duration = $this->view_duration + $duration;
					return;
				} else {
					$error = $mysql->error;
				}
			}

			// something went wrong
			echo 'KO [' . $error . ']';
		}
	}
?>
