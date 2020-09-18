<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Xls_writer {

	private $xls;
	private $header_style;
	private $body_style;
	private $worksheet;
	private $data			= array();
	private $fields			= array();
	private $headers		= array();
	private $config			= array();

	public function add_sheet($sheet)
	{
		chdir(APPPATH.'libraries/phpxls');
		require_once 'Writer.php';

		$this->xls = new Spreadsheet_Excel_Writer;
		$this->xls->setVersion(8);
		$this->worksheet =& $this->xls->addWorksheet($sheet);
	}

	public function store_data($data)
	{
		$this->data = $data;
	}

	public function set_string($string)
	{
		foreach ($this->data as $k => $v)
		{
			for ($x = 0; $x < count($string); $x++)
			{
				if (isset($v[$string[$x]])) $v[$string[$x]] = $v[$string[$x]]."\x00";
			}

			$this->data[$k] = $v;
		}
	}

	public function save($filename)
	{
		$this->create_grid();
		$this->xls->send($filename);
		$this->xls->close();
	}

	public function config($config)
	{
		$arr_config		= $config;
		$this->config	= (array) json_decode($arr_config['cols']);
		$this->fields	= $this->config['ColsIdx'];
		$this->headers	= $this->config['ColsName'];

		if (isset($arr_config['filter'])) $this->config['filter'] = $arr_config['filter'];
	}

	public function get_grid()
	{
		return $this->config;
	}

	private function set_header_style()
	{
		$this->header_style =& $this->xls->addFormat();
		$this->header_style->setBold();
		$this->header_style->setFontFamily('Arial');
		$this->header_style->setSize(10);
		$this->header_style->setAlign('center');
		$this->header_style->setVAlign('vcenter');
		$this->header_style->setBorder(1);
		$this->worksheet->setRow(0,20);
	}

	private function set_body_style($bg_color)
	{
		$this->body_style =& $this->xls->addFormat();
		$this->body_style->setColor('black');
		$this->body_style->setFontFamily('Arial');
		$this->body_style->setSize(10);
		$this->body_style->setFgColor($bg_color);
		$this->body_style->setBorder(1);
		$this->body_style->setTextWrap();
		$this->body_style->setVAlign('top');
	}

	private function create_grid()
	{
		$this->set_header_style();

		for ($x = 0; $x < count($this->headers); $x++)
		{
			$this->worksheet->write(0, $x, $this->headers[$x], $this->header_style);
		}

		$row	= 1;
		$final_col_width = array();

		foreach ($this->data as $k => $v)
		{
			$this->set_body_style('white');

			for ($x = 0; $x < count($this->fields); $x++)
			{
				if (empty($v[$this->fields[$x]])) $output = '';
				else $output = $v[$this->fields[$x]];

				$col_width = strlen($output) > strlen($this->headers[$x]) ? (strlen($output)*1.4) : (strlen($this->headers[$x])*1.2);

				if ( ! isset($final_col_width[$x]) OR $col_width > $final_col_width[$x]) $final_col_width[$x] = $col_width;
				if (count($this->data) == $row) $this->worksheet->setColumn($x,$x,$final_col_width[$x]);

				if (is_string($output)) $this->worksheet->writeString($row, $x, $output, $this->body_style);
				else $this->worksheet->write($row, $x, $output, $this->body_style);
			}
			$row++;
		}
	}
}