/* ==================================================================
 *
 *            QNRWP_A MAIN JAVASCRIPT 
 * 
 * ================================================================== */

// Our theme global
var QNRWP_GLOBAL = {};
// .clientServerTimeDelta

function afterLoad() {
    // Obtain time delta between client and server, from ajax cookie value
    var cV = getCookieValue("qnrwp_ajax_cookie");
    var cT = parseInt(cV.split("T")[1]);
    var secsNow = Math.ceil(Date.now()/1000);
    QNRWP_GLOBAL.clientServerTimeDelta = secsNow - cT;
    
    print("QNRWP_A themed page loaded OK");
}

// AJAX request using WP methodology and Ajax functions in QI
// Data must be an object, with A-Za-z0-9-_ keys, actiontype and datajson
// Remember to trigger the post-load event if inserting content into page (TODO for JetPack...)
function QNRWP_Ajax_Request(data, mode, callType) {
    try {
        if (data.actiontype === undefined || data.datajson === undefined) return "ERROR: Invalid data";
        // Encode data as query string for POST
        var dataString = "";
        for (var key in data) {
            dataString += key + "=" + encodeURIComponent(data[key]) + "&";
        }
        // Add ajax cookie to data (hex format (plus T and secs), no need to encode) as last data after final &
        dataString += 'qnrwp_ajax_cookie=' + getCookieValue("qnrwp_ajax_cookie");
        var xhr=new XMLHttpRequest();
        // qnrwp_ajax_handler is defined in functions.php; qnrwp_global_enqueued_wp_object defined 
        //   in script enqueue (buggy WP fuction call, but working for this)
        if (mode == "sync") {
            // AjaxSync(url, mode, request, contentTypeL, customHeaderL)
            var aR = AjaxSync(qnrwp_global_enqueued_wp_object.ajaxurl + "?action=qnrwp_ajax_handler", 
                                callType, dataString,
                                ["Content-Type", "application/x-www-form-urlencoded"],
                                null);
            return decodeURIComponent(aR); // On error, will begin with "ERROR:", else "Success:"
        }
    } catch (e) {
        return "ERROR: "+e.message;
    }
}


// ----------------------- SAMPLES

function SamplesLoadMore(obj, event, sampleName, sampleCategories, sampleSize, samplesNumber, pageNumber) {
    // Load more samples, called from button at end of samples list
    // obj = clicked button
    // samplesNumber = number of samples to return
    // pageNumber = paging number used by Wordpress
    var dataObj = {};
    dataObj.sampleName = sampleName;
    dataObj.sampleCategories = sampleCategories;
    dataObj.sampleSize = sampleSize;
    dataObj.samplesNumber = samplesNumber;
    // Check button dataset for next page number (set on a previous button click), or use the parameter
    if (obj.dataset.qnrwpNextPageNumber) dataObj.pageNumber = parseInt(obj.dataset.qnrwpNextPageNumber);
    else dataObj.pageNumber = pageNumber;
    // Create Ajax wrapper object, with all data as a one-field datajson string, plus actiontype
    var ajaxObj = {};
    ajaxObj.datajson = JSON.stringify(dataObj);
    ajaxObj.actiontype = "samples";
    // Send request
    var rT = QNRWP_Ajax_Request(ajaxObj, "sync", "POST");
    // Respond back to the page, success or failure
    if (rT.slice(0, 5) == "ERROR") {
        console.log(rT);
        //alert(rT);
        obj.parentNode.removeChild(obj);
    } else {
        // Success, place the returned HTML in the doc
        var samplesList = objClass("samples-list-block", obj.parentNode);
        samplesList.innerHTML = samplesList.innerHTML + "\n" + rT;
        // Delete button if all samples shown
        if (rT.indexOf("<!-- All samples items displayed -->") != -1) {
            obj.parentNode.removeChild(obj);
        } else {
            // Add next page number dataset attribute to the button for next click
            if (obj.dataset.qnrwpNextPageNumber) obj.dataset.qnrwpNextPageNumber = parseInt(obj.dataset.qnrwpNextPageNumber)+1;
            else obj.dataset.qnrwpNextPageNumber = pageNumber+1;
            // Lose focus on button
            obj.blur();
        }
    }
}

