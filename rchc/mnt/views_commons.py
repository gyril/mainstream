import datetime

def ilya (t):
	deltime=datetime.datetime.now() - t
	if deltime.seconds < 60:
		return "Il y a moins d'une minute"
	if deltime.seconds < 3600:
		return "Il y a "+str(deltime.seconds/60)+" minutes"
	if deltime.days > 0:
			if deltime.days<2:
				return "Il y a 1 jour"		
			else:
				return "Il y a "+str(deltime.days)+" jours"
	if deltime.seconds > 3600:
		if deltime.seconds/3600 < 2:		
			return "Il y a 1 heure"
		if deltime.seconds/3600 < 24:		
			return "Il y a "+str(deltime.seconds/3600)+" heures"
		




