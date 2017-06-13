#!/usr/bin/perl
#============================================================#
#                                                            #
# kp                                                         #
# Patent Application Pending                                 #
#                                                            #
# Manages clustered Data ONTAP qos policies by service level #
#                                                            #
# "kp" means kitchen police.  You can't run a restaurant if  #
# overweight patrons are loose in the kitchen, eating        #
# more than they paid for and preventing others from         #
# getting what they paid for.                                #
#                                                            #
# Written by Evan Miller for NetApp, Inc.                    #
# evan.miller@netapp.com
#                                                            #
# Copyright 2014 NetApp, Inc. All rights reserved.           #
# Specifications subject to change without notice.           #
#                                                            #
# NetApp Confidential under NDA only.                        #
#                                                            #
# This software is provided AS IS, with no support or        #
# warranties of any kind, including but not limited to       #
# warranties of merchantability or fitness of any kind,      #
# expressed or implied.                                      #
#                                                            #
# The software is governed by the terms of the clustered     #
# Data ONTAP 8.2.1 license.                                  #
#                                                            #
# tab size = 8                                               #
#                                                            #
#============================================================#
my $myversion=0.7.2;
my $myname="kp";
#
# Perl OO tutorial
# http://perldoc.perl.org/perlootut.html
# $object=class->method();
# $object2=$object->method();
# a method is what an object can do, a subroutine in a class's package
# "->" is an operator specifying a method
# the first argument of the method is to the left of "->", either a class name or an $object
# we can pass additional arguments to the method in ()
# class::version of class, or parent::child or superclass::subclass
# to call a parent method from a child, use $object->SUPER::subclassmethod();
#
# minimum perl version
require 5.10.0;

my $VERSION = '1.1';	# Controls the NetApp Managability SDK release.

use POSIX; 
use NaServer;
use NaElement;
use WFAUtil;
use Data::Dumper;
$Data::Dumper::Indent=1;
use XML::Simple;
use LWP::UserAgent;
use MIME::Base64;
use Win32::Registry;

#my $wfa_util = WFAUtil->new();
# set my environment
$ENV{'PATH'} = '/bin:/usr/bin:/usr/local/bin:.';
umask(077); # create files rw for owner, no permission otherwise
my $Mod;

BEGIN{
    if ($^O ne "linux"){
        $Perlpath = "\"$^X\"";
        ($Mod,$Script,$Ext) = ($Perlpath =~ /^(.*\\)(.*)\.(.*)$/ ) [0..2];
        $Mod = $Mod.'..\lib\Memoize\"';
    }
}
if ($^O ne "linux"){
    use lib $Mod;
    use DB_File;
}
if ($^O eq "linux"){
    #use NDBM_File;
}
my ($basename,$confdir);
 
# internal fixed configuration
$basename="kitchen"; # for kp directory of stuff

if ($^O eq "linux"){
	$confdir="$ENV{'HOME'}/$basename";
}
else{
    my ($Cwd,$Bn,$Ext) = 
      ( Win32::GetFullPathName($0) =~ /^(.*\\)(.*)\.(.*)$/ ) [0..2];
     $confdir = $Cwd."\\..\\".$basename."\\";     
	}

$pidfile = "$confdir/$myname.pid";
$configurationfile = "$confdir/config.txt";
$reportcsv = "$confdir/report.csv";
$reporttxt = "$confdir/report.txt";
$voldbfile = "$confdir/voldb";
$logger = 'logger';
$minimum_cdot_version=820;
$max_policy_groups=3500;

if ($^O ne "linux"){
	$_ =~s'/'\\'g foreach ($pidfile,$configurationfile,$reportcsv,$reporttxt,$voldbfile);
}

# Default CONFIGURATION written to new config file
$nodename = "CLUSTER_MGMT_IP";
$username = "USERNAME";
$password = "PASSWORD";

#my $Credentials = &GetCredential();
#&AddCredential($Credentials);

#foreach (@Credentials){
#	($nodename,$username,$password) = /nodename\s(\d+\.\d+\.\d+\.\d+)\s(.*)\s(.*)/;
#	chomp($nodename);chomp($username);chomp($password);
#	$NODES{$nodename}{'username'}=$username;
#	$NODES{$nodename}{'password'}=$password;
#}

$logtoconsole=0;	# 0=print only to syslog, 1=also log to console
$facility="user";       # syslog facility: mail, user, local0, etc...
$debug=0;               # 1 or more =debug messages, 1=no debug messages
$maxcpupercent=90;	# don't run if CPU utilization exceeds this percent
$maxcpuduration=60;	# measured over a period of seconds
$defaultservicelevel="unlimited"; # if a volume cannot be classified otherwise
$prefer="faster"; # faster or slower service level if multiple definition matches
$defaultenforcement="effective"; # thin (used capacity), thick (committed), or effective (used+space saved)
$defaultthinmin=10; # minimum percentage of volume usage to calculate minimum QoS throttle
$defaultheadroom=1; # minimum headroom for growth
$defaultminiops=75; # minimum IOPS for a volume to simulate a single disk drive
$SL{'unlimited'}{'iopsthrottle'}=0;
$SL{'extreme'}{'iopsthrottle'}=4096;
$SL{'extreme'}{'disktypes'}='SSD';
$SL{'extreme'}{'hybrid'}='false';
$SL{'extreme'}{'miniops'}=400;
$SL{'extreme'}{'headroom'}=5;
$SL{'highread'}{'iopsthrottle'}=2048;
$SL{'highread'}{'hybrid'}='false';
$SL{'highread'}{'disktypes'}='FC|FSAS';
$SL{'highread'}{'miniops'}=175;
$SL{'highread'}{'diskrpm'}=10000;
$SL{'highread'}{'maxrpm'}=15000;
$SL{'highread'}{'agginclude'}='appxyz|app123'; 
$SL{'highread'}{'volinclude'}='appxyz|app123'; 
$SL{'highread'}{'headroom'}=2;
$SL{'hiupdate'}{'iopsthrottle'}=2048;
$SL{'hiupdate'}{'hybrid'}='true';
$SL{'hiupdate'}{'disktypes'}='FC|FSAS';
$SL{'hiupdate'}{'hybrid'}="true";
$SL{'hiupdate'}{'miniops'}=175;
$SL{'hiupdate'}{'agginclude'}='oracle|sql'; 
$SL{'hiupdate'}{'headroom'}=2;
$SL{'perform'}{'iopsthrottle'}=1024;
$SL{'perform'}{'disktypes'}='FC|LUN|FSAS';
$SL{'perform'}{'hybrid'}='false';
$SL{'perform'}{'diskrpm'}=10000;
$SL{'perform'}{'maxrpm'}=10000;
$SL{'perform'}{'miniops'}=140;
$SL{'perform'}{'headroom'}=2;
$SL{'value'}{'iopsthrottle'}=256;
$SL{'value'}{'hybrid'}='false';
$SL{'value'}{'disktypes'}='ATA|SCSI|BSAS';
$SL{'value'}{'volexclude'}='back|mirror|vault';
$SL{'value'}{'thinmin'}=20;
$SL{'capacity'}{'iopsthrottle'}=64;
$SL{'capacity'}{'hybrid'}='false';
$SL{'capacity'}{'disktypes'}='ATA|SCSI|unknown';
$SL{'capacity'}{'volinclude'}='archive|retention|back|mirror|vault'; 
$SL{'capacity'}{'enforce'}='thick';

# set signal handler
$SIG{'INT'}='sighandler';
$SIG{'QUIT'}='sighandler';
$SIG{'TERM'}='sighandler';

# BEGIN MAIN PROGRAM
print "$myname version $myversion\n";
print "Copyright (c) 2014 NetApp, Inc.  All rights reserved.\n";
print "Patent Pending.  Available only under NDA.\n";
print "\nThis software is provided AS IS, with no support or\nwarranties of any kind, including but not limited to\nwarranties of merchantability or fitness of any kind,\nexpressed or implied.\n";
print "\nThis software is governed by the terms of the\nclustered Data ONTAP 8.2.1 license.\n\n";


if ($^O eq "linux"){
# check if I am already running
if (open(FILE,"<$pidfile")) {
  $pid=<FILE>; close(FILE); chop $pid;
  @ps=`ps -e`;
  if (grep(/$myname/,grep(/$pid/,@ps))) {
    die "$myname is already running! Exiting.\n";
  }
}
}
#end;

&read_configuration;

my $Credentials = &GetCredential();
#&AddCredential($Credentials);

foreach (@$Credentials){
	($nodename,$username,$password) = /nodename\s(\d+\.\d+\.\d+\.\d+)\s(.*)\s(.*)/;
	chomp($nodename);chomp($username);chomp($password);
	$NODES{$nodename}{'username'}=$username;
	$NODES{$nodename}{'password'}=$password;
}

if ($^O eq "linux"){
	system("cp $0 $confdir/$myname.$$") if ($debug); # copy myself to a backup copy
}
else{
	open PROCESS , ">$confdir\\ProcessId";
	print PROCESS $$;
	system("copy $0 $confdir\\$myname.$$") if ($debug); # copy myself to a backup copy
}
&initnodes;
print "Starting background daemon. Monitor syslog for messages.\n";


if ($^O eq "linux"){
#fork to daemon
unless(fork) {          #this is the child
  unless(fork) {        #this is the child's child
    sleep 1 until getppid == 1;         # wait until pid 1 is my parent
    setpgrp;          #disconnect from forking shell process group
    if (open(PIDFILE,">$pidfile")) {  # record PID
      print PIDFILE "$$\n";
      close(PIDFILE);
    }

    &logmsg("Daemon started with PID $$");
    &read_volume_db; # for recording trend metrics

    while ( 1 ) { #forever until killed
      &checkchangedconfiguration();
      &startcounters;
      foreach $nodename (keys %NODES) {
        if ( &cpuidle ) { 
          if ( &getvolumes ) {
            &matchsl;
            &getpg;
            &getwl;
            &setpolicies;
          } # if getvolumes
        } # if cpuidle
      } # foreach nodename
      &endcounters;
      #&writereport;
    } # while forever

  } # unless fork child's child
  exit 0;
} # unless fork child
}#Linux Block
else{
    &logmsg("Daemon started with PID $$");
    &read_volume_db; # for recording trend metrics

#    while ( 1 ) { #forever until killed
      &checkchangedconfiguration();
      &startcounters;
      foreach $nodename (keys %NODES) {
        if ( &cpuidle ) { 
          if ( &getvolumes ) {
            &matchsl;
            &getpg;
            &getwl;
            &setpolicies;
          } # if getvolumes
        } # if cpuidle
      } # foreach nodename
      &endcounters;
      #&writereport;
#    } # while forever
#  exit 0;
}

wait; # parent reaps first child quickly
&logmsg("Daemon starting. Logging to $facility syslog. PID in $pidfile");
#exit;
# END MAIN PROGRAM

