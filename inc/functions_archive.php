<?php
/**
 * MyBB 1.2
 * Copyright � 2006 MyBB Group, All Rights Reserved
 *
 * Website: http://www.mybboard.com
 * License: http://www.mybboard.com/eula.html
 *
 * $Id$
 */

/**
* Output the archive page header.
*
* @param string The page title.
* @param string The full page title.
* @param string The full page URL.
*/
function archive_header($title="", $fulltitle="", $fullurl="")
{
	global $mybb, $lang, $db, $nav, $archiveurl;

	// Build the archive navigation.
	$nav = archive_navigation();

	// If there is a title, append it to the bbname.
	if(!$title)
	{
		$title = $mybb->settings['bbname'];
	}
	else
	{
		$title = $mybb->settings['bbname']." - ".$title;
	}

	// If the language doesn't have a charset, make it UTF-8.
	if($lang->settings['charset'])
	{
		$charset = $lang->settings['charset'];
	}
	else
	{
		$charset = "utf-8";
	}
	$GLOBALS['fulltitle'] = $fulltitle;
	$GLOBALS['fullurl'] = $fullurl;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $charset; ?>" />
<meta name="robots" content="index,follow" />
<link type="text/css" rel="stylesheet" rev="stylesheet" href="<?php echo $archiveurl; ?>/screen.css" media="screen" />
<link type="text/css" rel="stylesheet" rev="stylesheet" href="<?php echo $archiveurl; ?>/print.css" media="print" />
</head>
<body>
<div id="container">
<h1><a href="<?php echo $mybb->settings['bburl']; ?>/index.php"><?php echo $mybb->settings['bbname']; ?></a></h1>
<div class="navigation"><?php echo $nav; ?></div>
<div id="fullversion"><strong><?php echo $lang->archive_fullversion; ?></strong> <a href="<?php echo $fullurl; ?>"><?php echo $fulltitle; ?></a></div>
<div id="infobox"><?php echo sprintf($lang->archive_note, $fullurl); ?></div>
<div id="content">
<?php
}

/**
* Build the archive navigation.
*
*/
function archive_navigation($addlinks=1)
{
	global $navbits, $mybb, $lang;
	$navsep = " &gt; ";
	if(is_array($navbits))
	{
		reset($navbits);
		foreach($navbits as $key => $navbit)
		{
			if($navbits[$key+1])
			{
				if($navbits[$key+2]) { $sep = $navsep; } else { $sep = ""; }
				$nav .= "<a href=\"".$navbit['url']."\">".$navbit['name']."</a>$sep";
			}
		}
	}
	$navsize = count($navbits);
	$navbit = $navbits[$navsize-1];
	if($nav) {
		$activesep = $navsep;
	}
	$nav .= $activesep.$navbit['name'];
	return $nav;
}

/**
* Output multipage navigation.
*
* @param int The total number of items.
* @param int The items per page.
* @param int The current page.
* @param string The URL base.
*/
function archive_multipage($count, $perpage, $page, $url)
{
	global $lang;
	if($count > $perpage)
	{
		$pages = $count / $perpage;
		$pages = ceil($pages);

		for($i=1;$i<=$pages;$i++)
		{
			if($i == $page)
			{
				$mppage .= "<strong>$i</strong> ";
			}
			else
			{
				$mppage .= "<a href=\"$url-$i.html\">$i</a></strong> ";
			}
		}
		$multipage = "<div class=\"multipage\"><strong>".$lang->archive_pages."</strong> $mppage</div>";
		echo $multipage;
	}
}

/**
* Output the archive footer.
*
*/
function archive_footer()
{
	global $mybb, $lang, $db, $nav, $maintimer, $fulltitle, $fullurl, $mybboard, $sent_header;
	$totaltime = $maintimer->stop();
	if($mybb->settings['showvernum'] == "on")
	{
		$mybbversion = $mybboard['internalver'];
	}
	else
	{
		$mybbversion = "";
	}
	$sent_header = 1;
?>
</div>
<div class="navigation"><?php echo $nav; ?></div>
<div id="printinfo">
<strong><?php echo $lang->archive_reference_urls; ?></strong>
<ul>
<li><strong><?php echo $mybb->settings['bbname']; ?>:</strong> <?php echo $mybb->settings['bburl']."/index.php"; ?></li>
<?php if($fullurl != $mybb->settings['bburl']) { ?><li><strong><?php echo $fulltitle; ?>:</strong> <?php echo $fullurl; ?></li><?php } ?>
</ul>
</div>
</div>
<div id="footer">
<?php echo $lang->powered_by; ?> <a href="http://www.mybboard.com">MyBB</a> <?php echo $mybbversion; ?><br /><?php echo $lang->copyright; ?> &copy; 2002-<?php echo date("Y"); ?> <a href="http://www.mybboard.com">MyBB Group</a>
<!-- temporary code to be removed before release -->
<!-- <?php echo "<br />Page Loaded in $totaltime with $db->query_count queries."; ?> -->
<!-- end temporary code -->
</div>
</body>
</html>
<?php
}

/**
* Output an archive error.
*
* @param string The error language string identifier.
*/
function archive_error($error)
{
	global $lang, $mybb, $sent_header;
	if(!$sent_header)
	{
		archive_header("", $mybb->settings['bbname'], $mybb->settings['bburl']."/index.php");
	}
?>
<div class="error">
<div class="header"><?php echo $lang->error; ?></div>
<div class="message"><?php echo $error; ?></div>
</div>
<?php
	archive_footer();
	exit;
}

/**
* Ouput a "no permission"page.
*/
function archive_nopermission()
{
	global $lang;
	archive_error($lang->archive_nopermission);
}
?>