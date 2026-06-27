Arguments := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

.PHONY: all build dependency_test dependency_commit dependency_cs_fixer dependency_all deployment_production dp deployment_development dd dd2 deployment_sites ds deployment_rotate localize_commit fixer_test fixer_commit fixer_lint create shell_a shell command daemons_start daemons_stop daemons_restart

all: deployment_production

build:
	# building application
	@-if ! test -d ".git"; \
	then \
		git init; \
	fi;
	@# granting permissions
	@-$(MAKE) permissions;
	# run composer for the first time
	@-if ! test -d "libraries/vendor/numbers/framework/System/Managers"; \
	then \
		$(MAKE) composer; \
	fi
	# process dependencies
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php dependency commit 0 1;
	# run composer for the second time
	@-$(MAKE) composer;
	@# granting permissions
	@-$(MAKE) permissions;
	# deploying code in production mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php deployment production 0 1;

composer:
	# process dependencies
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php dependency commit 0 1;
	# updating composer repositories
	@-cd libraries; \
	rm -r vendor; \
	composer update --prefer-source; \
	find vendor -type d -name ".git" | xargs rm -rf \;

permissions:
	# granting permissions
	@-chmod -R 0777 $(shell pwd);
	@-chmod -R 0777 $(shell pwd)/../cache;
	@-chmod -R 0777 $(shell pwd)/../documents;
	@-chmod -R 0777 libraries/vendor/numbers/
	@-chmod -R 0777 libraries/private/
	@-chmod -R 0777 public_html/components

# ----------------------------------------------------------------------------------------------------------------
# --- Dependencies -----------------------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------------------------------
dependency_test:
	@# testing dependencies in test mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php dependency test 0;

dependency_commit:
	@# commit dependency changes
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php dependency commit 0 2;

dependency_cs_fixer:
	@# cs fix dependency
	@-cd libraries/vendor/numbers/framework/System/Config; \
	  php-cs-fixer fix --config=.php-cs-fixer.php-8.3.php --diff;

dependency_all:
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php dependency commit 0 2;
	@-cd libraries/vendor/numbers/framework/System/Config; \
	  php-cs-fixer fix --config=.php-cs-fixer.php-8.3.php --diff;

# ----------------------------------------------------------------------------------------------------------------
# --- Deployment commands ----------------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------------------------------
deployment_production:
	@# deploying code in production mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php deployment production $(verbose);

dp:
	@# deploying code in production mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php deployment production $(verbose);

deployment_development:
	@# deploying code in development mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php deployment development $(verbose);

dd:
	@# deploying code in development mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php deployment development $(verbose);

dd2:
	(cd public_sites && bash sites.sh)
	@# deploying code in development mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php deployment development 0 1;

deployment_sites:
	(cd public_sites && bash sites.sh)
	@# deploying code in production mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php deployment production 0 1;

ds:
	(cd public_sites && bash sites.sh)
	@# deploying code in production mode
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php deployment production 0 1;

deployment_rotate:
	@# rotating deplyment directories
	@-cd ../deployments; \
	find . -mtime +1 -delete;

# ----------------------------------------------------------------------------------------------------------------
# --- Schema & migration commands --------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------------------------------
schema_test:
	@# test for changes between code and database
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php schema test $(verbose);

schema_commit:
	@# commit changes from code to database
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php schema commit $(verbose);

schema_drop:
	@# drop schema in database (empty database)
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php schema drop $(verbose);

migration_code_test:
	@# test for changes between code and saved migrations
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php migration_code test $(verbose);

migration_code_commit:
	@# commit changes from code to migrations
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php migration_code commit $(verbose);

migration_code_drop:
	@# drop migrations
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php migration_code drop $(verbose);

migration_db_test:
	@# test migration between code and database
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php migration_db test $(verbose);

migration_db_commit:
	@# commit changes from migrations to database
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php migration_db commit $(verbose);

migration_db_rollback:
	@# rollback to particular migration
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php migration_db rollback $(verbose);

migration_new:
	@# create new migration
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php migration_new new $(verbose);

seeder_new:
	@# create new seeder
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php seeder new $(verbose);

seeder_all:
	@# seed all seeders
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php seeder all $(verbose);

seeder_one:
	@# seed one seeder
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php seeder one $(verbose);

data_import:
	@# import data from model
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php data_import commit $(verbose);

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Unit testing commands ------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
unit_testing_application:
	@# test application
	@-phpunit --testdox --stop-on-failure --configuration ./application/Overrides/UnitTests/application.xml

