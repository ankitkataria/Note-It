#!/usr/bin/php
<?php
	include ("./config.php");
	include ("Console/Getopt.php");
	$conn=new mysqli($server,$user,$pass);
	$q="";
	$tq="";
	$cg=new Console_Getopt();
	$allowedShortOptions="CDc:n:ld:s:";
	$allowedLongOptions=array("new-user","delete-db","create-note=","note=","note-list","delete-note=","show-note=");
	if($conn->connect_error)
	{
		die("connection failed :". $conn->connect_error."\n");
	}
	//reading the cli arguments
	$args=$cg->readPHPArgv();
	// to get the options
	$ret=$cg->getopt($args,$allowedShortOptions,$allowedLongOptions);
	//checking for the errors
	if(PEAR::isError($ret))
	{
		die("Error Occurred :".$ret->getMessage()."\n");
	}
	$opts=$ret[0];

	if(sizeof($opts)>0)
	{
		foreach($opts as $o)
		{
			switch ($o[0])
			{
				case 'C':;
				case  '--new-user':
						if($conn->select_db('notes')==false)
						{
							$q="create database notes;";
							if($conn->query($q))
							{
								$conn->query("use notes;");
								fwrite(STDOUT,"database successfull created \n");
								$tq="create table note_list(title varchar(30),content varchar(500),created datetime,updated datetime);";
								//fwrite(STDOUT,$tq);					
								if($conn->query($tq))
									fwrite(STDOUT,"table successfull created.\n");
								else 
									fwrite(STDERR,"table not created \n");



							}
							else 
								fwrite(STDOUT,"database already exists. Start adding new notes \n");

						}						
						else
							fwrite(STDOUT,"Database already exits \n");

						break;
				case 'D': ;
				case '--delete-db':
						if($conn->select_db('notes')==false)
							fwrite(STDOUT,"The database doesn't exists. Create one using --new-user/-C flags. \n");
						else 
						{
							$q="drop database notes";
							if($conn->query($q))
								fwrite(STDOUT,"The database is successfully deleted. \n");
							else 
								fwrite(STDERR,"Problem Deleting database. \n");
						}
						break;
				case 'c':;
				case '--create-note':

						break;
			    case  'n': ;
			    case '--note':
			    		foreach($opts as $opt)
			    		{
			    			if($opt[0]=='c'||$opt[0]=="--create-note")
			    				
			    		}

			    		break;
			}
		}
	}
	//print_r($ret);





	
	
?>