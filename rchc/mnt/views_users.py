#register, login_view

from django.contrib import auth
from mnt.forms_registration import RegistrationForm, MyUserCreationForm
from django import forms
#from django.contrib.auth.forms import UserCreationForm, UserChangeForm
from django.http import HttpResponseRedirect
from django.shortcuts import render_to_response
from mnt.models import Profil
from django.http import HttpResponse
import datetime
from django.conf import settings
from django.contrib.auth import authenticate, login


def register(request):
    presence_avatar=False
    if request.method == 'POST' and request.POST.get('register', '')=='False':
        username = request.POST.get('username', '')
        password = request.POST.get('password', '')
        user = auth.authenticate(username=username, password=password)
        if user is not None and user.is_active:
            # Correct password, and the user is marked "active"
            auth.login(request, user)

            # Redirect to a success page.
            return HttpResponseRedirect("/")

        elif user is not None and not user.is_active:
                #Correct password but profil not completed
################
#A REPRENDRE
            return HttpResponseRedirect("/profil/perso?redirect=1")
###############"
    elif request.method == 'POST' and request.POST.get('register','')=='True':

        form = MyUserCreationForm(request.POST)

        if form.is_valid():
            username = request.POST.get('username','')
            password = request.POST.get('password1','')
            new_user = form.save()
            user = auth.authenticate(username=username, password=password)
            if user is not None:# and user.is_active:
                # Correct password, and the user is marked "active"
                auth.login(request, user)              
                # Redirect to following form.
                user.is_active=False
                user.save()
                
                form=RegistrationForm(presence_avatar)
		
                return render_to_response("register.html", {'form2': form})
        else:
            return render_to_response("register.html", {'form': form})

           

    elif request.method == 'POST' and request.POST.get('register','')=='next':

        new_profil = Profil(user= request.user, registration_date = datetime.date.today())

        form = RegistrationForm(presence_avatar, request.POST.copy(), request.FILES, instance=new_profil)

        if form.is_valid():
            new_profil = form.save(commit=False)
            print form.data
            new_profil.save()
            request.user.is_active=True
            request.user.save()
            return HttpResponseRedirect("/")
        else:
#MODIF
            return render_to_response("register.html", {'form2': form})
        
    else:
        #assert False
        if request.user.is_authenticated():
		    return HttpResponseRedirect("/")
        else:
			form = MyUserCreationForm()
		
			return render_to_response("register.html", locals())


def login_view(request):
    username = request.POST['username']
    password = request.POST['password']
    user = authenticate(username=username, password=password)
    if user is not None:
        if user.is_active:
            login(request, user)
	    return HttpResponseRedirect("/")
        else:
	# Redirects to login page with message error if user did not match password
            return render_to_response("register.html", {'login_error': True, 'form': MyUserCreationForm(), 'register': False})
    else:
        return HttpResponseRedirect("/")


