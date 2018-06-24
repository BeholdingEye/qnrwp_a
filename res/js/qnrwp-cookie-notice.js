/* ==================================================================
 *
 *            QNRWP COOKIE NOTICE JAVASCRIPT 
 * 
 * ================================================================== */

QNRWP.CookieNotice = {};

/**
 * Displays cookie notice for EU compliance, if dismissed cookie not set
 */
QNRWP.CookieNotice.cookie_notice_dismissed = function () {
    if (getCookieValue("qnrwp-cookie-notice-dismissed") != 1) {
        try { // The DIV may not be there...
            objClass('qnrwp-cookie-notice').style.display = 'block';
        } catch (e) {
            // Do nothing
        }
    }
}


/**
 * Closes cookie notice, called from click on X icon in notice
 */
QNRWP.CookieNotice.close_cookie_notice = function (obj, event) {
    objClass('qnrwp-cookie-notice').style.display = 'none';
    createOrUpdateCookie("qnrwp-cookie-notice-dismissed", 1, 0, true); // Name, value, duration, secure (path automatic)
    QNRWP.Main.content_footer_sizer(); // Account for missing notice
}
