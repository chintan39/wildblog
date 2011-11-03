import random

alphabet = 'abcdefghijklmnopqrstuvwxz     '

def getString(mn, mx):
	"""
	Generate random string with the length between mn and mx.
	"""
	s = ''
	for i in range(random.randint(mn, mx)):
		s += alphabet[random.randint(0, len(alphabet) -1)]
	return s

# generates data for table produkty
for i in range(1000):
	print('INSERT INTO `produkty` (`id`, `name`, `price`, `active`, `text`) VALUES (NULL, \'%s\', \'%s\', %s, \'%s\');' % (getString(3, 20), str(random.randint(10, 1000)), str(random.randint(0, 1)), getString(50, 500))) 

# generates data for table atributy
for i in range(1000):
	for j in range(5):
		print('INSERT INTO `atributy` (`id`, `produkt`, `type`, `value_number`, `value_text`, `value_datetime`) VALUES (NULL, \'%s\', \'%s\', %s, \'NULL\', \'NULL\');' % (str(i), '2', str(random.randint(10, 1000)))) 
		print('INSERT INTO `atributy` (`id`, `produkt`, `type`, `value_number`, `value_text`, `value_datetime`) VALUES (NULL, \'%s\', \'%s\', \'NULL\', \'%s\', \'NULL\');' % (str(i), '3', getString(10, 1000))) 
		print('INSERT INTO `atributy` (`id`, `produkt`, `type`, `value_number`, `value_text`, `value_datetime`) VALUES (NULL, \'%s\', \'%s\', \'NULL\', \'NULL\', \'%s\');' % (str(i), '4', ('2009-' + str(random.randint(1, 12)) + '-' + str(random.randint(1, 28)) + ' 19:08:23'))) 

