###############################################################################
### Copyright NetApp Inc.  All rights reserved                              ### 
### Date: 01/26/2017                                                          ###
### Name: MYSQL.pm                                                          ###
###############################################################################
package MySQL;

our @ISA = qw(SnapCreator::Mod);

use strict;
use warnings;
use diagnostics;
use POSIX;

use SnapCreator::Event qw ( INFO ERROR WARN DEBUG COMMENT ASUP CMD DUMP );
use SnapCreator::Util::Config;
use SnapCreator::Util::Generic qw ( trim isEmpty );
use SnapCreator::Util::OS qw ( isWindows isUnix getUid createTmpFile );
use SnapCreator::Discovery qw ( DEVICE_DATA DEVICE_ONLINE_LOG DEVICE_OFFLINE_LOG DEVICE_TEMP DEVICE_EXTERNAL_FILES DEVICE_CONTROL DEVICE_DUMP DEVICE_DB );

use Net::MySQL;

### constants ###
use constant VERSION => 1.0;
use constant DATABASE => "Database";
use constant PLUGIN_NAME => "MySQL";
use constant RESOURCES => "RESOURCES";
use constant RESOURCES_DELIMITER => ";";
use constant RESOURCE_DELIMITER => ",";
use constant USERNAME_SUFFIX  => "_APP_INSTANCE_USERNAME";
use constant PASSWORD_SUFFIX  => "_APP_INSTANCE_PASSWORD";
use constant PORT_SUFFIX  => "_PORT";
use constant HOST_SUFFIX  => "_HOST";
use constant MASTER_SLAVE_SUFFIX  => "_MASTER_SLAVE";
use constant PURGE_BINARY_LOG_WITH_LATEST_LOG_SUFFIX => "_PURGE_BINARY_LOG_WITH_LATEST_LOG";
#
# static variable
#
BEGIN {
	my $hrefPDO = {};

	sub static { return $hrefPDO }
}

## Hashes ##
my $mysql=();
my %mysqlConfig_h = ();		# Hash of maxdb databases and username/passwords

my %config_h = ();

## Objects ##
my $configObj = new SnapCreator::Util::Config();
my $msgObj = new SnapCreator::Event();

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
	my ($self, $obj) = @_;

        my $result = {
                exit_code => 0,
                stdout => "",
                stderr => "",
        };

        %config_h = ();

        %config_h = %{$obj};

        %mysqlConfig_h = ();

	my @message_a = ();

	my (@resources) = split (RESOURCES_DELIMITER, $config_h{RESOURCES});
	foreach my $resource (@resources) {
		my ($db, $type) = split(RESOURCE_DELIMITER, $resource);
		
		if($type !~ m/DATABASE/i) {
			$result->{exit_code} = -1;
			$msgObj->collect(\@message_a, DEBUG, "$type is not supported ResourceType for MySQL");
			$result->{stderr} = "$type is not supported ResourceType for MySQL";
			goto END;
		}
		my $pwd = $config_h{$db . PASSWORD_SUFFIX};
		my $user = $config_h{$db . USERNAME_SUFFIX};
		my $port = $config_h{$db . PORT_SUFFIX};
		my $host = $config_h{$db . HOST_SUFFIX};
		if(!defined(isEmpty $pwd) || !defined(isEmpty $user) || !defined(isEmpty $port) || !defined(isEmpty $host)) {
			$result->{exit_code} = -1;
			$msgObj->collect(\@message_a, ERROR, "Invalid/NO UserName or PassWord or Port or Host for Database $db");
			$result->{stderr} = "Invalid/NO UserName or PassWord or Port or Host for Database $db";
			goto END;
		}
		$mysqlConfig_h{$db}{'user'} = "$user";
		$mysqlConfig_h{$db}{'pwd'} = "$pwd";
		$mysqlConfig_h{$db}{'port'} = "$port";
		$mysqlConfig_h{$db}{'host'} = "$host";
	}

	#
	# Create HashRef of PDOs
	#
	my $hrefPDO=&static();
	foreach my $db (keys %mysqlConfig_h) {

		#
		# Create DB Handle
		#		
		
		if (!exists($hrefPDO->{$db})) {
			
			eval {
				$mysql = Net::MySQL->new(
					hostname => $mysqlConfig_h{$db}{'host'},   # Default use UNIX socket
					database => $db,
					user     => $mysqlConfig_h{$db}{'user'},
					password => $mysqlConfig_h{$db}{'pwd'},
					port => $mysqlConfig_h{$db}{'port'} 
				);
			};
	
			if ($@) {
				$msgObj->collect(\@message_a, ERROR, "Could not establish connection for DB $db. Error msg[$@]");
				$msgObj->debug($@);
				#Relaxing set exit_code to -1 
				#$result->{exit_code} = -1;
				goto END;
			} else {
				$hrefPDO->{$db} = $mysql; 
			}
 		} else {
			eval {
				$mysql->query(q{SHOW VARIABLES LIKE "%version%"});
			};
			if ($@ || $mysql->is_error) {
				eval {
					$mysql = Net::MySQL->new(
						hostname => $mysqlConfig_h{$db}{'host'},   # Default use UNIX socket
						database => $db,
						user     => $mysqlConfig_h{$db}{'user'},
						password => $mysqlConfig_h{$db}{'pwd'},
						port => $mysqlConfig_h{$db}{'port'} 
					);
				};
		
				if ($@) {
					$msgObj->collect(\@message_a, ERROR, "Could not establish connection for DB $db. Error msg[$@]");
					$msgObj->debug($@);
					#$result->{exit_code} = -1;
					delete($hrefPDO->{$db});
					goto END;
				} else {
					$hrefPDO->{$db} = $mysql; 
				}
				
			}
			
 		}

	}
	
	$self->setPDOs($hrefPDO);

	$msgObj->collect(\@message_a, DEBUG, "$config_h{'APP_NAME'}::setENV finished successfully");
