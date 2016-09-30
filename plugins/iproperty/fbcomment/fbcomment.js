/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

var ipPropertyPlug = (function(app) {
    app.buildPlug = function(){
        jQuery('a[href="#ipfbcommentplug"]').on("shown", function(e) {
            FB.XFBML.parse(jQuery('#ipfbcommentplug')[0]);
        });
    }
    return app;
})(ipPropertyPlug || {});

