from django import forms
from django.forms import ModelForm
from django.forms.extras.widgets import SelectDateWidget
from mnt.models import Profil
from django.contrib.auth.forms import UserCreationForm
from django.template.defaultfilters import filesizeformat
from django.conf import settings

f=open('list_year', 'r')
liste=[]
for lines in f:
    for word in lines.split("/"):
        try:
         liste.append(int(word))
        except ValueError :
            pass

   


    class MyUserCreationForm(UserCreationForm):
        username = forms.RegexField(label=("Username"), max_length=15, regex=r'^[\w.@+-]+$',
        help_text = (" Les seuls caracteres speciaux acceptes sont : @ . + - _ "),
        error_messages = {'invalid': ("Vous ne pouvez utiliser que des lettres, des chiffres et les caracteres @/./+/-/_ ")})
        password1 = forms.CharField(label=("Password"), widget=forms.PasswordInput)
        password2 = forms.CharField(label=("Password confirmation"), widget=forms.PasswordInput,
        help_text = (""))


    


#Create the profil form registration.
    class RegistrationForm(ModelForm):
        birth_date = forms.DateField(widget=SelectDateWidget(years=liste), label='date de naissance (jour/mois/annee)', required=False)
        email = forms.EmailField(label='e-mail*')
        avatar = forms.ImageField(required=False, widget=forms.FileInput)

        def clean_avatar(self):
                avatar = self.cleaned_data['avatar']
                try:
                    if avatar.size > settings.MAX_UPLOAD_SIZE:
                        self.data['avatar']=''
                        raise forms.ValidationError(('Votre avatar ne doit pas depasser %s. La taille actuelle du fichier est de %s') % (filesizeformat(settings.MAX_UPLOAD_SIZE), filesizeformat(avatar.size)))
                        assert False
                except AttributeError:
                    pass
           
                return self.cleaned_data['avatar']
        class Meta:
            model = Profil

        def __init__(self, presence_avatar, *args, **kwargs):
            super(RegistrationForm, self).__init__(*args, **kwargs)
            if presence_avatar:
                self.fields.keyOrder = ['email', 'birth_date', 'avatar']
            else:
                self.fields.keyOrder = ['email', 'birth_date']


            