sub endcounters {
  &debug("sub endcounters");
  foreach $nodename (keys %NODES) {
    $username=$NODES{$nodename}{'username'};
    $password=$NODES{$nodename}{'password'};
    &debug("Ending $nodename counters") if ($debug>1);
    my $s = NaServer->new($nodename, 1,1);
    $s->set_admin_user($username, $password);
    my $in = NaElement->new("perf-object-get-instances");
    $in->child_add_string("objectname",'workload');          
    my $counters = NaElement->new("counters"); 
    $counters -> child_add_string('counter', 'ops');
    $counters -> child_add_string('counter', 'read_ops');
    $counters -> child_add_string('counter', 'write_ops');
    $counters -> child_add_string('counter', 'latency');
    $counters -> child_add_string('counter', 'read_latency');
    $counters -> child_add_string('counter', 'write_latency');
    $in->child_add($counters);
    my $instances = NaElement->new("instances"); 
    $wlcnt= scalar keys %{ $VOLDB{$nodename} };
    $scount = scalar keys % { $SCOUNTERS{$nodename} };
    if ($scount < 1) {
      &debug("Start counters not collected yet");
      return 1;
    }
    &debug("Get ending IOPS for $wlcnt volumes on $nodename") if ($debug>1);
    $loopcnt=0;
    $instancecnt=0;
    foreach $volname ( sort keys %{ $VOLDB{$nodename} } ) { # for all volumes in this node

      $i++; $loopcnt++;
      $workload_name=$VOLDB{$nodename}{$volname}{'workload-name'};

      if ($workload_name) { # add the instance to the counter request list
        $instances->child_add_string('instance',$workload_name);
        $instancecnt++;
        &debug("Add $nodename workload instance $workload_name for volume $volname") if ($debug>1);
      }
    
      if ($instancecnt && (($i>=499) || ($loopcnt>=$wlcnt))) { # get the counters every 500 workloads or until done
        $i=0; 
        $instancecnt=0;
        $in->child_add($instances);
        &debug($in->sprintf()) if ($debug>2);
        sleep $debug if ($debug);
        my $out = $s->invoke_elem($in);
        $timenow = time;
        if($out->results_status() eq "failed") {
          &error("Cannot get IOPS counters from $nodename: ".$out->results_reason() );
          sleep $debug if ($debug);
          return 0;	
        } #if failed
        my $time_stamp = $out->child_get_string( 'timestamp' );
        my $instances_list = $out->child_get( 'instances' );
        my @instances = $instances_list->children_get();
        foreach $inst (@instances) {
          my $inst_name=$inst->child_get_string('name');
          my $counters_list=$inst->child_get('counters');
          my @counters=$counters_list->children_get();
          foreach $counter (@counters) {
            my $counter_name=$counter->child_get_string('name');
            my $counter_value=$counter->child_get_string('value');
            &debug("Instance $inst_name counter $counter_name value $counter_value at time $time_stamp") if ($debug>2);
            if ($counter_name=~/^read_ops/) {
              ($inst1,$time1,$value1)=split(/:/,$SCOUNTERS{$nodename}{$volname}{'read_ops'});
              if (($inst1 eq $inst_name) && ($time1) && ($time_stamp)) {
                $stattime=$time_stamp - $time1;
                $c_read_ops=$counter_value - $value1;
              }
            }
            if ($counter_name=~/^write_ops/) {
              ($inst1,$time1,$value1)=split(/:/,$SCOUNTERS{$nodename}{$volname}{'write_ops'});
              if (($inst1 eq $inst_name) && ($time1) && ($time_stamp)) {
                $stattime=$time_stamp - $time1;
                $c_write_ops=$counter_value - $value1;
              }
            }
            if ($counter_name=~/^ops/) {
              ($inst1,$time1,$value1)=split(/:/,$SCOUNTERS{$nodename}{$volname}{'ops'});
              if (($inst1 eq $inst_name) && ($time1) && ($time_stamp)) {
                $stattime=$time_stamp - $time1;
                $c_ops=$counter_value - $value1;
              }
            }
            if ($counter_name=~/^latency/) {
              ($inst1,$time1,$value1)=split(/:/,$SCOUNTERS{$nodename}{$volname}{'latency'});
              if (($inst1 eq $inst_name) && ($time1) && ($time_stamp)) {
                $stattime=$time_stamp - $time1;
                $c_latency=$counter_value - $value1;
              }
            }
            if ($counter_name=~/^read_latency/) {
              ($inst1,$time1,$value1)=split(/:/,$SCOUNTERS{$nodename}{$volname}{'read_latency'});
              if (($inst1 eq $inst_name) && ($time1) && ($time_stamp)) {
                $stattime=$time_stamp - $time1;
                $c_read_latency=$counter_value - $value1;
              }
            }
            if ($counter_name=~/^write_latency/) {
              ($inst1,$time1,$value1)=split(/:/,$SCOUNTERS{$nodename}{$volname}{'write_latency'});
              if (($inst1 eq $inst_name) && ($time1) && ($time_stamp)) {
                $stattime=$time_stamp - $time1;
                $c_write_latency=$counter_value - $value1;
              }
            }
          } # foreach counter

          ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($timenow);
          &debug("$year:$mon:$mday,$hour:$min:$sec,$wday,$yday,$isdst");
          $VOLDB{$nodename}{$volname}{'statistics'}{$timenow}="inst_name=$inst_name:stattime=$timenow:c_ops=$c_ops:c_read_ops=$c_read_ops:c_write_ops=$c_write_ops:c_latency=$c_latency:c_read_latency=$c_read_latency:c_write_latency=$c_write_latency";
          &debug("VOLDB statistics $VOLDB{$nodename}{$volname}{'statistics'}{$timenow}");
        } #foreach instance
       
        my $instances = NaElement->new("instances"); # new next 500 instances
      } # if 500 instances added or done

      delete $SCOUNTERS{$nodename}{$volname}; # delete for next collection cycle

    } # foreach volname


    delete $SCOUNTERS{$nodename}; # delete for next collection cycle
    sleep $debug if ($debug);

  } # foreach nodename

  return 1;
}

sub startcounters {
  &debug("sub startcounters");
  foreach $nodename (keys %NODES) {
    $username=$NODES{$nodename}{'username'};
    $password=$NODES{$nodename}{'password'};
    &debug("Starting $nodename counters") if ($debug>1);
    my $s = NaServer->new($nodename, 1,1);
    $s->set_admin_user($username, $password);
    my $in = NaElement->new("perf-object-get-instances");
    $in->child_add_string("objectname",'workload');          
    my $counters = NaElement->new("counters"); 
    $counters -> child_add_string('counter', 'ops');
    $counters -> child_add_string('counter', 'read_ops');
    $counters -> child_add_string('counter', 'write_ops');
    $counters -> child_add_string('counter', 'latency');
    $counters -> child_add_string('counter', 'read_latency');
    $counters -> child_add_string('counter', 'write_latency');
    $in->child_add($counters);
    my $instances = NaElement->new("instances"); 
    $wlcnt= scalar keys %{ $VOLDB{$nodename} };
    &debug("Get IOPS for $wlcnt volumes on $nodename") if ($debug>1);
    $loopcnt=0;
    foreach $volname ( sort keys %{ $VOLDB{$nodename} } ) { # for all volumes in this node
      $i++; $loopcnt++;
      $workload_name=$VOLDB{$nodename}{$volname}{'workload-name'};
      if ($workload_name) { # add the instance to the counter request list
        $instances->child_add_string('instance',$workload_name);
        &debug("Add $nodename workload instance $workload_name for volume $volname") if ($debug>1);
      }
      if (($i==500) || ($loopcnt==$wlcnt)) { # get the counters every 500 workloads or until done
        $i=0; 
        $in->child_add($instances);
        &debug($in->sprintf()) if ($debug>2);
        sleep $debug if ($debug);
        my $out = $s->invoke_elem($in);
        if($out->results_status() eq "failed") {
          &error("Cannot get IOPS counters from $nodename: ".$out->results_reason() );
          sleep $debug if ($debug);
          return 0;	
        } #if failed
        my $time_stamp = $out->child_get_string( 'timestamp' );
        my $instances_list = $out->child_get( 'instances' );
        my @instances = $instances_list->children_get();
        foreach $inst (@instances) {
          my $inst_name=$inst->child_get_string('name');
          my $counters_list=$inst->child_get('counters');
          my @counters=$counters_list->children_get();
          foreach $counter (@counters) {
            my $counter_name=$counter->child_get_string('name');
            my $counter_value=$counter->child_get_string('value');
            &debug("Instance $inst_name counter $counter_name value $counter_value at time $time_stamp") if ($debug>2);
            if ($counter_name=~/^read_ops/) {
              $SCOUNTERS{$nodename}{$volname}{'read_ops'}="$inst_name:$time_stamp:$counter_value";
            }
            if ($counter_name=~/^write_ops/) {
              $SCOUNTERS{$nodename}{$volname}{'write_ops'}="$inst_name:$time_stamp:$counter_value";
            }
            if ($counter_name=~/^ops/) {
              $SCOUNTERS{$nodename}{$volname}{'ops'}="$inst_name:$time_stamp:$counter_value";
            }
            if ($counter_name=~/^latency/) {
              $SCOUNTERS{$nodename}{$volname}{'latency'}="$inst_name:$time_stamp:$counter_value";
            }
            if ($counter_name=~/^read_latency/) {
              $SCOUNTERS{$nodename}{$volname}{'read_latency'}="$inst_name:$time_stamp:$counter_value";
            }
            if ($counter_name=~/^write_latency/) {
              $SCOUNTERS{$nodename}{$volname}{'write_latency'}="$inst_name:$time_stamp:$counter_value";
            }
          } # foreach counter
        } #foreach instance
       
        my $instances = NaElement->new("instances"); # new next 500 instances
      } # if 500 instances added or done
    }
    sleep $debug if ($debug);

  } # foreach nodename

  return 1;

} # sub startcounters

sub initnodes ()
{
  &debug("sub initnodes()");

  foreach $nodename (keys %NODES) {
    &debug("$nodename username $NODES{$nodename}{'username'}");
    sleep $debug if ($debug);
    #if (!&system_get_version) {
    #  &shut_down("Version query of $nodename failed. Check syslog for errors");
    #} # if system_get_version
    $NODES{$nodename}{'online'}=1;
    if (!&get_cpus) {
      &shut_down("CPU Query of $nodename failed. Check syslog for errors");
    } # if get_cpus
  }

} # sub initnodes

sub writereport ()
{
  &debug("sub writereport()");

  open(CSVFILE,">$reportcsv") || &shut_down("Cannot write $reportcsv : $!");
  open(TXTFILE,">$reporttxt") || &shut_down("Cannot write $reporttxt : $!");
  #print CSVFILE "Cluster Name,Service Level,Usable GB,GB Used,GB Free,Effective Used,Effective Free,IOPS Budget Used,IOPS Budget Remaining,Days GB Remaining,Days IOPS Remaining\n";
  #print TXTFILE "Cluster Name,Service Level,Usable GB,GB Used,GB Free,Effective Used,Effective Free,IOPS Budget Used,IOPS Budget Remaining,Days GB Remaining,Days IOPS Remaining\n";
  print TXTFILE "Cluster,Volume,Timestamp,Ops,Read Ops,Write Ops,Latency,Read Latency,Write Latency\n";
  print CSVFILE "Cluster,Volume,Timestamp,Ops,Read Ops,Write Ops,Latency,Read Latency,Write Latency\n";
  foreach $nodename (sort keys %NODES) {
    foreach $volname ( sort keys %{ $VOLDB{$nodename} } ) {
      foreach $stattime ( sort keys %{ $VOLDB{$nodename}{$volname}{'statistics'} } ) {
        &debug("$VOLDB{$nodename}{$volname}{'statistics'}{$stattime}");
        @statvalues=split(/:/,$VOLDB{$nodename}{$volname}{'statistics'}{$stattime});
        foreach $statpair (@statvalues) {
	  #($major_version, $minor_version, $sub_version) = ($version =~ /(\d+).(\d+).(\d*)/);
          &debug("$statpair");
          ($inst_name)=($statpair =~ /inst_name=(\d+)/);
          ($c_ops)=($statpair =~ /c_ops=(\d+)/);
          ($c_read_ops)=($statpair =~ /c_read_ops=(\d+)/);
          ($c_write_ops)=($statpair =~ /c_write_ops=(\d+)/);
          ($c_latency)=($statpair =~ /c_latency=(\d+)/);
          ($c_read_latency)=($statpair =~ /c_read_latency=(\d+)/);
          ($c_write_latency)=($statpair =~ /c_write_latency=(\d+)/);
        }
        print CSVFILE "$nodename,$volname,$stattime,$c_ops,$c_read_ops,$c_write_ops,$c_latency,$c_read_latency,$c_write_latency\n";
        print TXTFILE "$nodename,$volname,$stattime,$c_ops,$c_read_ops,$c_write_ops,$c_latency,$c_read_latency,$c_write_latency\n";
      } # foreach stattime
    } # foreach volname
  } # foreach nodename
  close(CSVFILE);
  close(TXTFILE);
  &debug("Report written to $reportcsv and $reporttxt");
} # sub writereport

