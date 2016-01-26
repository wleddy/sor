# Bill's Simple Web Framework

### Overview 

The idea for this framework is to be extremely simple. At it's base, each page
of the site is in a separate directory so the URLs look something like 
www.example.com/about/. The "about" directory contains an index.php file and 
at least one other file called "content.xxx" where .xxx is .php, .html or .md 
(markdown). The markdown file is converted to html for display. Markdown is used
to make it easier for average users to maintain the site pages.

The role of the index file is to call `/templates/base.php` which will construct 
the page and provide the html scaffold. 

The body section of the html scaffold contains 4 divs with ids: "head", "nav", 
"content" and "foot". base.php attempts to fill each div using the file contents
of 4 files with names that match the div ids. The process for each div is as 
follows:

1.	Look in the directory of the calling `index.php` file for a file named
	`head.php`. If the file is found, the contents are inserted into the
	html scaffold and base moves on to the next div id (`nav` in this case)

2.	If `head.php` is not found base looks for `head.html`, then `head.md` 
	in the local `index` directory. Once a file is found, base moves on the
	the next div id.
	
3.	If no `head.xxx` file is found, `base.php` repeats the search in the
	`/templates/` directory. This allows you to place site wide files for
	the header and footer in the `/templates/` directory so they are used
	on all pages. If no file is found for a particular id, the div is left
	empty.
	
### Adding Extra Header Elements

You can add extra header elements such as css and javascript by storing the
html for the elements in the `$extraCSS`, `$extraJS` & `$extraHeaders` variables
in the `index.php` file of a page directory.

If you want to add a site wide header, just add it in `/templates/base.php`.