<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name'			=> 'In Array',
	'pi_version'		=> '1.5',
	'pi_author'			=> 'Simon Andersohn',
	'pi_author_url'		=> '',
	'pi_description'	=> 'Searches for a value within given pipe or comma separated values',
	'pi_usage'			=> In_array::usage()
);


class In_array {

    var $return_data;    
    
	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
    public function __construct()
    {
        $this->EE = get_instance();
		
		$this->return_data = $this->compare_array();
	}
	
	public function pair()
	{
		
		$return = $this->compare_array();
		
		$this->_prep_no_results();
		
		if ($return === TRUE)
		{
			return $this->EE->TMPL->tagdata;
		}
		else
		{
			return $this->EE->TMPL->no_results();
		}
	}
	
	
	private function compare_array()
	{
	
		$array = $this->EE->TMPL->fetch_param('array');
		$value = $this->EE->TMPL->fetch_param('value');
		$not = $this->EE->TMPL->fetch_param('not');
		$delimiter = $this->EE->TMPL->fetch_param('delimiter', '|');
		$case_insensitive = $this->EE->TMPL->fetch_param('case_insensitive');

		$array = explode($delimiter, trim($array, $delimiter));

		if (!empty($array))
		{
		
			if ($this->check_bool($case_insensitive) === TRUE)
			{
				$array = array_map('strtolower', $array);
				$value = strtolower($value);
				$not = strtolower($not);
			}		

			if (!empty($value))
			{
				$values = explode($delimiter, trim( $value, $delimiter));
				
				foreach ($values as $val)
				{
					if (in_array($val, $array))
					{
						return TRUE;
					}
				}
			}
			elseif (!empty($not))
			{
				$values = explode(",", trim( str_replace($delimiter, ",", $not),",") );
				$found = FALSE;
				foreach ($values as $val)
				{
					if (in_array($val, $array))
					{
						$found = TRUE;
					}
				}
				return !$found;
			}
		}
		
		return FALSE;
		
	}
	
	private function check_bool($var) {
		if (!is_string($var)) return (bool) $var;
			switch (strtolower($var)) {
			case '1':
			case 'true':
			case 'on':
			case 'yes':
			case 'y':
				return true;
			default:
				return false;
		}
	}
	
	private function _prep_no_results()
	{
		// Shortcut to tagdata
		$open = strtolower(get_class($this)).':no_results';
		$td    = ee()->TMPL->tagdata;
		$open  = 'if '. $open;
		$close = '/if';
		// Check if there is a custom no_results conditional

		if (strpos($td, $open) !== FALSE
			&& preg_match('#' .LD .$open .RD .'(.*?)' .LD .$close .RD .'#s', $td, $match)
		){
			ee()->TMPL->log_item("Prepping {$open} conditional");
			// Check if there are conditionals inside of that
			if (stristr($match[1], LD.'if'))
			{
				$match[0] = ee()->functions->full_tag($match[0], $td, LD.'if', LD.'\/if'.RD);
			}
			// Set template's no_results data to found chunk
			ee()->TMPL->no_results = substr($match[0], strlen(LD.$open.RD), -strlen(LD.$close.RD));
			// Remove no_results conditional from tagdata
			$td = str_replace($match[0], '', $td);
		}
	}


	// --------------------------------------------------------------------
	
	/**
	 * Usage
	 *
	 * Plugin Usage
	 *
	 * @access	public
	 * @return	string
	 */
	
	public static function usage()
	{
		$output = '';
		$file = __DIR__ .'/README.md';
		if (file_exists($file))
		{
			$output = file_get_contents( __DIR__ .'/README.md');
		}
		return $output;
	}

	// --------------------------------------------------------------------

}

// END CLASS

/* End of file pi.in_array.php */
/* Location: ./system/expressionengine/third_party/in_array/pi.in_array.php */