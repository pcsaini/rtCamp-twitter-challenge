#Assignment: Twitter-Timeline Challenge

Create a Laravel web application to accomplish following parts:

###Part-1: User Timeline

   1. Start => User visits your script page.
   2. User will be asked to connect using his Twitter account using Twitter Auth.
   3. After authentication, your script will pull latest 10 tweets from his “home” timeline.
   4. 10 tweets will be displayed using a jQuery-slideshow.

###Part-2: Followers Timeline

   1. Below jQuery-slideshow (in step#4 from part-1), display a list 10 followers (you can take any 10 random followers).
   2. Also, display a search followers box. Add auto-suggest support. That means as soon as user starts typing, his followers will start showing up.
   3. When user will click on a follower name, 10 tweets from that follower’s user-timeline will be displayed in same jQuery-slider, without page refresh (use AJAX).

###Part-3: Email Tweets as PDF

   1. There will be an Email button above the jQuery Slider to email all the tweets for logged in user.
   2. Clicking on that should open a popup that will ask the user’s email address.
   3. Clicking on Go after entering the email address should lead to the user receiving all his tweets in PDF format by mail.


## Package
### Twitter API Library
Install the package using composer:
```
composer require thujohn/twitter
```

[Twitter Api Github Repo](https://github.com/thujohn/twitter)


### Laravel Dot Env Generator
Install the package using composer:
```
composer require mathiasgrimm/laravel-dot-env-gen:dev-master
```
