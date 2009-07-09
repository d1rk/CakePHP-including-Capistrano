# This defines a deployment "recipe" that you can feed to capistrano
# (http://manuals.rubyonrails.com/read/book/17). It allows you to automate
# (among other things) the deployment of your application.

# =============================================================================
# REQUIRED VARIABLES
# =============================================================================
# You must always specify the application and repository for every recipe. The
# repository must be the URL of the repository you want this recipe to
# correspond to. The deploy_to path must be the path on each machine that will
# form the root of the application path.

set :application, ENV['PROJECT_NAME'] || 'deployapp' #this will be autofilled with project_name
set :repository, ENV['SCM_URL'] || 'put your repository path here' #will also be autofilled

# by default subversion is assumed
set :scm, :git if 'git' == ENV['SCM'] #change to git, if needed

# =============================================================================
# ROLES
# =============================================================================
# You can define any number of roles, each of which contains any number of
# machines. Roles might include such things as :web, or :app, or :db, defining
# what the purpose of each machine is. You can also specify options that can
# be used to single out a specific subset of boxes in a particular role, like
# :primary => true.

# if you want to deploy with another host value,
# uncomment this block definition and define your own :host, :user and other params outside of it,
# but don't remove it!

#if ENV['STAGE']
set :host, "localhost"
set :user, "deploy"
set :deploy_to, "/var/www/apps/#{application}"

#CakePHP specific
set :webroot_path, "app/webroot"
set :tmp_path, "app/tmp"

role :app, host
role :web, host
role :db,  host, :primary => true

#examples of host-notations
#role :web, "www01.example.com", "www02.example.com"
#role :app, "app01.example.com", "app02.example.com", "app03.example.com"
#role :db,  "db01.example.com", :primary => true
#role :db,  "db02.example.com", "db03.example.com"

# =============================================================================
# OPTIONAL VARIABLES
# =============================================================================
# set :deploy_to, "/path/to/app" # defaults to "/u/apps/#{application}"
# set :user, "flippy"            # defaults to the currently logged in user
# set :scm, :darcs               # defaults to :subversion
# set :svn, "/path/to/svn"       # defaults to searching the PATH
# set :darcs, "/path/to/darcs"   # defaults to searching the PATH
# set :cvs, "/path/to/cvs"       # defaults to searching the PATH
# set :gateway, "gate.host.com"  # default to no gateway

# =============================================================================
# SSH OPTIONS
# =============================================================================
# ssh_options[:keys] = %w(/path/to/my/key /path/to/another/key)
# ssh_options[:port] = 25

# =============================================================================
# TASKS
# =============================================================================
# Define tasks that run on all (or only some) of the machines. You can specify
# a role (or set of roles) that each task should be executed on. You can also
# narrow the set of servers to a subset of a role by specifying options, which
# must match the options given for the servers to select (like :primary => true)

# Tasks may take advantage of several different helper methods to interact
# with the remote server(s). These are:
#
# * run(command, options={}, &block): execute the given command on all servers
#   associated with the current task, in parallel. The block, if given, should
#   accept three parameters: the communication channel, a symbol identifying the
#   type of stream (:err or :out), and the data. The block is invoked for all
#   output from the command, allowing you to inspect output and act
#   accordingly.
# * sudo(command, options={}, &block): same as run, but it executes the command
#   via sudo.
# * delete(path, options={}): deletes the given file or directory from all
#   associated servers. If :recursive => true is given in the options, the
#   delete uses "rm -rf" instead of "rm -f".
# * put(buffer, path, options={}): creates or overwrites a file at "path" on
#   all associated servers, populating it with the contents of "buffer". You
#   can specify :mode as an integer value, which will be used to set the mode
#   on the file.
# * render(template, options={}) or render(options={}): renders the given
#   template and returns a string. Alternatively, if the :template key is given,
#   it will be treated as the contents of the template to render. Any other keys
#   are treated as local variables, which are made available to the (ERb)
#   template.

desc "Demonstrates the various helper methods available to recipes."
task :helper_demo do
  # "setup" is a standard task which sets up the directory structure on the
  # remote servers. It is a good idea to run the "setup" task at least once
  # at the beginning of your app's lifetime (it is non-destructive).
  setup

  buffer = render("maintenance.rhtml", :deadline => ENV['UNTIL'])
  put buffer, "#{shared_path}/system/maintenance.html", :mode => 0644
  sudo "killall -USR1 dispatch.fcgi"
  run "#{release_path}/script/spin"
  delete "#{shared_path}/system/maintenance.html"