END:
	$result->{message} = \@message_a;
	return $result;
}

sub getPDOs {
	
	my $self = shift;
	
	return $self->{'pdos'};
	
}

sub setPDOs{
	
	my $self = shift;
	$self->{'pdos'} = shift;
	
}

sub quiesce {

	my $self = shift;

        my $result = {
                exit_code => 0,
                stdout => "",
                stderr => "",
        };

	my @message_a = ();
	my $error_code = "0";

	$msgObj->collect(\@message_a, INFO, "Quiescing databases");
	
	my $hrefPDOs = $self->{'pdos'};

	my $hashSize=keys(%{$hrefPDOs});
	if ($hashSize == 0) {
		$msgObj->collect(\@message_a, ERROR, "[mys-00001] Database connection does not exist");
		$result->{exit_code} = 1;
		$result->{stderr} = "[mys-00001] Database connection does not exist. Check DB Name";
		goto END;
	}

	foreach my $db (keys %mysqlConfig_h) {
		
		$msgObj->collect(\@message_a, INFO, "Quiescing database $db");
		$mysql = $hrefPDOs->{$db};

		if (! defined $mysql) {
			$msgObj->collect(\@message_a, ERROR, "[mys-00002] Database connection problem for $db detected");
			$error_code="1";
			$result->{stderr} = "[mys-00002] Database connection problem for $db detected";
			next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
			$result->{exit_code} = 1;
			goto END;
		}

		if ( $mysql->is_error ) {
			$msgObj->collect(\@message_a, ERROR, $mysql->get_error_message);
			$error_code="1";
			$result->{stderr} = $mysql->get_error_message;
			next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
			$result->{exit_code} = 1;
			goto END;
		}

		$msgObj->collect(\@message_a, DEBUG, "Connection to $db successfully established");
		
		# locking tables with read only mode
		my $query = "flush tables with read lock";
	
		$msgObj->collect(\@message_a, DEBUG, "Executing sql command '$query' for database $db");
	
		$mysql->query(q{
			flush tables with read lock
		});
		if ( $mysql->is_error ) {
			$msgObj->collect(\@message_a, ERROR, $mysql->get_error_message);
			$error_code="1";
			$result->{stderr} = $mysql->get_error_message;
			next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
			$result->{exit_code} = 1;
			goto END;
		}	

		# flush the logs for binlog rotate
		$query = "flush logs";
	
		$msgObj->collect(\@message_a, DEBUG, "Executing sql command '$query' for database $db");

		$mysql->query(q{
			flush logs
		});
		if ( $mysql->is_error ) {
			$msgObj->collect(\@message_a, ERROR, $mysql->get_error_message);
			$error_code="1";
			$result->{stderr} = $mysql->get_error_message;
			next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
			$result->{exit_code} = 1;
			goto END;
		}	

		# If Master/Slave is set preform a reset of both
		if (defined(isEmpty $config_h{$db . MASTER_SLAVE_SUFFIX}) && ($config_h{$db . MASTER_SLAVE_SUFFIX} =~ m/^y$/i)) {

			# flush the logs for binlog rotate
			# If PURGE_BINARY_LOG_WITH_LATEST_LOG is set to yes PURGE with latest LOGS
			my $purgeQuery;
			if(defined(isEmpty $config_h{$db . PURGE_BINARY_LOG_WITH_LATEST_LOG_SUFFIX}) && ($config_h{$db . PURGE_BINARY_LOG_WITH_LATEST_LOG_SUFFIX} =~ m/^y$/i)) {

				# Run SHOW MASTER STATUS get the latest bin log file.
				my $binQuery = "SHOW MASTER STATUS";
				$mysql->query($binQuery);
				if ( $mysql->is_error ) {
					$msgObj->collect(\@message_a, ERROR, $mysql->get_error_message);
					$error_code="1";
					next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
					$result->{stderr} = $mysql->get_error_message;
					$result->{exit_code} = 1;
					goto END;
				}

				my $a_record_iterator = $mysql->create_record_iterator;
				while (my $record = $a_record_iterator->each) {
					if($record->[0]){
						$purgeQuery = "PURGE BINARY LOGS TO '".$record->[0]."'";
					}
				}
			}
			else{
				my $purgeTime = strftime( "%Y-%m-%d %H:%M:%S", localtime() );
				$purgeQuery = "PURGE BINARY LOGS BEFORE '".$purgeTime."'";
			}
			$msgObj->collect(\@message_a, INFO, "Executing sql command '$purgeQuery' for database $db");

			$mysql->query($purgeQuery);

			if ( $mysql->is_error ) {
				$msgObj->collect(\@message_a, ERROR, $mysql->get_error_message);
				$error_code="1";
				next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
				$result->{stderr} = $mysql->get_error_message;
				$result->{exit_code} = 1;
				goto END;
			}
		}

		$msgObj->collect(\@message_a, INFO, "Quiescing database $db finished successfully");
	}

    if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/ && $error_code == 1) {
        $msgObj->collect(\@message_a, ERROR, "[mys-00003] Quiescing databases finished with errors");
		$result->{stderr} = "[mys-003] Quiescing database finished with errors";
		$result->{exit_code} = 1;
	} else {
		$msgObj->collect(\@message_a, INFO, "Quiescing databases finished successfully");
	}

	END:
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
	my $error_code = "0";

	$msgObj->collect(\@message_a, INFO, "Unquiescing databases");

	my $hrefPDOs = $self->{'pdos'};

	my $hashSize=keys(%{$hrefPDOs});
	if ($hashSize == 0) {
		$msgObj->collect(\@message_a, ERROR, "[mys-00004] Database connection does not exist");
		$result->{exit_code} = 1;
		goto END;
	}
	
	foreach my $db (keys %mysqlConfig_h) {

		$msgObj->collect(\@message_a, INFO, "Unquiescing database $db");
		$mysql = $hrefPDOs->{$db};

		if (! defined $mysql) {
			$msgObj->collect(\@message_a, ERROR, "[mys-00005] Database connection problem for $db detected");
			$error_code="1";
			next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
			$result->{exit_code} = 1;
			goto END;
		}

		if ( $mysql->is_error ) {
			$msgObj->collect(\@message_a, ERROR, $mysql->get_error_message);
			$error_code="1";
			$result->{stderr} = $mysql->get_error_message;
			next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
			$result->{exit_code} = 1;
			goto END;
		}

		$msgObj->collect(\@message_a, DEBUG, "Connection to $db established successfully");
		
		my $query = "unlock tables";
    
		$msgObj->collect(\@message_a, DEBUG, "Executing sql command '$query' for database $db");
	
		$mysql->query(q{
			unlock tables
		});
		if ( $mysql->is_error ) {
			$msgObj->collect(\@message_a, ERROR, $mysql->get_error_message);
			$error_code="1";
			$result->{stderr} = $mysql->get_error_message;
			next if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/);
			$result->{exit_code} = 1;
			goto END;
		}	

		# disconnect from database
		$msgObj->collect(\@message_a, DEBUG, "Disconnecting from database $db");
		$mysql->close();

		$msgObj->collect(\@message_a, INFO, "Unquiescing database $db finished successfully");
	}

        if ($config_h{'APP_IGNORE_ERROR'} =~ m/^y$|^Y$/ && $error_code == 1) {
                $msgObj->collect(\@message_a, ERROR, "[mys-00006] Unquiescing databases finished with errors");
		$result->{exit_code} = 1;
	} else {
		$msgObj->collect(\@message_a, INFO, "Unquiescing databases finished successfully");
	}
	
	END:
	$result->{message} = \@message_a;
	
	return $result;
}

1;
