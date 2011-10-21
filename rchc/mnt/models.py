from django.db import models
from django.contrib.auth.models import User
from django.utils.encoding import smart_unicode

# Create your models here.

class Profil(models.Model):
	# DO NOT MODIFY THIS LINE
    user = models.ForeignKey(User, unique=True, editable=False)
    email = models.EmailField()
    avatar = models.ImageField(upload_to='img/avatars/%Y/%m/%d', default='img/icones/anonymous.gif')
    birth_date = models.DateField(blank=True, null=True)
    registration_date = models.DateField(editable=False)
    
    def __str__(self):
		return ("prof de :" + self.user.username).encode("utf-8")	

class Message(models.Model):
    mess = models.CharField(max_length=200)
    flag = models.ManyToManyField(User, blank=True, related_name='flag')
    datetime = models.DateTimeField(auto_now_add=True)
    user = models.ForeignKey(User)
 #   timeline = models.CharField(max_length=20)

    def __str__(self) :
	if len(self.mess) < 150 :
		return self.mess.encode('utf-8')
	else :
        	return (self.mess[:150]+" ...").encode('utf-8') 

#class Timeline(models.Model):
#    timeline = models.CharField(max_length=20)
  #  mess = models.ForeignKey(Message)
 #   datetime = models.DateTimeField(auto_now_add=True)
#
 #   def __str__(self) :
#       	return (self.timeline).encode('utf-8') 


