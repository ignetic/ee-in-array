<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name'			=> 'In Array',
	'pi_version'		=> '1.3.1',
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
        $this->EE =& get_instance();
		
		$this->return_data = $this->compare_array();
	}
	
	public function pair()
	{
		
		$return = $this->compare_array();
		
		if ($return === TRUE)
		{
			return $this->EE->TMPL->tagdata;
		}
		else
		{
			return $this->no_results();
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

	
	private function no_results()
	{
			
		$tagdata  = $this->EE->TMPL->tagdata;
		$tag_name = strtolower(get_class($this)).':no_results';
		
		$pattern = '#' .LD .'if ' .$tag_name .RD .'(.*?)' .LD .'/if' .RD .'#s';

		if (is_string($tagdata) && is_string($tag_name)
		  && preg_match($pattern, $tagdata, $matches)
		)
		{
			return $matches[1];
		}
		
		return $this->EE->TMPL->no_results();
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
		ob_start(); 
		?>
		
		Searches for a value within given pipe or comma separated values
		
		To use this plugin, enter the value to search for in the value parameter
		and pipe or comma delimited values in the array parameter:

		{exp:in_array value="3" array="2|4|5|20"}
		
		{exp:in_array value="Cow" array="The|Cow|Jumped|Over|The|Moon"}
		
		Typical use:
		
		{if '{exp:in_array value="2" array="1|2|3"}'}
			We found your value
		{/if}
		
		{if '{exp:in_array not="4|5" array="1|2|3"}'}
			We did not find your value
		{/if}
		
		Tag Pair:
		
		{exp:in_array:pair value="2" array="1|2|3"}
			{if no_results}Value not found{/if}
			We found your value
		{/exp:in_array:pair}
		
		Alternative no_results: Note that this only works with no "if" conditions inside it.
		
		{exp:in_array:pair value="2" array="1|2|3"}
			{if in_array:no_results}Value not found{/if}
			We found your value
		{/exp:in_array:pair}
		

		Available parameters:
		
		value="X" : The value to find in the array
		not="X" : The value not to find in the array
		array="1|2|3|4" : the values in the array to search
		delimiter="|" : change the default pipe delimiter
		case_insensitive="y" : make the search case insensitive

		
		<?php
		$buffer = ob_get_contents();
	
		ob_end_clean(); 

		return $buffer;
	}

	// --------------------------------------------------------------------

}

// END CLASS

/* End of file pi.in_array.php */
/* Location: ./system/expressionengine/third_party/in_array/pi.in_array.php */