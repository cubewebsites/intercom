#YouTube intercom
###A console for YouTube

YouTube Intercom is a JavaScript-based console I/O system designed for interacting with the YouTube API

This plugin is based on the [Intercom console by twisterghost](https://github.com/twisterghost/intercom)

[Live Demo](http://cubewebsites.com/console/)

#Contents
1. Installation
2. Commands
3. YouTube Interaction
4. Working with Intercom

##1. Installation
Installing a setup of Youtube intercom is as simple as dropping the project files into 
your folder of choice.

##2. Commands
The console currently allows the following commands:
`help` - display help message;

`clear` - clears the content of the screen");

`toprated` - top rated videos on YouTube");

`mostviewed` - most viewed videos on YouTube");

`recentlyfeatured` - recently featured videos on YouTube");

`user` - find videos by a specified user. Use `-u[sername]=username` to specify user.");

`search` - search by keyword. Use `-q[uery]=whatever` to specify search term.");

##3. YouTube Interaction
All the YouTube API requests are handled by the Zend GData library.

This allows potential for extending this project significantly and allowing far more advanced funcionality in due time.

##4. Working with Intercom
This YouTube console is based on the console project called [Intercom by twisterghost](https://github.com/twisterghost/intercom)

Please visit the link above for more details on how to extend the console