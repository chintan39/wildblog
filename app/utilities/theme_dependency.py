"""
Short script is used to analyse the Theme templates and to compose 
templates dependecies automaticly, which is defined by Theme class.

Using: 
python theme_dependency [dir]

Example with forwarding output to the file 'list.php': 
python theme_dependency ../themes/Default/templates/ >list.php 

Required: Python 3.0+

"""

import os, sys, re

def analyseFile(filePath):
	"""
	Analyse the file.
	Reads all 'require' commands (functions) in Smarty syntax.
	Returns all depended files in the array.
	@param string filePath Name of the file to read.
	@return array Array of files depended.
	"""
	# read the content of the file
	try:
		f = open(filePath)
	except IOError:
		print('could not open: ' + filePath)
		return None
	content = f.read()
	f.close()
	# regular expressions
	reg_req = r'\{require(.*)\}'
	reg_file = r'file=[\']?([a-zA-Z0-9\.]+)[\']?'
	reg_pack = r'package=[\']?([a-zA-Z0-9]+)[\']?'
	reg_them = r'theme=[\']?([a-zA-Z0-9]+)[\']?'
	# find first 'require' function
	m = re.search(reg_req, content)
	result = []
	while (m):
		# find 'file' parameter
		m_file = re.search(reg_file, m.group(1))
		# find 'package' parameter
		m_pack = re.search(reg_pack, m.group(1))
		# find 'theme' parameter
		m_them = re.search(reg_them, m.group(1))
		# compose all file name
		f = ''
		if m_them:
			f += m_them.group(1) + '|'
		if m_pack:
			f += m_pack.group(1) + '.'
		else:
			f += 'Base.'
		if m_file:
			f += m_file.group(1)
		# add file to array
		result.append(f)
		# find next 'require' function
		content = content[m.end(1):]
		m = re.search(reg_req, content)
	return result


def formatResult(result):
	"""
	Prints results as php array definition.
	@param dict result
	"""
	if not result:
		print('No template data')
		return
	r = ''
	for key in result.keys():
		# print name of the template itself
		r += '\t\t\'' + key + '\' => array('
		items = False
		# print all depending templates
		for item in result[key]:
			if not items:
				items = True
				r += '\n'
			r += '\t\t\t\'' + item + '\',\n'
		if items:
			r += '\t\t\t'
		r += '),\n'
	print (r)
			

def analyseDir(path):
	"""
	Read directory and analyse files in the directory.
	After analysing print all data.
	@param string path
	"""
	result = {}
	for fn in os.listdir(path):
		if os.path.isfile(path + fn):
			fileSplit = fn.split('.')
			# read all files with .tpl extention
			if fileSplit[-1] == 'tpl':
				fileJoin = str.join('.', (fileSplit[:-1]))
				result[fileJoin] = analyseFile(path + fn)
	formatResult(result)


def main():
	""" 
	Init analysing in directory from param or in actual directory if parameter not set.
	"""
	if len(sys.argv) > 1:
		analyseDir(sys.argv[1])
	else:
		analyseDir('./')


if __name__ == "__main__":
    main()
	
