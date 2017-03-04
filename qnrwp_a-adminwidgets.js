/* ==================================================================
 *
 *            QNRWP_A ADMIN WIDGETS JAVASCRIPT 1.0.0
 * 
 * ================================================================== */


function qnrwp_collect_pages_options_for_widget(event) {
    // Any checked input boxes will have value sent to output field on click
    var eT = event.target;
    // Labels contain the inputs, under the DIV...
    var eTP = eT.parentNode.parentNode;
    var inputObjs = eTP.querySelectorAll("input[type='checkbox']");
    var outObj = eTP.getElementsByClassName("qnrwp-setting-output-field")[0];
    var outValue = "";
    for (var i = 0; i < inputObjs.length; i++) {
        if (inputObjs[i].checked) {
            outValue = outValue + inputObjs[i].value + ",";
        }
    }
    outObj.value = outValue;
}

//function qnrwp_collect_widget_to_display(event) { // Not needed, not used
    //// Selected widget-defining page name sent to output field on mouseup
    //var eT = event.target;
    //// Parent of <select> of target is DIV containing output field
    //var outObj = eT.parentNode.parentNode.getElementsByClassName("qnrwp-setting-output-field")[0];
    //outObj.value = eT.value;
    //for (var i = 0; i < eT.parentNode.childNodes.length; i++) {
        //eT.parentNode.childNodes[i].selected = false;
    //}
    //eT.selected = true;
//}

