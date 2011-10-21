from django.conf.urls.defaults import patterns, include, url
from django.views.generic.simple import direct_to_template, redirect_to
from django.contrib.auth.views import login, logout

from mnt.views import home
from mnt.views_users import register, login_view

# Uncomment the next two lines to enable the admin:
# from django.contrib import admin
# admin.autodiscover()



urlpatterns = patterns('',
#Home page (MaiNsTream + Login )
	url(r'^$', home),
	# gestion redirection post login	
	url(r'^login/$', login_view),
# Afficher plus dans la timeline
	url(r'^more/(?P<more>\d{1,2})/$', home),
#Formulaire d'inscription
	url(r'^registration/$', register),
#Afficher la confirmation de logout
	url(r'^logout/$', logout, {'next_page':'/'}),
)

