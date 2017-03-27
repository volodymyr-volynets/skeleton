# Skeleton Application for Numbers Framework

# Installation Instructions
1) Create a new project:

	composer create-project --no-install numbers/skeleton [project directory]

	Note: Composer must be installed on your system.
	Note: We skip vendor directory here by using --no-install option;

2) Build application:

	Navigate to [project directory] and run following command:

		make build

	Note: By default application will be complied to run in production mode. You can use "make deployment_development" to compile it in development mode.

3) Setup host:

	3.1) For single host applications add an entry to /etc/hosts like:

		127.0.0.1	[your domain]

	3.2) For multi host application you need to install "dnsmasq" and add an entry to dnsmasq.conf file as follows:

		address=/.[your domain]/127.0.0.1

		Note: For development you can add entry to process all domains that ends with .local:

			address=/.local/127.0.0.1

4) Setup Apache:

	4.1) Take [project directory]/conf/dev/vhosts.000.general.conf as a template and put it to apache configuration folder;

	4.2) Take [project directory]/conf/dev/vhosts.100.your_domain.conf as a template and replace [your domain] with your domain, adjust directories as per your system and put it to your apache configuration folder;

	4.3) Restart Apache

5) Create database and promote schema changes:

	Framework provides two options to promote schema changes to database:

	5.1) Direct schema changes, available commands:
		make schema_test
		make schema_commit
		make schema_drop

	5.2) Migrations, available commands:
		make migration_code_test
		make migration_code_commit
		make migration_code_drop
		make migration_db_test
		make migration_db_commit
		make migration_db_rollback

	Note: If you want to have migrations as mandatory promotion method you can set following setting in environment.ini file:

			application.structure.db_migration = 1

		In this case you cannot run schema commands and vise versa.

	Note: you need to navigate to [project directory] to run make commands.

6) Run your application in a browser and enjoy.