sub system_get_version ()
{
	my $major_version=0;
        my $minor_version=0;
        my $sub_version=0;
	&debug("sub system_get_version()");

        $username=$NODES{$nodename}{'username'};
        $password=$NODES{$nodename}{'password'};
	my $s = NaServer->new($nodename, 1, 1);
	$s->set_admin_user($username, $password);
	my $output = $s->invoke( "system-get-version" );

	if ($output->results_errno != 0) {
		my $result = $output->results_reason();
		&error("Invoke API system-get-version failed: $result");
                sleep $debug;
                return 0;
	}
	else {
		$versionstring = $output->child_get_string( "version" );
		$version=$versionstring;
	}

	&logmsg($versionstring);
	&shut_down("Not cluster mode") unless ($clustermode) = ($version =~ /cluster/i);
	($major_version, $minor_version, $sub_version) = ($version =~ /(\d+).(\d+).(\d*)/);
	&debug("Clustered Data ONTAP $major_version.$minor_version.$sub_version\n") if ($debug>1);
        $sub_version=0 if (($sub_version < 1) || ($sub_version eq ""));
	my $current_cdot_version="$major_version$minor_version$sub_version";
        $NODES{$nodename}{'version'}=$current_cdot_version;
	&debug("$minimum_cdot_version > $current_cdot_version ?") if ($debug>1);
	&shut_down("Clustered Data ONTAP version $major_version.$minor_version.$sub_version is too old") if ( $minimum_cdot_version > $current_cdot_version );
  sleep $debug if ($debug);
  return 1;
}

sub getaggr()
{
  &debug("sub getaggr");
  my $next_tag = "";
  my $done = 0;
  my $username=$NODES{$nodename}{'username'};
  my $password=$NODES{$nodename}{'password'};

  my $s = NaServer->new($nodename, 1, 1);
  $s->set_admin_user($username, $password);

  while ( ! $done ) {
    my $in = NaElement->new("aggr-get-iter");
    if($next_tag) {
      $in->child_add_string("tag",$next_tag);
      &debug("Got next tag") if ($debug>2);
    } #if next tag
    &debug($in->sprintf()) if ($debug>2);
    sleep $debug if ($debug>2);
    my $out = $s->invoke_elem($in);
    if($out->results_status() eq "failed") {
      &error("Cannot get list of aggregates from $nodename: ".$out->results_reason() );
      sleep $debug;
      return 0;
    } # if failed
    my $aggr_attributes = $out->child_get("attributes-list");
    my $num_records = $out->child_get_int("num-records");
    &debug("$num_records records returned from aggr-get-iter API call") if ($debug>2);
    return 1 if (($num_records==0) || (!$num_records));;
    $next_tag = $out->child_get_string("next-tag");
    if($next_tag) {
      &debug("Returned next tag") if ($debug>2);
    } else { 
      $done=1; 
    }

    my @result = $aggr_attributes->children_get();
    foreach $aggr_attr (@result) {
      $aggregate_name = $aggr_attr->child_get_string("aggregate-name");
      $aggr_raid_attributes = $aggr_attr->child_get("aggr-raid-attributes");
      $has_local_root = $aggr_raid_attributes->child_get_int("has-local-root");
      $has_partner_root = $aggr_raid_attributes->child_get_int("has-partner-root");
      $is_hybrid = $aggr_raid_attributes->child_get_int("is-hybrid");
      &debug("aggr $aggregate_name root $has_local_root partner_root $has_partner_root hybrid $is_hybrid") if ($debug>1);
      $AGG{$nodename}{$aggregate_name}{'has-local-root'}=$has_local_root;
      $AGG{$nodename}{$aggregate_name}{'has-partner-root'}=$has_partner_root;
      $AGG{$nodename}{$aggregate_name}{'is-hybrid'}=$is_hybrid;
      if (!$AGG{$nodename}{$aggregate_name}{'effective-disk-type'}) {
        if (!&getstorage) {
          return 0;
        }
      }
    } # foreach attr
  } # while not done
  sleep $debug if ($debug);
  return 1;
} #sub get aggr
#

sub getstorage()
{
  &debug("sub getstorage");
  my $next_tag = "";
  my $done = 0;
  my $username=$NODES{$nodename}{'username'};
  my $password=$NODES{$nodename}{'password'};

  my $s = NaServer->new($nodename, 1, 1);
  $s->set_admin_user($username, $password);

  while ( ! $done ) {
    my $in = NaElement->new("storage-disk-get-iter");
    if($next_tag) {
      $in->child_add_string("tag",$next_tag);
      &debug("Get next tag") if ($debug>1);
    }
    &debug($in->sprintf()) if ($debug>2);
    sleep $debug if ($debug>1);
    my $out = $s->invoke_elem($in);
    if($out->results_status() eq "failed") {
      &error("Cannot get list of disks from $nodename: ".$out->results_reason() );
      sleep $debug;
      return 0;
    }
    my $storage_attributes = $out->child_get("attributes-list");
    my $num_records = $out->child_get_int("num-records");
    &debug("$num_records records returned from storage-disk-get-iter API call") if ($debug>2);
    return 1 if (($num_records==0) || (!$num_records));;
    $next_tag = $out->child_get_string("next-tag");
    if($next_tag) {
      &debug("Returned next tag") if ($debug>1);
    } else { 
      $done=1; 
    }

    my @result = $storage_attributes->children_get();
    foreach $storage_attr (@result){
      # get raid group info
      $disk_raid_info = $storage_attr->child_get("disk-raid-info");
      $disk_type = $disk_raid_info->child_get_string("effective-disk-type");
      $disk_rpm = $disk_raid_info->child_get_string("effective-rpm");
      $active_node_name = $disk_raid_info->child_get_string("active-node-name");
      $container_type = $disk_raid_info->child_get_string("container-type");
      # get aggregate info
      $disk_aggregate_info = $disk_raid_info->child_get("disk-aggregate-info");
      $aggregate_name = $disk_aggregate_info->child_get_string("aggregate-name") if ($disk_aggregate_info);
      $raid_group_name = $disk_aggregate_info->child_get_string("raid-group-name") if ($disk_aggregate_info);
      if ($container_type eq "aggregate") {
        &debug("Disk type $disk_type $disk_rpm $active_node_name $container_type $aggregate_name $raid_group_name") if ($debug>1);
        $AGG{$nodename}{$aggregate_name}{'effective-disk-type'}=$disk_type;
        $AGG{$nodename}{$aggregate_name}{'effective-rpm'}=$disk_rpm;
        $AGG{$nodename}{$aggregate_name}{'active-node-name'}=$active_node_name;
      } # if aggregate
   } # foreach

  } # while not done
  sleep $debug if ($debug);
  return 1;
} #sub getstorage
#

sub matchsl()
{
  &debug("sub matchsl");

  %MULTISL=();
  undef %MULTISL;

  foreach $volname (keys %VOL) {
    next if ($volname eq "");

    #&logmsg("IS-HYBRID $VOL{$volname}{'is-hybrid'}");

    foreach $level (keys %SL) {
      $diskmatch=0; $rpmmatch=0; $hybridmatch=0; $volincludematch=0; $volexcludematch=0; $aggincludematch=0; $aggexcludematch=0;

      # disk type match?
      if ($SL{$level}{'disktypes'}) { # if disk type specified for this SL
        &debug("$VOL{$volname}{'disk-type'} matches $SL{$level}{'disktypes'} ?") if ($debug>2);

        if ($VOL{$volname}{'disk-type'}=~/$SL{$level}{'disktypes'}/) {
          &debug("$volname disk-type $VOL{$volname}{'disk-type'} matched $SL{$level}{'disktypes'}");
          $diskmatch++;
        } elsif (!$SL{$level}{'disktypes'}) {
          $diskmatch++;
        } # disk match?

      } else { # disk type is not specified for this SL
        $diskmatch=1; # match this SL
      }

      if ($SL{$level}{'diskrpm'}) { # if disk-rpm is specified for this SL

        &debug("$volname disk-rpm $VOL{$volname}{'disk-rpm'} >= $level $SL{$level}{'diskrpm'} ?") if ($debug>2);

        # disk rpm match?
        if ($VOL{$volname}{'disk-rpm'} >= $SL{$level}{'diskrpm'}) {
          &debug("$volname disk-rpm $VOL{$volname}{'disk-rpm'} <= $level maxrpm $SL{$level}{'maxrpm'} ? ") if ($debug>2);
          if ($VOL{$volname}{'disk-rpm'} <= $SL{$level}{'maxrpm'}) {
            $rpmmatch++;
          } elsif ($SL{$level}{'maxrpm'} <= $SL{$level}{'diskrpm'}) {
            &debug("$level maxrpm $SL{$level}{'maxrpm'} <= $level diskrpm $SL{$level}{'diskrpm'}") if ($debug>1);
            $rpmmatch++;
          }
        } elsif (!$SL{$level}{'diskrpm'}) {
          $rpmmatch++;
        } # rpm match

      } else { # diskrpm was not specified for this SL
        $rpmmatch=1; # not specified for this SL
      } # if disk-rpm is specified for this SL

      if ($SL{$level}{'hybrid'}) { # if hybrid was specified for this SL

        if (($VOL{$volname}{'is-hybrid'} =~ /true/i) && ($SL{$level}{'hybrid'} =~ /true/i ))  {
          $hybridmatch=1;
          &debug("$volname is-hybrid is $VOL{$volname}{'is-hybrid'} and $level hybrid is $SL{$level}{'hybrid'}") if ($debug>1);
        } elsif (($VOL{$volname}{'is-hybrid'} =~ /false/i) && ($SL{$level}{'hybrid'} =~ /false/i ))  {
          $hybridmatch=1;
          &debug("$volname is-hybrid is $VOL{$volname}{'is-hybrid'} and $level hybrid is $SL{$level}{'hybrid'}") if ($debug>1);
        }
      } else {
        $hybridmatch=1;
      }

      &debug("$level volinclude is /$SL{$level}{'volinclude'}/") if ($debug>2);

      if ( ($SL{$level}{'volinclude'}) || ($SL{$level}{'volinclude'} ne "") ) {
        &debug("$volname=~ /$SL{$level}{'volinclude'}/ ?") if ($debug>1);
        if ( $volname=~ /$SL{$level}{'volinclude'}/ ) {
          &debug("$volname matched $level $SL{$level}{'volinclude'}");
          $volincludematch=1;
        } else {
          &debug("$level volinclude $SL{$level}{'volinclude'} no match in $volname") if ($debug>1);
        }
      } else {
        $volincludematch=1;
      } # volinclude match

      if ( ($SL{$level}{'volexclude'}) || ($SL{$level}{'volexclude'} ne "") ) {
        &debug("$volname!~ /$SL{$level}{'volexclude'}/ ?") if ($debug>1);
        if ( $volname!~ /$SL{$level}{'volexclude'}/ ) {
          &debug("$volname no match in $level $SL{$level}{'volexclude'}") if ($debug>1);
          $volexcludematch=1;
        } else {
          &debug("$level volexclude $SL{$level}{'volexclude'} match in $volname");
        }
      } else {
        $volexcludematch=1;
      } # volexclude match

      $aggname=$VOL{$volname}{'containing-aggregate-name'};

      &debug("$level agginclude is /$SL{$level}{'agginclude'}/") if ($debug>2);

      if (($SL{$level}{'agginclude'}) || ($SL{$level}{'agginclude'} ne "") ) {
        &debug("$aggname=~ /$SL{$level}{'agginclude'}/ ?") if ($debug>1);
        if ( $aggname=~ /$SL{$level}{'agginclude'}/ ) {
          &debug("$aggname matched $level $SL{$level}{'agginclude'}");
          $aggincludematch=1;
        } else {
          &debug("$level agginclude $SL{$level}{'agginclude'} no match in $aggname") if ($debug>1);
        }
      } else {
        $aggincludematch=1;
      } # agginclude match

      if (($SL{$level}{'aggexclude'}) || ($SL{$level}{'aggexclude'} ne "") ) {
        &debug("$aggname!~ /$SL{$level}{'aggexclude'}/ ?") if ($debug>1);
        if ( $aggname!~ /$SL{$level}{'aggexclude'}/ ) {
          &debug("$aggname no match in $level $SL{$level}{'aggexclude'}") if ($debug>1);
          $aggexcludematch=1;
        } else {
          &debug("$level aggexclude $SL{$level}{'aggexclude'} match in $aggname");
        }
      } else {
        $aggexcludematch=1;
      }

      if (($diskmatch) && ($rpmmatch) && ($hybridmatch) && ($volincludematch) && ($volexcludematch) && ($aggincludematch) && ($aggexcludematch)) {
        $VOL{$volname}{'service-level'}=$level;
        $MULTISL{$volname}{$level}=1;
        &debug("$volname matched Service Level $level") if ($debug>1);
      } else {
        delete $MULTISL{$volname}{$level};
        &debug("$volname did not match Service Level $level") if ($debug>2);
      }

    } #foreach level

    # check for multiple matches to service levels for this volume
    for $level ( keys %{ $MULTISL{$volname} } ) {
      if ( $prefer=~ /faster/ ) {
        if ( $SL{$level}{'iopsthrottle'} > $SL{$lastsl}{'iopsthrottle'} ) {
           $VOL{$volname}{'service-level'}=$level;
           &debug("Set $volname to the faster Service Level $level")if ($debug>1);
        }
      } else {
        if ( $SL{$level}{'iopsthrottle'} < $SL{$lastsl}{'iopsthrottle'} ) {
           $VOL{$volname}{'service-level'}=$level;
           &debug("Set $volname to the slower Service Level $level")if ($debug>1);
        }
      }
      $lastsl=$level;
    }

    # if there were no matches for this volume, set it to the default
    if (!$VOL{$volname}{'service-level'}) {
      $VOL{$volname}{'service-level'}=$defaultservicelevel;
      &debug("Set $volname to the default Service Level $defaultservicelevel")if ($debug>1);
    }

    &debug("Service Level for $volname is $VOL{$volname}{'service-level'}");
  } #foreach volname

  sleep $debug if ($debug);
  return 1;
} # sub matchsl

