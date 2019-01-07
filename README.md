ePlayer
=======
ePlayer is m4u8 player application developed in Javascript, Vue and Electron. This player enables users to add their own favourite free/paid streaming server m3u8 of their favourite live channels.

There is a free web based remote which helps in controlling the ePlayer. You can download the Ubuntu [installer](http://eplayer.softhem.se/dist/linux/jsplayer.AppImage) and use the online remote control [here](http://eplayer.softhem.se/remote.html). You can also install it locally using the installation instructions given below.

![ePlayer screen shot](http://eplayer.softhem.se/img/eplayer.png)

## How it works
   * We have developed a backend application in PHP which serves the API requests.  
   * We have used MySQL database which saves the remote control commands and channels list of the user. 
   * When the user clicks any button on the remote control (web based) sends the command (e.g change channel) to the server which is stored in the MySQL database. The ePlayer continuously reads commands from the server and executes it.
   
## Development tools
   * We used PhpStorm IDE, Git, Composer, PHP 7.2, Node, Ubuntu and Apache2 for development environment.
   * On programming side we used Javascript, Vue, Php, Mysql.
     
## Setup and first run

  * Clone the repository `git clone git@github.com:iloveyii/ePlayer.git`.
  * Run npm install in the root directory `npm install`.
  * Run npm script to see player window in the root directory `npm start`.
  * Make a virtual host ( you may use [vh](https://github.com/iloveyii/vh)) pointing to server/web OR cd to server/web directory and run `php -S localhost:8080`.
  * Create a database (manually for now) and adjust the database credentials in the `config/app.php` file as per your environment.
  * Run composer the server directory `composer dump-autoload`.
  * Run (inside directory : server/) the init command to create the database tables as `php init.php`.
  * Browse to [http://localhost:8080/remote.html](http://localhost:8080/remote.html) to see remote control.
  * Browse to [http://localhost:8080/?data=channel](http://localhost:8080/?data=channel) to see data about channels.
  
For more information about using Composer please see its [documentation](http://getcomposer.org/doc/).

DEMO is here [DEMO](http://eplayer.softhem.se/remote.html).

## Overall Structure

Bellow the directory structure used:

```

   |-app
   |--assets
   |---css
   |---img
   |---js
   |-main.js
   |-server
   |--config
   |--models
   |--web
   |--composer.json
   |--init.php
   
```

## Requirements
   * The application has been tested with apache2 virtual hosts so it is recommended.
   * You need to enable mode rewrite and use the file `.htaccess` in the web directory.
   * Point your web server ( wwwroot ) to backend/src/web directory for the backend application.
   * Make web directory writable for web server user (www-data in apache), to enable logging.
   * Disable displaying errors in config/app.php.
   * PHP 7.2
   * Apache 2
   * MySql 5.6
   * Node 11.6.0
   * Electron 4.0.0
   
  
 <i>Web streaming is fun.</i>  
 [Hazrat Ali](http://blog.softhem.se/) 