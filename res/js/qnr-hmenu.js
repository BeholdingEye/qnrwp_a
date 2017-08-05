/* ==================================================================
 *
 *            QUICKNR HIERARCHICAL MENU 1.3.0
 *
 *            Copyright 2016 Karl Dolenc, beholdingeye.com.
 *            All rights reserved.
 * 
 * ================================================================== */
 
/* ----------------------- LICENSE ---------------------------------
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * -------------------------------------------------------------- */

// Global object, providing access to QNR properties and methods
var QNR_HMENU = {};

(function(){
    
    /* ----------------------- INFO ---------------------------------
     * 
     *                         Hierarchical Menu
     *
     * Any clickable element assigned the class of "qnr-hmenu" will 
     * display a dropdown hierarchical menu widget, potentially 
     * containing submenus, if the clickable element contains a UL 
     * element, interpreted by the code as defining the dropdown menu
     * 
     * Note that a horizontal navigation menu cannot itself be a hmenu,
     * but its items, the horizontally positioned LI elements, may
     * contain hmenus:
     * 
     *      MainMenuItem1   MainMenuItem2   MainMenuItem3
     *                                        Hmenu in above item
     *                                          Hmenu Item1
     *                                          Hmenu Item2
     *                                            Hmenu Submenu
     *                                              Submenu Item1
     *                                              Submenu Item2
     *                                              Submenu Item3
     *                                              etc.
     * 
     * A submenu is defined as a UL contained in a LI item of the main
     * hmenu, and the submenu LI items may contain further submenus
     * 
     * The LI item containing a submenu should precede the submenu UL
     * with text that will be the LI item's label. The text must not be
     * part of a HTML tag of its own. If the text is wrapped in an A tag
     * (as may be on Wordpress) the A tag will be removed. It is bad
     * user experience if (sub)menu holders are also links - they are
     * not supported
     * 
     * The vertical and horizontal direction of the menu's and any 
     * submenu's appearance can be controlled with dataset attributes:
     * 
     * "data-qnr-hmenu-direction-x" - horizontal, "left" or "right"
     * "data-qnr-hmenu-direction-y" - vertical, "up" or "down"
     * 
     * The code assigns CSS classes to relevant objects:
     * 
     * "qnr-hmenu-menu" - main UL menu contained in widget object
     * "qnr-hmenu-subholder" - any LI in the widget containing a submenu
     * "qnr-hmenu-submenu" - any submenu UL in the widget
     * 
     * Directions other than "down" and "right" will result in further
     * CSS classes: "qnr-hmenu-submenu-up" and "qnr-hmenu-submenu-left".
     * Only the vertical direction is considered on the main menu, and 
     * managed in code, not a CSS rule
     * 
     * Note that only "down" and "right" directions, the defaults, will
     * result in container scrollbars if (sub)menus overflow. However,
     * in relation to width of the client window, left/right direction
     * of (sub)menu appearance may alter to avoid overflowing offscreen
     * 
     * Menus can be set to open and close on mouse hover with the 
     * "data-qnr-hover" attribute set to "yes", the default, or "no" for
     * clicking support only
     * 
     * LI items that should not be active but act as spacers or section
     * titles should be assigned the class of "qnr-hmenu-idle", perhaps
     * overriden
     * 
     * By default, submenus are positioned by CSS. To align them to top
     * edges of their parent menus, set a "data-qnr-hmenu-align" 
     * attribute on the widget to "yes"
     * 
     * While menus are open, the code assigns the "qnr-hmenu-hover" 
     * class to the widget object. This helps to time the appearance of
     * the widget in sync with the menus. Likewise, submenu-holding LI
     * items are assigned the "qnr-hmenu-sub-hover" class when their 
     * submenus are open
     * 
     * Delay in ms before mouseouts have effect is by default 200, and
     * can be set with the "data-qnr-hmenu-delay" attribute
     * 
     * Navmenus from "qnr-interface.js" now support Hmenus, and there is
     * some cross-reference to both in the code
     * 
     * -------------------------------------------------------------- */ 

    // ===================== STARTUP ===================== 
    
    // ----------------------- Create a list of widgets
    QNR_HMENU.hmenusL = null; // List of hierarchical menu elements in doc
    QNR_HMENU.hmenuObjectsL = []; // List of JS hierarchical menu objects
    
    // ----------------------- HIERARCHICAL MENU
    
    function HmenuObject() {
        this.object = null;
        this.directionX = "down";
        this.directionY = "right";
        this.menu = null;
        this.submenus = [];
        this.parents = []; // Containers of submenus
        this.hoverOpen = "yes"; // Open on hover or only on click
        this.changeTime = 0; // Used for mouse event delays
        this.delayTime = 200; // Delay in ms before mouseouts have effect
        this.alignSubmenus = "no"; // Align submenus to parent top
    }
    HmenuObject.prototype.initialize = function() {
        // Set preferences from dataset attributes
        if (this.object.dataset.qnrHmenuDirectionX) this.directionX = this.object.dataset.qnrHmenuDirectionX;
        if (this.object.dataset.qnrHmenuDirectionY) this.directionY = this.object.dataset.qnrHmenuDirectionY;
        if (this.object.dataset.qnrHmenuHover) this.hoverOpen = this.object.dataset.qnrHmenuHover;
        if (this.object.dataset.qnrHmenuAlign) this.alignSubmenus = this.object.dataset.qnrHmenuAlign;
        if (this.object.dataset.qnrHmenuDelay) this.delayTime = this.object.dataset.qnrHmenuDelay;
        // If first child of widget object is A tag, remove it from the link text
        if (this.object.firstElementChild && this.object.firstElementChild.tagName == "A") {
            var objAText = this.object.firstElementChild.innerHTML; // TODO improve over innerHTML?
            this.object.removeChild(this.object.firstElementChild);
            this.object.innerHTML = objAText + this.object.innerHTML;
        }
        // Hide the main UL
        this.menu = objTag("ul", this.object);
        this.menu.classList.add("qnr-hmenu-menu");
        var menuHeight = this.menu.offsetHeight; // Must be here, before hiding (??:)
        this.menu.style.display = "none"; // Redundant now, it is "none" in CSS (TODO refactor with visibility)
        // Consider only vertical direction for main menu
        if (this.directionY == "up") {
            // Set bottom of menu to top of widget object
            this.menu.style.top = "-" + menuHeight + "px";
        }
        // Get submenus and their LI parents
        this.submenus = tagObjs("ul", this.menu);
        for (var i = 0; i < this.submenus.length; i++) {
            this.submenus[i].classList.add("qnr-hmenu-submenu");
            this.submenus[i].style.display = "none";
            if (this.directionX == "left") this.submenus[i].classList.add("qnr-hmenu-submenu-left");
            if (this.directionY == "up") this.submenus[i].classList.add("qnr-hmenu-submenu-up");
            // Record parent LI objects
            this.parents.push(this.submenus[i].parentNode);
            this.parents[i].classList.add("qnr-hmenu-subholder");
            this.parents[i].dataset.qnrSubmenuParentId = i; // Needed for event handler
            // Remove A tags around first element child of submenu holder
            if (this.parents[i].firstElementChild && this.parents[i].firstElementChild.tagName == "A") {
                var objAText = this.parents[i].firstElementChild.innerHTML; // TODO improve over innerHTML?
                this.parents[i].removeChild(this.parents[i].firstElementChild);
                this.parents[i].innerHTML = objAText + this.parents[i].innerHTML;
            }
        }
        
        // Set onclick handler on the widget object to display the menu
        var this1 = this;
        this.object.addEventListener("click", function(event) {
            if (this1.menu.style.display == "none") {
                this1.hideMenus();
                this1.menu.style.display = "block";
                this1.object.classList.add("qnr-hmenu-hover");
                this1.positionHMenu();
            }
            else {
                this1.hideMenus();
            }
            //event.stopPropagation();
        }, false);
        
        // Set onmouseover handler, but only if not mobile, it interferes in Chrome
        if (this.hoverOpen == "yes" && !deviceIsMobile()) {
            this1 = this;
            this.object.addEventListener("mouseover", function(event) {
                // Test for hoverOpen again, in case controlled by a later script
                //   and for "qnr-hmenu-in-collapsed" class set by containing navmenu
                if (event.target == this1.object && this1.hoverOpen == "yes" && 
                             !this1.object.classList.contains("qnr-hmenu-in-collapsed")) {
                    this1.hideMenus();
                    this1.menu.style.display = "block";
                    this1.object.classList.add("qnr-hmenu-hover");
                    this1.positionHMenu();
                    event.stopPropagation();
                }
            }, true);
        }
        
        // Set onclick and onmouseover handlers on menu/submenu LIs, perhaps displaying submenus
        var liItemsL = tagObjs("li", this.menu);
        for (var i = 0; i < liItemsL.length; i++) {
            this1 = this;
            var ii = i;
            if (liItemsL[i].classList.contains("qnr-hmenu-subholder")) { // Submenu holder
                // Onclick
                liItemsL[i].addEventListener("click", function(event) {
                    // Prevent child LI objects in further submenus bubbling up to here and failing
                    if (event.target.dataset.qnrSubmenuParentId) {
                        var sMenu = this1.submenus[parseInt(event.target.dataset.qnrSubmenuParentId)];
                        if (sMenu.style.display == "none") {
                            // Hide any other shown submenus, not in path of target (must be here, not above test)
                            this1.hideSubmenus(event, "others");
                            sMenu.style.display = "block";
                            this1.positionSubmenu(sMenu);
                            sMenu.parentNode.classList.add("qnr-hmenu-sub-hover");
                            if (this1.alignSubmenus == "yes") this1.alignSubmenuToParent(sMenu);
                        }
                        else {
                            sMenu.style.display = "none";
                            sMenu.parentNode.classList.remove("qnr-hmenu-sub-hover");
                        }
                        event.stopPropagation();
                    }
                }, true); // Registered for the capturing phase, not bubbling up to ancestors
                // Onmouseover (only if not mobile)
                if (this.hoverOpen == "yes" && !deviceIsMobile()) {
                    //this1 = this;
                    liItemsL[i].addEventListener("mouseover", function(event) {
                        // Prevent child LI objects in further submenus bubbling up to here and failing
                        if (event.target.dataset.qnrSubmenuParentId && 
                                    !this1.object.classList.contains("qnr-hmenu-in-collapsed")) {
                            // Hide any other shown submenus, not in path of target
                            this1.hideSubmenus(event, "others");
                            var sMenu = this1.submenus[parseInt(event.target.dataset.qnrSubmenuParentId)];
                            sMenu.style.display = "block";
                            this1.positionSubmenu(sMenu);
                            sMenu.parentNode.classList.add("qnr-hmenu-sub-hover");
                            if (this1.alignSubmenus == "yes") this1.alignSubmenuToParent(sMenu);
                            event.stopPropagation();
                        }
                    }, true);
                }
            }
            else { // Not a submenu holder (mouseout/over handled at window level) TODO iPhone?
                liItemsL[i].addEventListener("click", function(event) {
                    // Hide any submenus
                    this1.hideSubmenus(event, "all");
                    this1.hideMenus();
                    // If part of a collapsed navmenu, close that as well
                    if (this1.object.classList.contains("qnr-hmenu-in-collapsed")) {
                        // To id the navmenu we rely on the fact only one can exist
                        QNR_INTER.navmenuObject.hideVerticalMenu();
                    }
                    event.stopPropagation();
                }, true);
            }
        }
    }
    HmenuObject.prototype.positionHMenu = function() {
        var mPos = getXPos(this.menu, 0);
        var mW = this.menu.offsetWidth;
        var cW = objHtml().clientWidth;
        // If menu would spill out of client area, move it forward
        if (mPos + mW > cW) {
            this.menu.style.left = "-" + ((mPos + mW) - cW) + "px";
            // And style the submenus to open left
            if (this.submenus !== []) {
                for (var i = 0; i < this.submenus.length; i++) {
                    if (!this.submenus[i].classList.contains("qnr-hmenu-submenu-left")) {
                        this.submenus[i].classList.add("qnr-hmenu-submenu-left");
                    }
                }
            }
        }
        else if (this.object.classList.contains("qnr-hmenu-in-collapsed")) {
            this.menu.style.left = ""; // Undo position
        }
    }
    HmenuObject.prototype.positionSubmenu = function(subM) {
        // If submenu would exceed client bounds, range it and its child submenus left/right
        var sPos = getXPos(subM, 0);
        var sW = subM.offsetWidth;
        var cW = objHtml().clientWidth;
        if (sPos + sW > cW) {
            if (!subM.classList.contains("qnr-hmenu-submenu-left")) {
                subM.classList.add("qnr-hmenu-submenu-left");
            }
            var subSubs = tagObjs("ul", subM);
            for (var i = 0; i < subSubs.length; i++) {
                if (!subSubs[i].classList.contains("qnr-hmenu-submenu-left")) {
                    subSubs[i].classList.add("qnr-hmenu-submenu-left");
                }
            }
        }
        else if (sPos < 0) {
            if (subM.classList.contains("qnr-hmenu-submenu-left")) {
                subM.classList.remove("qnr-hmenu-submenu-left");
            }
            var subSubs = tagObjs("ul", subM);
            for (var i = 0; i < subSubs.length; i++) {
                if (subSubs[i].classList.contains("qnr-hmenu-submenu-left")) {
                    subSubs[i].classList.remove("qnr-hmenu-submenu-left");
                }
            }
        }
    }
    HmenuObject.prototype.alignSubmenuToParent = function(sMenu) {
        sMenu.style.top = "-" + sMenu.parentNode.offsetTop + "px";
    }
    HmenuObject.prototype.hideMenus = function() {
        // Hide any hmenus that are open
        for (var i = 0; i < QNR_HMENU.hmenuObjectsL.length; i++) {
            if (window.getComputedStyle(QNR_HMENU.hmenuObjectsL[i].menu, "").display != "none") {
                if (QNR_HMENU.hmenuObjectsL[i].submenus) { // Hide any submenus
                    for (var x = 0; x < QNR_HMENU.hmenuObjectsL[i].submenus.length; x++) {
                        if (QNR_HMENU.hmenuObjectsL[i].submenus[x].style.display != "none") {
                            QNR_HMENU.hmenuObjectsL[i].submenus[x].style.display = "none";
                            QNR_HMENU.hmenuObjectsL[i].submenus[x].parentNode.classList.remove("qnr-hmenu-sub-hover");
                        }
                    }
                }
                QNR_HMENU.hmenuObjectsL[i].menu.style.display = "none";
                QNR_HMENU.hmenuObjectsL[i].object.classList.remove("qnr-hmenu-hover");
            }
        }
    }
    HmenuObject.prototype.hideSubmenus = function(event, allOrOthers) {
        // Hide submenus of this hmenu that are open, all or others
        if (allOrOthers != "all") {
            // Make a list of all submenu objects in target path
            var targetPathObjectsL = [];
            var etp = event.target.parentNode;
            while (1) {
                if (etp.tagName == "UL" && etp.classList.contains("qnr-hmenu-submenu")) {
                    targetPathObjectsL.push(etp);
                    etp = etp.parentNode;
                }
                else if (etp.tagName == "LI") { // Needed for tree traversal
                    etp = etp.parentNode;
                    continue;
                }
                else break;
            }
        }
        for (var i = 0; i < this.submenus.length; i++) {
            var continueOuter = false;
            if (window.getComputedStyle(this.submenus[i], "").display != "none") {
                if (allOrOthers == "all") {
                    this.submenus[i].style.display = "none";
                    this.submenus[i].parentNode.classList.remove("qnr-hmenu-sub-hover");
                }
                else { // Hide the submenu if it isn't in the path of the event target
                    for (var x = 0; x < targetPathObjectsL.length; x++) {
                        // Continue upper loop if the submenu is in target path
                        if (this.submenus[i] == targetPathObjectsL[x]) {
                            continueOuter = true;
                            break;
                        }
                    }
                    if (continueOuter) continue;
                    this.submenus[i].style.display = "none";
                    this.submenus[i].parentNode.classList.remove("qnr-hmenu-sub-hover");
                }
            }
        }
    }
    
    
    // ----------------------- ONLOAD
    
    window.addEventListener("load", function(){
        // Needed for accurate element position measurement on load
        window.scrollBy(0, 1);
        window.scrollBy(0, -1);
        
        // ----------------------- Hierarchical menus
        QNR_HMENU.hmenusL = classObjs("qnr-hmenu");
        if (QNR_HMENU.hmenusL) {
            for (var i = 0; i < QNR_HMENU.hmenusL.length; i++) {
                // Create a data- id attribute on the hmenu
                QNR_HMENU.hmenusL[i].dataset.qnrHmenuId = i;
                // Create a new JS object for the hmenu
                QNR_HMENU.hmenuObjectsL.push(new HmenuObject());
                QNR_HMENU.hmenuObjectsL[i].object = QNR_HMENU.hmenusL[i];
                // Initialize object
                QNR_HMENU.hmenuObjectsL[i].initialize();
            }
        }
        
    }, false);
    
    
    // ----------------------- ONCLICK
    
    window.addEventListener("click", function(event){
        //print("w");
        // Close any open menus and submenus, if click not in menu or submenu
        if (QNR_HMENU.hmenuObjectsL) {
            var etp = event.target.parentNode;
            // If for some reason we reach beyond <HTML>, it will be undefined
            // <A> tags in <LI>s taken care of in LI handler
            if ((event.target.tagName === undefined || etp.tagName === undefined) 
                                                    || (!event.target.classList.contains("qnr-hmenu") 
                                                    && !etp.classList.contains("qnr-hmenu-menu") 
                                                    && !etp.classList.contains("qnr-hmenu-submenu"))) {
                for (var i = 0; i < QNR_HMENU.hmenuObjectsL.length; i++) {
                    if (window.getComputedStyle(QNR_HMENU.hmenuObjectsL[i].menu, "").display != "none") {
                        QNR_HMENU.hmenuObjectsL[i].menu.style.display = "none";
                        QNR_HMENU.hmenuObjectsL[i].object.classList.remove("qnr-hmenu-hover");
                        // Submenus of this menu
                        if (QNR_HMENU.hmenuObjectsL[i].submenus) {
                            var subms = QNR_HMENU.hmenuObjectsL[i].submenus;
                            for (var x = 0; x < subms.length; x++) {
                                if (window.getComputedStyle(subms[x], "").display != "none") {
                                    subms[x].style.display = "none";
                                    subms[x].parentNode.classList.remove("qnr-hmenu-sub-hover");
                                }
                            }
                        }
                    }
                }
            }
        }
    }, false);
    
    
    if (!deviceIsMobile()) {
        // ----------------------- ONMOUSEOUT (only if not mobile)
        
        window.addEventListener("mouseout", function(event){
            // Close any open menus and submenus, if mouse not over menu or submenu
            if (QNR_HMENU.hmenuObjectsL) {
                var et = event.target;
                var etp = et.parentNode;
                // Test for mouseout from a (sub)menu item (taking care of <A> tags in <Li>s)
                if (etp !== undefined && etp.className !== undefined && etp.parentNode.className !== undefined
                        && (et.classList.contains("qnr-hmenu") 
                        || etp.classList.contains("qnr-hmenu-menu")
                        || etp.parentNode.classList.contains("qnr-hmenu-menu")
                        || etp.classList.contains("qnr-hmenu-subholder")
                        || etp.classList.contains("qnr-hmenu-submenu")
                        || etp.parentNode.classList.contains("qnr-hmenu-submenu"))) {
                    if (et.classList.contains("qnr-hmenu-in-collapsed")) return;
                    else if (et.classList !== undefined && et.classList.contains("qnr-hmenu")) etp = et;
                    else {
                        while (1) { // Get hmenu widget object
                            etp = etp.parentNode;
                            if (etp.className === undefined || etp.classList.contains("qnr-hmenu-in-collapsed")) return;
                            else if (etp.classList.contains("qnr-hmenu")) break;
                        }
                    }
                    var tObj = QNR_HMENU.hmenuObjectsL[etp.dataset.qnrHmenuId];
                    if (tObj.hoverOpen != "yes") return; // No action
                    // Record time in hmenu object
                    var tDate = new Date();
                    tObj.changeTime = tDate.getTime();
                    
                    function onMove(event) {
                        var mObj = event.target;
                        var mObjP = mObj.parentNode;
                        // Test mouse not over any (sub)menu, hide all after delay
                        if ((mObj.tagName === undefined || mObjP.tagName === undefined || mObjP.parentNode.tagName === undefined) 
                                                            || (!mObj.classList.contains("qnr-hmenu") 
                                                            && !mObj.classList.contains("qnr-hmenu-submenu") 
                                                            && !mObjP.parentNode.classList.contains("qnr-hmenu") 
                                                            && !mObjP.classList.contains("qnr-hmenu-menu") 
                                                            && !mObjP.parentNode.classList.contains("qnr-hmenu-menu") 
                                                            && !mObjP.classList.contains("qnr-hmenu-submenu") 
                                                            && !mObjP.parentNode.classList.contains("qnr-hmenu-submenu"))) {
                            var nDate = new Date();
                            if (nDate.getTime() - tObj.changeTime > tObj.delayTime) {
                                window.removeEventListener("mousemove", onMove, false);
                                tObj.hideMenus();
                            }
                        }
                        // Test mouse not over any subholder or submenu, hide submenus after delay
                        else if (!mObj.classList.contains("qnr-hmenu-subholder") && !mObj.classList.contains("qnr-hmenu-submenu")) {
                            var nDate = new Date();
                            if (nDate.getTime() - tObj.changeTime > tObj.delayTime) {
                                window.removeEventListener("mousemove", onMove, false);
                                tObj.hideSubmenus(event, "others");
                            }
                        }
                        else { // Mouse is back over a (sub)menu
                            window.removeEventListener("mousemove", onMove, false);
                        }
                    }
                    window.addEventListener("mousemove", onMove, false);
                }
            }
        },false);
    
        // ----------------------- ONKEYDOWN (only if not mobile)
        
        window.addEventListener("keydown", function(event){
            if (QNR_HMENU.hmenuObjectsL) {
                var keyCode = ('which' in event) ? event.which : event.keyCode;
                if (keyCode == 27) { // Escape key
                    for (var i = 0; i < QNR_HMENU.hmenuObjectsL.length; i++) {
                        QNR_HMENU.hmenuObjectsL[i].hideSubmenus(event, "all");
                    }
                    QNR_HMENU.hmenuObjectsL[0].hideMenus();
                }
            }
        },false);
    }

    // ===================== UTILITY FUNCTIONS =====================
    
    // Removed, using global functions in Quicknr Interface

})() // End of Quicknr Hmenu
