/* ==================================================================
 *
 *            QUICKNR INTERFACE 1.7+ NAVMENU HMENU
 *
 *            Copyright 2019 Karl Dolenc, beholdingeye.com.
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

"use strict";

var QNR_NAVMENU = {};

(function(){
    
    /* ----------------------- INFO ---------------------------------
     * 
     *                  1.7 VERSION UPDATE
     * 
     * Navmenu and Hmenu widgets are now provided separately from other
     * QI widgets, as they are considered too complex and difficult to
     * maintain in most use cases. They will not be developed further. 
     * In usage, they require the main QI script.
     * 
     * 
     *                         Navmenu
     * 
     * A DIV with the class of "qnr-navmenu" is a top navigation menu 
     * that should contain menu items in a UL list. The UL/LI items need
     * no class markup. On a large screen, items appear horizontally,
     * but if their containing DIV is resized down to cause a line wrap
     * between items, they collapse into a menu icon with the class of
     * "qnr-navmenu-icon", using the "quicknr-interface.woff" font for
     * the glyph. When the navmenu is collapsed, clicking the icon 
     * reveals the menu.
     * 
     * The icon will be classed "qnr-navmenu-icon-open" in default state
     * and "qnr-navmenu-icon-close" when collapsed menu is shown.
     * 
     * If the "data-qnr-menu-icon-still" attribute is present and not
     * set to "false", the icon will not change on toggle. This is 
     * useful when the collapsed menu is of type "drawer" (see below).
     * 
     * The UL of a collapsed navmenu is taken out of the widget DIV by
     * the code and placed in a new DIV after the widget, the new DIV
     * classed as "qnr-navmenu-wrapper".
     * 
     * To position an element - such as a logo - to the left of the 
     * menu, place it before the menu UL in the widget DIV and float it.
     * 
     * Hierarchical menus from "qnr-hmenu.js" are supported by the 
     * navmenu, and there is some cross-reference to both in the code.
     * 
     * Two types of the collapsed navmenu are available: the newer
     * "drawer" and the older "list", and can be set with the dataset
     * attribute "data-qnr-navmenu-type".
     * 
     * Drawer is default and recommended, as the list type is fragile 
     * due to the fact that for this type 1) the height of the widget 
     * DIV is assumed to be the same as the height of the containing 
     * header and 2) it is assumed that height is unchanging.
     * 
     * DRAWER TYPE:
     * 
     * The menu wrapper as drawer, classed "qnr-navmenu-wrapper-drawer",
     * will slide in from the left, over a dynamically created shim with
     * the class of "qnr-background-shim".
     * 
     * The menu wrapper is classed "qnr-navmenu-vertical-hidden-left" 
     * initially, while "qnr-navmenu-vertical-horizontal-drawer" is 
     * added to the UL in the wrapper.
     * 
     * The class "qnr-navmenu-vertical-show-right" is added to the menu
     * wrapper when drawer is shown, "qnr-navmenu-vertical-hide-left"
     * while being hidden.
     * 
     * LIST TYPE:
     * 
     * The wrapper has CSS height of 0 and allows the contained UL to 
     * overflow, when the header is positioned relative, or matches 
     * viewport height if header is fixed.
     * 
     * The direction of the menu animation can be set with the 
     * "data-qnr-direction" attribute, either "vertical" (default) or 
     * "horizontal". The menu appears over page content.
     * 
     * The UL menu is assigned the class of "qnr-navmenu-vertical" for
     * animation from the top, and "qnr-navmenu-vertical-horizontal" for
     * horizontal animation. The menu is animated by pure CSS.
     * 
     * If an element with the class of "qnrwp-has-fixed-header" is found
     * in the document, the header containing the navmenu is assumed to
     * have fixed CSS positioning by the code, and the menu wrapper will
     * be classed "qnr-navmenu-wrapper-fixed". Otherwise the wrapper is 
     * classed with "qnr-navmenu-wrapper-absolute".
     * 
     * The navmenu widget is expected to be the topmost object on the
     * page for vertical menu animation to work correctly. If the menu
     * is placed further down the page, use the horizontal animation.
     * 
     * 
     *                         Hmenu
     * 
     * Hmenu is in its own function after the Navmenu code.
     * 
     * -------------------------------------------------------------- */ 


    // ===================== STARTUP ===================== 
    
    // ----------------------- Create a list of widgets
    
    // Only one menu object, no list
    QNR_NAVMENU.navmenuObject = null;
    
    
    // ----------------------- NAVMENU
    
    function NavmenuObject() {
        this.object         = null;
        this.menuUL         = null;
        this.menuItemsL     = [];
        this.itemHeight     = 0;
        this.menuIcon       = null;
        this.menuIconStill  = "false"; // Will icon change on toggle?
        this.direction      = "vertical";
        this.menuWrapper    = null;
        this.bgShim         = null; // Shim used for drawer type
        this.navmenuType    = "drawer"; // Or "list"
        this.fixedHeader    = false;
        // Record of window scroll when wrapper shown
        this.winScroll      = 0;
    };
    NavmenuObject.prototype.initialize = function() {
        if (this.object.dataset.qnrMenuIconStill) this.menuIconStill = this.object.dataset.qnrMenuIconStill;
        if (this.object.dataset.qnrNavmenuType) this.navmenuType = this.object.dataset.qnrNavmenuType;
        if (this.object.dataset.qnrDirection) this.direction = this.object.dataset.qnrDirection;
        //this.menuUL = this.object.querySelector("ul"); // OLD
        this.menuUL = objTag("ul", this.object); // NEW
        //this.menuItemsL = this.object.querySelectorAll("ul:first-child > li"); // Not a live collection... OLD
        this.menuItemsL = this.menuUL.children; // NEW
        // Get dimensions of first LI item (traversing all does not work)
        this.itemHeight = this.menuItemsL[0].offsetHeight;
        // Is header fixed? Check for special class in doc (used in QNRWP-A on header and content rows)
        if (objClass("qnrwp-has-fixed-header")) this.fixedHeader = true;
        // Style menu, expanded or collapsed
        this.stylemenu();
        // Make the menu DIV visible (from hidden, in CSS file)
        // If collapsed, UL within will not be displayed, until icon is clicked
        this.object.style.visibility = "visible";
    };
    NavmenuObject.prototype.stylemenu = function() {
        // The following must work on load and on resize...
        // Remove "qnr-hmenu-in-collapsed" class from any LI items as hmenu widgets
        for (var x = 0; x < this.menuItemsL.length; x++) {
            if (this.menuItemsL[x].classList.contains("qnr-hmenu-in-collapsed")) {
                this.menuItemsL[x].classList.remove("qnr-hmenu-in-collapsed");
            }
        }
        if (this.bgShim) {
            // Remove BG shim
            this.bgShim.parentNode.removeChild(this.bgShim);
            this.bgShim = null;
        }
        if (this.menuWrapper) {
            // Remove wrapper and place menu list back in widget DIV
            this.object.appendChild(this.menuWrapper.removeChild(this.menuUL));
            this.menuWrapper.parentNode.removeChild(this.menuWrapper);
            this.menuWrapper = null;
        }
        if (this.menuIcon) {
            this.object.removeChild(this.menuIcon);
            this.menuIcon = null;
            this.menuUL.classList.remove("qnr-navmenu-vertical");
            this.menuUL.classList.remove("qnr-navmenu-vertical-horizontal");
            this.menuUL.classList.remove("qnr-navmenu-vertical-horizontal-drawer");
            if (this.navmenuType != "drawer") {
                if (this.direction == "vertical") {
                    this.menuUL.classList.remove("qnr-navmenu-vertical-show");
                    this.menuUL.classList.remove("qnr-navmenu-vertical-hide");
                    this.menuUL.classList.remove("qnr-navmenu-vertical-hidden");
                } else {
                    this.menuUL.classList.remove("qnr-navmenu-vertical-show-right");
                    this.menuUL.classList.remove("qnr-navmenu-vertical-hide-left");
                    this.menuUL.classList.remove("qnr-navmenu-vertical-hidden-left");
                }
                if (this.fixedHeader) {
                    // Reset height & overflow of navmenu
                    this.menuUL.style.height = "";
                    this.menuUL.style.maxHeight = "";
                    this.menuUL.style.overflow = "";
                }
            }
        }
        for (var i = 0; i < this.menuItemsL.length; i++) {
            // Test for line wrap in nav menu, and collapse it (consider half of item height, and negative values, to avoid rounding errors and upward folding ?)
            if (this.menuItemsL[i].offsetTop > (this.itemHeight/2) || this.menuItemsL[i].offsetTop < ((this.itemHeight/2)*-1.0)) {
            //console.log("offsetTop of item:");
            //console.log(this.menuItemsL[i].offsetTop);
            //console.log("itemHeight:");
            //console.log(this.itemHeight);
            //if (true === false) { // TEST TODO
                // Assign "qnr-hmenu-in-collapsed" class to any LI items as hmenu widgets
                for (var x = 0; x < this.menuItemsL.length; x++) {
                    if (this.menuItemsL[x].classList.contains("qnr-hmenu")) {
                        this.menuItemsL[x].classList.add("qnr-hmenu-in-collapsed");
                        // Clear left/up classes from submenus of hmenu
                        var hmSubs = classObjs("qnr-hmenu-submenu-left", this.menuItemsL[x]);
                        for (var z = hmSubs.length - 1; z >= 0; z--) {
                            hmSubs[z].classList.remove("qnr-hmenu-submenu-left");
                        }
                        hmSubs = classObjs("qnr-hmenu-submenu-up", this.menuItemsL[x]);
                        for (var z = hmSubs.length - 1; z >= 0; z--) {
                            hmSubs[z].classList.remove("qnr-hmenu-submenu-up");
                        }
                        
                    }
                }
                // Create vertical menu, and hide it, up/down or left/right
                if (!this.menuWrapper) {
                    // Wrap menu UL in new DIV to place it in DOM after widget DIV
                    if (this.navmenuType == "drawer" && !this.bgShim) {
                        // BG shim
                        this.bgShim = document.createElement("div");
                        this.bgShim.className = "qnr-background-shim";
                        // Set onclick handler as window not working on mobile
                        var thisBgShim = this;
                        this.bgShim.onclick = function (event) {
                            thisBgShim.hideVerticalMenu(event);
                        }
                        //objTag("body").insertBefore(this.bgShim, objTag("body").firstChild);
                        objTag("body").appendChild(this.bgShim);
                    }
                    this.menuWrapper = document.createElement("div");
                    this.menuWrapper.className = "qnr-navmenu-wrapper";
                    if (this.navmenuType == "drawer") this.menuWrapper.classList.add("qnr-navmenu-wrapper-drawer");
                    else if (this.fixedHeader) this.menuWrapper.classList.add("qnr-navmenu-wrapper-fixed");
                    else this.menuWrapper.classList.add("qnr-navmenu-wrapper-absolute");
                    if (this.fixedHeader && this.navmenuType != "drawer") this.menuWrapper.style.visibility = "hidden";
                    this.menuWrapper.appendChild(this.object.removeChild(this.menuUL));
                    if (this.fixedHeader && this.navmenuType != "drawer") {
                        // Place wrapped menu after menu DIV
                        this.object.parentNode.insertBefore(this.menuWrapper, this.object.nextSibling);
                    } else {
                        // Place wrapped menu as first object in BODY so that the menu will drop down from under header (or over it if drawer)
                        objTag("body").insertBefore(this.menuWrapper, objTag("body").firstChild);
                        // Position wrapper after navmenu (assumed same height as containing header)
                        if (this.navmenuType != "drawer") this.menuWrapper.style.top = this.object.offsetHeight+"px";
                    }
                }
                if (this.navmenuType != "drawer") {
                    if (this.direction == "vertical") {
                        this.menuUL.classList.add("qnr-navmenu-vertical");
                        this.menuUL.classList.add("qnr-navmenu-vertical-hidden");
                    } else {
                        this.menuUL.classList.add("qnr-navmenu-vertical-horizontal");
                        this.menuUL.classList.add("qnr-navmenu-vertical-hidden-left");
                    }
                    if (this.fixedHeader) {
                        // Adjust height & overflow of vertical navmenu to max of window - widget (assumed same height as containing header)
                        var headerHeight = this.object.offsetHeight;
                        this.menuUL.style.height = "auto";
                        this.menuUL.style.maxHeight = (objHtml().clientHeight - headerHeight) + "px";
                        this.menuUL.style.overflow = "auto";
                    }
                } else {
                    this.menuUL.classList.add("qnr-navmenu-vertical-horizontal-drawer");
                    this.menuWrapper.classList.add("qnr-navmenu-vertical-hidden-left");
                }
                // Create icon
                if (!this.menuIcon) this.createMenuIcon();
                break;
            }
        }
    };
    NavmenuObject.prototype.createMenuIcon = function() {
        this.menuIcon = document.createElement("div");
        // Try to work around iOS Safari problem with the window onclick event
        var this1 = this;
        this.menuIcon.onclick = function(event) {
            if (this1.menuIcon) {
                if (this1.menuIcon.classList.contains("qnr-navmenu-icon-open")) {
                    this1.showVerticalMenu();
                }
                else { // Closed
                    this1.hideVerticalMenu(event);
                }
                event.stopPropagation();
            }
        };
        this.menuIcon.classList.add("qnr-navmenu-icon");
        this.menuIcon.classList.add("qnr-navmenu-icon-open");
        // Place menu icon in widget DIV, now possibly empty
        this.object.appendChild(this.menuIcon);
    };
    NavmenuObject.prototype.showVerticalMenu = function() {
        if (this.navmenuType != "drawer") {
            if (this.fixedHeader && QNR.deviceIsMobile()) {
                this.menuWrapper.style.transition = "none";
                this.menuWrapper.style.background = "#333";
                // Disable scroll on BODY
                this.winScroll = window.pageYOffset;
                objTag("html").style.overflow = "hidden";
                objTag("body").style.overflow = "hidden";
            }
        }
        // Change the icon to close
        this.menuIcon.classList.remove("qnr-navmenu-icon-open");
        this.menuIcon.classList.add("qnr-navmenu-icon-close");
        if (this.menuIconStill != "false") this.menuIcon.classList.add("qnr-navmenu-icon-close-still");
        //// Set top of menu wrapper (absolute positioned) to after widget
        //if (this.menuWrapper && window.getComputedStyle(this.object, "").position == "fixed") {
            //this.menuWrapper.style.top = (this.object.offsetTop + this.object.offsetHeight + window.pageYOffset) + "px";
            //// Record window scroll for later comparison on scrolling
            //this.winScroll = window.pageYOffset;
        //}
        //else if (this.menuWrapper) { // Widget positioned relative or absolute
            //this.menuWrapper.style.top = (this.object.offsetTop + this.object.offsetHeight) + "px"; // TODO, offsetTop??
        //}
        // Show vertical menu list
        if (this.fixedHeader && this.navmenuType != "drawer") {
            // Adjust overflow & visibility & top of wrapper
            var headerHeight = this.object.offsetHeight;
            this.menuWrapper.style.top = headerHeight + "px";
            this.menuWrapper.style.overflow = "hidden";
            this.menuWrapper.style.visibility = "visible";
        }
        if (this.navmenuType != "drawer") {
            if (this.direction == "vertical") {
                this.menuUL.classList.remove("qnr-navmenu-vertical-hide");
                this.menuUL.classList.remove("qnr-navmenu-vertical-hidden");
                this.menuUL.classList.add("qnr-navmenu-vertical");
                this.menuUL.classList.add("qnr-navmenu-vertical-show");
            } else {
                this.menuUL.classList.remove("qnr-navmenu-vertical-hide-left");
                this.menuUL.classList.remove("qnr-navmenu-vertical-hidden-left");
                this.menuUL.classList.add("qnr-navmenu-vertical-horizontal");
                this.menuUL.classList.add("qnr-navmenu-vertical-show-right");
            }
        } else {
            this.bgShim.style.display = "block";
            var that = this;
            window.setTimeout(function(){
                that.bgShim.style.transition = "all 0.3s";
                that.bgShim.style.opacity = 1;
            },10);
            this.menuWrapper.classList.remove("qnr-navmenu-vertical-hide-left");
            this.menuWrapper.classList.remove("qnr-navmenu-vertical-hidden-left");
            this.menuUL.classList.add("qnr-navmenu-vertical-horizontal-drawer");
            this.menuWrapper.classList.add("qnr-navmenu-vertical-show-right");
        }
    };
    NavmenuObject.prototype.hideVerticalMenu = function(event) {
        if (this.navmenuType != "drawer") {
            if (this.fixedHeader && QNR.deviceIsMobile()) {
                // Enable scroll on BODY
                objTag("html").style.overflow = "";
                objTag("body").style.overflow = "";
                window.scrollBy(0, this.winScroll);
            }
        }
        // Change icon to open
        this.menuIcon.classList.remove("qnr-navmenu-icon-close");
        if (this.menuIconStill != "false") this.menuIcon.classList.remove("qnr-navmenu-icon-close-still");
        this.menuIcon.classList.add("qnr-navmenu-icon-open");
        // Animate only if not clicked on link, not navigating to another page
        if (event.target.tagName != "A") {
            if (this.fixedHeader && QNR.deviceIsMobile()) {
                this.menuWrapper.style.background = "transparent"; // TODO remove for drawer?
                this.menuWrapper.style.transition = "all 0.4s";
            }
            // Hide vertical menu list
            if (this.navmenuType != "drawer") {
                if (this.direction == "vertical") {
                    this.menuUL.classList.remove("qnr-navmenu-vertical-show");
                    this.menuUL.classList.remove("qnr-navmenu-vertical-hidden");
                    this.menuUL.classList.add("qnr-navmenu-vertical-hide");
                } else {
                    this.menuUL.classList.remove("qnr-navmenu-vertical-show-right");
                    this.menuUL.classList.remove("qnr-navmenu-vertical-hidden-left");
                    this.menuUL.classList.add("qnr-navmenu-vertical-hide-left");
                }
            } else {
                this.menuWrapper.classList.remove("qnr-navmenu-vertical-show-right");
                this.menuWrapper.classList.remove("qnr-navmenu-vertical-hidden-left");
                this.menuWrapper.classList.add("qnr-navmenu-vertical-hide-left");
                var that = this;
                window.setTimeout(function(){
                    that.bgShim.style.transition = "all 0.3s";
                    that.bgShim.style.opacity = 0;
                },10);
            }
            var that = this;
            window.setTimeout(function(){
                if (that.navmenuType != "drawer") {
                    if (that.direction == "vertical") {
                        that.menuUL.classList.add("qnr-navmenu-vertical-hidden");
                        that.menuUL.classList.remove("qnr-navmenu-vertical-hide");
                    } else {
                        that.menuUL.classList.add("qnr-navmenu-vertical-hidden-left");
                        that.menuUL.classList.remove("qnr-navmenu-vertical-hide-left");
                    }
                    if (that.fixedHeader) {
                        // Reset height of wrapper & overflow
                        that.menuWrapper.style.top = "";
                        that.menuWrapper.style.overflow = "";
                        that.menuWrapper.style.visibility = "hidden";
                    }
                } else {
                    that.menuWrapper.classList.add("qnr-navmenu-vertical-hidden-left");
                    that.menuWrapper.classList.remove("qnr-navmenu-vertical-hide-left");
                    that.bgShim.style.display = "none";
                }
            },600); // Assuming 0.8s animation
        } else { // Clicked out, no anim
            var that = this;
            // Delay destruction of menu, prevent flash of present page before moving on
            window.setTimeout(function(){
                if (that.navmenuType != "drawer") {
                    if (that.fixedHeader && QNR.deviceIsMobile()) {
                        that.menuWrapper.style.background = "transparent";
                        that.menuWrapper.style.transition = "none";
                    }
                    if (that.direction == "vertical") {
                        that.menuUL.classList.remove("qnr-navmenu-vertical-show");
                        that.menuUL.classList.remove("qnr-navmenu-vertical-hide");
                        that.menuUL.classList.add("qnr-navmenu-vertical-hidden");
                    } else {
                        that.menuUL.classList.remove("qnr-navmenu-vertical-show-right");
                        that.menuUL.classList.remove("qnr-navmenu-vertical-hide-left");
                        that.menuUL.classList.add("qnr-navmenu-vertical-hidden-left");
                    }
                    if (that.fixedHeader) {
                        // Reset height of wrapper & overflow
                        that.menuWrapper.style.top = "";
                        that.menuWrapper.style.overflow = "";
                        that.menuWrapper.style.visibility = "hidden";
                    }
                } else {
                    that.bgShim.style.display = "none";
                    that.menuWrapper.classList.remove("qnr-navmenu-vertical-show-right");
                    that.menuWrapper.classList.remove("qnr-navmenu-vertical-hide-left");
                    that.menuWrapper.classList.add("qnr-navmenu-vertical-hidden-left");
                }
            },100);
        }
        // Hide any hmenus
        if (QNR_HMENU.hmenuObjectsL.length > 0) {
            QNR_HMENU.hmenuObjectsL[0].hideMenus();
        }
    };
    NavmenuObject.prototype.onWinScroll = function(event) {
        //if (this.fixedHeader && this.menuWrapper.style.visibility == "visible") {
        //console.log("On scroll");
            //event.stopImmediatePropagation();
            //event.stopPropagation();
            //event.preventDefault();
        //}
        
        //// Move the wrapper up with scrolling if it was shown at scroll > 0, used when widget is "fixed"
        //if (this.menuWrapper && window.pageYOffset < this.winScroll) {
            //this.menuWrapper.style.top = (this.menuWrapper.offsetTop - (this.winScroll - window.pageYOffset)) + "px";
            //this.winScroll = window.pageYOffset;
        //}
        //// Hide vertical menu if scrolled down to its last item
        //else if (this.menuWrapper && window.getComputedStyle(this.object,"").position == "fixed" && 
                                                                //window.pageYOffset > this.winScroll) {
            //if (this.menuIcon.classList.contains("qnr-navmenu-icon-close")) {
                //// QNR.getYPos required instead of offsetTop
                //if (QNR.getYPos(this.menuItemsL[this.menuItemsL.length-1],0) <= 
                                //QNR.getYPos(this.object,0) + this.object.offsetHeight + window.pageYOffset) {
                    //this.hideVerticalMenu();
                //}
            //}
        //}
    };
    
    
    // ----------------------- ONLOAD
    
    window.addEventListener("load", function() {
        // Needed for accurate element position measurement on load
        window.scrollBy(0, 1);
        window.scrollBy(0, -1);
        
        // ----------------------- Navmenu JS object
        
        if (objClass("qnr-navmenu")) {
            QNR_NAVMENU.navmenuObject = new NavmenuObject();
            QNR_NAVMENU.navmenuObject.object = objClass("qnr-navmenu");
            QNR_NAVMENU.navmenuObject.initialize();
        }
        
    }, false);
    
    
    // ----------------------- ONCLICK
    
    // UI elements working independently must capture their events
    window.addEventListener("click", function(event) {
        var clicked = event.target;
        
        // ----------------------- Navmenu icon
        
        //if (clicked.classList.contains("qnr-navmenu-icon")) {
            //if (clicked.classList.contains("qnr-navmenu-icon-open")) {
                //QNR_NAVMENU.navmenuObject.showVerticalMenu();
            //}
            //else { // Closed
                //QNR_NAVMENU.navmenuObject.hideVerticalMenu();
            //}
        //}
        // Dismiss navmenu on any click
        //else if (QNR_NAVMENU.navmenuObject && document.querySelector("div.qnr-navmenu-icon-close")) {
        if (QNR_NAVMENU.navmenuObject && document.querySelector("div.qnr-navmenu-icon-close") &&
                        !clicked.classList.contains("qnr-hmenu")) { // Don't close on click on hmenu widget
            QNR_NAVMENU.navmenuObject.hideVerticalMenu(event);
        }
        
        return;
    }, false);
    
    
    // ----------------------- ONRESIZE TODO (see note)
    
    window.addEventListener("resize", function(event) {
        if (QNR_NAVMENU.navmenuObject) QNR_NAVMENU.navmenuObject.stylemenu(); // Must be after font resize, so let it be last
    }, false);
    
            
    // ----------------------- ONSCROLL
    
    // Set up scroll event listener, must be on window
    window.addEventListener("scroll", function(event){
        
        // ----------------------- Navmenu
        if (QNR_NAVMENU.navmenuObject) {
            QNR_NAVMENU.navmenuObject.onWinScroll(event);
        }
        
    },false);

})() // End of Quicknr Navmenu


