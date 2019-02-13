/* ==================================================================
 *
 *            QNRWP CONTACT JAVASCRIPT 
 * 
 * ================================================================== */


QNRWP.Contact = {};


// ----------------------- FUNCTIONS

/**
 * Called on keypresses in textarea
 */
QNRWP.Contact.count_text_area = function (ta, mode) {
    var count = ta.value.length;
    // Place count under the textarea
    var counter = objClass("textarea-count", ta.parentNode.parentNode);
    // The counter may be hidden
    if (counter) {
        // Mode can be "count" or "reset"
        if (mode == "count") {
            //counter.innerHTML = (500-count) + " remaining";
            counter.innerHTML = count + " / 500";
        }
        else if (count == 0) {
            var note1 = 'Max 500 characters';
            try {note1 = QNRWP_JS_Global.i18n.contact.note1;} catch (e) {} // Safely handle undefined global
            counter.innerHTML = note1;
        }
    }
};


// ----------------------- VALIDATE

/**
 * Validates email address, called from send_email() when form submitted
 */
QNRWP.Contact.validate_email_address = function (email) {
    email = email.trim();
    if (!email) return false;
    if (email.length < 7 || email.length > 80) return false;
    var re = /^(?:\w|-|\.)+@(?:\w|-|\.)+\.[a-z]{2,8}$/;
    var notre = /(?:\.|-|_|@)(?:\.|-|_|@)+/;
    if (notre.test(email)) return false;
    if (re.test(email)) return true;
    return false;
};


/**
 * Validates message, that it is between 30 and 500 chars long
 */
QNRWP.Contact.validate_message = function (message) {
    message = message.trim();
    if (30 <= message.length && message.length <= 500) return true;
    return false;
};


/**
 * Alerts user with passed message
 */
QNRWP.Contact.alert_user = function (alertText) {
    var alert1 = "Your message cannot be submitted";
    try {alert1 = QNRWP_JS_Global.i18n.contact.alert1;} catch (e) {}
    var alert2 = "Please correct and try again.";
    try {alert2 = QNRWP_JS_Global.i18n.contact.alert2;} catch (e) {}
    alertText = alert1+":\n\n" + alertText;
    alertText += "\n"+alert2;
    alert(alertText);
};


/**
 * Returns true if session (passed time limit for AJAX call) not expired, false otherwise
 * 
 * Assumes a cookie value with a [T + secs since epoch start] component
 */
QNRWP.Contact.session_unexpired = function (cookieName, expirySecs) {
    var cV = getCookieValue(cookieName);
    // Add time delta obtained after load to make up for difference between client and server
    var cT = parseInt(cV.split("T")[1]) + QNRWP.Main.clientServerTimeDelta;
    var secsNow = Math.ceil(Date.now()/1000);
    if (secsNow - cT > expirySecs) {
        return false;
    } else return true;
};


// ----------------------- SEND

/**
 * Sends email on click of button in contact form
 */
QNRWP.Contact.send_email = function (obj, event) { // obj is the form (there may be more than one on page)
    var alertText = "";
    if (!this.session_unexpired("qnrwp_ajax_cookie", 880)) { // 900 secs is 15 mins, as set in PHP
        var alert3 = 'Page session has expired. Reload the page to restart the session.';
        try {alert3 = QNRWP_JS_Global.i18n.contact.alert3;} catch (e) {}
        alertText += " - "+alert3+"\n";
    }
    if (!this.validate_email_address(objClass("email", obj).value)) { // Email address box always appears
        var alert4 = 'Not a valid email address.';
        try {alert4 = QNRWP_JS_Global.i18n.contact.alert4;} catch (e) {}
        alertText += " - "+alert4+"\n";
    }
    if (objClass("message", obj)) { // Message box may not appear
        if (!this.validate_message(objClass("message", obj).value)) {
            var alert5 = 'Not a valid message.';
            try {alert5 = QNRWP_JS_Global.i18n.contact.alert5;} catch (e) {}
            alertText += " - "+alert5+"\n";
        }
    }
    if (alertText) {
        this.alert_user(alertText);
        event.stopPropagation();
        event.preventDefault();
    }
    else { // No alert, proceed to confirm
        event.stopPropagation();
        event.preventDefault();
        var confirm1 = 'Please check that %s is the correct email address. Click OK to send or Cancel to abort.';
        try {confirm1 = QNRWP_JS_Global.i18n.contact.confirm1;} catch (e) {}
        confirm1 = confirm1.replace("%s", '"'+objClass("email", obj).value+'"');
        if (confirm(confirm1) === true) {
            this.send_email_by_ajax(obj);
        }
    }
};


