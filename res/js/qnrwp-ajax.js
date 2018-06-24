/* ==================================================================
 *
 *            QNRWP AJAX JAVASCRIPT 
 * 
 * ================================================================== */

QNRWP.Ajax = {};

/**
 * AJAX request using WP methodology and Ajax functions in QI
 * 
 * Remember to trigger the post-load event if inserting content into page (TODO for JetPack...)
 * 
 * @param   object        data            Object with A-Za-z0-9-_ keys, actiontype and datajson
 * @param   string        mode            "sync" TODO async support (not needed)
 * @param   string        callType        "POST"
 * @return  string                        Returned data string, starting with "ERROR:" or "Success:"
 */
QNRWP.Ajax.request = function (data, mode, callType) {
    try {
        // Get error text from WP translated
        var error1 = "Invalid data";
        error1 = QNRWP_JS_Global.i18n.ajax.error1; // No check needed as we're in a try block
        //console.log(error1);
        
        if (data.actiontype === undefined || data.datajson === undefined) return "ERROR: "+error1;
        // Encode data as query string for POST
        var dataString = "";
        for (var key in data) {
            dataString += key + "=" + encodeURIComponent(data[key]) + "&";
        }
        // Add ajax cookie to data (hex format (plus T and secs), no need to encode) as last data after final &
        dataString += 'qnrwp_ajax_cookie=' + getCookieValue("qnrwp_ajax_cookie");
        var xhr=new XMLHttpRequest();
        // qnrwp_ajax_handler is defined in functions.php; QNRWP_JS_Global defined in script enqueue
        if (mode == "sync") {
            // AjaxSync(url, mode, request, contentTypeL, customHeaderL)
            var aR = AjaxSync(QNRWP_JS_Global.Ajax.url + "?action=qnrwp_ajax_handler", 
                                callType, dataString,
                                ["Content-Type", "application/x-www-form-urlencoded"],
                                null);
            return decodeURIComponent(aR); // On error, will begin with "ERROR:", else "Success:"
        }
    } catch (e) {
        return "ERROR: "+e.message;
    }
};

