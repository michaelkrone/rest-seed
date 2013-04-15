<?php

/**
 * Example:
 * <taskdef name="rename" classname="phing.tasks.ext.RenameTask" />
 * <mkdir dir="test1" />
 * <mkdir dir="test2" />
 * <rename src="test1" dest="test2" replace="true" />
 */
include_once 'phing/Task.php';

class RenameTask extends Task {
	
	protected $src;
	protected $dest;
	protected $replace = false;
	protected $fail = false;

	public function setSrc($val) {
		$this->src = $val;
	}

	public function setDest($val) {
		$this->dest = $val;
	}

	public function setReplace($val) {
		$this->replace = $val;
	}

	public function main() {
		// Removing target-dir if replace = true
		if ((is_dir($this->dest) || is_file($this->dest)) && $this->replace == true) { 
			if (is_dir($this->dest)) {
				@rmdir($this->dest);
			} else {
				@unlink($this->dest);
			}
		}

		if (is_dir($this->src) && !is_dir($this->dest) && !is_file($this->dest)) { 
			$this->log("Renaming " . $this->src . " to " . $this->dest);

			// Rename the file
			if (!rename($this->src, $this->dest)) {
				throw new BuildException("Can't move.", $this->location);
			}
		} else {
			if ($this->fail) {
				throw new BuildException("There is a directory with the same name.", $this->location); 
			} else {
				$this->log("No action perfomed - there is a directory with the same name: " . $this->src . " equals " . $this->dest);	
			}
		}
	}
}
