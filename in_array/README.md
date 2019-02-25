In Array Usage
====================

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