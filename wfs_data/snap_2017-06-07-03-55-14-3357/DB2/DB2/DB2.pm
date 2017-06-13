###############################################################################
### Copyright NetApp Inc.  All rights reserved                              ### 
### Date: 01/26/2017                                                          ###
### Name: DB2.pm                                                          ###
###############################################################################
package DB2;

our @ISA = qw(SnapCreator::Mod);

use strict;
use warnings;
use diagnostics;
use XML::Writer;

use SnapCreator::Util::Generic qw ( trim isEmpty );
use SnapCreator::Util::OS qw ( isWindows isUnix getUid createTmpFile );
use SnapCreator::Event qw ( INFO ERROR WARN DEBUG COMMENT ASUP CMD DUMP );
use SnapCreator::Discovery qw ( DEVICE_DATA DEVICE_ONLINE_LOG DEVICE_OFFLINE_LOG DEVICE_TEMP DEVICE_EXTERNAL_FILES DEVICE_CONTROL DEVICE_DUMP DEVICE_DB );

### Global Definitions ###
my $uid = ();
my %db2Config_h = ();
my %config_h = ();
my $msgObj = new SnapCreator::Event();

my %dbInstList = ();
my @instanceArray = ();
my @mount_pathArray = ();
 
### constants ###
use constant INSTANCE => "Instance";
use constant DATABASE => "Database";
use constant PLUGIN_NAME => "DB2";
use constant RESOURCES => "RESOURCES";
use constant RESTORE_MOUNT_PATHS => "RESTORE_MOUNT_PATHS";
use constant RESOURCES_DELIMITER => ";";
use constant RESOURCE_DELIMITER => ",";
use constant UID_DELIMITER => "\\";

### local methods ###
my $parseSystemDatabaseDirectory = ();
my $getBaseCmd = ();
my $get_databases = ();
my $quiesce_internal = ();
my $unquiesce_internal = ();
my $isInQuiesceState = ();
my $parseCurrentInstance = ();

### public methods ###
sub new {

	my $invocant = shift;
	my $class = ref($invocant) || $invocant;

	my $self = {
		@_
	};

	bless ($self, $class);
	
	return $self;
	
}

