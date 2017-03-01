#!/usr/bin/php
<?php
	include ("./config.sample.php");
	include ("Console/Getopt.php");

	
	$conn=new mysqli($server,$user,$pass);
	if($conn->select_db('notes')==true)
		$conn->query("use notes;");

	function note_exists($name,$conn)
	{
		//$c=new mysqli($server,$user,$pass,$db);
		$sql1="select * from note_list;";
		//if($conn->query($sql1))
		//	fwrite(STDOUT,"query done");
		$result=$conn->query($sql1);
		//fwrite(STDOUT,$result);
		if($result->num_rows>0)
		{
			while($row=$result->fetch_assoc()){
				if($row['title']==$name)
					{
						return true;
					}
			}


		}
		return false;
	}
	
	$test="";$test2="";$test3="";$test4="";
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
				case 'c': $test=1;
				case '--create-note':
						if($test==1)
							$tmp_1=1;
						else 
							$tmp_1=0;
						if($conn->select_db('notes'))
						{

							$tmp_n=explode("=",$o[1]);
							//print_r($tmp_n);
							$note_name=$tmp_n[$tmp_1];
							//fwrite(STDOUT,"the note name".$note_name);
							if(!note_exists($note_name,$conn))
							{
								$date=date("Y-m-d h:i:s");
								$q="insert into note_list(title,created,updated,content) values('".$note_name."','$date','$date','title:$note_name;');";
									//fwrite(STDOUT,$q);
								if($conn->query($q))
									fwrite(STDOUT,'Note successfully created. Starting adding notes into in using:'."\n".'--create-note="note_name" --note="random stuff" '."\n");
								else
									fwrite(STDERR,"note not created. \n");
							}
							else
								fwrite(STDOUT,"The note already exists. \n");
						}
						else
							fwrite(STDOUT,"The database doesn't exist. Create one using: \n --new-user/-C \n");
						break;
			    case 'n': $test2=1;
			    case '--note':
					    if($test2==1)
							$tmp_2=1;
						else 
							$tmp_2=0;

			    		$tmp=0;
			    		foreach($opts as $opt)
			    		{
			    			if($opt[0]=='c'||$opt[0]=="--create-note")
			    			{
			    				$tmp=1;
								break;
			    			}
			    		}
			    		if($tmp==0)
			    			fwrite(STDOUT,"please specify a note name using -c/--create-note='note-name' \n");
			    		else
			    		{
			    			$tmp_c=explode("=",$o[1]);
							//print_r($tmp_n);
							$note_content=$tmp_c[$tmp_2];
							$note_content=$note_content.";";
			    			fwrite(STDOUT,"Writing to the note \n");
			    			if(note_exists($note_name,$conn))
			    			{
			    				$date=date("Y-m-d h:i:s");
			    				$q="update note_list set content=concat(content,'$note_content'),updated='$date' where title='$note_name';";
			    				if($conn->query($q))
			    					fwrite(STDOUT,"Note successfully added. \n");
			    				else 
			    					fwrite(STDERR,"Could not create note.");
			    			}
			    		}

			    		break;
			    case 'd': $test3=1;
			    case '--delete-note':
			    		if($test3==1)
							$tmp_3=1;
						else 
							$tmp_3=0;

						$tmp_name=explode("=",$o[1]);
							//print_r($tmp_n);
						$note_tmp_name=$tmp_name[$tmp_3];

						if(note_exists($note_tmp_name,$conn))
						{
							$q="delete from note_list where title='$note_tmp_name'";
							if($conn->query($q))
								fwrite(STDOUT,"The note is deleted \n");
							else 
								fwrite(STDERR,"error deleting note");
						}
						else
							fwrite(STDOUT,"No such Note exists \n");

			    		break;
			    
			    case 'l':
			    case '--note-list':
			    		$q="select title from note_list";
			    		$result=$conn->query($q);
			    		if($result->num_rows>0)
			    		{
			    			fwrite(STDOUT,"The notes saved in the database are: \n");
			    			while($row=$result->fetch_assoc())
			    			{
			    				fwrite(STDOUT,$row['title']."\n");
			    			}
			    		}
			    		else
			    		{
			    			fwrite(STDOUT,"No notes exists.");
			    		}

			    		break;
			    case 's':$test4=1;
			    case '--show-note':
			    		if($test4==1)
							$tmp_4=1;
						else 
							$tmp_4=0;
						$tmp_name2=explode("=",$o[1]);
							//print_r($tmp_n);
						$note_tmp_name2=$tmp_name2[$tmp_4];
						if(note_exists($note_tmp_name2,$conn))
						{
							$q="select * from note_list where title='$note_tmp_name2'";
							$result=$conn->query($q);
							if($result->num_rows>0)
							{
								while($row=$result->fetch_assoc())
								{
									$t=$row['title'];
									$c=$row['content'];
									$d=$row['created'];
									$u=$row['updated'];
									$ca=explode(";",$c);
									//print_r($ca);
									$i=0;
									for($i=0;$i<sizeof($ca)-1;$i++)
									{	
										if($i==0)
											fwrite(STDOUT,$ca[$i]."\n");
										else
										{	
											fwrite(STDOUT,"$i . $ca[$i].\n");
										}
									}
									fwrite(STDOUT,"created on: $d \nupdated on: $u \n");
								}
							}
						}
						else
							fwrite(STDOUT,"The note doesn't exists");

			    		break;

			}
		}
	}
	//print_r($ret);





	
	
?>