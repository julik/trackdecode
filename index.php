<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>TrackDecode::trackback decoder for MT</title>
		<style type="text/css" media="screen">
body {
	background: white;
	padding: 2em 10em 5em 10em;
	color: #888;
	font-family: "Lucida Grande", "Lucida Sans Unicode", Verdana, sans-serif;
	font-size: x-small;
}

*[lang="en"] {
	color: black;
}

h1 span[lang="ru"],
h2 span[lang="ru"],
h3 span[lang="ru"],
h4 span[lang="ru"],
h5 span[lang="ru"],
h6 span[lang="ru"] {
	font-size: 80%;
}

a:link {
	color: #5c69bf;
}

a:hover {
	color: #110426;
}

#head {
	font-size: 120%;
}
		</style>
	</head>

<body>


<div id='head'>
<h1>TrackDecode</h1>
<p lang="en">Trackback decoder for Movable Type</p>
<p lang="ru">Декодер пингов для MT</p>

<p><a href='TrackDecode.pl.gz'>Download</a></p>
</div>

<% ob_start('markdown') %>
<% include("trackdecode_readme.markdown"); %>
<% ob_end_flush() %>

</body>
</html>