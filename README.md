
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
to enable debugger : 
</br>
output_buffering=Off </br>
</br> download xdebug dll 
</br> add this to phpini </br>

[XDebug] </br>
zend_extension = "c:\xampp\php\ext\php_xdebug-2.6.0-7.2-vc15.dll" </br>
xdebug.remote_autostart = 1 </br>
xdebug.profiler_append = 0 </br>
xdebug.profiler_enable = 0 </br>
xdebug.profiler_enable_trigger = 0 </br>
xdebug.profiler_output_dir = "c:\xampp\tmp" </br>
;xdebug.profiler_output_name = "cachegrind.out.%t-%s" </br>
xdebug.remote_enable = 1 </br>
xdebug.remote_handler = "dbgp" </br>
xdebug.remote_host = "127.0.0.1" </br>
xdebug.remote_log = "c:\xampp\tmp\xdebug.txt" </br>
xdebug.remote_port = 9000 </br>
xdebug.trace_output_dir = "c:\xampp\tmp" </br>
;36000 = 10h </br>
xdebug.remote_cookie_expire_time = 36000 </br>

</br> </br>
listen for debug on phpstorm and start debugging using debug links on website. </br>
https://www.jetbrains.com/phpstorm/marklets/ 
A Symfony project created on March 22, 2018, 6:28 pm.