end


namespace :deploy do
  
  desc "This will complete the whole deployment"
  task :default do
    deploy
  end
  
  # set owner
  desc "This will set owner on public files/folders"
  task :change_owner, :roles => :app do
    sudo "chown -R #{user}:www-data #{deploy_to}"
  end

  # set owner
  desc "This will make the latest release to current"
  task :symlink, :roles => :app do
    run "cp -rf /home/#{user}/deploy_#{application} #{release_path}" #copy files into release-folder
    run "find #{release_path} -type d | grep \.svn | sort -r | xargs rm -rf" #remove all .svn folders
    change_owner
    sudo "chmod -R 777 #{release_path}/app/tmp"
    run "cp -u #{shared_path}/system/database.php #{release_path}/app/config/database.php"
    run "rm -rf #{current_path}" #make sure, current does not exist
    run "ln -nfs #{release_path} #{current_path}" #symlink
  end

  desc "This will deploy the app"
  task :deploy do
    VAR_DUMP = <<-TEXT
application: #{application}
user: #{user}
deploy_to: #{deploy_to}
webroot_path: #{webroot_path}
current_path: #{current_path}
release_path: #{release_path}
TEXT
    put VAR_DUMP, "/tmp/last_cap_run"
    passed = false
    run "/home/#{user}/deploy_#{application}/cake/console/cake -app /home/#{user}/deploy_#{application}/app testsuite app all" do |ch, stream, data|
      passed = true if data =~ /\d\/\d test cases complete: \d passes\./ #check, if all tests passed
    end
    exit 1 unless passed
    run "echo 'ALL TESTS PASSED'" #sometimes this is useful in output
    symlink
  end
  
  desc "This will migrate the db"
  task :migrate do
    run "#{current_path}/cake/console/cake -app #{current_path}/app migrate" # release_path will not work, it is a new one since deploy
  end
  
  desc "This will always be executed at the end of each run (independet of deployment run)"
  task :cleanup do
    #run "echo 'Done.'"
  end
  
  desc "This will create a database.php"
  task :create_db_config do
    DATABASE_CONFIG = <<-TEXT
<?php
class DATABASE_CONFIG {

	var $default = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => '#{application}',
		'password' => 'einstein',
		'database' => '#{application}_int',
		'prefix' => '',
	);

	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => '#{application}',
		'password' => 'einstein',
		'database' => '#{application}_test',
		'prefix' => '',
	);
}
?>
TEXT
    put DATABASE_CONFIG, "#{shared_path}/system/database.php", :mode => 0644 #we put that in the shared-folder
  end
  
  desc "This will create databases"
  task :create_tables, :roles => :db do
    run "mysql -u root -e \"CREATE DATABASE IF NOT EXISTS #{application}_int; CREATE DATABASE IF NOT EXISTS #{application}_test;\"" #create tables
  end
  
  #creates a user (if not already there) with permissions on tables that start with application_name and underscore, e.g. MyApp_*
  desc "This will create database user"
  task :create_db_user, :roles => :db do
    run "mysql -u root -e 'use mysql; GRANT ALL PRIVILEGES ON `#{application}_%`.* TO \"#{application}\"@\"localhost\" IDENTIFIED BY \"einstein\";'"
  end
  
  desc "This will create apache-config and enable it."
  task :create_site, :roles => :app do
    sudo "mkdir -p #{deploy_to}/current  #{deploy_to}/releases #{deploy_to}/shared"
    sudo "chown -R #{user}:www-data #{deploy_to}"
    CONFIG_FILE = "/etc/apache2/sites-available/#{application}"
    TEMPLATE = <<-TEXT
<VirtualHost *:80>
  DocumentRoot %s
  # Custom log file locations
  ErrorLog /var/log/apache2/#{application}_errors.log
  CustomLog /var/log/apache2/#{application}_access.log combined
</VirtualHost>
TEXT

    unless test(?f, CONFIG_FILE)
      config = TEMPLATE % "#{deploy_to}/current/#{webroot_path}"
      File.open("/tmp/#{application}", 'w+') {|f| f.write(config)}
      sudo "mv /tmp/#{application} #{CONFIG_FILE}"
      sudo 'a2dissite default'
      sudo "a2ensite #{application}"
      sudo '/etc/init.d/apache2 reload'
      sudo "rm -rf #{deploy_to}/current"
    end
    create_db_config
    create_db_user
    create_tables
  end

end