// ----------------------- AJAX

/**
 * Sends email by AJAX
 */
QNRWP.Contact.send_email_by_ajax = function (obj) { // obj is the form
    // Get inputs (if placed by shortcode) and construct data object
    var dataObj = {};
    dataObj.email = objClass("email", obj).value;
    if (objClass("name", obj)) dataObj.emailname = objClass("name", obj).value;
    if (objClass("subject", obj)) {
        // If subject input empty, get placeholder
        dataObj.subject = objClass("subject", obj).value;
        //if (!dataObj.subject && objClass("subject", obj).hasAttribute("placeholder")) { // Changed from original, avoiding "SUBJECT" from placeholder...
            //dataObj.subject = objClass("subject", obj).getAttribute("placeholder");
        //}
    }
    if (objClass("message", obj)) dataObj.message = objClass("message", obj).value;
    if (objClass("form-name-hidden", obj)) dataObj.formname = objClass("form-name-hidden", obj).value; // Should never be empty
    if (objClass("form-class-hidden", obj)) dataObj.formclass = objClass("form-class-hidden", obj).value; // Ditto (hex val of class)
    if (objClass("client-ip-hidden", obj)) dataObj.clientip = objClass("client-ip-hidden", obj).value;
    if (objClass("permalink-hidden", obj)) dataObj.permalink = objClass("permalink-hidden", obj).value;
    if (objClass("options-hidden", obj)) dataObj.options = objClass("options-hidden", obj).value;
    // Create Ajax wrapper object, with all data as a one-field datajson string, plus actiontype
    var ajaxObj = {};
    ajaxObj.datajson = JSON.stringify(dataObj);
    ajaxObj.actiontype = "email";
    // Send email
    var rT = QNRWP.Ajax.request(ajaxObj, "sync", "POST"); // TODO async (not needed though)
    // Respond back to the page, success or failure
    console.log(rT);
    if (rT.slice(0, 5) == "ERROR") {
        alert(rT);
    } else if (rT.slice(0, 7) == "Success") {
        // Redirect to Thank you page if set, or display response text
        if (objClass("thank-slug-hidden", obj)) {
            window.location.href = atob(objClass("thank-slug-hidden", obj).value);
        } else {
            obj.parentNode.classList.add("sent-reply");
            obj.parentNode.innerHTML = atob(objClass("sent-reply-hidden", obj).value);
        }
    }
};




// ----------------------- OLD

/**
 * Contact global object
 * 
 * Depends on the QNRWP theme global object
 */
