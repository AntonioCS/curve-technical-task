# curve-technical-task
Curve technical task

To get the project up and running execute the following on the command line (in the root dir):
```
	make setup
```

This will start docker, run composer install and also execute the tests.

You should be able to access the api endpoint by using the browser (or curl) with the following url:

http://localhost:8080/api/rate

Run `make` to get a list of all available commands

Due to the time constraints I was unable to add caching (there are targets in the Makefile for redis) and I was unable to add tests for everything


