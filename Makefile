.PHONY: up bd run
up:
	alias sail='bash vendor/bin/sail'
	sail up -d
bd:
 	sail artisan migrate:refresh
run: 
	make up
	make bd