sub getpg()
{
  &debug("sub getpg");
  my $next_tag = "";
  my $done = 0;
  my $username=$NODES{$nodename}{'username'};
  my $password=$NODES{$nodename}{'password'};

  foreach $pgname (keys %PG) {
    delete $PG{$pgname}; #throw away prior values because they likely changed
  }
  %PG=();
  undef %PG;

  my $s = NaServer->new($nodename, 1, 1);
  $s->set_admin_user($username, $password);

  $pgcnt=0;
  $userpgcnt=0;
  while ( ! $done ) {
    my $in = NaElement->new("qos-policy-group-get-iter");
    if($next_tag) {
      $in->child_add_string("tag",$next_tag);
      &debug("Get next tag") if ($debug>2);
    }
    &debug($in->sprintf()) if ($debug>2);
    sleep $debug if ($debug>1);
    my $out = $s->invoke_elem($in);
    if($out->results_status() eq "failed") {
      &error("Cannot get list of QoS policies from $nodename:".$out->results_reason() );
      sleep $debug;
      return 0;
    }
    my $num_records = $out->child_get_int("num-records");
    &debug("$num_records records returned from qos-workload-get-iter API call") if ($debug>2);
    return 1 if (($num_records==0) || (!$num_records));;
    $next_tag = $out->child_get_string("next-tag");
    if($next_tag) {
      &debug("Next tag returned") if ($debug>2);
    } else {
      $done=1; 
    }
    my $attributes_list = $out->child_get("attributes-list");
    my @result = $attributes_list->children_get();

    foreach $attributes (@result){
      $pgcnt++;
      my $max_throughput = $attributes->child_get_string('max-throughput');
      my $num_workloads = $attributes->child_get_int('num-workloads');
      my $policy_group_class = $attributes->child_get_string('policy-group-class');
      my $vserver = $attributes->child_get_string('vserver');
      my $pgid = $attributes->child_get_int('pgid');
      my $policy_group = $attributes->child_get_string('policy-group');
      &debug("policy-group $policy_group pgid $pgid max-throughput $max_throughput class $policy_group_class vserver $vserver num-workloads $num_workloads") if ($debug>1);
      if ($policy_group =~ /^_/ ) {
        &debug("Skipping system policy group $policy_group") if ($debug>1);
        next;
      }
      if ($policy_group) {
        $PG{$policy_group}{'max-throughput'}=$max_throughput;
        $PG{$policy_group}{'pgid'}=$pgid;
        $PG{$policy_group}{'policy-group-class'}=$policy_group_class;
        $PG{$policy_group}{'vserver'}=$vserver;
        $PG{$policy_group}{'num-workloads'}=$num_workloads;
        if ($policy_group_class =~ /user/i ) { # a user defined policy group
          $userpgcnt++
        } else { # not a user defined policy group
          &debug("Policy Group $policy_group with class $policy_group_class is not user defined.") if ($debug >1);
        } # if policy_group is user defined
        if ($num_workloads < 1) {
          &debug("Policy Group $policy_group has no workloads") if ($debug>1);
        }
        if (($policy_group_class =~ /user/i) && ($num_workloads > 1)) {
          &shut_down("$num_workloads workloads for $policy_group_class policy-group $policy_group not supported - reduce workloads or delete policy and restart.");
        }
      } # if policy_group
    } # foreach attribute result

  } # while not done

  &debug("$pgcnt total policy groups and $userpgcnt user policy groups") if ($debug>1);
  sleep $debug if ($debug);
  return 1;
} # sub getpg

