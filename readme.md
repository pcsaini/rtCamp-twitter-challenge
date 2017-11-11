# rtCamp Assignment: Twitter Timeline Challenge

Create a Laravel web application to accomplish following parts:

### Part-1: User Timeline

   1. Start => User visits your script page.
   2. User will be asked to connect using his Twitter account using Twitter Auth.
   3. After authentication, your script will pull latest 10 tweets from his “home” timeline.
   4. 10 tweets will be displayed using a jQuery-slideshow.

### Part-2: Followers Timeline

   1. Below jQuery-slideshow (in step#4 from part-1), display a list 10 followers (you can take any 10 random followers).
   2. Also, display a search followers box. Add auto-suggest support. That means as soon as user starts typing, his followers will start showing up.
   3. When user will click on a follower name, 10 tweets from that follower’s user-timeline will be displayed in same jQuery-slider, without page refresh (use AJAX).

### Part-3: Email Tweets as PDF

   1. There will be an Email button above the jQuery Slider to email all the tweets for logged in user.
   2. Clicking on that should open a popup that will ask the user’s email address.
   3. Clicking on Go after entering the email address should lead to the user receiving all his tweets in PDF format by mail.

### Part-4: Download Followers

   1. There will be a download button to download all followers of any user(we will input user @handler).

   2. Download can be performed in one of the following formats i.e. You choose the format you want. It would act as an advantage if you give the option to download the tweets in all the following formats: csv, xls, google-spreadhseet, pdf, xml and json formats.

   3. For Google-spreadsheet export feature, your app-user must have Google account. Your app should ask for permission to create spreadsheet on user’s Google-Drive.

   4. Once user clicks download button (after choosing option) all followers of specified user should be downloaded. Hint: You can implement this as background job. Come up with some creative solutions.

## Package
### Twitter API Library
[Github Repo](https://github.com/thujohn/twitter)

Install the package using composer:
```
composer require thujohn/twitter
```

### Laravel Dot Env Generator
[Github Repo](https://github.com/mathiasgrimm/laravel-dot-env-gen)

Install the package using composer:
```
composer require mathiasgrimm/laravel-dot-env-gen:dev-master
```

