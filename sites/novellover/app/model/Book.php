<?php
class Book extends DB\SQL\Mapper
{
	function __construct($db)
	{
		return parent::__construct($db,'books');
	}
}
?>