sub setpolicies()
{
  &debug("sub setpolicies");
  my $next_tag = "";
  my $done = 0;
  my $username=$NODES{$nodename}{'username'};
  my $password=$NODES{$nodename}{'password'};

  my $s = NaServer->new($nodename, 1, 1);
  $s->set_admin_user($username, $password);

  foreach $volname (keys %VOL) {
    next if ($volname eq "");

    my $servicelevel = $VOL{$volname}{'service-level'}; # matchsl should have set it
    next if ($VOL{$volname}{'state'} !~ /online/ );

    if ( !$servicelevel ) {
      &error("No Service Level for volume $volname");
      sleep $debug;
      next;
    }
    
    $iopsthrottle = $SL{$servicelevel}{'iopsthrottle'};
    $headroom = $SL{$servicelevel}{'headroom'};
    $miniops = $SL{$servicelevel}{'miniops'};

    if (!$miniops) {
      $miniops = $defaultminiops;
    }

    if (!$headroom) {
      $headroom = $defaultheadroom;
    }

    $enforce="";
    $enforce=$SL{$servicelevel}{'enforce'};
    if ((!$enforce) || ($enforce eq "")) {
      $enforce=$defaultenforcement;
      &debug("Set enforcement to default $defaultenforcement") if ($debug);
    }

    if ( $enforce =~ /thick/ ) {
      $thinmin=100;
      &debug("Set thinmin to $thinmin % for $enforce enforcement") if ($debug>1);
    } else { # if enforcement is thick else thin or effective
      $thinmin=0;
      $thinmin=$SL{$servicelevel}{'thinmin'};
      if ((!$thinmin) || ($thinmin < 1) ) {
        $thinmin=$defaultthinmin;
        &debug("Set thinmin to default $thinmin %") if ($debug>1);
      }
    } # if enforcement is thick 

    $volsizetotal=$VOL{$volname}{'size-total'};
    $spacesaved=$VOL{$volname}{'total-space-saved'};
    $savedgb=$spacesaved / 1024 / 1024 / 1024;
    $volsizeused=$VOL{$volname}{'size-used'};
    &error("Volume size for $volname is $volsizetotal") if ($volsizetotal <=0);

    if ( $enforce =~ /effective/ ) {
      $voltotalgb=(($volsizetotal + $spacesaved) / 1024 / 1024 / 1024);
      &debug("$volname size-total is $volsizetotal + $spacesaved converted to $voltotalgb GB Total") if ($debug>2);
      $volusedgb=(($volsizeused + $spacesaved) / 1024 / 1024 / 1024);
      &debug("$volname size-used is $volsizeused + $spacesaved converted to $volusedgb GB") if ($debug>2);
    } else {
      $voltotalgb=($volsizetotal / 1024 / 1024 / 1024);
      &debug("$volname size-total is $volsizetotal converted to $voltotalgb GB") if ($debug>2);
      $volusedgb=($volsizeused / 1024 / 1024 / 1024);
      &debug("$volname size-used is $volsizeused converted to $volusedgb GB") if ($debug>2);
    }

    my $thinthrottle=int(($voltotalgb * ($iopsthrottle / 1024)) * ($thinmin / 100));
    &debug("my $thinthrottle=int(($voltotalgb * ($iopsthrottle / 1024)) * ($thinmin / 100))") if ($debug>2);

    my $vol_iops_throttle= int(( $iopsthrottle * (1 + ($headroom / 100)) * $volusedgb / 1024) + $thinthrottle);
    &debug("my $vol_iops_throttle= int(( $iopsthrottle * (1 + ($headroom / 100)) * $volusedgb / 1024) + $thinthrottle)") if ($debug>2);

    my $max_iops_throttle=int(($voltotalgb * ($iopsthrottle / 1024)) + 1);
    &debug("my $max_iops_throttle=int(($voltotalgb * ($iopsthrottle / 1024)) + 1)") if ($debug>2);

    if (( $vol_iops_throttle > $max_iops_throttle ) || ($enforce =~ /thick/)) {
      $vol_iops_throttle=$max_iops_throttle;
      &debug("Volume IOPS throttle set to thick $vol_iops_throttle IOPS/TB") if ($debug>1);
    }

    if ( $vol_iops_throttle < $miniops ) {
      $vol_iops_throttle = $miniops;
      &debug("Volume IOPS throttle set to minimum $vol_iops_throttle IOPS/TB") if ($debug>1);
    }

    if ($iopsthrottle>0) {
      $iops_string="$vol_iops_throttle"."IOPS";
      &debug("Enforce $enforce Service Level $servicelevel headroom $headroom% thinmin $thinmin% volume $volname at $iops_string for $volusedgb GB used $savedgb GB effective $voltotalgb GB total"); 
    } else {
      $iops_string="INF";
      &debug("Enforce unlimited Service Level $servicelevel on volume $volname at $iops_string"); 
    }

    $policy_group=$VOL{$volname}{'policy-group'};
    $newpgname=$servicelevel."_".$volname;

    if (( $policy_group ) || ($policy_group ne "")) { 

      # if user policy exists then modify the policy

      my $max_throughput=$PG{$policy_group}{'max-throughput'};
      &debug("Policy group $policy_group max-throughput is $max_throughput") if ($debug>1);;

      if ($newpgname ne $policy_group) { # rename policy group
        &debug("Rename $policy_group for $volname to $newpgname");
        my $mpin = NaElement->new("qos-policy-group-rename");
        $mpin->child_add_string("policy-group-name",$policy_group);
        $mpin->child_add_string("new-name",$newpgname);
        &debug($mpin->sprintf()) if ($debug>2);
        my $mpout = $s->invoke_elem($mpin);
        if($mpout->results_status() eq "failed") {
          &error("Cannot rename policy group $policy_group to $newpgname: ".$mpout->results_reason() );
          sleep $debug;
        } #if failed
        &logmsg("Renamed $policy_group for $volname to $newpgname");
        $VOL{$volname}{'policy-group'}=$newpgname;
        $policy_group=$newpgname;
      } # if rename policy group

      if (( $max_throughput=~/([\d]+)/i ) && ($iops_string!~/INF/)) {
        my $oldiops =$1;
        &debug("$oldiops == $vol_iops_throttle ?") if ($debug>2);
        if ( $oldiops == $vol_iops_throttle ) {
          &debug("Old throttle $oldiops and new $vol_iops_throttle for $volname are the same: No change required");
          next;
        }
      } elsif (($max_throughput=~/INF/) && ($iops_string=~/INF/)) {
        &debug("Old and new throttle are both unlimited: No change required");
        next;
      } # if string match to number IOPS

      &debug("Modify $policy_group for $volname to max-throughput $iops_string") if ($debug>1);
      my $mpin = NaElement->new("qos-policy-group-modify");
      $mpin->child_add_string("policy-group",$policy_group);
      $mpin->child_add_string("max-throughput",$iops_string);
      &debug($mpin->sprintf()) if ($debug>2);
      sleep $debug if ($debug>1);
      my $mpout = $s->invoke_elem($mpin);
      if($mpout->results_status() eq "failed") {
        &error("Cannot modify policy $policy_group for $volname to $iops_string: ".$mpout->results_reason() );
        sleep $debug;
        next;
      } #if failed

      $VOL{$volname}{'max-throughput'}=$iops_string;

      if ($iopsthrottle>0) {
        &logmsg("Modified policy $policy_group for $volname to $iops_string to provide $enforce $iopsthrottle IOPS/TB in Service Level $servicelevel");
      } else {
        &logmsg("Modified policy $policy_group for $volname to $iops_string in Service Level $servicelevel");
      }

    } else { # else the policy doesn't exist so create it

      if ($pgcnt > $max_policy_groups) {
        &error("Max $max_policy_groups policy groups reached: $newpgname not created");
        next;
      }

      if (!$PG{$newpgname}{'pgid'}) { # policy group doesn't exist
        &debug("Create $newpgname for $volname to max-throughput $iops_string") if ($debug>1);
        my $cpin = NaElement->new('qos-policy-group-create');
        $cpin->child_add_string("policy-group",$newpgname);
        $cpin->child_add_string("max-throughput",$iops_string);
        $cpin->child_add_string("vserver",$VOL{$volname}{'owning-vserver-name'});
        &debug($cpin->sprintf()) if ($debug>2);
        sleep $debug if ($debug>1);
        my $cpout = $s->invoke_elem($cpin);
        if($cpout->results_status() =~ /fail/i) { # create failed
          &error("Create policy $newpgname failed: ".$cpout->results_reason() );
          sleep $debug;
        } #if create failed
        &logmsg("Created policy $newpgname for $volname at $iops_string to provide $enforce $iopsthrottle IOPS/TB in Service Level $servicelevel");
      } else { # policy group exists
        &debug("Policy $newpgname already exists and so not created");
      }

      #if policy group class isn't user

      my $vmin = NaElement->new('volume-modify-iter');

      my $query = NaElement->new('query'); 
      $vmin->child_add($query);
      my $query_volume_attributes=NaElement->new('volume-attributes'); 
      $query->child_add($query_volume_attributes);
      my $query_volume_id_attributes=NaElement->new('volume-id-attributes'); 
      $query_volume_attributes->child_add($query_volume_id_attributes);
      $query_volume_id_attributes->child_add_string('name',$volname); 

      my $attributes = NaElement->new('attributes'); 
      $vmin->child_add($attributes);
      my $volume_attributes=NaElement->new('volume-attributes'); 
      $attributes->child_add($volume_attributes);

      my $volume_qos_attributes=NaElement->new('volume-qos-attributes'); 
      $volume_attributes->child_add($volume_qos_attributes);
      $volume_qos_attributes->child_add_string('policy-group-name',$newpgname); 

      &debug($vmin->sprintf()) if ($debug>2);

      my $vmout = $s->invoke_elem($vmin);
      if($vmout->results_status() =~ /fail/i) {
        &error("Cannot modify $volname to use QoS policy $policy_group: ".$vmout->results_reason() );
        sleep $debug;
        next;
      }
      $VOL{$volname}{'policy-group'}=$newpgname;
      $VOL{$volname}{'max-throughput'}=$iops_string;
 
      if ($iopsthrottle>0) {
        &logmsg("Modified volume $volname to use policy $newpgname at $iops_string to provide $enforce $iopsthrottle IOPS/TB in Service Level $servicelevel");
      } else {
        &logmsg("Modified volume $volname to use policy $newpgname at $iops_string in Service Level $servicelevel");
      }
    } # if the policy exists then modify it else create it

    # record the report data
    $VOLDB{$nodename}{$volname}{'policy-group'}=$VOL{$volname}{'policy-group'};
    $VOLDB{$nodename}{$volname}{'workload-name'}=$VOL{$volname}{'workload-name'};
    $VOLDB{$nodename}{$volname}{'max-throughput'}=$VOL{$volname}{'max-throughput'};
    $VOLDB{$nodename}{$volname}{'enforce'}=$enforce;
    $VOLDB{$nodename}{$volname}{'iopsthrottle'}=$iopsthrottle;
    $VOLDB{$nodename}{$volname}{'servicelevel'}=$servicelevel;
    $VOLDB{$nodename}{$volname}{'thinmin'}=$thinmin;
    $VOLDB{$nodename}{$volname}{'volthrottle'}=$vol_iops_throttle;
    $VOLDB{$nodename}{$volname}{'thinthrottle'}=$thinthrottle;
    $VOLDB{$nodename}{$volname}{'voltotalgb'}=$voltotalgb;
    $VOLDB{$nodename}{$volname}{'volusedgb'}=$volusedgb;
    $VOLDB{$nodename}{$volname}{'savedgb'}=$savedgb;
    $VOLDB{$nodename}{$volname}{'workload-class'}=$workload_class;
    $VOLDB{$nodename}{$volname}{'state'}=$VOL{$volume_name}{'state'};
    $VOLDB{$nodename}{$volname}{'is-node-root'}=$VOL{$volume_name}{'is-node-root'};
    $VOLDB{$nodename}{$volname}{'is-vserver-root'}=$VOL{$volume_name}{'is-vserver-root'};
    $VOLDB{$nodename}{$volname}{'size-available'}=$VOL{$volume_name}{'size-available'};
    $VOLDB{$nodename}{$volname}{'size-used'}=$VOL{$volume_name}{'size-used'};
    $VOLDB{$nodename}{$volname}{'size-total'}=$VOL{$volume_name}{'size-total'};
    $VOLDB{$nodename}{$volname}{'total-space-saved'}=$VOL{$volume_name}{'total-space-saved'};
    $VOLDB{$nodename}{$volname}{'owning-vserver-name'}=$VOL{$volume_name}{'owning-vserver-name'};
    $VOLDB{$nodename}{$volname}{'containing-aggregate-name'}=$VOL{$volume_name}{'containing-aggregate-name'};
    $VOLDB{$nodename}{$volname}{'disk-type'}=$VOL{$volume_name}{'disk-type'};
    $VOLDB{$nodename}{$volname}{'disk-rpm'}=$VOL{$volume_name}{'disk-rpm'};
    $VOLDB{$nodename}{$volname}{'is-hybrid'}=$VOL{$volume_name}{'is-hybrid'};

  } #foreach volname

  sleep $debug if ($debug);
  return 1;
} # sub setpolicies

sub getwl()
{
  &debug("sub getwl");
  my $next_tag = "";
  my $done = 0;
  my $username=$NODES{$nodename}{'username'};
  my $password=$NODES{$nodename}{'password'};

  my $s = NaServer->new($nodename, 1, 1);
  $s->set_admin_user($username, $password);

  while ( ! $done ) {
    my $in = NaElement->new("qos-workload-get-iter");
    if($next_tag) {
      $in->child_add_string("tag",$next_tag);
      &debug("Got next tag") if ($debug>1);
    }
    &debug($in->sprintf()) if ($debug>2);
    sleep $debug if ($debug>1);
    my $out = $s->invoke_elem($in);
    if($out->results_status() eq "failed") {
      &error("Cannot get QoS workloads: ".$out->results_reason() );
      sleep $debug;
      return 0;
    }
    my $num_records = $out->child_get_int("num-records");
    &debug("$num_records records returned from qos-workload-get-iter API call") if ($debug>1);
    return 1 if (($num_records==0) || (!$num_records));;
    $next_tag = $out->child_get_string("next-tag");
    if($next_tag) {
      &debug("Next tag returned") if ($debug>1);
    } else {
      $done=1; 
    }
    my $workloads = $out->child_get("attributes-list");
    my @result = $workloads->children_get();

    foreach $workload (@result) {

      $workload_name_string = $workload->child_get_string("workload-name");
      if (!$workload_name_string) {
        &error("Cannot get workload-name");
        sleep $debug if ($debug>1);
        next;
      } # if no workload name

      $volume_string = $workload->child_get_string("volume");
      $lun_string = $workload->child_get_string("lun");
      $file_string = $workload->child_get_string("file");
      $qtree_string = $workload->child_get_string("qtree");
      $vserver_string = $workload->child_get_string("vserver");
      $readahead = $workload->child_get_string("read-ahead");

      if ($debug>1) {
        &debug("Read ahead setting $readahead") if ($readahead);
        &debug("LUN workload $lun_string") if ($lun_string);
        &debug("FILE workload $file_string") if ($file_string);
        &debug("QTREE workload $qtree_string") if ($qtree_string);
        &debug("VSERVER workload $vserver_string") if ($vserver_string);
        &debug("VOLUME workload $volume_string") if ($volume_string);
      }

      $pgname = $workload->child_get_string("policy-group");
      if (!$pgname) {
        &error("Cannot get policy-group for workload $workload_name_string");
        sleep $debug if ($debug>1);
        next;
      } # if no pgname

      if ($pgname =~ /^_/) {
        &debug("Skipping system defined policy $pgname") if ($debug>1);
        next;
      }

      $workload_class = $workload->child_get_string("workload-class");
      if (!$workload_class) {
        &error("Cannot get workload-class for policy group $pgname");
        sleep $debug if ($debug>1);
        next;
      } # if no workload class

      if ((!$volume_string) || ($volume_string eq "") || ($lun_string) || ($file_string) || ($qtree_string) ) {
        delete $VOL{$volume_string};
        &debug("Non-volume policy $pgname will not be managed") if ($debug>1);
        next;
      }

      &debug("Volume $volume_string policy_group $pgname workload-name $workload_name_string workload-class $workload_class" ) if ($debug>1);

      &getpg if (!$PG{$pgname}{'policy-group-class'}); # refresh list of policy groups

      if (($volume_string) || ($pgname) || ($workload_class)) {
        if ($workload_class =~ /autovolume/i) { # 
          &debug("Skipping autovolume policy $pgname") if ($debug>1);
          next;
        }
        if ($workload_class =~ /user/i) { # user defined workload
          $VOL{$volume_string}{'policy-group'}=$pgname;
          $VOL{$volume_string}{'workload-class'}=$workload_class;
          $VOL{$volume_string}{'workload-name'}=$workload_name_string;
          $VOLDB{$nodename}{$volume_string}{'policy-group'}=$pgname;
          $VOLDB{$nodename}{$volume_string}{'workload-class'}=$workload_class;
          $VOLDB{$nodename}{$volume_string}{'workload-name'}=$workload_name_string;
          $PG{$pgname}{'volume-name'}=$volume_string;
          &debug("Volume $volume_string has $workload_class policy-group $pgname");
        } else { # system defined workload - DELETE volume from list
          delete $VOL{$volume_string} if ($VOL{$volume_string});
          delete $VOLDB{$nodename}{$volume_string} if ($VOLDB{$nodename}{$volume_string});
          &debug("Policy $pgname class $workload_class is not user defined so $volume_string will not be managed") if ($debug>1);
        }
      } # if volume_string

    } # foreach workload result
  } # while not done

  sleep $debug if ($debug);
  return 1;
} # getwl

