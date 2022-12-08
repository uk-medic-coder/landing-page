<?php

$CRLF = chr(13).chr(10);


// ================================
// ================================
// Theme hack funcs
// ================================
// ================================

function CheckThrivePage()
{
	$GLOBALS['ANCTHRIVEDIT'] = false;
	$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($url, "tve=true")>0)
	{	$GLOBALS['ANCTHRIVEDIT'] = true;	}
	if (strpos($url, "?customize")>0)
	{	$GLOBALS['ANCTHRIVEDIT'] = true;	}	
}

// ===============================

function OutputAntiCJ()
{
?>
	<!--Anti CJ-->
	<style id="antiCJ">body{display:none !important;}</style>
	<script type="text/javascript">
	   if (self === top) {
		   var anticj = document.getElementById("antiCJ");
		   anticj.parentNode.removeChild(anticj);
	   } else {
		   top.location = self.location;
	   }
	</script>
<?php
}

// ================================

function OutputGA()
{
?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ancana');
</script>
<?php
}

// ================================

function OutputFBPixel($FBpixel)
{
?>
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=<?php echo $FBpixel; ?>&ev=PageView&noscript=1"
/></noscript>
<?php	
}

// ================================

function DoThriveWPheadStuff($dogdpr, $GA_UA, $thissiteurl, $trcm, $FBpixel, $privacyloc)
{
	// All header stuff for Thrive Themes
	
	
	remove_action('wp_head', 'wp_resource_hints', 2);
	
	// disable XMLPRC
	add_filter('xmlrpc_enabled', '__return_false');
	remove_action ('wp_head', 'rsd_link');
	remove_action( 'wp_head', 'wlwmanifest_link');

    // Remove the REST API lines from the HTML Header
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

    // Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );

    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover', '__return_false' );

    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );

   // Remove all embeds rewrite rules.
   add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );


	// REMOVE WP EMOJI
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');

	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// Remove the WordPress Generator Meta Tag
	function remove_generator_filter() { return ''; }

	if (function_exists('add_filter')) {
	 $types = array('html', 'xhtml', 'atom', 'rss2', /*'rdf',*/ 'comment', 'export');

	 foreach ($types as $type)
	 add_filter('get_the_generator_'.$type, 'remove_generator_filter');
	}

	// Others
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
	remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version	

	// Remove Jquery

	wp_deregister_script( 'jquery' );

	echo('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" ></script><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.4.1/jquery-migrate.min.js" ></script>');
	//echo('<script>jQuery.noConflict();</script>');	---- was an extra line I found in thrive Jquery
	

	// Fall back to a local copy of jQuery if the CDN fails
	?>
	<script>
	window.jQuery || document.write('<script src="<?php echo get_stylesheet_directory_uri()."/js/jquery.min.js"; ?>"><\/script>');
	window.jQuery || document.write('<script src="<?php echo get_stylesheet_directory_uri()."/js/jquery-migrate.min.js"; ?>"><\/script>');
	</script>

	<?php

	OutputAntiCJ();

		echo('<script>var privacyloc="'.$privacyloc.'";var FBpixel="'.$FBpixel.'";var GAUAvar="'.$GA_UA.'";var dogdpr="'.$dogdpr.'";var thissiteurl="'.$thissiteurl.'";var trackcampn="'.$trcm.'";var NGXurl="'.$thissiteurl.'/"</script>');

		// Make sure Jquery called before this

	OutputGA();
}

// ==========================================
// JS Minifier
// ==========================================

function OutputJSFile($jstype, $jsfilesarray, $CRLF, $SiteFURL, $SiteVpath, $jsname)
{

// 1 = reconstruct each time and inline
// 2 = echo individual preloads 

	// Not done in composer
	require __DIR__ . '/UglifyJSPHP/parse-js.php';
	require __DIR__ . '/UglifyJSPHP/process.php';

	if ($jstype==1)	
	{
        $buffer = '';
    
        foreach ($jsfilesarray as $a)
        {
            if($a != "")
            {
                $ast = $parse(file_get_contents($a)); // parse code and get the initial AST
                $ast = $ast_mangle($ast); // get a new AST with mangled names
                $ast = $ast_squeeze($ast); // get an AST with compression optimizations
                $nm = $strip_lines($gen_code($ast)); // compressed code here				
                $buffer .= '<script>'.$nm.'</script>';
            }
        }	  

        $ret = '<!-- js type1-->'.$CRLF.$buffer;	
    }
    else
    {
        // Type 2
        
        $aa = '';           
	
        foreach ($jsfilesarray as $a)
        {
            if($a != "")
            {
                $aa .= file_get_contents($a);
            }
        }
        $ast = $parse($aa); // parse code and get the initial AST
        $ast = $ast_mangle($ast); // get a new AST with mangled names
        $ast = $ast_squeeze($ast); // get an AST with compression optimizations
        $nm = $strip_lines($gen_code($ast)); // compressed code here				
    
        // 1st store the newly created JS minified file.
        
        $tmpaa = $SiteVpath.'/inc';
        
        if ( !is_dir($tmpaa)) {
            mkdir($tmpaa);       
        }        
        
        $zz = file_put_contents($tmpaa.'/'.$jsname, $nm);
        
        // now reference it in the code
        
		$ret = $CRLF.'<!-- js type2-->'.$CRLF.'<script src="'.$SiteFURL.'/inc/'.$jsname.'" type="text/javascript"></script>'.$CRLF;
	}

	return $ret;
}