sub setENV {
	my ($self, $config) = @_;

        my $result = {
                exit_code => 0,
                stdout => "",
                stderr => "",
        };

	%config_h = ();
	$config->{'DB2_CMD'} = "sqllib/bin/db2";
	%config_h = %{$config};
	%db2Config_h = ();
	%dbInstList = ();
	@instanceArray = ();
	@mount_pathArray = ();
        my @message_a=();
		
	$uid = SnapCreator::Util::OS->getUid();

	if (exists $config->{'DB2_CLONE_META'}) {
		foreach (split (";", $config->{'DB2_CLONE_META'})) {
			if (m/^(\w+):(.*),(.*),(.*)\/(.*)$/) {
				$db2Config_h{'target'}{$2}{'source_sid'} = $1;  				
				$db2Config_h{'target'}{$2}{'target_db_path'} = $3;  				
				$db2Config_h{'target'}{$2}{'target_user'} = $4;  				
				$db2Config_h{'target'}{$2}{'target_group'} = $5;
			}
		} 
	}
	
	if(defined $config->{RESOURCES} && $config->{RESOURCES} ne ''){
		my @resources = split(RESOURCES_DELIMITER,  $config->{RESOURCES});
		foreach my $resource (@resources) {
			my ($uid, $type) = split(RESOURCE_DELIMITER, $resource);
			$uid = trim $uid;
			$type = trim $type;
			if(!defined(isEmpty $uid) || !defined(isEmpty $type)) {
				$result->{exit_code} = -1;
        		$msgObj->collect(\@message_a, ERROR, "Param RESOUCRES contain invalid UID or TYPE");
				last;
			}
			if($type =~ m/INSTANCE/i) {
				push(@instanceArray, $uid);
			}
			elsif($type =~ m/DATABASE/i) {
				my ($instance, $database) = split(/\\/, $uid);
				$instance = trim $instance;
				$database = trim $database;
				if(!defined(isEmpty $instance) || !defined(isEmpty $database)) {
					$result->{exit_code} = -1;
        				$msgObj->collect(\@message_a, ERROR, "Param RESOUCRES contain invalid UID for Type Database");
					last;
				}
				my $dbs = $dbInstList{$instance};
				my $dbinfo = {
					db => $database,
					path => ""   # Dummy
				};
        			$msgObj->collect(\@message_a, INFO, "Resource DB:$database Instance:$instance is pushed");
				push(@{$dbs}, $dbinfo);
				$dbInstList{$instance}=$dbs;
			}
		}
	}
	if(defined $config->{RESTORE_MOUNT_PATHS} && $config->{RESTORE_MOUNT_PATHS} ne ''){
		(@mount_pathArray) = split(RESOURCE_DELIMITER, $config->{RESTORE_MOUNT_PATHS});
	}
	
	foreach (grep (/^DB2_CLONE_RELOC\d+/i, sort keys %{$config})) {
		if (exists $config->{$_}) {
			if ($config->{$_} =~ m/(\w+):(.*)$/) {
				my $db_sid = $1;
				my $entry = $2;
				push (@{$db2Config_h{'target'}{$db_sid}{'reloc'}}, $entry);
			}
		}
	}

	foreach (grep (/^DB2_CLONE_RELOC_FILE\d*/i, sort keys %{$config})) {
        	foreach (split(";", $config->{$_})) {
            		if (m/(\w+):(.*)$/) {
                		$db2Config_h{'target'}{$1}{'reloc_file'} = $2;
            		} 
        	}
    	}

	foreach (grep (/^DB2_CLONE_PARAM\d+/i, sort keys %{$config})) {
		if (exists $config->{$_}) {
			if ($config->{$_} =~ m/(\w+):(.*)$/) {
				my $db_sid = $1;
				foreach (split (",", $2)) {
					my $entry = $_;
					$entry =~ s/=/ /g;
					push (@{$db2Config_h{'target'}{$db_sid}{'param'}}, $entry);
				}
			}
		}
	}

	if ($result->{exit_code} != 0) {
        	$msgObj->collect(\@message_a, ERROR, "$config_h{'APP_NAME'}::setENV failed");
	} else {
        	$msgObj->collect(\@message_a, DEBUG, "$config_h{'APP_NAME'}::setENV finished successfully");
	}

        $result->{message} = \@message_a;

        return $result;
}

=head
To check if DB is in Quiesce state already based on error message parsing
Input:
   Error message
Output:
   -1: Not in quiesce state already
    0: Is in Quiesce state already
=cut
$isInQuiesceState = sub {
	my $ret = -1;
	my $error = shift;
	if(!defined($error)) {
		return $ret;
	}
	if($error =~ m/SQL1550N/i && $error =~ m/The SET WRITE SUSPEND command failed/i && $error =~ m/Reason code = \"4\"/i) {
		$ret = 0;
	}
	return $ret;
};

=head
Perform quiesce of all specified DBs of a given Instance. If any DB is in Quiesce state already, then would continue with other DBs.
Input:
  instance: Db Instance name 
  dbs: List of DBs
Output:
  0 : On Success or other appropriate error code on failure