sub getvolumes()
{
  &debug("sub getvolumes");
  my $next_tag = "";
  my $done = 0;
  my $username=$NODES{$nodename}{'username'};
  my $password=$NODES{$nodename}{'password'};

  #foreach $volname 
  foreach $volname (keys %VOL) {
    delete $VOL{$volname}; # throw away prior values
  }

  my $s = NaServer->new($nodename, 1, 1);
  $s->set_admin_user($username, $password);

  while ( ! $done ) {
    my $in = NaElement->new("volume-get-iter");
    if($next_tag) {
      $in->child_add_string("tag",$next_tag);
      &debug("Get next tag $next_tag") if ($debug>1);
    }
    &debug($in->sprintf()) if ($debug>2);
    sleep $debug if ($debug);
    my $out = $s->invoke_elem($in);
    if($out->results_status() eq "failed") {
      &error("Cannot get list of volumes: ".$out->results_reason() );
      sleep $debug if ($debug);
      return 0;
    }
    my $num_records = $out->child_get_int("num-records");
    &debug("$num_records records returned from volume-get-iter API call") if ($debug>1);
    return 1 if (($num_records==0) || (!$num_records));;
    $next_tag = $out->child_get_string("next-tag");
    if($next_tag) {
      &debug("Returned next tag") if ($debug>1);
    } else { 
      $done=1; #exit loop next iteration
    }
    my $vols = $out->child_get("attributes-list");
    return 1 if (!$vols);
    my @result = $vols->children_get();

    foreach $vol (@result){
 
      $volume_state_attributes = $vol->child_get("volume-state-attributes");
      $state = $volume_state_attributes->child_get_string("state");
      $is_node_root = $volume_state_attributes->child_get_int("is-node-root");
      $is_vserver_root = $volume_state_attributes->child_get_int("is-vserver-root");
      # get space attributes
      $volume_space_attributes = $vol->child_get("volume-space-attributes");
      $size_available= $volume_space_attributes->child_get_int("size-available");
      $size_used= $volume_space_attributes->child_get_int("size-used");
      $size_total= $volume_space_attributes->child_get_int("size-total");
      # get mirror attributes
      $volume_mirror_attributes = $vol->child_get("volume-mirror-attributes");
      $is_dp_mirror = $volume_mirror_attributes->child_get_int("is-data-protection-mirror");
      $is_ls_mirror = $volume_mirror_attributes->child_get_int("is-load-sharing-mirror");
      $is_mv_mirror = $volume_mirror_attributes->child_get_int("is-move-mirror");
      $is_replica = $volume_mirror_attributes->child_get_int("is-replica-volume");
      &debug("$volume_name is-data-protection-mirror $is_dp_mirror is-load-sharing-mirror $is_ls_mirror is-move-mirror $is_mv_mirror is-replica-volume $is_replica") if ($debug>1);
      # get efficiency attributes
      $volume_efficiency_attributes = $vol->child_get("volume-sis-attributes");
      if ($volume_efficiency_attributes) {
        $total_space_saved = $volume_efficiency_attributes->child_get_int("total-space-saved");
      } else {
        $total_space_saved = 0;
      }
      # get id attributes
      $volume_id_attributes = $vol->child_get("volume-id-attributes");
      $containing_aggregate_name = $volume_id_attributes->child_get_string("containing-aggregate-name");
      $owning_vserver_name = $volume_id_attributes->child_get_string("owning-vserver-name");
      $volume_name = $volume_id_attributes->child_get_string("name");

      next if ($state !~ /online/ );
      next if ($is_node_root eq "true");
      next if ($is_vserver_root eq "true");
      next if ($is_dp_mirror eq "true");
      next if ($is_ls_mirror eq "true");
      next if ($is_mv_mirror eq "true");
      next if ($is_replica eq "true");

      &debug("$state vol $volume_name in aggr $containing_aggregate_name in vserver $owning_vserver_name with $size_available available and $size_used used of $size_total bytes with total space saved $total_space_saved bytes") if ($debug>1);
      sleep $debug if ($debug);

      $VOL{$volume_name}{'state'}=$state;
      $VOL{$volume_name}{'is-node-root'}=$is_node_root;
      $VOL{$volume_name}{'is-vserver-root'}=$is_vserver_root;
      $VOL{$volume_name}{'size-available'}=$size_available;
      $VOL{$volume_name}{'size-used'}=$size_used;
      $VOL{$volume_name}{'size-total'}=$size_total;
      $VOL{$volume_name}{'total-space-saved'}=$total_space_saved;
      $VOL{$volume_name}{'owning-vserver-name'}=$owning_vserver_name;
      $VOL{$volume_name}{'containing-aggregate-name'}=$containing_aggregate_name;
      if (!$AGG{$nodename}{$containing_aggregate_name}{'effective-disk-type'}) {
        if (!&getaggr) {
          return 0;
        }
      }
      $VOL{$volume_name}{'disk-type'}=$AGG{$nodename}{$containing_aggregate_name}{'effective-disk-type'};
      $VOL{$volume_name}{'disk-rpm'}=$AGG{$nodename}{$containing_aggregate_name}{'effective-rpm'};
      $VOL{$volume_name}{'is-hybrid'}=$AGG{$nodename}{$containing_aggregate_name}{'is-hybrid'};
    } #foreach volume from API call
  } #while not done
  sleep $debug if ($debug);
  return 1;
}

sub analyze_volumes()
{
	my $s = $_[0];
	my $total_size_available = 0;
	my $total_size_used = 0;
	my $total_size_total = 0;
	my $next_tag = "";
	my $done = 0;

	while ( ! $done ) {
		my $in = NaElement->new("volume-get-iter");
		if($next_tag) {
			$in->child_add_string("tag",$next_tag);
			#print "Get next tag $next_tag\n";
		}
                &debug($in->sprintf()) if ($debug>2);
                sleep $debug if ($debug);
		my $out = $s->invoke_elem($in);
		if($out->results_status() eq "failed") {
			print($out->results_reason() ."\n");
			exit(-2);
		}

		my $vols = $out->child_get("attributes-list");
		my $num_records = $out->child_get_int("num-records");
		print "$num_records records returned from volume-get-iter API call.\n";
                return 1 if (($num_records==0) || (!$num_records));;
		$next_tag = $out->child_get_string("next-tag");
		if($next_tag) {
			#print "Returned next tag $next_tag\n";
		} else { 
			$done=1; 
		}
		my @result = $vols->children_get();
		foreach $vol (@result){
			# BROKEN API - volume-qos-attributes don't work.
			#$volume_qos_attributes = $vol->child_get("volume-qos-attributes");
			#if (!$volume_qos_attributes) {
			#	die "NO QOS ATTRIBUTES\n";
			#}
			## get space attributes
			$volume_state_attributes = $vol->child_get("volume-state-attributes");
			$is_node_root = $volume_state_attributes->child_get_int("is-node-root");
			$volume_space_attributes = $vol->child_get("volume-space-attributes");
			$size_available+= $volume_space_attributes->child_get_int("size-available");
			$total_size_available+= $size_available;
			$size_used+= $volume_space_attributes->child_get_int("size-used");
			$total_size_used+= $size_used;
			$size_total+= $volume_space_attributes->child_get_int("size-total");
			$total_size_total+= $size_total;
			# get id attributes
			$volume_id_attributes = $vol->child_get("volume-id-attributes");
			$comment = $volume_id_attributes->child_get_string("comment");
			$containing_aggregate_name = $volume_id_attributes->child_get_string("containing-aggregate-name");
			$volume_name = $volume_id_attributes->child_get_string("name");
			$owning_vserver_uuid = $volume_id_attributes->child_get_string("owning-vserver-uuid");
			print "$volume_name in $containing_aggregate_name with $size_available available and $size_used used of $size_total bytes $is_node_root\n";
		}
		print "TOTAL: $total_size_used used and $total_size_available available out of $total_size_total total (bytes)\n";
	} # while not done
  sleep $debug if ($debug);
}

sub logmsg {
  my $msg = shift;
  return if (!$msg);
  my $console="-s" if ($logtoconsole);
  system("$logger $console -p \"$facility.info\" -t \"$0\[$$\]\" \"$msg\"");
}

sub sighandler {
  my $sig = shift;
  $gotsig=1;
  &shut_down("Got signal SIG$sig");
}

sub debug {
  return if (!$debug);
  my $msg = shift;
  my $console="-s" if ($logtoconsole);
  system("$logger $console -p \"$facility.debug\" -t \"$0\[$$\]\" \"DEBUG: $msg\"");
}

sub error {
  my $msg = shift;
  my $console="-s" if ($logtoconsole);
  system("$logger $console -p \"$facility.err\" -t \"$0\[$$\]\" \"ERROR: $msg\"");
}

sub shut_down {
  my $msg = shift;
  system("$logger -s -p \"$facility.crit\" -t \"$0\[$$\]\" \"SHUTDOWN: $msg\"");
  unlink($pidfile);
  untie(%VOLDB);
  exit 1;
}

sub checkchangedconfiguration {
  &debug("sub checkchangedconfiguration");
  ($dev, $ino, $mode, $nlnk, $uid, $gid, $rdev, $size, $atime, $mtime, $ctime, $bsize, $blks) = stat($configurationfile);
  if (( ! -f $configurationfile) || ($mtime > $lastconfigurationtime )) { # config file changed

    &logmsg("Re-reading configuration file $configuration because it changed.");
    &read_configuration;

    &initnodes; 

  }
}

