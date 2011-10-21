from django.conf.urls.defaults import patterns, include, url
from django.views.generic.simple import direct_to_template, redirect_to
from django.contrib.auth.views import login, logout

from mnt.views import home
from mnt.views_users import register, login_view

# Uncomment the next two lines to enable the admin:
# from django.contrib import admin
# admin.autodiscover()



urlpatterns = patterns('',
	url(r'^login/$', login_view),
	url(r'^$', home),
# Afficher plus dans la timeline
	url(r'^more/(?P<more>\d{1,2})/$', home),
	url(r'^registration/$', register),
#Afficher la confirmation de logout
	url(r'^logout/$', logout, {'next_page':'/'}),

    # Examples:
    # url(r'^$', 'rchc.views.home', name='home'),
    # url(r'^rchc/', include('rchc.foo.urls')),

    # Uncomment the admin/doc line below to enable admin documentation:
    # url(r'^admin/doc/', include('django.contrib.admindocs.urls')),

    # Uncomment the next line to enable the admin:
    # url(r'^admin/', include(admin.site.urls)),
)
