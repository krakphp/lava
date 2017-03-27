.PHONY: test doc

test:
	./vendor/bin/peridot test

doc:
	cd doc; make html