=cut
$quiesce_internal = sub {
	my $instance = shift;
	my $dbs = shift;
	my ($base_cmd, $inidb_cmd, $relocatedb_cmd) = ();
	my $cmd = (); 
	my $cmd_file = ();
	my $content = ();
	my $result = {
		exit_code => 0,
		stdout => "",
		stderr => "",
	};
	my @message_a = ();
	if(! defined ($dbs)) {
		$msgObj->collect(\@message_a, ERROR, "Either instance $instance does not exits or it has no Databases");
		$result->{exit_code} = 1;
		$result->{stderr} = "Either instance $instance does not exits or it has no Databases";
		goto END;
	}

	($base_cmd, $inidb_cmd, $relocatedb_cmd) = $getBaseCmd->($instance);

	foreach my $dbinfo(@{$dbs}) {
		my $db = $dbinfo->{db};
		if (defined (isWindows())) {
			($cmd_file, $content) = SnapCreator::Util::OS->createTmpFile(("set DB2INSTANCE=$instance","db2 connect to $db", "db2 set write suspend for database", "db2 connect reset", "exit %ERRORLEVEL%"));
			$cmd = "db2cmd -i -w < $cmd_file";
		}
		else {
			($cmd_file, $content) = SnapCreator::Util::OS->createTmpFile(("connect to $db;", "set write suspend for database;", "connect reset;"));
			$cmd = sprintf($base_cmd, $cmd_file);
		}
		$msgObj->collect(\@message_a, DEBUG, "Executing SQL sequence:\n$content");		
		$result = SnapCreator::Util::OS->execute($cmd);
		$msgObj->collect(\@message_a, DEBUG, "Command [$cmd] finished with\nexit code: [$result->{exit_code}]\nstdout: [$result->{stdout}]\nstderr: [$result->{stderr}]");
		unlink ($cmd_file);
		if($result->{exit_code} != 0) {
			if($isInQuiesceState->($result->{stdout}) == 0) { # If DB is in Quiesce state already
				$msgObj->collect(\@message_a, INFO, "DB: $db is in Quiesce state already");
				$result->{exit_code} = 0;
				next;
			}
		}
		last if( $result->{exit_code} != 0 );
		$msgObj->collect(\@message_a, DEBUG, "Quiesce Database $db on instance $instance successful");
	}

END:

	$result->{message} = \@message_a;
	return $result;
};

=head
Perform UnQuiesce of all specified DBs of a given Instance. unQuiesce of other DBs doesn't stop on error on previous Db.
Input:
  instance: Db Instance name 
  dbs: List of DBs
Output:
  0 : On Success or other appropriate error code on failure
=cut
$unquiesce_internal = sub {
	my $instance = shift;
	my $dbs = shift;
	my ($base_cmd, $inidb_cmd, $relocatedb_cmd) = ();
	my $cmd = (); 
	my $cmd_file = ();
	my $content = ();
	my $result = {
		exit_code => 0,
		stdout => "",
		stderr => "",
	};
	my @message_a = ();
	
	($base_cmd, $inidb_cmd, $relocatedb_cmd) = $getBaseCmd->($instance);
	
	foreach my $dbinfo(@{$dbs}) {
		my $db = $dbinfo->{db};
		if (defined (isWindows())) {
			($cmd_file, $content) = SnapCreator::Util::OS->createTmpFile(("set DB2INSTANCE=$instance","db2 connect to $db", "db2 set write resume for database", "db2 connect reset", "exit %ERRORLEVEL%"));
			$cmd = "db2cmd -i -w < $cmd_file";
		}
		else {
			($cmd_file, $content) = SnapCreator::Util::OS->createTmpFile(("connect to $db;", "set write resume for database;", "connect reset;"));
			$cmd = sprintf($base_cmd, $cmd_file);	
		}
		$msgObj->collect(\@message_a, DEBUG, "Executing SQL sequence:\n$content");		
		$result = SnapCreator::Util::OS->execute($cmd);
		$msgObj->collect(\@message_a, DEBUG, "Command [$cmd] finished with\nexit code: [$result->{exit_code}]\nstdout: [$result->{stdout}]\nstderr: [$result->{stderr}]");
		unlink ($cmd_file);
		if($result->{exit_code}==0) {
			$msgObj->collect(\@message_a, DEBUG, "UnQuiesce Database $db on instance $instance successful");			
		}
	}

	$result->{message} = \@message_a;	
	return $result;
};

