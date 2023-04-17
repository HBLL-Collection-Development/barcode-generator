<?php
namespace Barcode;

/**
 * Template for displaying data
 *
 * @author  Jared Howland <barcode-generator@jaredhowland.com>
 * @version 2023-03-29
 * @since   2012-09-19
 */

class Template
{
    /**
     * Constructor. Sets up template system for displaying data.
     *
     * @access public
     *
     * @param string $template Valid values: 'header', 'footer', 'navigation'
     *
     * @return string
     */
    public function tm($template, $data = null)
    {
        switch ($template):
            // HTML
            case 'header':
                return $this->header($data);
            case 'footer':
                return $this->footer();
            default:
                $message = 'You may only pass the following $type to the template class template function: "header", "footer".';
                echo $message;
                error_log($message, 0);
                die();
        endswitch;
    }

    /**
     * Header information
     *
     * @access private
     *
     * @param string $title
     *
     * @return string Header data for HTML5 document
     */
    private function header($title)
    {
        echo <<<HTML
<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'>
	<meta http-equiv='X-UA-Compatible' content='IE=100; IE=9; IE=8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=yes' />

	<title>$title</title>

	<link rel='stylesheet' href='//media.lib.byu.edu/assets/css/loader/fonts-1.0,reset-1.0,grid-1.0,hbll-2.9,lists-1.2,forms-1.3,buttons-1.3' type='text/css' />

	<link rel='stylesheet' href='/web/css/style.css' type='text/css' />
	<!--[if lt IE 9]>
	<script src='//media.lib.byu.edu/assets/js/html5shiv/html5shiv-1.0.js'></script>
	<![endif]-->

	<script type='text/javascript' charset='utf-8'>document.domain = 'lib.byu.edu';</script>
	<script src='//media.lib.byu.edu/assets/js/jquery/jquery-1.7.1.js' type='text/javascript'></script>

	<script src='/web/js/highlight.js' type='text/javascript'></script>
	<style type="text/css">
        .page{
            font-family: helvetica, arial, sans-serif;
            font-size: 12pt;
        }
        input{
            display: block;
        }
        section.barcode {
            text-align: center;
            width: 450px;
            margin:0;
            padding:0;
        }
        section.barcode p{
            padding: 0;
            margin: 0;
        }
        section.barcode p.byu{
            margin-bottom: -2pt;
        }
        section.barcode p.human_barcode{
            margin-top: -6pt;
            font-size: 14pt;
        }
        img {
            border: none!important;
            box-shadow: none!important;
            -webkit-box-shadow: none!important;
            -moz-box-shadow: none!important;
        }
        .byu{
            font-size: 8pt;
        }
    </style>
    <style type="text/css" media="print">
        #site_footer, #subsite_header, .no_print{
            display: none;
        }
        section.barcode {
           float: left;
        }
    </style>
</head>
<body>

<header id='site_header' class='frame compact'>
	<div class='container'>
	    <h1><a id='site_header_link_byu' href='http://www.byu.edu'>BYU</a> <a id='site_header_link_hbll' href='http://lib.byu.edu'>Harold B. Lee Library</a></h1>
	</div>
</header>

<section class='main'>
	<header id='subsite_header'>
			<div class='container'><h1>$title</h1></div>
	</header>
	<div class='container'>
	<section class='page'>
HTML;
    }

    /**
     * Footer information
     *
     * @access private
     * @return string Footer data for HTML5 document
     */
    private function footer()
    {
        echo <<<HTML
    </section></div>
</section>
<footer id='site_footer' class='frame'>
	<div class='container'>
		<address class='right'>
			P.O. Box 26800, Provo, UT 84602-6800<br/>
			(801) 422-2927
		</address>
		<ul class='inline_list'>
			<li><a href='http://lib.byu.edu/'>Home</a></li>
			<li><a href='http://lib.byu.edu/contact/'>Contact Us</a></li>
			<li><a href='http://lib.byu.edu/site_index/'>Site Index</a></li>
			<li><a href='http://lib.byu.edu/m/'>Mobile Site</a></li>
		</ul>

		<p id='copyright'><a href='http://lib.byu.edu/services/copyright.php' title='Copyright and Use Information'>&copy; 2012 Brigham Young University</a></p>
	</div>
</footer>

</body>
</html>
HTML;
    }
}

?>
