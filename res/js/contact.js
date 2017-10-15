

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

function SendEmail(event) {
    var alertText = "";
    if (!ValidateEmailAddress(objClass("email", event.target.parentNode.parentNode).value)) {
        alertText += " - Not a valid email address.\n";
    }
    if (objClass("message", event.target.parentNode.parentNode)) {
        if (!ValidateMessage(objClass("message", event.target.parentNode.parentNode).value)) {
            alertText += " - Not a valid message.\n";
        }
    }
    if (alertText) {
        AlertUser(alertText);
        event.stopPropagation();
        event.preventDefault();
    }
    else {
        if (confirm('Please check that the email address you entered is correct. Click OK to send or Cancel to abort.') !== true) {
            event.stopPropagation();
            event.preventDefault();
        }
    }
}
