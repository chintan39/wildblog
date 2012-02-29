#!/usr/bin/python

"""
Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

Description:

Webdiffer is a simple utility, that stores content of web pages and then 
compares the content with the previous attempt. The difference is reported.

If some segments are expected to be different, then it should be wrapped by 
the following html comments:
<!-- webdiffer-no-log-begin -->
this part won't be considered if different
<!-- webdiffer-no-log-end -->

Configuration is stored in ~/.webdiffer.cfg and its format is a simple init 
file:

[Wildweblocal]
enabled=1
sitemapurl=http://wild-web.web/sitemap.xml

"""

import os, sys, urllib, ConfigParser, xml.dom.minidom, md5, commands, shutil, re

def print_welcome():
	print('Web differ  Copyright (C) 2012  Honza Horak')
	print('This program comes with ABSOLUTELY NO WARRANTY.')
	print('This is free software, and you are welcome to redistribute it')
	print('under certain conditions.')
	print

def print_help():
	print_welcome()
	print(sys.argv[0] + ' OPTIONS')
	print('  [ -c | --config-file configfile ]    sets configuration file')
	print('  [ -d | --cache_dir cachedirectory ]  sets cache directory')
	print('  [ -e | --enable website ]            force enable web')
	print('  [ -l | --list ]                      shows configuration')
	print('  [ -h | --help ]                      shows this help')
	
def remove_nologs(s):
	lparent = '<!-- webdiffer-no-log-begin -->'
	rparent = '<!-- webdiffer-no-log-end -->'
	begin = s.find(lparent)
	while begin != -1:
		end = s.find(rparent, begin+len(lparent))
		if end == -1:
			return s
		s = s[:begin] + s[end+len(rparent):]
		begin = s.find(lparent)
	return s

def pring_changed(changed_urls):
	print('===============================================================')
	print('Pages to check:')
	for page in changed_urls:
		print(page)
		
def check_config(config_file):
	if not os.path.isfile(config_file) or not os.access(config_file, os.R_OK):
		print('Error while reading config file: {0}'.format(config_file))
		sys.exit(1)
		
def check_cache_dir(cache_dir):
	if not os.path.isdir(cache_dir) or not os.access(cache_dir, os.W_OK):
		print('{0} does not directory or is not a writable directory'.format(cache_dir))
		sys.exit(1)
		
def parse_config_file(config_file):
	config = ConfigParser.ConfigParser()
	try:
		print('Parsing config file: {0}'.format(config_file))
		config.read(config_file)
	except IOError:
		print('Error while reading config file: {0}'.format(config_file))
		sys.exit(1)
	return config

def check_web(web, config, cache_dir, debug_limit, enabled):
	print("Section {0}".format(web));
	if not enabled:
		print('Skipping: dissabled.')
		return
	sitemapurl = config.get(web, 'sitemapurl')
	try:
		sitemapxml = urllib.urlopen(sitemapurl).read()
	except IOError:
		print('Error while reading url "{0}"'.format(sitemapurl))
		return

	try:
		sitemap = xml.dom.minidom.parseString(sitemapxml)
	except (TypeError, AttributeError, xml.parsers.expat.ExpatError):
		print('Error while parsing site map "{0}"'.format(sitemapurl))
		return
			
	i = 0
	changed_urls = []
	for url in sitemap.getElementsByTagName("url"):
		for location in url.getElementsByTagName("loc"):
			page = location.firstChild.nodeValue
			m = md5.new()	
			m.update(page)
			pagefile = m.hexdigest()
			print("Getting difference in {0}.{1}".format(web, page))
			page_old = os.path.join(cache_dir, web + '.' + pagefile + '.old')
			page_new = os.path.join(cache_dir, web + '.' + pagefile + '.new')
			page_diff = os.path.join(cache_dir, web + '.' + pagefile + '.diff')
			u = urllib.urlopen(page)
			file_new = open(page_new, 'w')
			content = "Page: {0}\n==============================================\n".format(page)
			content += u.read()
			content = remove_nologs(content)
			#content = re.sub(r'<!-- webdiffer-no-log-begin -->.*<!-- webdiffer-no-log-end -->', '', content)
			file_new.write(content)
			file_new.close()
			ret = commands.getstatusoutput('diff -up {0} {1} >{2}'.format(page_old, page_new, page_diff))
			if ret[0] != 0:
				changed_urls.append(page)
				print("Page {0} has changed. Diff ({1}) is stored in {2}".format(page, ret[0], page_diff))
			shutil.move(page_new, page_old)
		i += 1
		if debug_limit and i > debug_limit:
			break
			
	pring_changed(changed_urls)
	
def main():
	debug_limit = 0 # used if not all pages should be checked
	home = os.getenv('USERPROFILE') or os.getenv('HOME')
	config_file = os.path.join(home, '.webdiffer.cfg')
	cache_dir = os.path.join(home, '.webdiffer_cache')
	
	i = 0
	force_enabled = {}
	print_config = False
	indent = "    "
	
	while i < len(sys.argv):
		if i == 0:
			i+=1
			continue
		if (sys.argv[i] == '-c' or sys.argv[i] == '--config-file') and i+1<len(sys.argv):
			config_file = sys.argv[i+1]
			i+=1
		elif (sys.argv[i] == '-d' or sys.argv[i] == '--cache-dir') and i+1<len(sys.argv):
			cache_dir = sys.argv[i+1]
			i+=1
		elif (sys.argv[i] == '-e' or sys.argv[i] == '--enable') and i+1<len(sys.argv):
			force_enabled[sys.argv[i+1]] = 1
			i+=1
		elif (sys.argv[i] == '-l' or sys.argv[i] == '--list'):
			print_config=True
		else:
			print_help()
			exit(1)
		i+=1
	
	print_welcome()
	
	check_config(config_file)
	check_cache_dir(cache_dir)
	config = parse_config_file(config_file)
	
	for web in config.sections():
		enabled = str(config.get(web, 'enabled')) == '1' or force_enabled.has_key(web) or force_enabled.has_key('all')
		if print_config:
			print
			print("Website: {0}".format(web));
			print("{0}{1}".format(indent, 'Enabled' if enabled else 'Dissabled'))
			print("{0}Sitemap URL: {1}".format(indent, config.get(web, 'sitemapurl')))
		else:	
			check_web(web, config, cache_dir, debug_limit, enabled)

if __name__ == "__main__":
    main()