sub quiesce {
	my $self = shift;
	my $result = {
		exit_code => 0,
		stdout => "",
		stderr => "",
	};
	my @message_a = ();
		
	$msgObj->collect(\@message_a, INFO, "Quiescing databases");
	
	if(0!= @instanceArray)
	{
		my $dbList_info = {};
		($result,$dbList_info) = $get_databases->(@instanceArray);	
		push(@message_a,@{$result->{message}});
		
		if($result->{exit_code} != 0) {
			goto END;
		}
		foreach my $instance(@instanceArray) {
			$result = $quiesce_internal->($instance, $dbList_info->{$instance});
			push(@message_a,@{$result->{message}});
			last if($result->{exit_code} != 0); 
		}
	}
	if(0 != (keys %dbInstList)) # DBs
	{
		my @instances = keys %dbInstList;

		foreach my $instance(@instances)
		{
			$result = $quiesce_internal->($instance, $dbInstList{$instance});
			push(@message_a,@{$result->{message}});
			last if($result->{exit_code} != 0); 
		}
	}

END:
	
	if ($result->{exit_code} == 0) {
		$msgObj->collect(\@message_a, INFO, "Quiescing databases finished successfully");
	} else {
		$msgObj->collect(\@message_a, ERROR, "[db2-00002] Quiescing databases failed");
	}

	$result->{message} = \@message_a;

	return $result;
}

sub unquiesce {
	my $self = shift;
	my $result = {
		exit_code => 0,
		stdout => "",
		stderr => "",
	};
	my @message_a = ();
	
	$msgObj->collect(\@message_a, INFO, "Unquiescing databases");
	
	if(0!= @instanceArray)
	{
		my $dbList_info = {};
		($result,$dbList_info) = $get_databases->(@instanceArray);	
		push(@message_a,@{$result->{message}});

		if($result->{exit_code} != 0) {
			goto END;
		}
		
		foreach my $instance(@instanceArray) {
			$result = $unquiesce_internal->($instance, $dbList_info->{$instance});
			push(@message_a,@{$result->{message}});
		}
	}
	if(0 != (keys %dbInstList)) # DBs
	{
		my @instances = keys %dbInstList;

		foreach my $instance(@instances)
		{
			$result = $unquiesce_internal->($instance, $dbInstList{$instance});
			push(@message_a,@{$result->{message}});
		}
	}
END:
	
	if ($result->{exit_code} == 0) {
		$msgObj->collect(\@message_a, INFO, "Unquiescing databases finished successfully");
	} else {
		$msgObj->collect(\@message_a, ERROR, "[db2-00004] Unquiescing databases failed");
	}

	$result->{message} = \@message_a;

	return $result;
}

