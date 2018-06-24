/* ==================================================================
 *
 *            QNRWP ADMIN MEDIA JAVASCRIPT
 * 
 * ================================================================== */

/**
 * This script file will be loaded only on the options-media admin screen
 */

(function(){
    window.addEventListener("load", function() {
        // Make the Submit button ask for confirmation before Regenerating Images
        var sBtn = document.getElementById('submit');
        sBtn.onclick = function(event) {
            if (document.getElementById('qnrwp_regenerate_images').checked) {
                var confirm1 = 'Are you sure you want to Regenerate Images?';
                try {confirm1 = QNRWP_JS_Global.i18n.admin.media.confirm1;} catch (e) {} // The only safe way to deal with undefined global
                var answer = confirm(confirm1);
                if (!answer) { // User cancelled
                    event.stopPropagation();
                    event.preventDefault();
                    //return false; // Prevent form being submitted
                } // Else do nothing, let the PHP code run
            }
        }
    }, false);
    
})()