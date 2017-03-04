/* ==================================================================
 *
 *            QNRWP_A ADMIN WIDGETS JAVASCRIPT 1.0.0
 * 
 * ================================================================== */


(function(){
    window.addEventListener("load", function() {
        var obj = document.getElementById("wpbody-content");
        var sub = obj.querySelector("div.wrap p.submit");
        var myNote = document.createElement("p");
        myNote.innerHTML = "QNRWP NOTE: An additional size, 'QNRWP-Largest', of maximum 2000px width or 1500px height is available but not listed here.";
        sub.parentNode.appendChild(myNote);
    }, true);
})()