$get_databases = sub {
	my @instance_list = @_;
	my $result = {
		exit_code => 0,
		stdout => "",
		stderr => "",
		};
	my $base_cmd = ();
	my $cmd = ();
	my $cmd_file = ();
	my $output_file = ();
	my $content = ();
	my $dbList = {};
 	my $cmd_output_file = ();
	my $temp_output_file = ();
	my @message_a = ();
	my $output_string = "";
	my %db_info_h = ();
	my $curr_instance_name = "";

	if (defined isWindows()) 
	{
		($cmd_output_file) = SnapCreator::Util::OS->createTmpFile();
		($temp_output_file) = SnapCreator::Util::OS->createTmpFile();

		foreach my $instance (@instance_list)
		{
			$output_string = "";
			$msgObj->collect(\@message_a, INFO, "Discovered Instance $instance");
			$msgObj->collect(\@message_a, INFO, "Discovering Databases for Instance $instance");
			($cmd_file, $content) = SnapCreator::Util::OS->createTmpFile(("set DB2INSTANCE=$instance","db2 get instance > $cmd_output_file","db2 list database directory >> $cmd_output_file","exit"));
			$msgObj->collect(\@message_a, DEBUG, "Executing SQL sequence:\n$content");

			#my $base_cmd = "$config_h{'DB2_CMD'}";
			my $base_cmd = "db2cmd";
			$cmd = "$base_cmd -i -w < $cmd_file";
			$result = SnapCreator::Util::OS->execute("$cmd > $temp_output_file");
			open (CMD_OUTPUT,"$cmd_output_file") or die ("Error in opening file $cmd_output_file $! \n");
			while (my $line = <CMD_OUTPUT>)
			{
				$output_string = "$output_string"."$line" if ($line ne "");
			}
			$msgObj->collect(\@message_a, INFO, "Command [$cmd] finished with\nexit code: [$result->{exit_code}]\nstdout: [$output_string]\nstderr: [$result->{stderr}]");	

			if ($result->{exit_code} != 0) 
			{
				if($output_string =~ "The system database directory is empty")
				{
					$msgObj->collect(\@message_a, DEBUG, "No Database found for instance $instance");
					$dbList->{$instance} = ();
					$result->{exit_code} = 0;					
					next;
				}
				elsif($output_string =~ "SQL1031N" && $output_string =~ /The database directory cannot be found on the indicated file system/i ) {
					$msgObj->collect(\@message_a, DEBUG, "Fresh Instance $instance. No DB exist");					
					$result->{exit_code} = 0;
				}
				else
				{
					$msgObj->collect(\@message_a, ERROR, "Error in Retrieving Database for $instance");
					next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
					$result->{exit_code} = 1;					$result->{stderr} = "Error in Retrieving Database for $instance";
					goto END;
				}
			}

			$curr_instance_name = $parseCurrentInstance->($output_string);
			$msgObj->collect(\@message_a, DEBUG, "Retrieved instance name is $curr_instance_name");

			if($curr_instance_name ne $instance) {
				$msgObj->collect(\@message_a, DEBUG, "No instance with instance name $instance exists");
				$dbList->{$instance} = ();
				$result->{exit_code} = 0;
				next;
			}

			%db_info_h = $parseSystemDatabaseDirectory->($output_string);
			foreach my  $entry (keys %db_info_h )
			{
=head
				foreach my $innerEntry (keys %{$db_info_h{$entry}})
				{
					if( $innerEntry =~ /Database name/)
					{
						my $database =  $db_info_h{$entry}{$innerEntry};
						$msgObj->collect(\@message_a, INFO, "Discovered Database $database");
						push (@{$dbList->{$instance}}, $database);
					}					
				}
=cut
				my $db = $db_info_h{$entry}{"Database name"};
				my $path = $db_info_h{$entry}{"Local database directory"};
				
				if(defined($db ) && defined($path))
				{
					my	$db_info = {
						db => $db,
						path => $path
					};
					push(@{$dbList->{$instance}}, $db_info);
				}				
			}
			
		}
		
	}
	
	if (defined isUnix()) 
	{
		foreach my $instance (@instance_list)
		{
			$output_string = "";
			$msgObj->collect(\@message_a, INFO, "Discovered Instance $instance");
			$msgObj->collect(\@message_a, INFO, "Discovering Databases for  Instance $instance");
			$msgObj->collect(\@message_a, DEBUG, "Setting Database Instance  $instance");
			$result = SnapCreator::Util::OS->execute(" /bin/su -c \"export DB2INSTANCE=$instance\"");
			$msgObj->collect(\@message_a, DEBUG, "Command  finished with\nexit code: [$result->{exit_code}]\nstdout: [$result->{stdout}]\nstderr: [$result->{stderr}]");
			$base_cmd = "su - $instance -c \"$config_h{'DB2_CMD'} -tvf %s\"";
			($cmd_file, $content) = SnapCreator::Util::OS->createTmpFile(("get instance;"));
			$msgObj->collect(\@message_a, DEBUG, "Executing SQL sequence:\n$content");
			$cmd = sprintf($base_cmd, $cmd_file);	
			$result = SnapCreator::Util::OS->execute($cmd);
			$msgObj->collect(\@message_a, DEBUG, "Command [$cmd] finished with\nexit code: [$result->{exit_code}]\nstdout: [$result->{stdout}]\nstderr: [$result->{stderr}]");
			if($result->{stdout} !~ $instance)
			{
				$msgObj->collect(\@message_a, ERROR, "Error in Setting Instance $instance");
				next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
				$result->{exit_code} = 1;
				goto END;
			}
			($cmd_file, $content) = SnapCreator::Util::OS->createTmpFile(("list database directory;"));
			$msgObj->collect(\@message_a, DEBUG, "Executing SQL sequence:\n$content");
			$cmd = sprintf($base_cmd, $cmd_file);	
			$result = SnapCreator::Util::OS->execute($cmd);
			$msgObj->collect(\@message_a, DEBUG, "Command [$cmd] finished with\nexit code: [$result->{exit_code}]\nstdout: [$result->{stdout}]\nstderr: [$result->{stderr}]");
			$output_string = $result->{stdout};
			if ($result->{exit_code} != 0) 
				{
					if($output_string =~ "The system database directory is empty")
					{
						$msgObj->collect(\@message_a, DEBUG, "No Database found for instance $instance");
						$dbList->{$instance} = ();
						$result->{exit_code} = 0;
						next;
					}
					elsif($output_string =~ "SQL1031N" && $output_string =~ /The database directory cannot be found on the indicated file system/i ) {
						$msgObj->collect(\@message_a, DEBUG, "Fresh Instance $instance. No DB exist");					
						$result->{exit_code} = 0;	
					}
					else
					{
						$msgObj->collect(\@message_a, DEBUG, "Error in Retrieving Database for $instance");
						next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
						$result->{exit_code} = 1;						$result->{stderr} = "Error in Retrieving Database for $instance";
						goto END;
					}
				}
			%db_info_h = $parseSystemDatabaseDirectory->($output_string);
			foreach my $entry (keys %db_info_h )
			{
=head			
				foreach my $innerEntry (keys %{$db_info_h{$entry}})
				{
					if( $innerEntry =~ /Database name/)
					{
						my $database =  $db_info_h{$entry}{$innerEntry};
						$msgObj->collect(\@message_a, INFO, "Discovered Database $database");
						push (@{$dbList->{$instance}}, $database);
					}
				}
=cut
				my $db = $db_info_h{$entry}{"Database name"};
				my $path = $db_info_h{$entry}{"Local database directory"};
				
				if(defined($db ) && defined($path))
				{
					my	$db_info = {
						db => $db,
						path => $path
					};
					push(@{$dbList->{$instance}}, $db_info);
				}
			}
			
		}
	
	}
	END:
	unlink ($cmd_file);
	if (defined isWindows())
	{
		close(CMD_OUTPUT);
		unlink ($cmd_output_file);
		unlink ($temp_output_file);
	}
	$result->{message} = \@message_a;
	return ($result,$dbList);
	
};

