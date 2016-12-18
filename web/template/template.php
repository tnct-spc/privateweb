<?php
class Template
{
	private $tmpl_file_path = '';
	public function __construct($tmpl_file_path)
	{
		$this->tmpl_file_path = $tmpl_file_path;
	}
    public function show()
    {
        extract((array)$this);
        include($this->tmpl_file_path.'.php');
    }
}
