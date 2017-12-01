

// ----------------------- FUNCTIONS

// Called on keypresses in textarea
function CountTextarea(ta, mode) {
    var count = ta.value.length;
    // Place count under the textarea
    var counter = objClass("textarea-count", ta.parentNode.parentNode);
    // The counter may be hidden
    if (counter) {
        // Mode can be "count" or "reset"
        if (mode == "count") {
            counter.innerHTML = (500-count) + " remaining";
        }
        else if (count == 0) {
            counter.innerHTML = "Max 500 characters";
        }
    }
}


// ----------------------- VALIDATE

// Called from SendEmail when form submitted
function ValidateEmailAddress(email) {
    email = email.trim();
    if (!email) return false;
    if (email.length < 7 || email.length > 80) return false;
    var re = /^(?:\w|-|\.)+@(?:\w|-|\.)+\.[a-z]{2,8}$/;
    var notre = /(?:\.|-|_|@)(?:\.|-|_|@)+/;
    if (notre.test(email)) return false;
    if (re.test(email)) return true;
    return false;
}

function ValidateMessage(message) {
    message = message.trim();
    if (30 <= message.length && message.length <= 500) return true;
    return false;
}

function AlertUser(alertText) {
    alertText = "Your message cannot be submitted:\n\n" + alertText;
    alertText += "\nPlease correct and try again.";
    alert(alertText);
}

function SessionUnexpired(cookieName, expirySecs) {
    // Assumes a cookie value with a T + secs since epoch start component
    var cV = getCookieValue(cookieName);
    // Add time delta obtained after load to make up for difference between client and server
    var cT = parseInt(cV.split("T")[1]) + QNRWP_GLOBAL.clientServerTimeDelta;
    var secsNow = Math.ceil(Date.now()/1000);
    if (secsNow - cT > expirySecs) {
        return false;
    } else return true;
}


// ----------------------- SEND

function SendEmail(obj, event) { // obj is the form (there may be more than one on page)
    var alertText = "";
    if (!SessionUnexpired("qnrwp_ajax_cookie", 880)) { // 900 secs is 15 mins, as set in PHP
        alertText += " - Page session has expired. Reload the page to restart the session.\n";
    }
    if (!ValidateEmailAddress(objClass("email", obj).value)) { // Email address box always appears
        alertText += " - Not a valid email address.\n";
    }
    if (objClass("message", obj)) { // Message box may not appear
        if (!ValidateMessage(objClass("message", obj).value)) {
            alertText += " - Not a valid message.\n";
        }
    }
    if (alertText) {
        AlertUser(alertText);
        event.stopPropagation();
        event.preventDefault();
    }
    else { // No alert, proceed to confirm
        event.stopPropagation();
        event.preventDefault();
        if (confirm('Please check that "'+objClass("email", obj).value+'" is the correct email address. Click OK to send or Cancel to abort.') === true) {
            SendEmailByAjax(obj);
        }
    }
}


// ----------------------- AJAX

function SendEmailByAjax(obj) { // obj is the form
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
    var rT = QNRWP_Ajax_Request(ajaxObj, "sync", "POST"); // TODO async (not needed though)
    // Respond back to the page, success or failure
    console.log(rT);
    if (rT.slice(0, 5) == "ERROR") {
        alert(rT);
    } else if (rT.slice(0, 7) == "Success") {
        obj.parentNode.classList.add("sent-reply");
        obj.parentNode.innerHTML = atob(objClass("sent-reply-hidden", obj).value);
    }
}

