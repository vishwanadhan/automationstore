#!C:\Program Files\NetApp\WFA\Perl64\bin\perl.exe

use strict;
use warnings;
use Win32;
use Win32::Daemon;

main();

use constant SERVICE_NAME => 'QoSPolicy';
use constant SERVICE_DESC => 'My service';

sub main
{
   # Get command line argument - if none passed, use empty string
   my $opt = shift (@ARGV) || "";

   # Check command line argument
   if ($opt =~ /^(-i|--install)$/i)
   {
      install_service(SERVICE_NAME, SERVICE_DESC);
   } 
   elsif ($opt =~ /^(-r|--remove)$/i)
   {
      remove_service(SERVICE_NAME);
   }
   elsif ($opt =~ /^(--run)$/i)
   {
      # Redirect STDOUT and STDERR to a log file
      # Derive the name of the file from the name of the program
      # The log file will be in the scripts directory, with extension .log
      my ($cwd,$bn,$ext) = 
      ( Win32::GetFullPathName($0) =~ /^(.*\\)(.*)\.(.*)$/ ) [0..2] ;
	  $cwd = $cwd ."..\\jboss\\standalone\\tmp\\wfa\\";	  
      my $log = $cwd . $bn . ".log";  
	  print $log;
      # Redirect STDOUT and STDERR to log file
      open(STDOUT, ">> $log") or die "Couldn't open $log for appending: $!\n";
      open(STDERR, ">&STDOUT");
      # Autoflush, no buffering
      $|=1;
  
      # Register the events which the service responds to
      Win32::Daemon::RegisterCallbacks( {
            start       =>  \&Callback_Start,
            running     =>  \&Callback_Running,
            stop        =>  \&Callback_Stop,
            pause       =>  \&Callback_Pause,
            continue    =>  \&Callback_Continue,
         } );
      my %Context = (
         last_state => SERVICE_STOPPED,
         start_time => time(),
      );
	  
      # Start the service passing in a context and indicating to callback 
      # using the "Running" event every 2000 milliseconds (2 seconds).
      # NOTE: the StartService method with in 'callback mode' will block, in other
      # words it won't return until the service has stopped, but the callbacks below
      # will respond to the various events - START, STOP, PAUSE etc...
      Win32::Daemon::StartService( \%Context, 2000 );
      
      # Here the service has stopped
      close STDERR; close STDOUT;
   }
   else 
   {
      print "No valid options passed - nothing done\n";
   }
}


sub Callback_Running
{
   my( $Event, $Context ) = @_;

   # Note that here you want to check that the state
   # is indeed SERVICE_RUNNING. Even though the Running
   # callback is called it could have done so before 
   # calling the "Start" callback.
   if( SERVICE_RUNNING == Win32::Daemon::State() )
   {
      # ... process your main stuff here...
      # ... note that here there is no need to
      #     change the state
      
      # For now just print hello to the STDOUT, which goes to the log file
      print "Starting KP Script in the background";
	  my ($cwd,$bn,$ext) = 
      ( Win32::GetFullPathName($0) =~ /^(.*\\)(.*)\.(.*)$/ ) [0..2] ;
	  #my $task = system ("tasklist /FI \"IMAGENAME eq kpv075.pl\" /NH | find /I /N  /C \"kpv075\" >NUL");
	  #my $task = system ("tasklist /FI \"IMAGENAME eq perl.exe\" /NH | find /I /N  /C \"perl.exe\" >NUL");
	  
	  my $path = "\"$^X\"";
	  my $kpscript = $cwd."kp.pl";
	  my $processId = $cwd."..\\kitchen\\ProcessId";
	  
	  if (! -e $processId){
			print "KP Script is not started in the background\n";
			system ("$path \"$kpscript\"");
	  }
	  else{
	     open PROCESS, "< $processId";
	     my $pId = <PROCESS>;
	     my $task = `tasklist /FI \"IMAGENAME eq perl.exe\" /NH | find /I /N  /C \"$pId\"`;
	     chomp($task);
	     print "Run KP Script $path $kpscript\n";
	     print "KP Script running status  :: $task\n\n";
	     if ($task == 0){
			print "KP Script is not running in the background\n";
			system ("$path \"$kpscript\""); 
	     }
		}
	  $Context->{last_state} = SERVICE_RUNNING;
      Win32::Daemon::State( SERVICE_RUNNING );
   }
}    

sub Callback_Start
{
   my( $Event, $Context ) = @_;
   # Initialization code
   # ...do whatever you need to do to start...

   print "Starting the QoS Service\n";

   $Context->{last_state} = SERVICE_RUNNING;
   Win32::Daemon::State( SERVICE_RUNNING );
}

sub Callback_Pause
{
   my( $Event, $Context ) = @_;

   print "Pausing...\n";

   $Context->{last_state} = SERVICE_PAUSED;
   Win32::Daemon::State( SERVICE_PAUSED );
}

sub Callback_Continue
{
   my( $Event, $Context ) = @_;

   print "Continuing...\n";

   $Context->{last_state} = SERVICE_RUNNING;
   Win32::Daemon::State( SERVICE_RUNNING );
}

sub Callback_Stop
{
   my( $Event, $Context ) = @_;

   print "Stopping...\n";

   $Context->{last_state} = SERVICE_STOPPED;
   Win32::Daemon::State( SERVICE_STOPPED );

   # We need to notify the Daemon that we want to stop callbacks and the service.
   Win32::Daemon::StopService();
}


sub install_service
{
   my ($srv_name, $srv_desc) = @_;
   my ($path, $parameters);
   
   # Get the program's full filename, break it down into constituent parts
   my $fn = Win32::GetFullPathName("kp.pl");
   my ($cwd,$bn,$ext) = ( $fn =~ /^(.*\\)(.*)\.(.*)$/ ) [0..2] ;

   # Determine service's path to executable based on file extension
   if ($ext eq "pl")
   {
      # Source perl script - invoke perl interpreter
      $path = "\"$^X\"";
      # Parameters include extra @INC directories and perl script
      # @INC directories must not end in \ otherwise perl hangs
      my $inc = ($cwd =~ /^(.*?)[\\]?$/) [0];
      # The command includes the --run switch needed in main()
      $parameters = "-I " . "\"$inc\"" . " \"$fn\" --run";
   }
   elsif ($ext eq "exe")
   {
      # Compiled perl script - invoke the compiled script
      $path = "\"$fn\"";
      $parameters = "";
   }
   else 
   {
      # Invalid file type?
      die "Can not install service for $fn,
      file extension $ext not supported\n";
   }

   # Populate the service configuration hash 
   # The hash is required by Win32::Daemon::CreateService
   my %srv_config = (
      name         => $srv_name,
      display      => $srv_name,
      path         => $path,
      description  => $srv_desc,
      parameters   => $parameters,
      service_type => SERVICE_WIN32_OWN_PROCESS,
      start_type   => SERVICE_AUTO_START,
   );
   # Install the service
   if( Win32::Daemon::CreateService( \%srv_config ) )
   {
      print "Service installed successfully\n";
   }
   else 
   {
      print "Failed to install service\n";
   }
}

sub remove_service
{
   my ($srv_name, $hostname) = @_;
   $hostname ||= Win32::NodeName(); 
   if ( Win32::Daemon::DeleteService ( $srv_name ) ) 
   {
      print "Service uninstalled successfully\n";
   }
   else 
   {
      print "Failed to uninstall service\n";
   }
}