// ==========================================
// CSS Minifier
// ==========================================

function OutputCSSFile($csstype, $cssfilesarray, $CRLF, $SiteFURL, $SiteVpath)
{
    // 1 = reconstruct each time and inline
    // 2 = echo individual preloads 
	
    $buffer = '';

    foreach ($cssfilesarray as $a) {
        if($a != "")
        {
            $buffer .= file_get_contents($a);
        }
    }	

    $cc = [];               // need blank array for this to work
    $buffer = Minify_CSS_Compressor::process($buffer, $cc);    
    
    $ret = '<!-- css type1-->'.$CRLF.'<style>'.$buffer.$CRLF.'</style>';
	

	if ($csstype==2)
	{
        // 1st store the newly created CSS minified file.
        
        $tmpaa = $SiteVpath.'/inc';
        
        if ( !is_dir($tmpaa)) {
            mkdir($tmpaa);       
        }      	
	
        $zz = file_put_contents($tmpaa.'/mincss.css', $buffer);
 
 
         // now reference it in the code

         $ret = $CRLF.'<!-- css type2-->'.$CRLF.'<link rel="stylesheet" type="text/css" href="'.$SiteFURL.'/inc/mincss.css" />'.$CRLF;        

    }	
	
	return $ret;
}


// ================================

// From https://github.com/mrclay/minify
  
class Minify_CSS_Compressor
{

    public static function process($css, $options = array())
    {
        $obj = new Minify_CSS_Compressor($options);

        return $obj->_process($css);
    }

    /**
     * @var array
     */
    protected $_options = null;

    /**
     * Are we "in" a hack? I.e. are some browsers targetted until the next comment?
     *
     * @var bool
     */
    protected $_inHack = false;

    /**
     * Constructor
     *
     * @param array $options (currently ignored)
     */
    private function __construct($options)
    {
        $this->_options = $options;
    }

