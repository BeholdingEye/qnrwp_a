/* ==================================================================
 *
 *            QNRWP_A ADMIN MEDIA SETTINGS JAVASCRIPT 1.0.0
 * 
 * ================================================================== */


(function(){
    window.addEventListener("load", function() {
        // Make the Submit button ask for confirmation before Regenerating Images
        var sBtn = document.getElementById('submit');
        sBtn.onclick = function(event) {
            if (document.getElementById('qnrwp_regenerate_images').checked) {
                var answer = confirm('Are you sure you want to Regenerate Images? Once started, this action will run in the background on the server and cannot be cancelled or undone. You may like to cancel now and check your settings one last time.');
                if (!answer) { // User cancelled
                    event.stopPropagation();
                    event.preventDefault();
                    //return false; // Prevent form being submitted
                } // Else do nothing, let the PHP code run
            }
        }
    }, false);
    
    
    //window.addEventListener("load", function() {
        //var obj = document.getElementById("wpbody-content");
        //var sub = obj.querySelector("div.wrap p.submit");
        //var myNote = document.createElement("p");
        
                            //// Image Size Options:
                            //// WP defaults are: T 150x150, M 300x300, L 1024x1024
                            //// QNRWP defaults: T 200x200, M 500x500, L 1024x1024
                            //// Additional sizes defined as below
                            
        //var myNoteText =    '<div style="font-size:1.1em"><h4>QNRWP NOTE: This theme defines and makes available two additional sizes not listed above:</h4>' + 
                            //"<ul>" + 
                            //"<li><b>QNRWP-Larger</b> - max 1600px width, height proportional.</li>" + 
                            //"<li><b>QNRWP-Largest</b> - max 2000px width, height proportional.</li>" + 
                            //"</ul>" + 
                            //"<p>In addition, uploads wider than 2500px will be reduced to 2500px width, " + 
                            //"and compressed at 50% quality like other downsized versions.</p>" + 
                            //"</div>";
        //myNote.innerHTML = myNoteText;
        //sub.parentNode.appendChild(myNote);
    //}, true);
})()