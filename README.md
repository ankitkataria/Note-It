# Note-It
A basic note making script written in php.

## Requirements
* PHP
* MYSQL
* [PEAR](https://www.sitepoint.com/getting-started-with-pear/)
* [PEAR::Console_Getopt](http://pear.php.net/package/Console_Getopt/redirected)

## Direction of Use:

Clone repo in the required directory. Install the above mentioned requirement. Open config.sample.php and enter in it the server, username, and password for mysql.Make the script executable:

'chmod +x ./script.php'

Run the following command to create the database for notes -

`./script.php --create-db`

Basic Usage:

`./script.php [OPTIONS]`		


##OPTIONS:
*  To create a new Database:

`--create-db/-C`

* To delete the databse:

`--delete-db/-D`

* To create a new note:

`--create-note/-c="note_name"`

* To Add points to a note:

`--create-note/-c="note_name" --note/-n="random stuff to note"`

* To delete a note:

`--delete-note/-d="note_name"`
	
* To show the list of note:

`--note-list/-l`

* To show content of a note:

`--show-note/-s="note_name"`	

