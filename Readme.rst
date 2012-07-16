TYPO3.Docs
============

This is a package for rendering TYPO3 documentation

@todo: create different queue
	* git
	* svn
	* ter
	* custom-git
	* custom-svn
	* get-the-docs
@todo: create github repository
@todo: put on-line a first example
@todo: make the doc be rendered properly
@todo: save the generated docs to the CR
@todo: check interaction with TYPO3.DocTools
@todo: add hook to render git

Installation
=============

* Download the source code::

	git clone --recursive git://git.typo3.org/Sites/DocsTypo3Org.git

* Install & launch ``beanstalkd``

* In the console, type::

	./flow3 job:work renderDocumentation

* and in another console, you can add jobs using::

	./flow3 documentationrendering:addjob /tmp/foo



Hints
=============

Package `TYPO3.Queue` includes a jobManager.

Command `job:work` comes from TYPO3.Queue
Command `documentationrendering:addjob` comes from TYPO3.Docs

