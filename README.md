# Requirements
Requires php 7.2 and composer.
Use "composer dump_autoload" or "composer install" in project directory before using this program

# About
cust-reg.php is a simple client registration program. Usage: php cust-reg.php \<command\>. It expects one of the following commands:

add<br/>
Description: Adds one client.<br/>
Usage: add "firstname" "lastname" "email" "phonenumber1" "phonenumber2" "comment"

add --csv<br/>
Description: Adds clients from csv.<br/>
Usage: add --csv="path/to/csv"

edit<br/>
Description: Edits client.<br/>
Usage: edit [--firstname="new value"] [--lastname="new value"] [--email="new value"] [--phonenumber1="new value"] [--phonenumber2="new value"] [--comment="new value"] "client email"

delete<br/>
Description: Deletes a client.<br/>
Usage: delete "client email"

list<br/>
Description: Lists clients.<br/>
Usage: list

help<br/>
Description: Prints this help message.<br/>
Usage: help

end<br/>
Description: Deletes data file.<br/>
Usage: end