$getBaseCmd = sub {
	my $user = shift;
	
	my ($base_cmd, $inidb_cmd, $relocatedb_cmd) = ();

	my $db2_cmd = $config_h{'DB2_CMD'} || "sqllib/db2";
	my $db2inidb_cmd = $config_h{'DB2INIDB_CMD'} || "sqllib/adm/db2inidb";
	my $db2relocatedb_cmd = $config_h{'DB2RELOCATEDB_CMD'} || "sqllib/bin/db2relocatedb";

	if ($uid == 0) {
		$base_cmd = "su - $user -c \"$db2_cmd -tvf %s\"";
		$relocatedb_cmd = "su - $user -c \"$db2relocatedb_cmd -f %s\"";
		$inidb_cmd = "su - $user -c \"$db2inidb_cmd %s as mirror\"";
	} else {
		$base_cmd = "$db2_cmd -tvf %s";
		$relocatedb_cmd = "$db2relocatedb_cmd -f %s";
		$inidb_cmd = "$db2inidb_cmd %s as mirror";
	}

	return ($base_cmd, $inidb_cmd, $relocatedb_cmd);
};

$parseSystemDatabaseDirectory = sub {
	my $db_directory_info = shift;
	my @values = split("\n", $db_directory_info);
	my %result_h = ();
	my $count = ();
	 	
	foreach my $entry (@values) {
		if ($entry =~ m/Database\s+(\d)+\s+entry:/) {
			$count = $1;
		}
		
		if ($entry =~ m/Database alias|Database name|Local database directory|Database directory|Catalog database partition number/) {
			my ($key, $value) = split("=", $entry);
			$key = trim($key); 
			$value = trim($value);

			$result_h{$count}{$key} = $value;
		}
	}

	return %result_h;
};

$parseCurrentInstance = sub {
	my $get_instance_output = shift;
	my @values = split("\n", $get_instance_output);
	my $instance_name = "";

	foreach my $entry (@values) {
		if($entry =~ m/database manager instance/) {
			my ($key, $value) = split(":", $entry);
			$value = trim($value);
			$instance_name = $value;
			last;
		}
	}

	return $instance_name;
};

1;
