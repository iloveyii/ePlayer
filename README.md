ePlayer
=======
ePlayer is m4u8 player application developed in Javascript and Electron. This player enables users to add their own favourite free/paid streaming server m3u8 of their favourite live channels.

There is a free web based remote which helps in controlling the ePlayer. You can download the Ubuntu installer or install it locally using the installation instructions given below.

![ePlayer screen shot]()
## What has been done
   * We have developed a backend application in PHP which serves the API requests. We have used a small PHP MVC framework
     called [Alip](http://github.com/iloveyii/alip).  
   * We have used MySQL database which imports the events in the given json file to the event table in the database. 
     The database also stores info about user login and their polling in related tables. We have used two views in the
     database to help simplify the task.
   * We have written a small PHP script init.php which does all the necessary tasks like importing json file and creating
     required tables in database.
   * We have developed required web pages using html 5 and CSS 3 for user login, user sign up and polling on the events.
   
## Development tools
   * We used PHPStorm IDE, git, bitbucket, composer, PHP 7.2, Ubuntu and Apache2 for development environment.
   * Fetching random category (sport) for which user has not polled before was a challenge. We created two views to 
     simplify this task. View category has the list of all (distinct) sports in the json file ( ie event table ). 
     If a new sport is added or json file with other sports is provided the view has the dynamic nature to get all of 
     them. We can easily fetch any dynamic sport from this View using simple SQL but how to make sure that the user has
     not already polled on it. We need inner joins with the table vote, but to simplify it further we have created a 
     View called user_voted_sport which shows the sport for which the user has already voted. So using these two views
     we can easily determine the solution of the challenge.
     
## Setup and first run

  * Clone the repository `git clone git@bitbucket.org:iloveyii/sports-poll.git`.
  * Run composer install `composer install`.
  * Then run composer command `composer dump-autoload`.
  * Create a database (manually for now) and adjust the database credentials in the `config/app.php` file as per your environment.
  * Run the init command to create the database table as `php init.php`.
  * Point web browser to backend/src/web directory or Create a virtual host using [vh](https://github.com/iloveyii/vh) `vh new sportspoll -p ~/sportspoll/backend/src/web`
  * Browse to [http://sportspoll.loc](http://sportspoll.loc) (default: username: admin, password: admin).
  
For more information about using Composer please see its [documentation](http://getcomposer.org/doc/).

## How to use the framework

This framework is very easy to be used. You can create an object of the router by passing a request object to it as shown below.

```
// index.php
require_once 'vendor/autoload.php';
require_once 'config/app.php';

use App\Models\Router;
use App\Models\Request;

/**
 * First create router object with params Request object and default route
 */
$router = new Router(new Request, '/posts/index');

/**
 * Next declare the http methods
 */
$router->get('/posts/index', function ($request) {
    $controller = new \App\Controllers\PostController($request);
    $controller->index();
});
```

DEMO is here [DEMO](http://sportspoll.softhem.se).

## Overall Structure

Bellow the directory structure used:

```

   |-backend
   |--src
   |---config
   |----app.php
   |---controllers
   |---models
   |----Database.php
   |----User.php
   |----Event.php
   |----Vote.php
   |----Winner.php
   |----Request.php
   |----Router.php
   |---views
   |----event
   |----user
   |---web
   |----index.php
   |----assets
   |-frontend
   |--src
   
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
   
## Testing
  * To run the php unit tests, inside backend/src run `phpunit ` .
  
<i>Web development has never been so fun.</i>  
[Hazrat Ali](http://blog.softhem.se/) 