    /**
     * Minify a CSS string
     *
     * @param string $css
     *
     * @return string
     */
    protected function _process($css)
    {
        $css = str_replace("\r\n", "\n", $css);

        // preserve empty comment after '>'
        // http://www.webdevout.net/css-hacks#in_css-selectors
        $css = preg_replace('@>/\\*\\s*\\*/@', '>/*keep*/', $css);

        // preserve empty comment between property and value
        // http://css-discuss.incutio.com/?page=BoxModelHack
        $css = preg_replace('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $css);
        $css = preg_replace('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $css);

        // apply callback to all valid comments (and strip out surrounding ws
        $pattern = '@\\s*/\\*([\\s\\S]*?)\\*/\\s*@';
        $css = preg_replace_callback($pattern, array($this, '_commentCB'), $css);

        // remove ws around { } and last semicolon in declaration block
        $css = preg_replace('/\\s*{\\s*/', '{', $css);
        $css = preg_replace('/;?\\s*}\\s*/', '}', $css);

        // remove ws surrounding semicolons
        $css = preg_replace('/\\s*;\\s*/', ';', $css);

        // remove ws around urls
        $pattern = '/
                url\\(      # url(
                \\s*
                ([^\\)]+?)  # 1 = the URL (really just a bunch of non right parenthesis)
                \\s*
                \\)         # )
            /x';
        $css = preg_replace($pattern, 'url($1)', $css);

        // remove ws between rules and colons
        $pattern = '/
                \\s*
                ([{;])              # 1 = beginning of block or rule separator
                \\s*
                ([\\*_]?[\\w\\-]+)  # 2 = property (and maybe IE filter)
                \\s*
                :
                \\s*
                (\\b|[#\'"-])        # 3 = first character of a value
            /x';
        $css = preg_replace($pattern, '$1$2:$3', $css);

        // remove ws in selectors
        $pattern = '/
                (?:              # non-capture
                    \\s*
                    [^~>+,\\s]+  # selector part
                    \\s*
                    [,>+~]       # combinators
                )+
                \\s*
                [^~>+,\\s]+      # selector part
                {                # open declaration block
            /x';
        $css = preg_replace_callback($pattern, array($this, '_selectorsCB'), $css);

        // minimize hex colors
        $pattern = '/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i';
        $css = preg_replace($pattern, '$1#$2$3$4$5', $css);

        // remove spaces between font families
        $pattern = '/font-family:([^;}]+)([;}])/';
        $css = preg_replace_callback($pattern, array($this, '_fontFamilyCB'), $css);

        $css = preg_replace('/@import\\s+url/', '@import url', $css);

        // replace any ws involving newlines with a single newline
        $css = preg_replace('/[ \\t]*\\n+\\s*/', "\n", $css);

        // separate common descendent selectors w/ newlines (to limit line lengths)
        $pattern = '/([\\w#\\.\\*]+)\\s+([\\w#\\.\\*]+){/';
        $css = preg_replace($pattern, "$1\n$2{", $css);

        // Use newline after 1st numeric value (to limit line lengths).
        $pattern = '/
            ((?:padding|margin|border|outline):\\d+(?:px|em)?) # 1 = prop : 1st numeric value
            \\s+
            /x';
        $css = preg_replace($pattern, "$1\n", $css);

        // prevent triggering IE6 bug: http://www.crankygeek.com/ie6pebug/
        $css = preg_replace('/:first-l(etter|ine)\\{/', ':first-l$1 {', $css);

        return trim($css);
    }

    /**
     * Replace what looks like a set of selectors
     *
     * @param array $m regex matches
     *
     * @return string
     */
    protected function _selectorsCB($m)
    {
        // remove ws around the combinators
        return preg_replace('/\\s*([,>+~])\\s*/', '$1', $m[0]);
    }

    /**
     * Process a comment and return a replacement
     *
     * @param array $m regex matches
     *
     * @return string
     */
    protected function _commentCB($m)
    {
        $hasSurroundingWs = (trim($m[0]) !== $m[1]);
        $m = $m[1];
        // $m is the comment content w/o the surrounding tokens,
        // but the return value will replace the entire comment.
        if ($m === 'keep') {
            return '/**/';
        }

        if ($m === '" "') {
            // component of http://tantek.com/CSS/Examples/midpass.html
            return '/*" "*/';
        }

        if (preg_match('@";\\}\\s*\\}/\\*\\s+@', $m)) {
            // component of http://tantek.com/CSS/Examples/midpass.html
            return '/*";}}/* */';
        }

        if ($this->_inHack) {
            // inversion: feeding only to one browser
            $pattern = '@
                    ^/               # comment started like /*/
                    \\s*
                    (\\S[\\s\\S]+?)  # has at least some non-ws content
                    \\s*
                    /\\*             # ends like /*/ or /**/
                @x';
            if (preg_match($pattern, $m, $n)) {
                // end hack mode after this comment, but preserve the hack and comment content
                $this->_inHack = false;

                return "/*/{$n[1]}/**/";
            }
        }

        if (substr($m, -1) === '\\') { // comment ends like \*/
            // begin hack mode and preserve hack
            $this->_inHack = true;

            return '/*\\*/';
        }

        if ($m !== '' && $m[0] === '/') { // comment looks like /*/ foo */
            // begin hack mode and preserve hack
            $this->_inHack = true;

            return '/*/*/';
        }

        if ($this->_inHack) {
            // a regular comment ends hack mode but should be preserved
            $this->_inHack = false;

            return '/**/';
        }

        // Issue 107: if there's any surrounding whitespace, it may be important, so
        // replace the comment with a single space
        return $hasSurroundingWs ? ' ' : ''; // remove all other comments
    }

    /**
     * Process a font-family listing and return a replacement
     *
     * @param array $m regex matches
     *
     * @return string
     */
    protected function _fontFamilyCB($m)
    {
        // Issue 210: must not eliminate WS between words in unquoted families
        $flags = PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY;
        $pieces = preg_split('/(\'[^\']+\'|"[^"]+")/', $m[1], -111, $flags);
        $out = 'font-family:';

        while (null !== ($piece = array_shift($pieces))) {
            if ($piece[0] !== '"' && $piece[0] !== "'") {
                $piece = preg_replace('/\\s+/', ' ', $piece);
                $piece = preg_replace('/\\s?,\\s?/', ',', $piece);
            }
            $out .= $piece;
        }

        return $out . $m[2];
    }
}

?>


