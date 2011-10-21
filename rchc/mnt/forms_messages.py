import re
from django import forms
from django.forms import ModelForm, Textarea
from mnt.models import Message


class FormAjoutMess(ModelForm):

	class Meta:
		model = Message
		fields = ('mess',)
		widgets = {
			'mess': Textarea(attrs={'cols':50, 'rows':5}),
		}

	# Pour remplacer le message d'erreur par defaut de django (concernant le field 'quest')
#	def clean(self):
#		cleaned_data = self.cleaned_data
#
#		if self._errors and 'quest' in self._errors and 'obligatoire' in self._errors['quest'][0]:
#			self._errors['quest'][0] = 'Si si, vous pouvez poser une question !'   			
#		if self._errors and 'quest' in self._errors and 'maximum' in self._errors['quest'][0]:
#			regex = re.compile(r'[^(]*\([^ ]* ([0-9]*)\).*')
#			value = regex.search(self._errors['quest'][0]).group(1)
#			self._errors['quest'][0] = 'Veuillez ne pas depasser le 200 caractere pour la question ('+value+' actuellement)'	
#		return cleaned_data
