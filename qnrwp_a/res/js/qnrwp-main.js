/* ==================================================================
 *
 *            QNRWP MAIN JAVASCRIPT 
 * 
 * ================================================================== */

"use strict";

(function(){
    
    window.addEventListener("load", function() {
        
        if (QNRWP.CookieNotice !== undefined) QNRWP.CookieNotice.cookie_notice_dismissed(); // Must be first for correct measurements
        
        QNRWP.Main.content_footer_sizer();
        
        QNRWP.Main.set_client_server_time_delta();
        
        QNRWP.Main.show_body();
        
    }, false);
    
    
    window.addEventListener("resize", function(event) {
        QNRWP.Main.content_footer_sizer();
    }, false);
    
    
    //window.addEventListener("QIEvent_ButtonToggle", function(event) {
        //console.log(event.data);
    //}, false);
    
})()


/**
 * Main sub object
 */
QNRWP.Main = {};


/**
 * Header block ID
 */
QNRWP.Main.headerID = "header-row";


/**
 * Content block ID
 */
QNRWP.Main.contentID = "content-row";


/**
 * Footer block ID
 */
QNRWP.Main.footerID = "footer-row";


/**
 * Time delta between client and server, from ajax cookie value
 */
QNRWP.Main.clientServerTimeDelta = 0;


/**
 * Sizes content block to keep footer always at bottom of page even on short pages
 * 
 * Also sets top margin for content row if header is positioned fixed
 * 
 * Relies on header, content and footer block IDs hardcoded into this object properties
 */
QNRWP.Main.content_footer_sizer = function () {
    var header = objID(this.headerID);
    var content = objID(this.contentID);
    var footer = objID(this.footerID);
    // Display footer, but not show it yet
    // Calculate content min height accounting for header and footer
    var headerHeight = (header) ? header.getBoundingClientRect().height : 0;
    var footerHeight = (footer) ? footer.getBoundingClientRect().height : 0;
    if (window.getComputedStyle(header, "").position == "fixed") content.style.marginTop = headerHeight + "px";
    content.style.minHeight = "calc(100% - "+headerHeight+"px - "+footerHeight+"px)";
    // Show footer row, completing layout
    window.setTimeout(function(){
        footer.style.transition = "all 0.2s";
        footer.style.opacity = "1";
    },20);
};


/**
 * Sets time delta between client and server, from ajax cookie value
 */
QNRWP.Main.set_client_server_time_delta = function () {
    var cV = QNR.getCookieValue("qnrwp_ajax_cookie");
    var cT = parseInt(cV.split("T")[1]);
    var secsNow = Math.ceil(Date.now()/1000);
    this.clientServerTimeDelta = secsNow - cT;
};


/**
 * Shows page content when loading done
 */
QNRWP.Main.show_body = function () {
    var content = objID(this.contentID);
    window.setTimeout(function(){
        content.style.transition = "all 0.2s";
        content.style.opacity = "1";
    },20);
};




