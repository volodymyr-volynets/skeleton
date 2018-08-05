.PHONY: all build dependency_test dependency_commit deployment_production deployment_development

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
	@-if ! test -d "libraries/vendor/Numbers/Framework/System/Managers"; \
	then \
		$(MAKE) composer; \
	fi
	# process dependencies
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php dependency commit 0 1;
	# run composer for the second time
	@-$(MAKE) composer;
	@# granting permissions
	@-$(MAKE) permissions;
	# deploying code in production mode
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php deployment production 0 1;

composer:
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

# ----------------------------------------------------------------------------------------------------------------
# --- Dependencies -----------------------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------------------------------
dependency_test:
	@# testing dependencies in test mode
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php dependency test $(verbose);

dependency_commit:
	@# commit dependency changes
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php dependency commit $(verbose);

# ----------------------------------------------------------------------------------------------------------------
# --- Deployment commands ----------------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------------------------------
deployment_production:
	@# deploying code in production mode
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php deployment production $(verbose);

deployment_development:
	@# deploying code in development mode
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php deployment development $(verbose);

# ----------------------------------------------------------------------------------------------------------------
# --- Schema & migration commands --------------------------------------------------------------------------------
# ----------------------------------------------------------------------------------------------------------------
schema_test:
	@# test for changes between code and database
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php schema test $(verbose);

schema_commit:
	@# commit changes from code to database
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php schema commit $(verbose);

schema_drop:
	@# drop schema in database (empty database)
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php schema drop $(verbose);

migration_code_test:
	@# test for changes between code and saved migrations
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_code test $(verbose);

migration_code_commit:
	@# commit changes from code to migrations
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_code commit $(verbose);

migration_code_drop:
	@# drop migrations
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_code drop $(verbose);

migration_db_test:
	@# test migration between code and database
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_db test $(verbose);

migration_db_commit:
	@# commit changes from migrations to database
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_db commit $(verbose);

migration_db_rollback:
	@# rollback to particular migration
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_db rollback $(verbose);

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Unit testing commands ------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
unit_testing_application:
	@# test application
	@-phpunit --debug --configuration ./application/Overrides/UnitTests/application.xml

unit_testing_submodules:
	@# test framework and submodules
	@-phpunit --debug --configuration ./application/Overrides/UnitTests/submodules.xml

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Development commands -------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
development_test:
	@# use this if you need to develop a test php script and run it from command line
	@# replace it to run your custom script and use test.php as a template
	@-php libraries/vendor/Numbers/Framework/System/Managers/test.php;

development_symlink_framework:
	@# building dev symlinks, so we can commit changes right in proper git repository
	@-for f in $(shell cd /Development/numbers; ls); \
	do \
	    echo " -> $$f"; \
	    rm -r libraries/vendor/Numbers/$$f ; \
	    ln -s /Development/numbers/$$f libraries/vendor/Numbers/$$f ; \
	done;

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Cache commands -------------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
cache_drop:
	@# reset caches
	@-php libraries/vendor/Numbers/Framework/System/Managers/Manager.php cache drop $(verbose);

# --------------------------------------------------------------------------------------------------------------------------------------------
# --- Help commands --------------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------------------
help:
	#
	#
	# Available commands:
	#   Development commands:
	#	make development_test - run a test php script from command line
	#	make development_symlink_framework - link to numbers framework repositories
	#   Deployment commands:
	#	make deployment_production - deploying code in production mode
	#	make deployment_development - deploying code in development mode
	#   Unit testing commands:
	#	make unit_testing_application - test application
	#	make unit_testing_submodules - test framework and submodules
	#   Schema & migration commands:
	#	make schema_test - test for changes between code and database
	#	make schema_commit - commit changes from code to database
	#	make schema_drop - drop schema in database (empty database)
	#	make migration_code_test - test for changes between code and saved migrations
	#	make migration_code_commit - commit changes from code to migrations
	#	make migration_code_drop - drop migrations from the code
	#	make migration_db_test - test migration between code and database
	#	make migration_db_commit - commit changes from migrations to database
	#	make migration_db_rollback - rollback to particular migration
	#   Dependencies:
	#	make dependency_test - testing dependencies in test mode
	#	make dependency_commit - commit dependency changes
	#   Caches:
	#	make cache_drop - reset caches
	#   Other:
	#	make build - building application
	#	make composer - updating composer repositories
	#	make permissions - granting permissions
	#
	#   Verbose:
	#	Some commands support verbose mode that would provide additional information, to enable - add "verbose=1" after make.
	#	For example: make verbose=1 schema_test
	#