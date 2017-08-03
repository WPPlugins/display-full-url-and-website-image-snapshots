    === Plugin Name ===
    Contributors: Advertise Me (www.advertiseme.com.au)
    Tags: TinyUrL, Bit.ly, URL Expand, Websnapr, t.co, Full Url display in post, Link Image in the post
    Requires at least: 2.6
    Tested up to: 3.3.1
    Stable tag: 1.1

    This Plugin displays the full url in the post & also display the first link image in the post through websnapr.

    == Description ==

    Plugin display the full url of tinyurl,bit.ly in the post & display the first link image in the post through websnapr.

    == Installation ==

    1. Upload /full_url_display/ to your Plugin directory (wp-content/plugins/)
    2. Go to the 'Plugins' Page of your Administration Area and activate the Plugin. That's all you need to do to display the full url in the post.
    3. Add the following script code in the header sector:
      <script type="text/javascript" src="http://www.websnapr.com/js/websnapr.js"></script>
    4. To display the Image of the first Url in the post add the following function in your template page (single.php) where you want to display the image.

    <?php if(function_exists('templateTinyUrlImage')) templateTinyUrlImage('right','classname'); ?>

    Where "right" is the align of the image alternatively you can specify the align 'left' etc. 'classname' is the name of the class that you want to apply on the image. If you don’t want to specify align & class, leave it blank as shown below:

    <?php if(function_exists('templateTinyUrlImage')) templateTinyUrlImage(); ?>

    5. Default size of image is 200*150. If you want the larger size you have to register on websnapr.com & buy the premium service of websnapr. They will provide the developer key. Enter the developer key in "Websnaper settings" of Wordpress setting.
    6. The Thumbnail takes 10-15 mins to update
    7. To change the size of image & tiny url options of individual post, Edit any post & here you will see the Tiny Options. To add a micro image (90x70) use the following code:
    '<?php if(function_exists('templateTinyUrlImage')) templateTinyUrlImage('left','',1); ?>'