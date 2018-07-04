/* ==================================================================
 *
 *            QNRWP SAMPLES JAVASCRIPT 
 * 
 * ================================================================== */

QNRWP.Samples = {};

/**
 * Loads more samples, called from button at end of samples list
 * 
 * @param element   obj                Clicked button
 * @param event     event              Event
 * @param string    sampleName         Name
 * @param int       sampleSize         Size of samples
 * @param int       sampleNumber       Number of samples to return
 * @param int       pageNumber         Paging number used by Wordpress
 */
QNRWP.Samples.load_more = function (obj, event, sampleName, sampleSize, samplesNumber, pageNumber) {
    var dataObj = {};
    dataObj.sampleName = sampleName;
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
    var rT = QNRWP.Ajax.request(ajaxObj, "sync", "POST");
    // Respond back to the page, success or failure
    if (rT.slice(0, 5) == "ERROR") {
        console.log(rT);
        //alert(rT);
        obj.parentNode.removeChild(obj);
    } else {
        // Success, place the returned HTML in the doc
        var samplesList = objClass("qnrwp-samples-list-block", obj.parentNode);
        samplesList.innerHTML = samplesList.innerHTML + "\n" + rT;
        // Delete button if all samples shown
        if (rT.indexOf("<!-- All qnrwp_sample_cards displayed -->") != -1) {
            obj.parentNode.removeChild(obj);
        } else {
            // Add next page number dataset attribute to the button for next click
            if (obj.dataset.qnrwpNextPageNumber) obj.dataset.qnrwpNextPageNumber = parseInt(obj.dataset.qnrwpNextPageNumber)+1;
            else obj.dataset.qnrwpNextPageNumber = pageNumber+1;
            // Lose focus on button
            obj.blur();
        }
    }
};

