from django.shortcuts import render_to_response
from django.http import HttpResponseRedirect

from mnt.views_commons import ilya
from mnt.models import *
import datetime
from forms_messages import FormAjoutMess

import re, random, os
from django.db.models import Q

from django.template import RequestContext
from django.utils.datastructures import MultiValueDictKeyError

# Create your views here.


# page home dont timeline, creation de compte, sidebar

def home(request, more=""):
	#creation offset pour navigation dans les anciens messages
	if not more or more == "1":
		offset=0
		next="2"
		previous=""
	else:
		offset=(int(more)-1)*10
		next=str(int(more)+1)
		previous=str(int(more)-1)

	list_messages = Message.objects.order_by('-datetime')[offset:offset+9]

	# Pour les messages de la timeline
	messages_timeline = []
	if len(list_messages)<9:
		next=""
	for message in list_messages :
		try :
			avatar = message.user.profil_set.all()[0].avatar
		except :
			avatar = "img/icones/anonymous.gif"
	
		messages_timeline.append({'message': message, 'avatar': avatar, "ilya":ilya(message.datetime)}) 


	#ajout message
	if request.user.is_active :
		form = FormAjoutMess()
		if request.method == "POST" :
		        form = FormAjoutMess(request.POST)
		        if form.is_valid() :
				newmess=form.save(commit=False)
				newmess.user_id = request.user.id
				newmess.save()
				return HttpResponseRedirect("/")
		
	return render_to_response("home.html", locals())


