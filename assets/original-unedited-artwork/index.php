<?php
	$time = microtime(true); // Gets microseconds
	
	DEFINE("CLASSNAME", "Unedited Artwork", 1); //Put your class - course number - section
	DEFINE("STUDENT", "Rick Anderson", 1); //Just change "Student Name" to your name
	
	//You may edit these if you wish
	//Please keep in mind that if there is anything in either of these it will not show it on the output
	DEFINE("illegalfiles", "® . .. favicon.ico index.html index.php default.html default.php .htaccess .htpasswd .bash_history .mysql_history", 1);
	DEFINE("illegaldirs", " . .. .cache .ssh _Movies indexer_files", 1);
	DEFINE("FOOTER", "<span>" . STUDENT . "</span><span>" . CLASSNAME . "</span>", 1);

	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	/*
	File: index.php
	Version:	1.4
	Date: May 8, 2014
	Author: Charlie Wallin
	
	Revision 1.1: Chris Craven
		shows date/time for the files located in the directory
	
	Revision 1.2: Rick Anderson
		created css to give the eye something to look at
	
	Revision 1.3: Rick Anderson
		Added sorting to the files to resemble how directory
		listing should be.

		Updated core code to browse entire site from the root
		directory.
	
	Revision 1.4: Rick Anderson
		Updated the entire code to be object orrientated. Also 
		made some changes to the CSS and sprite.png. Now accepts
		more filetypes than ever.
	
	Description: Creates an index.php file listing all of the files
		and directory names.
	*/
	
	if (empty($_GET['directories'])) {
		$prevDirectory = "";
		$dir = '.';
	}
	else {
		$dir = $_GET['directories'];
		$str = substr(strrchr($dir, '/'), 1);
		$upDir = substr($dir, 0, strrpos($dir, '/', -1));
		if (strlen($str) <= 1) { $upDir = ""; }
		$prevDirectory = "<li id=\"prevDir\"><a href=\"?directories=$upDir\" class=\"upDir\">Up Directory</a></li>";
	}
	
	class MyStuff {
		public $directory_list, $file_list;
		
		public function read_Dir($value) {
			global $dir;
			$var = scandir($value);

			foreach ($var as $x) {
					if (is_dir($dir."/".$x) && !is_file($dir."/".$x))
						$arr['directories'][] = $x;
					else {
						if (empty($_GET['directories'])) { $nvalue = $x; }
						else { $nvalue = $_GET['directories']."/".$x; }
						
						$arr['files'][] = 	array("filename" 	=> $x,
											"date"		=> $this->get_last_modified("date", $nvalue),
											"time"		=> $this->get_last_modified("time", $nvalue)
										);
					}
			}
			
			return $arr;
		}
		
		public function print_links($value) {
			global $dir, $prevDirectory;
			
			$a = "<ul id=\"dirLinks\">";
			$b = "<table id=\"fileLinks\">__headerRow__";
			if (!empty($prevDirectory))
				$a .= $prevDirectory;
			foreach ($value as $key => $val) {
				@natsort($val);
				asort($val);
				
				foreach ($val as $tmp) {
					if ($key == 'directories') {
						if (empty($_GET['directories'])) { $nvalue = $tmp; }
						else { $nvalue = $_GET['directories']."/".$tmp; }
						
						
						if (!strpos(illegaldirs, $tmp))
							$a .= "<li><a href=\"?directories=$nvalue\">$tmp</a></li>";
					
						
					}
					else {
						if (empty($_GET['directories'])) { $nvalue = $tmp['filename']; }
						else { $nvalue = $_GET['directories']."/".$tmp['filename']; }
						
						if (empty($_GET['directories'])) {	
							if (!strpos(illegalfiles, $tmp['filename'])) {
								$b .= "<tr><td><a href=\"$nvalue\">".$tmp['filename']."</a></td>
									<td class=\"date\">".$tmp['date']."</td>
									<td class=\"time\">".$tmp['time']."</td></tr>";
							}
						}
						else {
						$b .= "<tr><td><a href=\"$nvalue\">".$tmp['filename']."</a></td>
							<td class=\"date\">".$tmp['date']."</td>
							<td class=\"time\">".$tmp['time']."</td></tr>";
						}
					}	
				}
			}
			$a .= "</ul>";
			$b .= "</table>";
			
			$results = $a . $b;
			if (empty($results))
				$results = "<p class=\"error\">Directory Empty</p>";
			
			return $results;
		}
		
		private function get_last_modified($type,$file) {
			global $dir;
			if ($type == "date") 
				$result = gmdate("m/d/Y",filemtime($file)+3600*(date("I")-5));
			elseif ($type == "time")
				$result = gmdate("h:i A",filemtime($file)+3600*(date("I")-5));
			else 
				$result = "";
				
			return $result;
		}
	}
	
	class HTML {	
		function __construct() {
			global $dir, $prevDirectory, $upDir, $error, $time;
			
			$class = new MyStuff();
			
			$this->directory_list = $class->print_links($class->read_Dir($dir));
			$this->html = preg_replace('/__title__/', CLASSNAME .': '. STUDENT, $this->html);
			$this->html = preg_replace('/__content__/', $this->directory_list, $this->html);
			$this->html = preg_replace('/__class__/', CLASSNAME, $this->html);
			$this->html = preg_replace('/__footer__/', FOOTER, $this->html);
			$this->html = preg_replace('/__headerRow__/', $this->headerRow, $this->html);
			$this->html = preg_replace('/__loadtime__/', "Page generated in ".round((microtime(true) - $time),5)." seconds", $this->html);
			print($this->html);
		}
		
		private $html = "
			<!DOCTYPE html>
			<html lang=\"en-us\">
				<head>
					<title>__title__</title>
					<meta charset=\"utf-8\">
					<link href=\"indexer_files/main.css\" rel=\"stylesheet\" type=\"text/css\">
				</head>

				<body>
					<div id=\"wrapper\">
						<h1>__class__</h1>
						<div id=\"content\">
							__content__
						</div>
						<p id=\"loadtime\">__loadtime__</p>
						<p id=\"footer\">__footer__</p>
					</div>
				</body>
			</html>";
			
		private $headerRow = "<tr id=\"fileHeader\"><th>File Name</th><th>Last Modified Date</th><th>Last Modified Time</th></tr>";
	}
	
	new HTML();
	
?>