var QNR_CONTACT = {
    
    /**
     * Counts chars entered in textarea, called on keypresses
     */
    count_textarea : function (ta, mode) {
        var count = ta.value.length;
        // Place count under the textarea
        var counter = objClass("textarea-count", ta.parentNode.parentNode);
        // The counter may be hidden
        if (counter) {
            // Mode can be "count" or "reset"
            if (mode == "count") {
                counter.innerHTML = 500-count;
            }
            else if (count == 0) {
                counter.innerHTML = "500";
            }
        }
    },
    
    
    /**
     * Validates email address, called from send_email when form submitted
     */
    validate_email_address : function (email) {
        email = email.trim();
        if (!email) return false;
        if (email.length < 7 || email.length > 80) return false;
        var re = /^(?:\w|-|\.)+@(?:\w|-|\.)+\.[a-z]{2,8}$/;
        var notre = /(?:\.|-|_|@)(?:\.|-|_|@)+/;
        if (notre.test(email)) return false;
        if (re.test(email)) return true;
        return false;
    },
    
    
    /**
     * Validates email message
     */
    validate_message : function (message) {
        message = message.trim();
        if (30 <= message.length && message.length <= 500) return true;
        return false;
    },
    
    
    /**
     * Alerts user with dialog
     */
    alert_user : function (alertText) {
        var alert1 = "Your message cannot be submitted";
        try {alert1 = QNRWP_JS_Global.i18n.contact.alert1;} catch (e) {}
        alertText = alert1+":\n\n" + alertText;
        var alert2 = "Please correct and try again.";
        try {alert2 = QNRWP_JS_Global.i18n.contact.alert2;} catch (e) {}
        alertText += "\n"+alert2;
        alert(alertText);
    },
    
    
    /**
     * Returns true if session not expired, false otherwise
     * 
     * Assumes a cookie value with a [T + secs since epoch start] component
     */
    session_unexpired : function (cookieName, expirySecs) {
        var cV = getCookieValue(cookieName);
        // Add time delta obtained after load to make up for difference between client and server
        var cT = parseInt(cV.split("T")[1]) + QNRWP.Main.clientServerTimeDelta;
        var secsNow = Math.ceil(Date.now()/1000);
        if (secsNow - cT > expirySecs) {
            return false;
        } else return true;
    },
    
    
    /**
     * Sends email on click of button in contact form
     */
    send_email : function (obj, event) { // obj is the form (there may be more than one on page)
        var alertText = "";
        if (!this.session_unexpired("qnrwp_ajax_cookie", 880)) { // 900 secs is 15 mins, as set in PHP
            var alert3 = "Page session has expired. Reload the page to restart the session.";
            try {alert3 = QNRWP_JS_Global.i18n.contact.alert3;} catch (e) {}
            alertText += " - "+alert3+"\n";
        }
        if (!this.validate_email_address(objClass("email", obj).value)) { // Email address box always appears
            var alert4 = "Not a valid email address.";
            try {alert4 = QNRWP_JS_Global.i18n.contact.alert4;} catch (e) {}
            alertText += " - "+alert4+"\n";
        }
        if (objClass("message", obj)) { // Message box may not appear
            if (!this.validate_message(objClass("message", obj).value)) {
                var alert5 = "Not a valid message.";
                try {alert5 = QNRWP_JS_Global.i18n.contact.alert5;} catch (e) {}
                alertText += " - "+alert5+"\n";
            }
        }
        if (alertText) {
            this.alert_user(alertText);
            event.stopPropagation();
            event.preventDefault();
        }
        else { // No alert, proceed to confirm
            event.stopPropagation();
            event.preventDefault();
            var alert6 = 'Please check that "'+objClass("email", obj).value+'" is the correct email address. Click OK to send or Cancel to abort.';
            try {alert6 = QNRWP_JS_Global.i18n.contact.alert6.replace(/{this_email_address}/, '"'+objClass("email", obj).value+'"');} catch (e) {}
            if (confirm(alert6) === true) {
                this.send_email_by_ajax(obj);
            }
        }
    },
    
    
    /**
     * Sends email by Ajax
     */
    send_email_by_ajax : function (obj) { // obj is the form
        // Get inputs (if placed by shortcode) and construct data object
        var dataObj = {};
        dataObj.email = objClass("email", obj).value;
        if (objClass("name", obj)) dataObj.emailname = objClass("name", obj).value;
        if (objClass("subject", obj)) {
            // If subject input empty, get placeholder
            dataObj.subject = objClass("subject", obj).value;
            if (!dataObj.subject && objClass("subject", obj).hasAttribute("placeholder")) {
                dataObj.subject = objClass("subject", obj).getAttribute("placeholder");
            }
        }
        if (objClass("message", obj)) dataObj.message = objClass("message", obj).value;
        if (objClass("form-name-hidden", obj)) dataObj.formname = objClass("form-name-hidden", obj).value; // Should never be empty
        if (objClass("form-class-hidden", obj)) dataObj.formclass = objClass("form-class-hidden", obj).value; // Ditto (hex val of class)
        if (objClass("client-ip-hidden", obj)) dataObj.clientip = objClass("client-ip-hidden", obj).value;
        if (objClass("permalink-hidden", obj)) dataObj.permalink = objClass("permalink-hidden", obj).value;
        if (objClass("options-hidden", obj)) dataObj.options = objClass("options-hidden", obj).value;
        // Create Ajax wrapper object, with all data as a one-field datajson string, plus actiontype
        var ajaxObj = {};
        ajaxObj.datajson = JSON.stringify(dataObj);
        ajaxObj.actiontype = "email";
        // Send email
        var rT = QNRWP.Ajax.request(ajaxObj, "sync", "POST"); // TODO async (not needed though)
        // Respond back to the page, success or failure
        console.log(rT);
        if (rT.slice(0, 5) == "ERROR") {
            alert(rT);
        } else if (rT.slice(0, 7) == "Success") {
            obj.parentNode.classList.add("sent-reply");
            obj.parentNode.innerHTML = atob(objClass("sent-reply-hidden", obj).value);
        }
    }
    
};

