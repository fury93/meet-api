php init

composer install

common/config/main-local set DB config

yii migrate

Virtual host example
<VirtualHost *:80>
   DocumentRoot "C:\xampp\htdocs\meet-api\rest\web"
   ServerName meet-api.loc
   SetEnv APPLICATION_ENV development    
   <Directory "C:\xampp\htdocs\meet-api\rest\web">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>
</VirtualHost>