var QNR_HMENU = {};

(function(){
    
    /* ----------------------- INFO ---------------------------------
     * 
     *                   Hierarchical Menu
     *
     * Any clickable element assigned the class of "qnr-hmenu" will 
     * display a dropdown hierarchical menu widget, potentially 
     * containing submenus, if the clickable element contains a UL 
     * element, interpreted by the code as defining the dropdown menu.
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
     * hmenu, and the submenu LI items may contain further submenus.
     * 
     * The LI item containing a submenu should precede the submenu UL
     * with text that will be the LI item's label. The text must not be
     * part of a HTML tag of its own. If the text is wrapped in an A tag
     * (as may be on Wordpress) the A tag will be removed. It is bad
     * user experience if (sub)menu holders are also links - they are
     * not supported [v1.7 edit: this claim is old and untrue, but kept 
     * as explanation].
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
     * managed in code, not a CSS rule.
     * 
     * Note that only "down" and "right" directions, the defaults, will
     * result in container scrollbars if (sub)menus overflow. However,
     * in relation to width of the client window, left/right direction
     * of (sub)menu appearance may alter to avoid overflowing offscreen.
     * 
     * Menus can be set to open and close on mouse hover with the 
     * "data-qnr-hover" attribute set to "yes", the default, or "no" for
     * clicking support only.
     * 
     * LI items that should not be active but act as spacers or section
     * titles should be assigned the class of "qnr-hmenu-idle", perhaps
     * overriden.
     * 
     * By default, submenus are positioned by CSS. To align them to top
     * edges of their parent menus, set a "data-qnr-hmenu-align" 
     * attribute on the widget to "yes".
     * 
     * While menus are open, the code assigns the "qnr-hmenu-hover" 
     * class to the widget object. This helps to time the appearance of
     * the widget in sync with the menus. Likewise, submenu-holding LI
     * items are assigned the "qnr-hmenu-sub-hover" class when their 
     * submenus are open.
     * 
     * Delay in ms before mouseouts have effect is by default 200, and
     * can be set with the "data-qnr-hmenu-delay" attribute.
     * 
     * The Navmenu widget supports Hmenus, and there is some cross-
     * reference to both in the code.
     * 
     * If the hmenu object (the DIV containing the UL) is in a collapsed
     * navmenu (contained in a LI item of the navmenu), hmenu will be 
     * classed as "qnr-hmenu-in-collapsed".
     * 
     * -------------------------------------------------------------- */ 

    // ===================== STARTUP ===================== 
    
    // ----------------------- Create a list of widgets
    QNR_HMENU.hmenusL = []; // List of hierarchical menu elements in doc
    QNR_HMENU.hmenuObjectsL = []; // List of JS hierarchical menu objects
    
    // ----------------------- HIERARCHICAL MENU
    
    function HmenuObject() {
        this.object = null;
        this.directionX = "down";
        this.directionY = "right";
        this.menu = null;
        this.submenusL = [];
        this.parentsL = []; // Containers of submenus
        this.hoverOpen = "yes"; // Open on hover or only on click
        this.changeTime = 0; // Used for mouse event delays
        this.delayTime = 200; // Delay in ms before mouseouts have effect
        this.alignSubmenus = "no"; // Align submenus to parent top
    };
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
        this.submenusL = tagObjs("ul", this.menu);
        for (var i = 0; i < this.submenusL.length; i++) {
            this.submenusL[i].classList.add("qnr-hmenu-submenu");
            this.submenusL[i].style.display = "none";
            if (this.directionX == "left") this.submenusL[i].classList.add("qnr-hmenu-submenu-left");
            if (this.directionY == "up") this.submenusL[i].classList.add("qnr-hmenu-submenu-up");
            // Record parent LI objects
            this.parentsL.push(this.submenusL[i].parentNode);
            this.parentsL[i].classList.add("qnr-hmenu-subholder");
            this.parentsL[i].dataset.qnrSubmenuParentId = i; // Needed for event handler
            // Remove A tags around first element child of submenu holder
            if (this.parentsL[i].firstElementChild && this.parentsL[i].firstElementChild.tagName == "A") {
                var objAText = this.parentsL[i].firstElementChild.innerHTML; // TODO improve over innerHTML?
                this.parentsL[i].removeChild(this.parentsL[i].firstElementChild);
                this.parentsL[i].innerHTML = objAText + this.parentsL[i].innerHTML;
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
        if (this.hoverOpen == "yes" && !QNR.deviceIsMobile()) {
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
                        var sMenu = this1.submenusL[parseInt(event.target.dataset.qnrSubmenuParentId)];
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
                if (this.hoverOpen == "yes" && !QNR.deviceIsMobile()) {
                    //this1 = this;
                    liItemsL[i].addEventListener("mouseover", function(event) {
                        // Prevent child LI objects in further submenus bubbling up to here and failing
                        if (event.target.dataset.qnrSubmenuParentId && 
                                    !this1.object.classList.contains("qnr-hmenu-in-collapsed")) {
                            // Hide any other shown submenus, not in path of target
                            this1.hideSubmenus(event, "others");
                            var sMenu = this1.submenusL[parseInt(event.target.dataset.qnrSubmenuParentId)];
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
                        QNR_NAVMENU.navmenuObject.hideVerticalMenu(event);
                    }
                    event.stopPropagation();
                }, true);
            }
        }
    };
    HmenuObject.prototype.positionHMenu = function() {
        var mPos = QNR.getXPos(this.menu, 0);
        var mW = this.menu.offsetWidth;
        var cW = objHtml().clientWidth;
        // If menu would spill out of client area, move it forward
        if (mPos + mW > cW) {
            this.menu.style.left = "-" + ((mPos + mW) - cW) + "px";
            // And style the submenus to open left
            if (this.submenusL.length > 0) {
                for (var i = 0; i < this.submenusL.length; i++) {
                    if (!this.submenusL[i].classList.contains("qnr-hmenu-submenu-left")) {
                        this.submenusL[i].classList.add("qnr-hmenu-submenu-left");
                    }
                }
            }
        }
        else if (this.object.classList.contains("qnr-hmenu-in-collapsed")) {
            this.menu.style.left = ""; // Undo position
        }
    };
    HmenuObject.prototype.positionSubmenu = function(subM) {
        // If submenu would exceed client bounds, range it and its child submenus left/right
        var sPos = QNR.getXPos(subM, 0);
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
    };
    HmenuObject.prototype.alignSubmenuToParent = function(sMenu) {
        sMenu.style.top = "-" + sMenu.parentNode.offsetTop + "px";
    };
    HmenuObject.prototype.hideMenus = function() {
        // Hide any hmenus that are open
        for (var i = 0; i < QNR_HMENU.hmenuObjectsL.length; i++) {
            if (window.getComputedStyle(QNR_HMENU.hmenuObjectsL[i].menu, "").display != "none") {
                if (QNR_HMENU.hmenuObjectsL[i].submenusL.length > 0) { // Hide any submenus
                    for (var x = 0; x < QNR_HMENU.hmenuObjectsL[i].submenusL.length; x++) {
                        if (QNR_HMENU.hmenuObjectsL[i].submenusL[x].style.display != "none") {
                            QNR_HMENU.hmenuObjectsL[i].submenusL[x].style.display = "none";
                            QNR_HMENU.hmenuObjectsL[i].submenusL[x].parentNode.classList.remove("qnr-hmenu-sub-hover");
                        }
                    }
                }
                QNR_HMENU.hmenuObjectsL[i].menu.style.display = "none";
                QNR_HMENU.hmenuObjectsL[i].object.classList.remove("qnr-hmenu-hover");
            }
        }
    };
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
        for (var i = 0; i < this.submenusL.length; i++) {
            var continueOuter = false;
            if (window.getComputedStyle(this.submenusL[i], "").display != "none") {
                if (allOrOthers == "all") {
                    this.submenusL[i].style.display = "none";
                    this.submenusL[i].parentNode.classList.remove("qnr-hmenu-sub-hover");
                }
                else { // Hide the submenu if it isn't in the path of the event target
                    for (var x = 0; x < targetPathObjectsL.length; x++) {
                        // Continue upper loop if the submenu is in target path
                        if (this.submenusL[i] == targetPathObjectsL[x]) {
                            continueOuter = true;
                            break;
                        }
                    }
                    if (continueOuter) continue;
                    this.submenusL[i].style.display = "none";
                    this.submenusL[i].parentNode.classList.remove("qnr-hmenu-sub-hover");
                }
            }
        }
    };
    
    
    // ----------------------- ONLOAD
    
    window.addEventListener("load", function(){
        // Needed for accurate element position measurement on load TODO ??
        window.scrollBy(0, 1);
        window.scrollBy(0, -1);
        
        // ----------------------- Hierarchical menus
        QNR_HMENU.hmenusL = classObjs("qnr-hmenu");
        if (QNR_HMENU.hmenusL.length > 0) {
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
    
    
    // ----------------------- ONUNLOAD
    
    window.addEventListener("unload", function(event) {
        // It appears this needs to run, even if empty, to avoid loading 
        //   problems on Back navigation, at least in Firefox
    }, false);
    
    
    // ----------------------- ONCLICK
    
    window.addEventListener("click", function(event){
        //print("w");
        // Close any open menus and submenus, if click not in menu or submenu
        if (QNR_HMENU.hmenuObjectsL.length > 0) {
            var etp = event.target.parentNode;
            // If for some reason we reach beyond <HTML>, it will be undefined
            // <A> tags in <LI>s taken care of in LI handler
            if ((!etp || event.target.tagName === undefined || etp.tagName === undefined) 
                                                    || (!event.target.classList.contains("qnr-hmenu") 
                                                    && !etp.classList.contains("qnr-hmenu-menu") 
                                                    && !etp.classList.contains("qnr-hmenu-submenu"))) {
                for (var i = 0; i < QNR_HMENU.hmenuObjectsL.length; i++) {
                    if (window.getComputedStyle(QNR_HMENU.hmenuObjectsL[i].menu, "").display != "none") {
                        QNR_HMENU.hmenuObjectsL[i].menu.style.display = "none";
                        QNR_HMENU.hmenuObjectsL[i].object.classList.remove("qnr-hmenu-hover");
                        // Submenus of this menu
                        if (QNR_HMENU.hmenuObjectsL[i].submenusL.length > 0) {
                            var subms = QNR_HMENU.hmenuObjectsL[i].submenusL;
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
    
    
    if (!QNR.deviceIsMobile()) {
        // ----------------------- ONMOUSEOUT (only if not mobile)
        
        window.addEventListener("mouseout", function(event){
            // Close any open menus and submenus, if mouse not over menu or submenu
            if (QNR_HMENU.hmenuObjectsL.length > 0) {
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
            if (QNR_HMENU.hmenuObjectsL.length > 0) {
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

})() // End of Quicknr Hmenu



