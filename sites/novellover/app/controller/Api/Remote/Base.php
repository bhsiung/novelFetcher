<?php
namespace Api\Remote;
class Base extends \Api
{
	protected $siteClass;
	protected function setSiteClassByName($siteName)
	{
		switch($siteName){
			case \Site\Kxwxw::NAME:
				$this->siteClass = '\Site\Kxwxw';
				break;
			default:
				throw new \Exception("$siteName is not avaiable");
				break;
		}
	}
}
