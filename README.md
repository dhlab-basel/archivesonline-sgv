# archivesonline-sgv
This project is an interface for the Archives Online. It is a platform where users can search for public accessible meta data and documents in all the involved archives. This interface uses the salsah extended search API and returns the result in a XML file.

## Deployment
When you want to deploy to the server make sure that in the file "Controller/Api.php" you set the switch case statement right. 
Instead of `case "/sgv/"` change it to  `case ""`  
Look up in the file for the comment.

## Unit Testing
Before running the unit tests with the following line make sure there is PHPUnit installed:   
`phpunit Test`  
For a more detailed listing of the tests you use:  
`phpunit --testdox  Test`

## Bug
In case you find any bugs please let us know by reporting it on github:  
https://github.com/dhlab-basel/archivesonline-sgv/issues

## Author
Vijeinath Tissaveerasingham  
vijeinath.tissaveerasingham@unibas.ch

Digital Humanities Lab  
University of Basel   
Bernoullistrasse 32  
4056 Basel  
Switzerland