sub read_configuration {
  &debug("sub read_configuration");
  if ( ! -f $configurationfile ) {
    &logmsg("$configurationfile doesn't exist");
    &write_configuration(); # write a fresh config 
    &shut_down("Edit $configurationfile and restart $0");
  }
  &logmsg("Reading service levels in $configurationfile");
  open(FILE,"<$configurationfile") || &shut_down("Cannot open $configurationfile : $!");
  @CONF=<FILE>;
  close(FILE); 
  
  # delete prior values of arrays
  foreach $pgname (keys %PG) {
    delete $PG{$pgname}; # throw away prior values
  }
  %PG=();
  undef %PG;
  foreach $level (keys %SL) {
    delete $SL{$level}; # throw away prior values
  }
  %SL=();
  undef %SL;
  foreach $nodename (keys %NODES) {
    delete $NODES{$nodename}; #throw away prior values
    delete $AGG{$nodename};
  }
  %NODES=();
  undef %NODES;
  %AGG=();
  undef %AGG;
  foreach $volname (keys %VOL) {
    delete $VOL{$volname}; # throw away prior values
  }
  %VOL=();
  undef %VOL;
  
  foreach $line (@CONF) {
    next if ((!$line) || ($line=~/^\#/) || ($line=~/^\;/));
    if ($line=~/nodename[\s]+/) {
      $nodename=(split(/[\s]+/,$line))[1];
      $username=(split(/[\s]+/,$line))[2];
      $password=(split(/[\s]+/,$line))[3];
      $NODES{$nodename}{'username'}=$username;
      $NODES{$nodename}{'password'}=$password;
    } elsif ($line=~/^maxcpupercent[\s]+/) {
      $maxcpupercent=(split(/[\s]+/,$line))[1];
      &shut_down("maxcpupercent $maxcpupercent is not a decimal") if ($maxcpupercent!~/[\d]+/);
    } elsif ($line=~/^maxcpuduration[\s]+/) {
      $maxcpuduration=(split(/[\s]+/,$line))[1];
    } elsif ($line=~/^logtoconsole[\s]+/) {
      $logtoconsole=(split(/[\s]+/,$line))[1];
      &shut_down("logtoconsole is not a decimal") if ($logtoconsole!~/[\d]+/);
    } elsif ($line=~/^facility[\s]+/) {
      $facility=(split(/[\s]+/,$line))[1];
    } elsif ($line=~/^debug[\s]+/) {
      $debug=(split(/[\s]+/,$line))[1];
      &shut_down("debug $debug is not a decimal") if ($debug!~/[\d]+/);
    } elsif ($line=~/^defaultservicelevel[\s]+/) {
      $defaultservicelevel=(split(/[\s]+/,$line))[1];
    } elsif ($line=~/^defaultenforcement[\s]+/) {
      $defaultenforcement=(split(/[\s]+/,$line))[1];
    } elsif ($line=~/^defaultthinmin[\s]+/) {
      $defaultthinmin=(split(/[\s]+/,$line))[1];
      &shut_down("defaultthinmin $defaultthinmin is not a decimal") if ($defaultthinmin!~/[\d]+/);
    } elsif ($line=~/^defaultminiops[\s]+/) {
      $defaultminiops=(split(/[\s]+/,$line))[1];
      &shut_down("defaultminiops $defaultminiops is not a decimal") if ($defaultminiops!~/[\d]+/);
    } elsif ($line=~/^defaultheadroom[\s]+/) {
      $defaultheadroom=(split(/[\s]+/,$line))[1];
      &shut_down("defaultheadroom $defaultheadroom is not a decimal") if ($defaultheadroom!~/[\d]+/);
    } elsif ($line=~/^prefer[\s]+/) {
      $prefer=(split(/[\s]+/,$line))[1];
    } elsif ($line=~/^servicelevel[\s]+/) {
      $disktypes=""; $volinclude=""; $enforce=""; $volexclude=""; $agginclude=""; $aggexclude=""; $hybrid=""; 
      $diskrpm=0; $iopsthrottle=0; $maxrpm=0; $thinmin=0; $miniops=0; $headroom=0;
      $servicelevel=(split(/[\s]+/,$line))[1];
    } elsif ($line=~/^iopsthrottle[\s]+/) {
      $iopsthrottle=(split(/[\s]+/,$line))[1];
      &shut_down("iopsthrottle $iopsthrottle is not a decimal") if ($iopsthrottle!~/[\d]+/);
      $SL{$servicelevel}{'iopsthrottle'}=$iopsthrottle;
    } elsif ($line=~/^disktypes[\s]+/) {
      $disktypes=(split(/[\s]+/,$line))[1];
      $SL{$servicelevel}{'disktypes'}=$disktypes;
    } elsif (($line=~/^include[\s]+/) || ($line=~/^volinclude[\s]+/)) {
      $volinclude=(split(/[\s]+/,$line))[1];
      $SL{$servicelevel}{'volinclude'}=$volinclude;
    } elsif (($line=~/^exclude[\s]+/) || ($line=~/^volexclude[\s]+/)) {
      $volexclude=(split(/[\s]+/,$line))[1];
      $SL{$servicelevel}{'volexclude'}=$volexclude;
    } elsif ($line=~/^agginclude[\s]+/) {
      $agginclude=(split(/[\s]+/,$line))[1];
      $SL{$servicelevel}{'agginclude'}=$agginclude;
    } elsif ($line=~/^aggexclude[\s]+/) {
      $aggexclude=(split(/[\s]+/,$line))[1];
      $SL{$servicelevel}{'aggexclude'}=$aggexclude;
    } elsif ($line=~/^hybrid[\s]+/) {
      $hybrid=(split(/[\s]+/,$line))[1];
      &shut_down("hybrid $hybrid must be true or false") if ($hybrid!~/true|false/i);
      $SL{$servicelevel}{'hybrid'}=$hybrid;
    } elsif ($line=~/^enforce[\s]+/) {
      $enforce=(split(/[\s]+/,$line))[1];
      $SL{$servicelevel}{'enforce'}=$enforce;
    } elsif ($line=~/^thinmin[\s]+/) {
      $thinmin=(split(/[\s]+/,$line))[1];
      &shut_down("thinmin $thinmin is not a decimal") if ($thinmin!~/[\d]+/);
      $SL{$servicelevel}{'thinmin'}=$thinmin;
    } elsif ($line=~/^miniops[\s]+/) {
      $miniops=(split(/[\s]+/,$line))[1];
      &shut_down("miniops $miniops is not a decimal") if ($miniops!~/[\d]+/);
      $SL{$servicelevel}{'miniops'}=$miniops;
    } elsif ($line=~/^diskrpm[\s]+/) {
      $diskrpm=(split(/[\s]+/,$line))[1];
      &shut_down("diskrpm $diskrpm is not a decimal") if ($diskrpm!~/[\d]+/);
      $maxrpm=(split(/[\s]+/,$line))[2];
      &shut_down("diskrpm $maxrpm is not a decimal") if (($maxrpm) && ($maxrpm!~/[\d]+/));
      $maxrpm=$diskrpm if ($maxrpm < $diskrpm) ;
      $SL{$servicelevel}{'diskrpm'}=$diskrpm;
      $SL{$servicelevel}{'maxrpm'}=$maxrpm;
    } 
  }

#  foreach $nodename (keys %NODES) {
#    &shut_down("No password for $nodename") if (!$NODES{$nodename}{'password'});
#    &logmsg("Nodename $nodename username $NODES{$nodename}{'username'}");
#    $i++;
#  }
#  &shut_down("No nodenames in $configurationfile") if (!$i); $i=0;

  if ((!$defaultservicelevel) || (!$SL{$defaultservicelevel}))  {
    &shut_down("defaultservicelevel $defaultservicelevel is not defined");
  }

  foreach $level (keys %SL) {
    &logmsg("$level : $SL{$level}{'iopsthrottle'} IOPS/TB");
    $i++;
  }
  &shut_down("No Service Levels defined in $configurationfile") if (!$i); $i=0;

  &logmsg("Using configuration in $configurationfile");
  $lastconfigurationtime=time();
  return 1;
}       #read_configuration

sub write_configuration {
  &debug("sub write_configuration");
  mkdir($confdir,0700);
  open(FILE,">$configurationfile") || &shut_down("Cannot write $configurationfile : $!");
  print FILE "# Kitchen Police configuration $configurationfile generated by $0\n#\n";
  print FILE "# $myname is a QoS policy manager for clustered Data ONTAP\n#\n";
  print FILE "# Copyright 2014 NetApp, Inc. All rights reserved. Patent Pending.\n#\n";
  print FILE "# Specifications subject to change without notice.\n#\n"; 
  print FILE "# Written by Evan Miller for NetApp, Inc.\n#\n"; 
  print FILE "# kp means Kitchen Police.  You can't run a restaurant if\n";
  print FILE "# overweight patrons are loose in the kitchen, eating\n";
  print FILE "# more than they paid for and preventing others from\n";
  print FILE "# getting what they paid for.\n#\n";
  print FILE "# This software is provided AS IS, with no support or\n";
  print FILE "# warranties of any kind, including but not limited to\n";
  print FILE "# warranties of merchantability or fitness of any kind\n";
  print FILE "# expressed or implied.\n#\n";
  print FILE "# $myname governed by the software license of clustered Data ONTAP 8.2\n#\n";
  print FILE "# The format is: keyword value(s)\n#\n";
  print FILE "#nodename fully-qualified-DNS-name/IP admin-username password\n";
  foreach $nodename (keys %NODES) {
    $username=$NODES{$nodename}{'username'};
    $password=$NODES{$nodename}{'password'};
    print FILE "nodename $nodename $username $password\n";
  }
  print FILE "#\n";
  print FILE "# only online non-root non-mirror/vault volumes are managed\n";
  print FILE "# iopsthrottle is expressed as IOPS per TB. 0 means no throttle.\n";
  print FILE "# disktypes is expressed as a perl regular expression, case sensitive\n";
  print FILE "# ATA EATA FCAL LUN MSATA SAS BSAS SATA SCSI SSD XATA XSAS FSAS unknown\n"; 
  print FILE "# volinclude, volexclude, agginclude, and aggexclude are expressed as a perl case sensitive regular expressions.\n";
  print FILE "# the default service level if a volume doesn't match any definition\n";
  print FILE "defaultservicelevel $defaultservicelevel\n#\n";
  print FILE "# prefer faster or slower service level if multiple matches to definitions\n";
  print FILE "prefer $prefer\n#\n";
  print FILE "# default throttle enforcement, thick (committed), thin (used) or effective (used + space saved by efficiency)\n";
  print FILE "defaultenforcement $defaultenforcement\n#\n";
  print FILE "# default thin enforcement minimum %, minimum capacity for QoS throttle calculation\n";
  print FILE "# defaultthinmin is the default minimum IOPS throttle for a volume, in percentage of the volume size\n";
  print FILE "defaultthinmin $defaultthinmin\n#\n";
  print FILE "# defaultheadroom is the default growth headroom percentage for a volume\n";
  print FILE "defaultheadroom $defaultheadroom\n#\n";
  print FILE "# defaultminiops is the default minimum IOPS throttle for a volume, in absolute IOPS\n";
  print FILE "defaultminiops $defaultminiops\n#\n";
  foreach $servicelevel (keys %SL) {
    print FILE "# service level definition for all nodes\n";
    print FILE "servicelevel $servicelevel\n";
    print FILE "iopsthrottle $SL{$servicelevel}{'iopsthrottle'}\n";
    print FILE "disktypes $SL{$servicelevel}{'disktypes'}\n" if ($SL{$servicelevel}{'disktypes'});
    print FILE "volinclude $SL{$servicelevel}{'volinclude'}\n" if ($SL{$servicelevel}{'volinclude'});
    print FILE "volexclude $SL{$servicelevel}{'volexclude'}\n" if ($SL{$servicelevel}{'volexclude'});
    print FILE "agginclude $SL{$servicelevel}{'agginclude'}\n" if ($SL{$servicelevel}{'agginclude'});
    print FILE "aggexclude $SL{$servicelevel}{'aggexclude'}\n" if ($SL{$servicelevel}{'aggexclude'});
    print FILE "hybrid $SL{$servicelevel}{'hybrid'}\n" if ($SL{$servicelevel}{'hybrid'});
    print FILE "diskrpm $SL{$servicelevel}{'diskrpm'} $SL{$servicelevel}{'maxrpm'}\n" if ($SL{$servicelevel}{'diskrpm'});
    print FILE "enforce $SL{$servicelevel}{'enforce'}\n" if ($SL{$servicelevel}{'enforce'});
    print FILE "thinmin $SL{$servicelevel}{'thinmin'}\n" if ($SL{$servicelevel}{'thinmin'});
    print FILE "miniops $SL{$servicelevel}{'miniops'}\n" if ($SL{$servicelevel}{'miniops'});
    print FILE "headroom $SL{$servicelevel}{'headroom'}\n" if ($SL{$servicelevel}{'headroom'});
    print FILE "#\n";
  }
  print FILE "# Wait for CPU utilization to fall below this percentage\n";
  print FILE "maxcpupercent $maxcpupercent\n#\n";
  print FILE "# Measure cpu utilization over this many seconds (sleep period)\n";
  print FILE "maxcpuduration $maxcpuduration\n#\n";
  print FILE "# log to console=1, log only to syslog=0\n";
  print FILE "logtoconsole $logtoconsole\n#\n";
  print FILE "# syslog facility: user, local0, ...\n";
  print FILE "facility $facility\n#\n";
  print FILE "# debug=0 no debug information\n";
  print FILE "# debug=1 basic debug information\n";
  print FILE "# debug=2 verbose debug information and stop after every change\n";
  print FILE "# debug=3 all debug information and stop after every change\n";
  print FILE "debug $debug\n#\n";
  #print FILE "hostnames ";
  #foreach $name (@HOSTNAMES) {
  #  print FILE "$name ";
  #}
  #print FILE "\n#\n";
  close(FILE);
  &logmsg("Configuration written to $configurationfile");
}       #write_configuration

sub read_volume_db {
  &debug("sub read_volume_db");
  &opendb;
  &closedb;
}

sub opendb {
  &debug("sub opendb");
  if ($^O eq "linux"){
	tie(%VOLDB, NDBM_File, $voldbfile, O_RDWR|O_CREAT, 0644) ||
	&shut_down("Cannot open database $voldbfile: $!");
  }
  else{
  	tie(%VOLDB, DB_File, $voldbfile, O_RDWR|O_CREAT, 0644) ||
	&shut_down("Cannot open database $voldbfile: $!");
  }
  &debug("Volume database $voldbfile opened");
}

sub closedb {
  &debug("sub closedb");
  untie(%VOLDB);
}

sub cpuidle {
  my $cpunum=0;
  &debug("sub cpuidle");
  $username=$NODES{$nodename}{'username'};
  $password=$NODES{$nodename}{'password'};
  &debug("Checking $nodename CPU busy for $maxcpuduration seconds.");
  my $s = NaServer->new($nodename, 1, 1);
  $s->set_admin_user($username, $password);
  my $in = NaElement->new("perf-object-get-instances");
  $in->child_add_string("objectname",'processor');          
  my $counters = NaElement->new("counters"); 
  $counters -> child_add_string('counter', 'processor_busy');
  $counters -> child_add_string('counter', 'processor_elapsed_time');
  $in->child_add($counters);
  my $instances = NaElement->new("instances"); 
  for ($cpunum=0; $cpunum < $NODES{$nodename}{'numprocessors'}; $cpunum++) {
    $instances->child_add_string('instance',$CPU{$nodename}{$cpunum});
    &debug("Input instance $cpunum is $CPU{$nodename}{$cpunum}\n") if ($debug>1);
  } # for cpunum
  $in->child_add($instances);
  &debug($in->sprintf()) if ($debug>2);
  sleep $debug if ($debug);
  my $out = $s->invoke_elem($in);
   if($out->results_status() eq "failed") {
  	&error("Cannot get CPU counters from $nodename: ".$out->results_reason() );
        sleep $debug if ($debug);
        return 0;	
   } #if failed
   my $time_stamp1 = $out->child_get_string( 'timestamp' );
   &debug("Time 1 $time_stamp1") if ($debug>1); 
   my $instances_list1 = $out->child_get( 'instances' );
   my @instances1 = $instances_list1->children_get();
   foreach $inst1 (@instances1) {
     my $inst_name1=$inst1->child_get_string('name');
     my $counters_list1=$inst1->child_get('counters');
     my @counters1=$counters_list1->children_get();
     foreach $counter1 (@counters1) {
       my $counter_name1=$counter1->child_get_string('name');
       my $counter_value1=$counter1->child_get_string('value');
       if ($counter_name1=~/processor_busy/) {
         $total_processor_busy1+=$counter_value1;
         &debug("Time 1 matched processor_busy is $total_processor_busy1") if ($debug>1);
       }
       if ($counter_name1=~/processor_elapsed_time/) {
         $total_processor_elapsed1+=$counter_value1;
         &debug("Time 1 matched processor_elapsed_time is $total_processor_elapsed1") if ($debug>1);
       }
     } # foreach counter
   } #foreach instance
   &debug($in->sprintf()) if ($debug>2);
   sleep $maxcpuduration;
   my $out = $s->invoke_elem($in);
   if($out->results_status() eq "failed") {
  	&error("Cannot get CPU counters from $nodename 2nd call: ".$out->results_reason() );
        sleep $debug if ($debug);
 	return 0;
   } #if failed
   my $time_stamp2 = $out->child_get_string( 'timestamp' );
   &debug("Time 2 $time_stamp2") if ($debug>1); 
   my $instances_list2 = $out->child_get( 'instances' );
   my @instances2 = $instances_list2->children_get();
   foreach $inst2 (@instances2) {
     my $inst_name2=$inst2->child_get_string('name');
     my $counters_list2=$inst2->child_get('counters');
     my @counters2=$counters_list2->children_get();
     foreach $counter2 (@counters2) {
       my $counter_name2=$counter2->child_get_string('name');
       my $counter_value2=$counter2->child_get_string('value');
       if ($counter_name2=~/processor_busy/) {
         $total_processor_busy2+=$counter_value2;
         &debug("Time 2 matched processor_busy is $total_processor_busy2") if ($debug>1);
       }
       if ($counter_name2=~/processor_elapsed_time/) {
         $total_processor_elapsed2+=$counter_value2;
         &debug("Time 2 matched processor_elapsed_time is $total_processor_elapsed2") if ($debug>1);
       }
     } # foreach counter
   } #foreach instance
   #% CPU utilization = (total_processor_busy at time t2 - total_processor_busy at time t1) / (cpu_elapsed_time at time t2 - cpu_elapsed_time at time t1)
   $diff_timestamp=$time_stamp2 - $time_stamp1;
   &debug("diff_timestamp=time_stamp2 - time_stamp1 $diff_timestamp=$time_stamp2 - $time_stamp1") if ($debug>1);
   $diff_processor_busy=$total_processor_busy2 - $total_processor_busy1;
   &debug("diff_processor_busy=total_processor_busy2 - total_processor_busy1 $diff_processor_busy=$total_processor_busy2 - $total_processor_busy1") if ($debug>1);
   $diff_elapsed=$total_processor_elapsed2 - $total_processor_elapsed1;
   &debug("diff_elapsed=total_processor_elapsed2 - total_processor_elapsed1 $diff_elapsed=$total_processor_elapsed2 - $total_processor_elapsed1") if ($debug>1);
   $cpuutil=$diff_processor_busy / $diff_elapsed;
   &debug("cpuutil=diff_processor_busy / diff_elapsed $cpuutil=$diff_processor_busy / $diff_elapsed") if ($debug>1);
   $cpuutil=$cpuutil * 100;
   if ($cpuutil < $maxcpupercent ) {
     &logmsg(sprintf("%s CPU busy %3.2f below threshold %3.2f",$nodename,$cpuutil,$maxcpupercent));
     return 1; # CPU has idle time
   } else {
     &logmsg(sprintf("%s CPU busy %3.2f above threshold %3.2f",$nodename,$cpuutil,$maxcpupercent));
     return 0; # CPU is too busy
   }
   sleep $debug if ($debug);
   return 1;
} # sub cpuidle

sub get_cpus
{
  &debug("sub get_cpus");
  my $next_tag = "";
  my $done = 0;
  my $cpunum=0;

  $username=$NODES{$nodename}{'username'};
  $password=$NODES{$nodename}{'password'};
  my $s = NaServer->new($nodename, 1, 1);
  $s->set_admin_user($username, $password);

  while ( ! $done ) {
    my $in = NaElement->new("perf-object-instance-list-info-iter");
    $in->child_add_string("objectname",'processor');
    if($next_tag) {
      $in->child_add_string("tag",$next_tag);
      &debug("Get next tag $next_tag\n") if ($debug>1);
    }
    &debug($in->sprintf()) if ($debug>2);
    sleep $debug if ($debug);
    my $out = $s->invoke_elem($in);
    if($out->results_status() eq "failed") {
		print "coming to get cpus\n";
      &error("Cannot get number of CPUS from $nodename: ".$out->results_reason() );
      sleep $debug if ($debug);
      return 0;
    } #if failed

    my $num_records = $out->child_get_int("num-records");
    &debug("$num_records records returned from API call.") if ($debug>1);
    return 1 if (($num_records==0) || (!$num_records));;
    $next_tag = $out->child_get_string("next-tag");
    if($next_tag) {
      &debug("Returned next tag $next_tag") if ($debug>1);
    } else {
      $done=1;
    }
    my $attributes_list = $out->child_get("attributes-list");
    my @result = $attributes_list->children_get();
    foreach $attr (@result){
      $CPU{$nodename}{$cpunum}=$attr->child_get_string("name");
      &debug("CPU{$nodename}{$cpunum} = $CPU{$nodename}{$cpunum}") if ($debug>2);
      $cpunum++;
    }
    &debug("Node $nodename has $cpunum processors");
    $NODES{$nodename}{'numprocessors'}=$cpunum;
  } #while not done
  sleep $debug if ($debug);
  return 1;

} # sub get_cpus



sub GetCredential{

 # Set the request parameters
my $url = 'http://localhost:80/rest/execution/api/create';
my $Wcred = obtainCred();
my $credentials = encode_base64("admin:$Wcred");

my $XML = new XML::Simple;

my ($response,$results,$results1,$results2,$xml,$xml1,$xml2);
my ($uuid,$getHostURL,$getHostList,$getCredURL,$getCredList);
my $Cred ='';
my @Cred;
my $ua = LWP::UserAgent->new(ssl_opts =>{ verify_hostname => 0 });

$response = $ua->post($url,
                         'Content-Type' => 'application/xml',
                         'Authorization' => "Basic $credentials");
						 
# Check for HTTP errors
die 'http status: ' . $response->code . '  ' . $response->message
    unless ($response->is_success);
	
$results = $response->content;
$xml = $XML->XMLin($results);
$uuid = $xml->{uuid};

$getHostURL = 'http://localhost:80/rest/credentials';
$getHostList = $ua->get($getHostURL, 
                         'Content-Type' => 'application/xml',
                         'Authorization' => "Basic $credentials");
						 
die 'http status: ' . $getHostList->code . '  ' . $getHostList->message
    unless ($getHostList->is_success);

$results1 = $getHostList->content;
$xml1 = $XML->XMLin($results1);

if ($xml1->{credential} eq 'ARRAY'){

foreach (@{$xml1->{credential}}){
	if (($_->{'connectionType'} eq 'ONTAP') && ($_->{'matchType'} eq 'EXACT')){
			$getCredURL = "http://localhost/rest/execution/api/".$uuid."/credentials\?hostId=$_->{ip}";
			$getCredList = $ua->get($getCredURL, 
                        'Content-Type' => 'application/xml',
                        'Authorization' => "Basic $credentials");
						 
			die 'http status: ' . $getCredList->code . '  ' . $getCredList->message
			unless ($getCredList->is_success);
				
			$results2 = $getCredList->content;
			$xml2 = $XML->XMLin($results2);
			$Cred = $Cred."nodename\t$_->{ip}\t$xml2->{userName}\t$xml2->{password}\n";
		}
}
}
else{
foreach my $n (keys %{$xml1->{credential}}){
	if ((${$xml1->{credential}->{$n}}{'connectionType'} eq 'ONTAP') && (${$xml1->{credential}->{$n}}{'matchType'} eq 'EXACT')){
			$getCredURL = "http://localhost/rest/execution/api/".$uuid."/credentials\?hostId=${$xml1->{credential}->{$n}}{ip}";
			$getCredList = $ua->get($getCredURL, 
                        'Content-Type' => 'application/xml',
                        'Authorization' => "Basic $credentials");
						 
			die 'http status: ' . $getCredList->code . '  ' . $getCredList->message
			unless ($getCredList->is_success);
				
			$results2 = $getCredList->content;
			$xml2 = $XML->XMLin($results2);
			$Cred = "nodename ${$xml1->{credential}->{$n}}{ip} $xml2->{userName} $xml2->{password}";
			push (@Cred, $Cred);

		}
		else{next;}	
}	
}
print "Successfully retrived the credentials.\n";
return \@Cred;
}

sub obtainCred {
	my $getSid = &getSID();
	my @RegPath = (".DEFAULT\\Software\\WUA", "$getSid\\Software\\WUA");
	my $OpenKey;
	foreach my $Rpath(@RegPath){
		$main::HKEY_USERS->Open($Rpath, $OpenKey) || 
				die "Open: $!";
		$OpenKey->GetValues(\%vals);
		if (! %vals){
			die "Key not found";
		}
		$OpenKey->Close();
		return $vals{'UserAccess'}->[2];
	}
}

sub getSID{
	my $whoami = `whoami /user`;
	chomp($whoami);
	my $val = $whoami;
	my $sid = (split /\s/ ,(split /\n/ ,$val)[6])[1];
	return $sid;
}