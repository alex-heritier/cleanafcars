.PHONY: server

server:
	php -S 127.0.0.1:8000

sync:
	rsync --exclude=db/* -r * aheritier_cleanafcars@ssh.phx.nearlyfreespeech.net:/home/public

login:
	ssh aheritier_cleanafcars@ssh.phx.nearlyfreespeech.net
