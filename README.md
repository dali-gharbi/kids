
.checkout
=========

<b>fist of all don't forget to do composer install </b>
</br>
Go to File => settings => Tools => Command line support 
</br>
++ Add composer for Global 
</br>
Example : 
</br>
Path to php executable =>  C:\wamp64\bin\php\php7.0.10\php.exe
</br>
Path to coposer.phar or composer => C:\ProgramData\ComposerSetup\bin\composer.phar
</br>
++ Add Symfony for project
</br>
example : 
</br>
Path to php executable =>  C:\wamp64\bin\php\php7.0.10\php.exe
</br>
Path to Symfony => C:\wamp64\www\kids\bin\console
</br>
ensuite 
app => config parameters.yml => database_name: kids
</br>
url : http://localhost/kids/web/app_dev.php
</br>
pour remplir les données de la base : </br>
s doctrine:fixtures:load</br>
</br>
run this to update web resource. </br>
s assets:install target --symlink </br>
routing fosuuserbundle 
</br>
 fos_user_security_login             GET|POST   ANY      ANY    /login    </br>                         
  fos_user_security_check             POST       ANY      ANY    /login_check      </br>                 
  fos_user_security_logout            GET|POST   ANY      ANY    /logout          </br>                  
  fos_user_profile_show               GET        ANY      ANY    /profile/             </br>             
  fos_user_profile_edit               GET|POST   ANY      ANY    /profile/edit             </br>         
  fos_user_registration_register      GET|POST   ANY      ANY    /register/                    </br>     
  fos_user_registration_check_email   GET        ANY      ANY    /register/check-email            </br>  
  fos_user_registration_confirm       GET        ANY      ANY    /register/confirm/{token}          </br>
  fos_user_registration_confirmed     GET        ANY      ANY    /register/confirmed               </br> 
  fos_user_resetting_request          GET        ANY      ANY    /resetting/request                 </br>
  fos_user_resetting_send_email       POST       ANY      ANY    /resetting/send-email              </br>
  fos_user_resetting_check_email      GET        ANY      ANY    /resetting/check-email             </br>
  fos_user_resetting_reset            GET|POST   ANY      ANY    /resetting/reset/{token}           </br>
  fos_user_change_password            GET|POST   ANY      ANY    /profile/change-password      </br>


A Symfony project created on March 22, 2018, 6:28 pm.
