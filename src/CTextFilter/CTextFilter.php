<?php 
/** 
 * Class to format text 
 * 
 */ 
class CTextFilter { 
     
	/** 
	* constructor 
	*/ 
    public function __construct() { 
         
    } 
     
	/** 
	* Helper, BBCode formatting converting to HTML 
	* 
	* @param string text The text to be converted 
	* @return string the formatted text 
	*/ 
    function bbcode2html($text) { 
        $search = array( 
            '/\[b\](.*?)\[\/b\]/is', 
            '/\[i\](.*?)\[\/i\]/is', 
                '/\[u\](.*?)\[\/u]/ix', 
            '/\[img\](https?.*?)\[\/img\]/is', 
            '/\[url\](https?.*?)\[\/url\]/is',  
        '/\[url=(https?.*?)\](.*?)\[\/url\]/is'  
        ); 
       $replace = array( 
            '<strong>$1</strong>',  
        '<em>$1</em>',  
        '<u>$1</u>',  
        '<img src="$1" />',  
        '<a href="$1">$1</a>',  
        '<a href="$1">$2</a>'  
            ); 
        return preg_replace($search, $replace, $text); 
    } 
     
     
	/** 
	* Make clickable links from URLs in text 
	*  
	* @param string $text the text that should be formatted. 
	* @return string with formatted anchors 
	*/ 
    function make_clickable($text) { 
        return preg_replace_callback( 
    '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', 
        create_function( 
    '$matches', 
    'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'), 
        $text 
        ); 
    } 
     
     
	//use \Michelf\MarkdownExtra; 
	/** 
	* Format text according to Markdown syntax. 
	*  
	* @link http://dbwebb.se/coachen/skriv-for-webben-med-markdown-och-formattera-till-html-med-php 
	* @param string $text the text that should be formatted. 
	* @return string as the formatted html-text. 
	*/ 
	function markdown($text) { 
		require_once(__DIR__ . '/php-markdown/Michelf/Markdown.inc.php'); 
		require_once(__DIR__ . '/php-markdown/Michelf/MarkdownExtra.inc.php'); 
     
		return \Michelf\MarkdownExtra::defaultTransform($text); 
	} 


	/** 
	* Make \n to <br> 
	*  
	* @param string $text to be formatted. 
	* @return string as the formatted html-text. 
	*/ 
    public function nl2br($text) { 
        $text = str_replace('\n', "\n", $text); 
        return nl2br($text); 
    } 
     
     
	/** 
	* Call each filter. 
	*  
	* @param string $text the text to filter 
	* @param string $filters as comma separated list of filter. 
	* @return string the fomatted text. 
	*/ 
    public function doFilter($text, $filters) { 
        // Define all valid filters with their callback function. 
        $valid = array( 
            'bbcode' => 'bbcode2html', 
            'link' => 'make_clickable', 
            'markdown' => 'markdown', 
            'nl2br' => 'nl2br', 
        ); 
         
        // Make an array of the comma separated string $filters 
        $filter = preg_replace('/\s/', '', explode(',', $filters)); 
         
        // For each filter, call its function with the $text as parameter. 
        foreach($filter as $func ) { 
            if(isset($valid[$func])) { 
                $text = self::$valid[$func]($text); 
            } 
            else { 
                throw new Exception("The filter '$filters' is not a valid filter string"); 
            } 
        } 
        return $text; 
    } 
     
} 
