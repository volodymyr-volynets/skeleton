@ECHO OFF
SETLOCAL
SET command=%1
SET verbose=1

REM all
IF "%command%"=="all" (
	SET var=deployment_production
)

REM building application
IF "%command%"=="build" (
	REM create empty git repository
	IF NOT EXIST ".git" (
		call git init
	)
	REM granting permissions
	CALL :permissions
	REM run composer for the first time
	IF NOT EXIST "libraries/vendor/Numbers/Framework/System/Managers" (
		call :composer
	)
	REM process dependencies
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php dependency commit 0 1
	REM run composer for the second time
	call :composer
	REM granting permissions
	CALL :permissions
	REM deploying code in production mode
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php deployment production 0 1
)

REM ----------------------------------------------------------------------------------------------------------------
REM --- Dependencies -----------------------------------------------------------------------------------------------
REM ----------------------------------------------------------------------------------------------------------------
IF "%command%"=="dependency_test" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php dependency test 0
)
IF "%command%"=="dependency_commit" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php dependency commit 0 2
)

REM ----------------------------------------------------------------------------------------------------------------
REM --- Deployment commands ----------------------------------------------------------------------------------------
REM ----------------------------------------------------------------------------------------------------------------
IF "%command%"=="deployment_production" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php deployment production "%verbose%"
)
IF "%command%"=="deployment_development" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php deployment development "%verbose%"
)

REM ----------------------------------------------------------------------------------------------------------------
REM --- Schema & migration commands --------------------------------------------------------------------------------
REM ----------------------------------------------------------------------------------------------------------------
IF "%command%"=="schema_test" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php schema test "%verbose%"
)
IF "%command%"=="schema_commit" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php schema commit "%verbose%"
)
IF "%command%"=="schema_drop" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php schema drop "%verbose%"
)
IF "%command%"=="migration_code_test" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_code test "%verbose%"
)
IF "%command%"=="migration_code_commit" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_code commit "%verbose%"
)
IF "%command%"=="migration_code_drop" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_code drop "%verbose%"
)
IF "%command%"=="migration_db_test" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_db test "%verbose%"
)
IF "%command%"=="migration_db_commit" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_db commit "%verbose%"
)
IF "%command%"=="migration_db_rollback" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php migration_db rollback "%verbose%"
)

REM --------------------------------------------------------------------------------------------------------------------------------------------
REM --- Unit testing commands ------------------------------------------------------------------------------------------------------------------
REM --------------------------------------------------------------------------------------------------------------------------------------------
IF "%command%"=="unit_testing_application" (
	call phpunit --debug --configuration application/overrides/unit_tests/application.xml
)
IF "%command%"=="unit_testing_submodules" (
	call phpunit --debug --configuration application/overrides/unit_tests/submodules.xml
)

REM --------------------------------------------------------------------------------------------------------------------------------------------
REM --- Cache commands -------------------------------------------------------------------------------------------------------------------------
REM --------------------------------------------------------------------------------------------------------------------------------------------
IF "%command%"=="cache_drop" (
	call php libraries/vendor/Numbers/Framework/System/Managers/Manager.php cache drop "%verbose%"
)

REM --------------------------------------------------------------------------------------------------------------------------------------------
REM --- Help commands --------------------------------------------------------------------------------------------------------------------------
REM --------------------------------------------------------------------------------------------------------------------------------------------
IF "%command%"=="help" (
	echo "Available commands:"
REM	echo   Development commands:
REM	echo		make development_test - run a test php script from command line
REM	echo		make development_symlink_framework - link to numbers framework repositories
	echo "  Deployment commands:"
	echo "		make deployment_production - deploying code in production mode"
	echo "		make deployment_development - deploying code in development mode"
	echo "  Unit testing commands:"
	echo "		make unit_testing_application - test application"
	echo "		make unit_testing_submodules - test framework and submodules"
	echo "  Schema & migration commands:"
	echo "		make schema_test - test for changes between code and database"
	echo "		make schema_commit - commit changes from code to database"
	echo "		make schema_drop - drop schema in database (empty database)"
	echo "		make migration_code_test - test for changes between code and saved migrations"
	echo "		make migration_code_commit - commit changes from code to migrations"
	echo "		make migration_code_drop - drop migrations from the code"
	echo "		migration_db_test - test migration between code and database"
	echo "		migration_db_commit - commit changes from migrations to database"
	echo "		migration_db_rollback - rollback to particular migration"
	echo "  Dependencies:"
	echo "		make dependency_test - testing dependencies in test mode"
	echo "		make dependency_commit - commit dependency changes"
	echo "  Caches:"
	echo "		make cache_drop - reset caches"
	echo "  Verbose:"
	echo "		Some commands support verbose mode that would provide additional information, to enable - add "verbose=1" after make."
	echo "		For example: make verbose=1 schema_test"
)

REM force execution to quit at the end of the "main" logic
exit /B %ERRORLEVEL%

:permissions
	REM	@-chmod -R 0777 $(shell pwd);
	REM	@-chmod -R 0777 $(shell pwd)/../cache;
	REM	@-chmod -R 0777 $(shell pwd)/../documents;
exit /B 0

:composer
	chdir /D libraries
	IF EXIST "vendor" (
		rmdir vendor
	)
	call composer update --prefer-source
	chdir /D vendor
	for /f "tokens=* delims=" %%i in ('dir /s /b /a:d *.git') do (
		rd /s /q "%%i"
	)
	chdir /D ..
	chdir /D ..
exit /B 0

