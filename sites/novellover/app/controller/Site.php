<?php
class Site
{
	static function bookIdFromUrl($url)
	{
		throw new \Exception('method is not available');
	}
	static function infoUrl($bookId)
	{
		throw new \Exception('method is not available');
	}
	static function chapterListUrl($bookId)
	{
		throw new \Exception('method is not available');
	}
	static function chapterUrl($bookId,$chapterId=null)
	{
		throw new \Exception('method is not available');
	}
	static function absoluteHref($currentUrl,$href)
	{
		$urlParts = parse_url($currentUrl);
		$urlPrefix = $urlParts['scheme'].'://'.$urlParts['host'];
		if(preg_match('/^http:/',$href)){
			$url = $href;
		}else{
			if(preg_match('/^\//',$href)){
				$url = $urlPrefix.$href;
			}else{
				$url = $urlPrefix.preg_replace('/[^\/]+$/',$href,$urlParts['path']);
			}
		}
		return $url;
	}
	static protected function filterArticle($title,&$text)
	{
		$title = preg_replace('/\(/','\(',$title);
		$title = preg_replace('/\)/','\)',$title);
		$text = preg_replace("/ */",'',$text);
		$text = @preg_replace("/^[a-zA-Z0-9]{0,5}$title/",'',$text);
		$text = preg_replace("/ *<(p|div)\/*> */i",'<br/>',$text);
		$text = preg_replace("/<\/(p|div)>/i",'<br/>',$text);
		$text = preg_replace("/<(p|div)[^>]*>/i",'',$text);
		$text = preg_replace("/^ *<br\/> */",'',$text);
	}
	static public function classNameByUrl($url)
	{
		$files = scandir(__dir__.'/Site');
		foreach($files as $file){
			if(preg_match('/\.php$/',$file)){
				$className = preg_replace('/\.php$/','',$file);
				$className = "\Site\\$className";
				if(call_user_func("$className::bookIdFromUrl",$url)){
					return $className;
				}
			}
		}
	}
	static function asyncCraw($siteName,$bookId)
	{
		$command = "php /var/www/sites/novellover/index.php \"/cli/CrawNewBook/$siteName/$bookId\" > /dev/null &";
		$response = exec($command);
	}
}