unit_testing_submodules:
	@# test framework and submodules
	@-phpunit --testdox --stop-on-failure --configuration ./application/Overrides/UnitTests/submodules.xml

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Development commands -------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
development_test:
	@# use this if you need to develop a test php script and run it from command line
	@# replace it to run your custom script and use test.php as a template
	@-php libraries/vendor/numbers/framework/System/Managers/Test.php;

development_symlink_framework:
	@# building dev symlinks, so we can commit changes right in proper git repository
	@-for f in $(shell cd /Development/numbers-vendor; ls); \
	do \
	    echo " -> $$f"; \
	    rm -r libraries/vendor/Numbers/$$f ; \
	    ln -s /Development/numbers-vendor/$$f libraries/vendor/Numbers/$$f ; \
	done;

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Cache commands -------------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
cache_drop:
	@# reset caches
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php cache drop $(verbose);


# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Other commands -------------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
localize_commit:
	@# test localization
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php \\Numbers\\Internalization\\Internalization\\Manager\\Localization commit $(verbose);
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php dependency commit 0 2;
	@-cd libraries/vendor/numbers/framework/System/Config; \
	  php-cs-fixer fix --config=.php-cs-fixer.php-8.4.php --diff;

localize_drop:
	@# test localization
	@-php libraries/vendor/numbers/framework/System/Managers/Manager.php \\Numbers\\Internalization\\Internalization\\Manager\\Prune commit $(verbose);
	@-cd libraries/vendor/numbers/framework/System/Config; \
	  php-cs-fixer fix --config=.php-cs-fixer.php-8.4.php --diff;

fixer_test:
	@# check php code
	@-cd libraries/vendor/numbers/framework/System/Config; \
	php-cs-fixer fix --config=.php-cs-fixer.php-8.4.php --dry-run --diff;

fixer_commit:
	@# fix php code
	@-cd libraries/vendor/numbers/framework/System/Config; \
	  php-cs-fixer fix --config=.php-cs-fixer.php-8.5.php --diff;

fixer_lint:
	@# lint php code
	@-cd libraries/vendor/numbers; \
	  find . -type f -name '*.php' -exec php -l {} \; >/dev/null;


# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Create commands ------------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
create:
	@# create
	@-php libraries/vendor/numbers/framework/System/Managers/Creator.php create $(type) $(module) $(name);

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Shell commands -------------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
shell_a:
	@# PHP interactive shell
	@-php -a

command:
	@# run shell command
	@-php libraries/vendor/numbers/backend/System/ShellCommand/Manager/ShellCommands.php command "$(Arguments)"

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Daemons commands -----------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
daemons_start:
	@# start Daemons
	@-./Daemons.sh

daemons_stop:
	@# stop Daemons
	@-./Daemons.sh stop

daemons_restart:
	@# restart Daemons
	@-./Daemons.sh stop
	@-./Daemons.sh

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Help commands --------------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
help:
	#
	#
	# Available commands:
	#   Development commands:
	#		make development_test - run a test php script from command line
	#		make development_symlink_framework - link to numbers framework repositories
	#   Deployment commands:
	#		make deployment_production - deploying code in production mode
	#		make deployment_development - deploying code in development mode
	#   Unit testing commands:
	#		make unit_testing_application - test application
	#		make unit_testing_submodules - test framework and submodules
	#   Schema & migration commands:
	#		make schema_test - test for changes between code and database
	#		make schema_commit - commit changes from code to database
	#		make schema_drop - drop schema in database (empty database)
	#		make migration_code_test - test for changes between code and saved migrations
	#		make migration_code_commit - commit changes from code to migrations
	#		make migration_code_drop - drop migrations from the code
	#		make migration_db_test - test migration between code and database
	#		make migration_db_commit - commit changes from migrations to database
	#		make migration_db_rollback - rollback to particular migration
	#		make migration_new - create new migration
	#		make seeder_new - create new seeder
	#		make seeder_all - run all seeders
	#		make seeder_one - run one seeder
	#		make data_import - import data from model
	#   Dependencies:
	#		make dependency_test - testing dependencies in test mode
	#		make dependency_commit - commit dependency changes
	#   Caches:
	#		make cache_drop - reset caches
	#   Other:
	#		make build - building application
	#		make composer - updating composer repositories
	#		make permissions - granting permissions
	#		make localize_commit - translate localization files
	#		make fixer_test - list files to be fixed
	#		make fixer_commit - fix php files
	#		make fixer_lint - lint php code
	#		make shell_a - PHP interactive shell
	#		make command - run shell command
	#		make daemons_start - start daemons
	#		make daemons_stop - stop daemons
	#		make daemons_restart - restart daemons
	#
	#   Verbose:
	#		Some commands support verbose mode that would provide additional information, to enable - add "verbose=1" after make.
	#		For example: make verbose=1 schema_test
	#