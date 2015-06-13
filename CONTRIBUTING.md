# How to Contribute

Contributing to the PHP Mentoring App is a great way to help out the PHP Mentoring organization, or to help out with
some of the skills that you have learned while being an apprentice. Anyone is open to contribute to the application, and
we welcome people of all skill levels. There are opportunities to help with coding as well as documentation.

This document was shameless taken from <https://raw.githubusercontent.com/puppetlabs/puppet/master/CONTRIBUTING.md> and
changed to fit this project.

## Getting Started

* Make sure that you have a [Github Account](https://github.com/signup/free)
* Submit a ticket for your issue, assuming one does not already exist
  * Clearly describe the issue, including steps needed to reproduce when it is a bug
* Fork the repository

## Making Changes

* Create a topic branch from where you want to base your work.
  * This is usually the master branch.
  * Only target release branches if you are certain your fix must be on that
    branch.
  * To quickly create a topic branch based on master; `git checkout -b
    fix/master/my_contribution master`. Please avoid working directly on the
    `master` branch.
* Make commits of logical units.
* Check for unnecessary whitespace with `git diff --check` before committing.
* Make sure your commit messages are in the proper format.
````
    (#1234) Make the example in CONTRIBUTING imperative and concrete

    Without this patch applied the example commit message in the CONTRIBUTING
    document is not a concrete example.  This is a problem because the
    contributor is left to imagine what the commit message should look like
    based on a description rather than an example.  This patch fixes the
    problem by making the example concrete and imperative.

    The first line is a real life imperative statement with a ticket number
    from our issue tracker.  The body describes the behavior without the patch,
    why this is a problem, and how the patch fixes the problem when applied.
````
## Making Trivial Changes

### Documentation

For changes of a trivial nature to comments and documentation, it is not
always necessary to create a new issue in Github. In this case, it is
appropriate to start the first line of a commit with '(doc)' instead of
a ticket number.

````
    (doc) Add documentation commit example to CONTRIBUTING

    There is no example for contributing a documentation commit
    to the Mentoring App repository. This is a problem because the contributor
    is left to assume how a commit of this nature may appear.

    The first line is a real life imperative statement with '(doc)' in
    place of what would have been the ticket number in a
    non-documentation related commit. The body describes the nature of
    the new documentation or comments added.
````

## Submitting Changes

* Push your changes to a topic branch in your fork of the repository.
* Submit a pull request to the repository in the phpmentoring organization.
* Update your Issue ticket to mark that you have submitted code and are ready for it to be reviewed (Status: Ready for Merge).
  * Include a link to the pull request in the ticket.
* The core team looks at Pull Requests on a fairly regular basis.
* After reviewing the pull request, the PR will either be merged into master, responded to with some additional needs or changes, or closed depending on the situation. The core team will try and be as responsive as possible. Others may respond to the ticket, but only core team members have write access to the repository.

# Additional Resources

* [General GitHub documentation](http://help.github.com/)
* [GitHub pull request documentation](http://help.github.com/send-pull-requests/)
* #phpmentoring IRC channel on freenode.org