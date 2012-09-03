FreeLadder
==========

FreeLadder is a general purpose competitive ladder system for sports and games.  The goal of the project is to create an attractive, easy to use web application suitable for handling multiple ladders with cross-ladder membership for users.  

A combined user/developer discussion [Google Group](ttp://groups.google.com/group/freeladder) is available.


Task List
=========

Major Issues
------------
* There is no handling for multiple ladders in the UI.
* Profiles
** Summary isn't complete
** History graph is non-existent.
* Need ladder_user -based created_at in addition to user-based.
* Review the JSON timeout redirecting to login. It's too flaky.
* Add email verification


Minor Issues
------------
* Make login and signup boxes a bit wider so a typical email address isn't cut off.
* Set default focus for login and signup formats.
* Fix the annoying page flashing during signup.
* Change error message size and coloring
* Fix button size
* Change best rankings format from "1" and "3" to "1st" and "3rd" .
* Rationalize js and css includes on each view
* Pre-populate signup form after "back"


New Features
------------
* Add runtime detection of whether we're in dev mode on the server and alert server.
* Create a cron job to do a nightly pull from Bitbucket.
* Consolidate multiple queries that are very similar (e.g. in profile.php)


Ladder Algorithm
================
The way FreeLadder currently processes challenges and results and turns them into rankings 
needs improvement. There are a number of obvious problems including:

1. When you challenge someone and win, you may not end up where you expected because of order 
in which match results have been reported.
2. You may be faced with the scenario where you have multiple results to enter, and the order 
in which you enter them will impact the rankings.

There are however some ideas from the current system that are beneficial:

1. It's simple to enter results.
2. Multiple challenges in parallel probably allow for more games and avoid the annoyance of waiting for other challenges to complete.
3. Ladder changes are easy to understand. Though they suffer from the issues above, it is 
obvious why you moved and where you moved there with the current system. 

Proposal for a new ranking scheme:

What is a fair ranking? Is it based on when you challenged, when you played, or something in
between?  After some discussion with others, I believe that rankings should be determined by the
order in which the challenges occurred.  It seems reasonable that if I challenge the #1 player and
win,  I should move to the #1 spot regardless of events that occur after the challenge.  I'm
proposing a system wherein the results are processed and the ladder is updated in the  order in
which challenges were received.  In cases where the results entry order matches the challenge order, this will appear no different than what we have today. And if there are out-of-order results but they occur in non-overlapping parts of the ladder, there are also no issues. The difficulty from a UI perspective is what to show when there are potential out-of-order situations. 

I'll state now that I'm not proponent of trying to handle the problem by locking out challenges to avoid conflicts. 


Related sites/programs
======================
[Squash Ladder](http://sourceforge.net/projects/squash-ladd-php/)