# Skeleton Application for Numbers Framework

# Installation Instructions
1) Create a new project:

	composer create-project --no-install numbers/skeleton [project directory]

	Note: composer must be installed on your system.
	Note: we skip vendor directory here by using --no-install option;

2) Setup apache:

	2.1) Take [project directory]/conf/dev/vhosts.000.general.conf as a template and put it to apache configuration folder;
	2.2) Take [project directory]/conf/dev/vhosts.100.your_domain.conf as a template and replace [your_domain] with your domain, adjust directories as per your system and put it to your apache configuration folder;
	2.3) Restart apache
