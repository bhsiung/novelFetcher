<?php
class Author extends DB\SQL\Mapper
{
	function __construct($db)
	{
		return parent::__construct($db,'authors');
	}
}
?>
