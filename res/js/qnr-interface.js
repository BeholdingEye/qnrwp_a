/* ==================================================================
 *
 *            QUICKNR INTERFACE 1.5.0
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
var QNR_INTER = {};

(function(){
    
    /* ----------------------- INFO ---------------------------------
     * 
     *                         Widgets
     *
     * Widgets are DIVs with a class beginning with "qnr-". In the case 
     * of X-icons, the widget may be any tag that can have child 
     * elements
     * 
     * All properties and methods of widgets are accessible in your own
     * code through the global "QNR_INTER" object. See the end of the
     * Carousel section for an example
     * 
     * 
     *                         Arrow Anim
     * 
     * A DIV with the class of "qnr-arrow-anim" is a widget that will
     * display as a circle containing a downward pointing, animated 
     * arrow. The widget uses the "quicknr-interface.woff" font for the
     * glyph. The DIV must be empty and will have the arrow inserted as
     * a SPAN element
     * 
     * The direction of the arrow and its animation can be changed with
     * the dataset "data-qnr-arrow-dir" attribute set to "up", "left" or
     * "right". Default is "down"
     * 
     * Up and down arrows should also be winscroller widgets, see below.
     * Left and right arrows should have an onclick function assigned
     * 
     * The arrow can be set to stop a carousel animating, with the 
     * "data-qnr-stop-carousel" attribute set to the ID of the carousel
     * 
     * 
     *                         Font Resize
     * 
     * Any object that can have font styling applied, may be assigned
     * the class of "qnr-font-resize", which will make it a widget that
     * resizes its font size according to total display area - width 
     * times height. The sizing uses the declared or initial font size 
     * for the element as the largest size, for desktops. Mobiles will 
     * reduce the font size. The reference display area is 1,400x900px,
     * 1,260,000 square pixels. Larger areas have no effect. Smallest
     * area is considered to be 320x320px, 102400 square pixels, and
     * will be the display area size with the minimum font size
     * 
     * The minimum percentage size can be specified with the dataset
     * "data-qnr-font-min" attribute, default value being "80"
     * 
     * 
     *                         Responsive
     * 
     * A DIV with the class of "qnr-responsive" is a widget whose child
     * elements will be laid out in a responsive flexible grid at any 
     * screen size, to a minimum of 320px. The child elements must be
     * DIVs. The widget works well with 2 to 5 DIV items, not so well 
     * with more. For best results, the containing element of the widget
     * (BODY, if no other) should span the entire page and have padding 
     * and margins set to 0
     * 
     * Item DIVs will be assigned the class of "qnr-responsive-item",
     * with margins of 0, and padding set to "0.5em 2em". In some use
     * cases, particularly at small display sizes, you may need to
     * override this
     * 
     * Further overrides may be needed when narrower minimum widths of
     * the item DIVs are desired, as in columns of links in a footer. In
     * this case, override the flex and width properties of the two 
     * classes mentioned above
     * 
     * Remember that a responsive row of more than 4 blocks makes for 
     * poor user experience (except for image galleries)
     * 
     * 
     *                         Carousel
     * 
     * A carousel widget is a DIV with the class of "qnr-carousel". 
     * Items may be DIVs or IMGs and require no additional classes
     * 
     * If items are IMGs, the code will convert them to DIVs with 
     * background images. Items will be assigned the class of 
     * "qnr-carousel-item"
     * 
     * If the widget has an attribute of "data-qnr-captions" 
     * set to "on", ALT attribute values of item IMGs will be placed in 
     * a caption DIV under the carousel, created by the code. The DIV 
     * will be assigned the class of "qnr-carousel-caption"
     * 
     * Three navigation arrow types are supported, set with the 
     * "data-qnr-arrow-type" attribute: "regular" for a thick arrow in 
     * a circle, "big" for bigger thin arrows without a circle 
     * background, and "sticky" for smaller thin arrows on rounded 
     * square backgrounds flush with the left and right edges of the 
     * widget
     * 
     * Arrows are assigned these classes:
     * 
     * regular:     "qnr-carousel-arrow-left"
     *              "qnr-carousel-arrow-right"
     * big:         "qnr-carousel-bigarrow-left"
     *              "qnr-carousel-bigarrow-right"
     * sticky:      "qnr-carousel-stickyarrow-left"
     *              "qnr-carousel-stickyarrow-right"
     * 
     * The arrow glyphs are provided by the "quicknr-interface.woff" 
     * font, derived from the open source "open-iconic" font
     * 
     * The code creates a control strip in the widget, featuring tiny 
     * selector circles for each slide that the user can click or tap
     * 
     * The control strip will be assigned the class of 
     * "qnr-carousel-controlstrip", and the circles "qnr-carousel-thumb"
     * 
     * If the user device is not mobile, the little selector circles on 
     * the control strip will on hover display small animated previews 
     * and have an additional class of "qnr-carousel-thumb-preview"
     * 
     * Selector circles can be set to display slide numbers. For styling
     * override these CSS declarations, where active is the shown slide:
     * 
     * "qnr-carousel-thumb-inactive"
     * "qnr-carousel-thumb-active"
     * "qnr-carousel-thumb-number-inactive"
     * "qnr-carousel-thumb-number-active"
     * 
     * The navigation components - arrows, control strip and thumb 
     * previews and numbers - can be set to "on" or "off" with these 
     * attributes (all "on" by default, except numbers):
     * 
     * "data-qnr-arrows"
     * "data-qnr-strip"
     * "data-qnr-previews"
     * "data-qnr-thumb-numbers"
     * 
     * If a "data-qnr-scroller" attribute is set to "on" (default "off")
     * slide item DIVs will receive the "qnr-scroller" class, making
     * them Parallax Scroller widgets
     * 
     * The time it takes between loading of the page and the start of 
     * play can be set with the "data-qnr-start-interval" 
     * attribute, set to "4" by default (in seconds). The interval 
     * between slides can be set with the "data-qnr-interval" 
     * attribute, by default set to "3"
     * 
     * Duration of transitions between slides may be controlled with the
     * "data-qnr-transition" attribute, default value of "1" 
     * second. Transition mode is set with the "data-qnr-mode" 
     * attribute, with a value of:
     * 
     * "fade" - slides fade over each other without motion (default)
     * 
     * "slideboth" - both the previous and next slides move out/in 
     *                  together, with no fading
     * 
     * "slideover" - incoming slide moves over the previous, no fading
     * 
     * "slidefade" - incoming slide moves over the previous, fading in 
     *                  as it does so
     * 
     * Transition duration must be shorter than interval
     * 
     * If there is only one carousel widget on the page, keyboard 
     * navigation is supported with left and right keys
     * 
     * If the carousel is invisible due to page scrolling, its animation
     * will stop, and resume when back in view. The same when the 
     * browser window loses and regains focus
     * 
     * A scroll offset can be set with the "data-qnr-scroll-offset"
     * attribute, default is "0". Set it to a negative value if using
     * a fixed navbar, so that the slideshow will pause even though the
     * carousel is not completely off screen
     * 
     * When a slide has been selected by the user rather than shown 
     * automatically, its display time is doubled, then the show 
     * continues
     * 
     * To stop the slideshow when a slide is manually selected, set the
     * "data-qnr-resume-auto" attribute on the widget to "off". Default
     * is "on". This is useful if the slides are DIVs showing more than
     * images, with the user being able to interact with the content
     * 
     * Slide fading is controlled with Javascript manipulation of CSS 
     * animations, using the following classes on the items:
     * 
     * "qnr-carousel-fadein"
     * "qnr-carousel-slidefade-rtl"
     * "qnr-carousel-slidefade-ltr"
     * "qnr-carousel-slidein-rtl"
     * "qnr-carousel-slidein-ltr"
     * "qnr-carousel-slideout-rtl"
     * "qnr-carousel-slideout-ltr"
     * 
     * If very large images are used, timing errors may occur, resulting
     * in flashes on fades and slides. There is no fix other than 
     * compressing images more and using fewer of them. Six images or 
     * fewer, under 1MB each, should be fine
     * 
     * Advanced notes:
     * 
     * To display a slide from your own code, carousel methods can be 
     * accessed as follows; X = carousel object index, Y = slide index:
     * 
     *      QNR_INTER.carouselObjectsL[X].showSlide(Y, false, 
     *              QNR_INTER.carouselObjectsL[X].getDirection(Y))
     * 
     * To pause a carousel:
     * 
     *      QNR_INTER.carouselObjectsL[X].pauseCarousel()
     * 
     * ...or resume play with the ".resumeCarousel()" method
     * 
     * Scrolling the page, carousel in view, will resume play unless
     * auto resume is off as detailed above
     * 
     * For a hard stop, "QNR_INTER.carouselObjectsL[X].stopCarousel()"
     * can be used. Subsequent navigation will only start the automatic 
     * sliding again after "QNR_INTER.carouselObjectsL[X].hardStop" is 
     * set to false (and auto resume is on)
     * 
     * 
     *                         Stickybar
     * 
     * If you want a DIV, that spans 100% of the page width and appears
     * at a distance down from the top, perhaps after a header image, to
     * "stick" to the top of the window like a menu bar when the window
     * is scrolled down beyond it, mark the DIV with the "qnr-stickybar"
     * class
     * 
     * The widget will be assigned the class of "qnr-stickybar-fixed" 
     * for its fixed appearance
     * 
     * 
     *                         Slider
     * 
     * A DIV marked with the class of "qnr-slider" is a non-looping,
     * introductory slider of one or more DIVs it contains. The top DIV 
     * in HTML is the last item to play and will end the animation
     * 
     * The widget supports the "data-qnr-slide-duration" attribute with
     * a value of seconds, "4" being default
     * 
     * Direction of the sliding can be set to "rtl" (default), "ltr", 
     * "up" or "down" with the "data-qnr-slide-direction" attribute
     * 
     * If the direction is appended with "-stop", the animation will 
     * stop as the slide is fully on screen, no off movement to follow
     * 
     * By default the sliding will start on load, when the slider is
     * initialized. To prevent this, use the "data-qnr-auto-slide"
     * attribute, set to "off"
     * 
     * The item DIVs require no special markup, but "qnr-slide-none" is
     * available if a DIV needs to be hidden (perhaps to prevent it
     * flashing on load)
     * 
     * The DIV items are marked with the class of "qnr-slide-item" by 
     * the code
     * 
     * For more functionality, use a carousel
     * 
     * 
     *                         Image Animator
     * 
     * An image animator is a DIV with the class of "qnr-img-anim", 
     * containing IMG tags with classes defining animation:
     *  - qnr-img-zoom-in   - zoom in and fade over next image in stack
     *  - qnr-img-zoom-out  - zoom out and fade
     * 
     * An image animator is introductory, non-looping visual candy, "an
     * image with movement", not a slideshow. Use with 3-6 images
     * 
     * For the sake of the bottom image of the stack (top IMG tag in 
     * HTML), "qnr-img-zoom-in-opaque" and "qnr-img-zoom-out-opaque"
     * are also supported, with no fading
     * 
     * The following anchoring classes are supported on the IMG tags:
     * "qnr-centered", "qnr-topright", "qnr-topleft", "qnr-bottomright",
     * "qnr-bottomleft", "qnr-leftcenter", "qnr-rightcenter", top left 
     * being the default
     * 
     * Animation timing function classes on the IMGs can also be used:
     * "qnr-ease", "qnr-ease-in", "qnr-ease-out", "qnr-linear", linear 
     * being the default - ease-out should be used on last shown image
     * 
     * The widget DIV containing the IMGs supports a data- attribute of
     * "data-qnr-anim-duration", with a value in seconds, like "4", the
     * default. The duration is for each image layer
     * 
     * IMG tags may be followed by a DIV with the class of 
     * "qnr-img-translucent-cover", to darken the images
     * 
     * The cover may be succeeded by any other element that is to appear
     * over the image animation - it may require to be CSS positioned
     * 
     * The code converts the IMGs to DIVs, with the classes transferred,
     * plus the class of "qnr-img-anim-div"
     * 
     * Other animation parameters were coded but removed due to poor 
     * performance. If the present functionality is not sufficient, 
     * consider creating a video instead
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
     * the glyph
     * 
     * Clicking the icon reveals the menu. The direction of the menu
     * animation can be set with the "data-qnr-direction" attribute,
     * either "vertical" (default) or "horizontal". The menu appears 
     * over page content
     * 
     * The UL menu is assigned the class of "qnr-navmenu-vertical" for
     * animation from the top, and "qnr-navmenu-vertical-horizontal" for
     * horizontal animation. The menu is animated by pure CSS
     * 
     * The navmenu widget is expected to be the topmost object on the
     * page for vertical menu animation to work correctly. If the menu
     * is placed further down the page, use the horizontal animation
     * 
     * To position an element - such as a logo - to the left of the 
     * menu, place it before the menu UL in the widget DIV and float it
     * 
     * Hierarchical menus from "qnr-hmenu.js" are now supported by the
     * navmenu, and there is some cross-reference to both in the code
     * 
     * 
     *                         Scrollers
     * 
     * Scrollers are widgets that will be affected by scrolling in some
     * way, and have the class "qnr-scroller"
     * 
     * A scroller with a background-image applied will by default be 
     * parallax scrolled. The image should be larger in height than the 
     * containing DIV, or it won't scroll
     * 
     * Other widgets that affect the window scroll must update the
     * scrollers
     * 
     *                         Winscroller
     * 
     * A clickable element with the class of "qnr-winscroller" is a
     * widget that on click will scroll the window to a target object
     * 
     * The target ID is set on the widget with the "data-qnr-target" 
     * attribute; default target is the BODY object. A pixel scrolling 
     * offset may also be set with "data-qnr-offset" and may be negative
     * 
     * If the widget is an A tag, the preventDefault() method of the 
     * event object will be used for the scroll to work
     * 
     * If the widget is BODY, a SPAN object will be created and placed
     * as the first element in the BODY, with the class of 
     * "qnr-winscroller-arrow", appearing as an arrow fixed to the right
     * of the screen, that the user can click/tap to return to the top  
     * of the page. The "quicknr-interface.woff" font is used for the 
     * arrow glyph
     * 
     * Further, "data-qnr-winscroll-fraction" can be set on the BODY 
     * tag, a float controlling the proportion of the window that must 
     * be scrolled down before the arrow will be displayed; default 
     * value is "0.25"
     * 
     * The scrolling is limited to 1 second before it cancels
     * 
     * 
     *                         X-icons
     * 
     * X-icons are widgets with the class of "qnr-x-icon". The code will
     * place a closing "x" on the widget that the user can click
     * 
     * X-icons with additional class of "qnr-remove", will on click
     * set display of the widget to "none" instead of merely hiding it
     * 
     * The X-icon widget element must be CSS positioned
     * 
     * X-icons cannot be used on other widgets (accordions), they are
     * meant for the closing of static elements like image boxes and
     * notifications
     * 
     * 
     *                         Accordions
     * 
     * Accordions are widgets with the class of "qnr-accordion"
     * 
     * Accordions with additional class of "qnr-multi" will show more
     * than one item at a time if more than one is clicked
     * 
     * Accordion items consist of <P> questions and <DIV> answers. They
     * don't require any classes, and the DIVs may contain <P> and 
     * <DIV> tags, as well as others. Other tags such as headings 
     * may be placed between question-answer groups, but the question 
     * <P> and answer <DIV> must be next to each other
     * 
     * 
     *                         Aspect Keeper
     * 
     * An element with the class of "qnr-aspect-keeper" will keep its
     * aspect ratio, 1.5 by default, and settable with the dataset
     * attribute "data-qnr-aspect-ratio". The aspect ratio is preserved 
     * by using the width as the base of calculation for the height. 
     * Computed CSS values for min and max height are respected
     * 
     * 
     *                         Layout Helpers
     * 
     * The "qnr-interface.css" file contains a section of layout helper
     * style rules, with 2 items: ".center" and ".center-bottom". These
     * are not widgets but can be applied to block elements
     * 
     * -------------------------------------------------------------- */ 


    // ===================== ANIMATION =====================

    function animCallback(obj, intervalObj, args) {
        
        /* ----------------------- INFO ---------------------------------
         * 
         * The "obj" argument is the object to animate:
         * 
         *  * window scroll:
         *      - the object to animatedly scroll to
         * 
         *  * accordion hide/show:
         *      - the generated SPAN wrapper, containing the answer DIV
         * 
         * the "intervalObj" is the object returned by setInterval
         * 
         * The "args" argument is a list, its first item being one of:
         * 
         *  - "window scroll"
         *  - "accordion hide"
         *  - "accordion show"
         * 
         * Subsequent "args" items are:
         * 
         * * window scroll:
         *      - position offset number due to header height,
         *          usually negative
         * 
         * * accordion hide/show:
         *      - none
         * 
         * Call the animCallback() like so (last param is milliseconds):
         * 
         *  var intervalObj = null;
         *  window.setTimeout(function()
         *      {window.clearInterval(intervalObj);}, 1000);
         *  intervalObj = window.setInterval(function() 
         *      {animCallback(obj, intervalObj, [mode]);}, 8);
         * 
         * -------------------------------------------------------------- */ 
        
        
        // ----------------------- Accordion hide
        
        if (args[0] == "accordion hide") {
            var divObj = obj.firstElementChild;
            var objHeight = parseInt(obj.dataset.qnrAccordionItemHeight);
            obj.style.opacity = "0";
            divObj.style.opacity = "0";
            if (obj.offsetHeight > 0) {
                // Make the number of steps proportional to height
                var divisor = Math.min(30, 30 * (objHeight/300));
                obj.style.height = Math.max(0,obj.offsetHeight - (objHeight/divisor)) + "px";
                divObj.style.top = Math.max((-1 * objHeight),divObj.offsetTop - (objHeight/divisor)) + "px";
            }
            else {
                window.clearInterval(intervalObj);
                return;
            }
        }
        
        // ----------------------- Accordion show
        
        if (args[0] == "accordion show") {
            var divObj = obj.firstElementChild;
            var objHeight = parseInt(obj.dataset.qnrAccordionItemHeight);
            obj.style.opacity = "1";
            divObj.style.opacity = "1";
            if (obj.offsetHeight < objHeight) {
                // Make the number of steps proportional to height
                var divisor = Math.min(30, 30 * (objHeight/300));
                obj.style.height = Math.min(objHeight,obj.offsetHeight + (objHeight/divisor)) + "px";
                divObj.style.top = Math.min(0,divObj.offsetTop + (objHeight/divisor)) + "px";
            }
            else {
                window.clearInterval(intervalObj);
                return;
            }
        }
        
        // ----------------------- Window scroll
        
        if (args[0] == "window scroll") {
            var offsetPos = args[1];
            var pos = window.pageYOffset;
            if ((pos > getYPos(obj, offsetPos)) && (pos - getYPos(obj, offsetPos) > 2)) {
                // Let the scroll last 8 steps
                window.scrollTo(0, pos - (pos - getYPos(obj, offsetPos))/7);
            }
            else if ((pos < getYPos(obj, offsetPos)) && (getYPos(obj, offsetPos) - pos > 2)) {
                window.scrollTo(0, pos + (getYPos(obj, offsetPos) - pos)/7);
            }
            else {
                window.clearInterval(intervalObj);
                return;
            }
        }
    }

    // ===================== STARTUP ===================== 
    
    // ----------------------- Create a list of widgets
    QNR_INTER.accordionsL = null; // List of accordion elements in doc
    QNR_INTER.accordionObjectsL = []; // List of JS accordion objects
    
    QNR_INTER.xiconsL = null;
    QNR_INTER.xiconObjectsL = [];
    
    QNR_INTER.scrollersL = null;
    QNR_INTER.scrollerObjectsL = [];
    
    // Only one menu object, no list
    QNR_INTER.navmenuObject = null;
    
    QNR_INTER.stickybarObject = null;

    QNR_INTER.imageanimsL = null;
    QNR_INTER.imageanimObjectsL = [];
    
    QNR_INTER.slidersL = null;
    QNR_INTER.sliderObjectsL = [];
    
    QNR_INTER.carouselsL = null;
    QNR_INTER.carouselObjectsL = [];
    
    QNR_INTER.winscrollersL = null;
    QNR_INTER.winscrollerObjectsL = [];
    
    QNR_INTER.responsivesL = null;
    QNR_INTER.responsiveObjectsL = [];
    
    QNR_INTER.fontresizesL = null;
    QNR_INTER.fontresizeObjectsL = [];
    
    QNR_INTER.arrowanimsL = null;
    QNR_INTER.arrowanimObjectsL = [];
    
    QNR_INTER.aspectkeepersL = null;
    QNR_INTER.aspectkeeperObjectsL = [];
    
    
    // ----------------------- ASPECT KEEPER
    
    function AspectkeeperObject() {
        this.object                 = null;
        this.aspectRatio            = 1.5;
    }
    AspectkeeperObject.prototype.initialize = function() {
        if (this.object.dataset.qnrAspectRatio) this.aspectRatio = this.object.dataset.qnrAspectRatio;
        this.setHeight();
    }
    AspectkeeperObject.prototype.setHeight = function() {
        // Must work on load and resize...
        // Limit the height by min and max CSS
        var minH = (window.getComputedStyle(this.object, "").minHeight == "0px") ? 0 : 
                        parseFloat(window.getComputedStyle(this.object, "").minHeight.replace("px",""));
        var maxH = (window.getComputedStyle(this.object, "").maxHeight == "none") ? 9999 : 
                        parseFloat(window.getComputedStyle(this.object, "").maxHeight.replace("px",""));
        newHeight = this.object.offsetWidth * (1.0/this.aspectRatio);
        if (newHeight > minH && newHeight < maxH) this.object.style.height = newHeight + "px";
    }
    
    
    // ----------------------- ARROW ANIM
    
    function ArrowanimObject() {
        this.object                 = null;
        this.arrowSpan              = null;
        this.direction              = "down";
        this.stopCarousel           = null;
    }
    ArrowanimObject.prototype.initialize = function() {
        // Stop a carousel?
        if (this.object.dataset.qnrStopCarousel) this.stopCarousel = this.object.dataset.qnrStopCarousel;
        // Get the direction of the arrow
        if (this.object.dataset.qnrArrowDir) this.direction = this.object.dataset.qnrArrowDir;
        this.arrowSpan = document.createElement("span");
        if (this.direction == "down") this.arrowSpan.className = "qnr-glyph qnr-glyph-arrowthin-down qnr-arrow-anim-down";
        else if (this.direction == "up") this.arrowSpan.className = "qnr-glyph qnr-glyph-arrowthin-up qnr-arrow-anim-up";
        else if (this.direction == "left") this.arrowSpan.className = "qnr-glyph qnr-glyph-arrowthin-left qnr-arrow-anim-left";
        else if (this.direction == "right") this.arrowSpan.className = "qnr-glyph qnr-glyph-arrowthin-right qnr-arrow-anim-right";
        this.object.appendChild(this.arrowSpan);
        // Set onclick handler
        var that = this;
        this.object.addEventListener("click",function(){
            that.arrowSpan.classList.remove("qnr-arrow-anim-up");
            that.arrowSpan.classList.remove("qnr-arrow-anim-down");
            that.arrowSpan.classList.remove("qnr-arrow-anim-left");
            that.arrowSpan.classList.remove("qnr-arrow-anim-right");
            that.arrowSpan.classList.add("qnr-arrow-anim-still");
            if (that.stopCarousel) {
                carouselID(that.stopCarousel).pauseCarousel();
            }
        },true);
    }
    
    
    // ----------------------- FONT RESIZE
    
    function FontresizeObject() {
        this.object                 = null;
        this.origSize               = 0; // Original size
        this.min                    = 80.0;
        this.rangeF                 = 20.0; // Inverse of min
        // The difference between min and max area that is considered
        this.rangeA                 = 1106400;
    }
    FontresizeObject.prototype.initialize = function() {
        if (this.object.dataset.qnrFontMin) this.min = parseFloat(this.object.dataset.qnrFontMin);
        this.origSize = parseFloat(window.getComputedStyle(this.object, "").fontSize.replace("px",""));
        this.rangeF = 100.0 - this.min;
        this.resize();
    }
    FontresizeObject.prototype.resize = function() {
        // Resize according to display area
        var area = objHtml().clientWidth * objHtml().clientHeight;
        if (102400 <= area && area <= 1260000) {
            var delta = 1260000 - area;
            var factor = delta/this.rangeA;
            this.object.style.fontSize = (((100.0 - (this.rangeF * factor))/100.0) * this.origSize) + "px";
        }
        else if (area > 1260000) this.object.style.fontSize = this.origSize + "px";
    }
    
    
    // ----------------------- RESPONSIVE
    
    function ResponsiveObject() {
        this.object                 = null;
        this.divItems               = [];
    }
    ResponsiveObject.prototype.initialize = function() {
        var objChildren = this.object.children;
        for (var i = 0; i < objChildren.length; i++) {
            objChildren[i].classList.add("qnr-responsive-item");
            this.divItems.push(objChildren[i]);
        }
    }
    
    
    // ----------------------- WINSCROLLER
    
    function WinscrollerObject() {
        this.object                 = null;
        this.targetObj              = null; // Target object to scroll to
        this.offset                 = 0; // Offset the scroll by this many pixels
        // Page fraction to be scrolled down before up arrow will appear,
        //   if winscroller is the BODY (as SPAN element created below)
        this.winscrollFraction      = 0.25;
    }
    WinscrollerObject.prototype.initialize = function() {
        // Set up winscroller, either BODY or an element within
        this.targetObj = this.object.dataset.qnrTarget ? objID(this.object.dataset.qnrTarget) : objTag("body");
        if (this.object.dataset.qnrOffset) this.offset = parseInt(this.object.dataset.qnrOffset);
        if (this.object.dataset.qnrWinscrollFraction) this.winscrollFraction = parseFloat(this.object.dataset.qnrWinscrollFraction);
        if (this.object.tagName == "BODY") {
            // Convert BODY winscroller to SPAN, so it can be styled as arrow
            this.object = document.createElement("span");
            objTag("body").insertBefore(this.object, objTag("body").firstChild);
            this.object.classList.add("qnr-winscroller-arrow");
            // Add the fraction to the arrow so it can be read onscroll for hide/show
            this.object.dataset.qnrWinscrollFraction = this.winscrollFraction;
            this.object.style.visibility = "hidden";
        }
        // Set onclick handler
        var that = this;
        this.object.addEventListener("click",function(event){
            var intervalObj = null;
            var obj = that.targetObj;
            var off = that.offset;
            window.setTimeout(function() {window.clearInterval(intervalObj);}, 1000);
            intervalObj = window.setInterval(function() {animCallback(obj, intervalObj, ["window scroll",off]);}, 16);
            if (that.object.tagName == "A") event.preventDefault();
        },true);
    }
    
    
    // ----------------------- CAROUSEL
    
    function CarouselObject() {
        this.object                 = null;
        this.carouselItemsL         = [];
        this.carouselItemsLIndex    = 0;
        this.itemUrlsL              = [];
        this.captionItemsL          = [];
        this.navArrows              = "on";
        this.navStrip               = "on";
        this.navPreviews            = "on"; // Note thumbPreviews also
        this.cStripDiv              = null;
        this.captions               = "off";
        this.captionDiv             = null;
        this.arrowLeft              = null;
        this.arrowRight             = null;
        this.arrowType              = "regular";
        //this.thumbBorderColor       = "#CCC";
        //this.thumbBorderColorActive = "#DDD";
        //this.thumbBGColor           = "transparent";
        //this.thumbBGColorActive     = "#EEE";
        this.thumbPreviews          = false; // Relevant if IMG items
        this.carouselTimer          = null;
        this.carouselStartInterval  = 4.0; // Seconds
        this.carouselInterval       = 3.0;
        this.transitionTime         = 1.0;
        this.transitionMode         = "fade"; // Or "slideover", "slidefade", "slideboth"
        this.slideShowTime          = 0; // Milliseconds
        this.hardStop               = false; // Auto play stopped
        // Dataset preference, set it to "off" for hard stop on manual slide showing
        this.resumeAuto             = "on";
        // Offset for pausing on scroll, set to negative with dataset attribute for fixed navbar
        this.scrollOffset           = 0;
        // Preference for setting slide numbers in thumb circles
        this.thumbNumbers           = "off";
        // Set item DIVs as Parallax Scrollers with "qnr-scroller" class
        this.itemScroller           = "off";
    }
    CarouselObject.prototype.initialize = function() {
        if (!this.object.hasChildNodes() || this.object.children.length < 2) {
            print("Error: Carousel requires at least 2 DIV or IMG items.");
            return;
        }
        
        // Get user preferences on navigation elements, scroll offset, auto resuming, etc.
        if (this.object.dataset.qnrArrows) this.navArrows = this.object.dataset.qnrArrows;
        if (this.object.dataset.qnrStrip) this.navStrip = this.object.dataset.qnrStrip;
        if (this.object.dataset.qnrPreviews) this.navPreviews = this.object.dataset.qnrPreviews;
        if (this.object.dataset.qnrResumeAuto) this.resumeAuto = this.object.dataset.qnrResumeAuto;
        if (this.object.dataset.qnrScrollOffset) this.scrollOffset = parseInt(this.object.dataset.qnrScrollOffset);
        if (this.object.dataset.qnrMode) this.transitionMode = this.object.dataset.qnrMode;
        if (this.object.dataset.qnrTransition) this.transitionTime = parseFloat(this.object.dataset.qnrTransition);
        if (this.object.dataset.qnrStartInterval) this.carouselStartInterval = parseFloat(this.object.dataset.qnrStartInterval);
        if (this.object.dataset.qnrInterval) this.carouselInterval = parseFloat(this.object.dataset.qnrInterval);
        if (this.object.dataset.qnrCaptions) this.captions = this.object.dataset.qnrCaptions;
        if (this.object.dataset.qnrThumbNumbers) this.thumbNumbers = this.object.dataset.qnrThumbNumbers;
        if (this.object.dataset.qnrScroller) this.itemScroller = this.object.dataset.qnrScroller;
        // Set thumb preview property if preference "on" and device not mobile
        if (!deviceIsMobile() && this.navPreviews == "on") this.thumbPreviews = true;
        
        // ----------------------- List carousel items
        var objChildren = this.object.children;
        for (var i = 0; i < objChildren.length; i++) {
            if (objChildren[i].tagName == "IMG") {
                // Convert IMG to DIV, with background-image
                var newDiv = document.createElement("div");
                newDiv.style.backgroundImage = "url('"+objChildren[i].src+"')";
                this.carouselItemsL.push(newDiv);
                this.itemUrlsL.push(objChildren[i].src);
                this.captionItemsL.push(objChildren[i].alt);
                this.object.replaceChild(newDiv, objChildren[i]);
            }
            else if (objChildren[i].tagName == "DIV") {
                this.carouselItemsL.push(objChildren[i]);
                this.itemUrlsL.push(objChildren[i].style.backgroundImage.slice(5,objChildren[i].style.backgroundImage.length - 2));
            }
            // Assign class
            this.carouselItemsL[i].classList.add("qnr-carousel-item");
            // Assign Scroller class if set so
            if (this.itemScroller == "on") this.carouselItemsL[i].classList.add("qnr-scroller");
            // Assign dataset ID
            this.carouselItemsL[i].dataset.qnrCarouselItemId = i;
            // Display the slides
            this.carouselItemsL[i].style.display = "block";
        }
        // ----------------------- Create arrows
        if (this.navArrows == "on") {
            this.arrowLeft = document.createElement("span");
            this.arrowRight = document.createElement("span");
            // Set the type of arrow
            if (this.object.dataset.qnrArrowType) this.arrowType = this.object.dataset.qnrArrowType;
            if (this.arrowType == "big") {
                this.arrowLeft.className = "qnr-carousel-bigarrow-left";
                this.arrowRight.className = "qnr-carousel-bigarrow-right";
            }
            else if (this.arrowType == "sticky") {
                this.arrowLeft.className = "qnr-carousel-stickyarrow-left";
                this.arrowRight.className = "qnr-carousel-stickyarrow-right";
            }
            else if (this.arrowType == "regular") {
                this.arrowLeft.className = "qnr-carousel-arrow-left";
                this.arrowRight.className = "qnr-carousel-arrow-right";
            }
            var that = this;
            // Add click event handlers for carousel navigation
            this.arrowLeft.addEventListener("click",function(){
                that.showNextPrevImgSlide('prev',false);
            },true);
            this.arrowRight.addEventListener("click",function(){
                that.showNextPrevImgSlide('next',false);
            },true);
            // Place arrows in carousel
            this.object.appendChild(this.arrowLeft);
            this.object.appendChild(this.arrowRight);
        }
        
        // ----------------------- Create control strip
        if (this.navStrip == "on") {
            this.cStripDiv = document.createElement("div");
            this.cStripDiv.className = "qnr-carousel-controlstrip";
            var cStrip = "";
            var slideNum = "";
            // SPANs for all the img thumbs or just circles
            for (i = 0; i < this.carouselItemsL.length; i++) {
                if (this.thumbNumbers == "on") slideNum = "" + (i+1);
                if (this.thumbPreviews) {
                    cStrip += '<span onmouseout="this.style.backgroundImage=null;" onmouseover="this.style.backgroundImage=\'url('+
                            this.itemUrlsL[i]+')\';" class="qnr-carousel-thumb qnr-carousel-thumb-preview" data-qnr-carousel-thumb-id="'+i+'">'+slideNum+'</span>';
                }
                else {
                    cStrip += '<span class="qnr-carousel-thumb" data-qnr-carousel-thumb-id="'+i+'">'+slideNum+'</span>';
                }
            }
            this.cStripDiv.innerHTML = cStrip;
            // Place control strip in carousel
            this.object.appendChild(this.cStripDiv);
            // Add inactive class and click event handlers to thumbs
            var thumbs = classObjs("qnr-carousel-thumb", this.object);
            for (var i = 0; i < thumbs.length; i++) {
                if (this.thumbNumbers == "on") thumbs[i].classList.add("qnr-carousel-thumb-number-inactive");
                else thumbs[i].classList.add("qnr-carousel-thumb-inactive");
                that = this;
                thumbs[i].addEventListener("click",function(event){
                    that.showSlide(parseInt(event.target.dataset.qnrCarouselThumbId),false,
                                        that.getDirection(parseInt(event.target.dataset.qnrCarouselThumbId)));
                },true);
            }
        }
        
        // ----------------------- Create captions div
        if (this.captions == "on") {
            this.captionDiv = document.createElement("div");
            this.captionDiv.className = "qnr-carousel-caption";
            if (this.object.nextSibling) {
                this.object.parentNode.insertBefore(this.captionDiv,this.object.nextSibling);
            }
            else {
                this.object.parentNode.appendChild(this.captionDiv);
            }
        }
        
        // Set first div to top z-index
        this.carouselItemsL[0].style.zIndex = 2;
        if (this.captions == "on") this.captionDiv.innerHTML = this.captionItemsL[0];
        
        // Set border/bg color of first thumb to active, the rest remains inactive
        if (this.navStrip == "on") {
            if (this.thumbNumbers == "on") {
                objTag("span", this.cStripDiv).classList.remove("qnr-carousel-thumb-number-inactive");
                objTag("span", this.cStripDiv).classList.add("qnr-carousel-thumb-number-active");
            }
            else {
                objTag("span", this.cStripDiv).classList.remove("qnr-carousel-thumb-inactive");
                objTag("span", this.cStripDiv).classList.add("qnr-carousel-thumb-active");
            }
            //objTag("span", this.cStripDiv).style.borderColor = this.thumbBorderColorActive;
            //objTag("span", this.cStripDiv).style.backgroundColor = this.thumbBGColorActive;
        }
        
        this.styleCarousel();
        
        this.startCarouselTimer();
        
        // Start async loading of the rest of the images
        async(loadImagesIntoMemory, this.itemUrlsL.slice(1));
    }
    CarouselObject.prototype.styleCarousel = function() {
        // This must work on load and resize...
        // Hide the control strip if it would be higher than one row
        if (this.navStrip == "on") {
            if (this.cStripDiv.offsetHeight > 50) {
                print(this.cStripDiv.offsetHeight);
                this.cStripDiv.style.display = "none";
            }
            else this.cStripDiv.style.display = "block";
        }
    }
    CarouselObject.prototype.onScrollCarousel = function() {
        if (window.pageYOffset >= this.object.offsetHeight + getYPos(this.object) - this.scrollOffset && this.carouselTimer) {
            this.pauseCarousel();
        }
        else if (!this.hardStop && this.resumeAuto == "on") {
            if (window.pageYOffset < this.object.offsetHeight + getYPos(this.object) && !this.carouselTimer) {
                this.resumeCarousel();
            }
        }
    }
    CarouselObject.prototype.pauseCarousel = function() {
        clearTimeout(this.carouselTimer);
        this.carouselTimer = null;
    }
    CarouselObject.prototype.resumeCarousel = function() {
        this.startCarouselTimer();
    }
    CarouselObject.prototype.stopCarousel = function() {
        this.pauseCarousel();
        this.hardStop = true;
    }
    CarouselObject.prototype.startCarouselTimer = function() {
        this.hardStop = false; // Just to be sure
        var that = this;
        this.carouselTimer = setTimeout(function() {that.showNextPrevImgSlide("next",true);}, that.carouselStartInterval*1000);
    }
    CarouselObject.prototype.showSlide = function(newSlideIndex, autoSliding, slideDirection) {
        // newSlideIndex = index number of new component div
        // autoSliding = bool for auto sliding
        // slideDirection = "prev" or "next", affects direction of transition
        var theDate = new Date();
        // Transition time dependent on action time
        var transitionT = this.transitionTime;
        if (theDate.getTime() - this.slideShowTime < this.transitionTime*1000) {
            transitionT = (theDate.getTime() - this.slideShowTime)/1000;
        }
        clearTimeout(this.carouselTimer);
        this.carouselTimer = null;
        // Act only if not trying to show the shown div
        if (this.carouselItemsL[newSlideIndex].style.zIndex != 2) {
            // ----------------------- Toggle component div on display
            for (i = 0; i < this.carouselItemsL.length; i++) {
                // Set animation duration
                if (this.carouselItemsL[i].style.animationDuration !== undefined) {
                    this.carouselItemsL[i].style.animationDuration = transitionT+"s";
                }
                else {
                    this.carouselItemsL[i].style.webkitAnimationDuration = transitionT+"s";
                }
                // The newSlideIndex div moved to 2 (top)
                // The div that was 2 (top) moved to 1 (bottom)
                // The bottom div on 1 moved down to 0
                if (i != newSlideIndex) {
                    if (this.carouselItemsL[i].style.zIndex != 2) {
                        // Not the previous top slide
                        this.carouselItemsL[i].style.zIndex = 0;
                        this.carouselItemsL[i].classList.remove("qnr-carousel-slidefade-rtl");
                        this.carouselItemsL[i].classList.remove("qnr-carousel-slidefade-ltr");
                        this.carouselItemsL[i].classList.remove("qnr-carousel-slidein-rtl");
                        this.carouselItemsL[i].classList.remove("qnr-carousel-slidein-ltr");
                        this.carouselItemsL[i].classList.remove("qnr-carousel-slideout-rtl");
                        this.carouselItemsL[i].classList.remove("qnr-carousel-slideout-ltr");
                        this.carouselItemsL[i].classList.remove("qnr-carousel-fadein");
                    }
                    else {
                        // Former 2 (top), move to 1 (bottom)
                        this.carouselItemsL[i].style.zIndex = 1;
                        if (this.transitionMode == "fade") {
                            this.carouselItemsL[i].classList.remove("qnr-carousel-fadein");
                        }
                        else {
                            this.carouselItemsL[i].classList.remove("qnr-carousel-slidefade-rtl");
                            this.carouselItemsL[i].classList.remove("qnr-carousel-slidefade-ltr");
                            this.carouselItemsL[i].classList.remove("qnr-carousel-slidein-rtl");
                            this.carouselItemsL[i].classList.remove("qnr-carousel-slidein-ltr");
                            if (this.transitionMode == "slideboth") {
                                if (slideDirection == "prev") {
                                    this.carouselItemsL[i].classList.add("qnr-carousel-slideout-ltr");
                                }
                                else {
                                    this.carouselItemsL[i].classList.add("qnr-carousel-slideout-rtl");
                                }
                            }
                        }
                    }
                }
                else { // New slide, move to 2 (top)
                    this.carouselItemsL[i].style.zIndex = 2;
                    if (this.transitionMode == "fade") {
                        this.carouselItemsL[i].classList.add("qnr-carousel-fadein");
                    }
                    else {
                        if (this.transitionMode == "slidefade") {
                            if (slideDirection == "prev") {
                                this.carouselItemsL[i].classList.add("qnr-carousel-slidefade-ltr");
                            }
                            else {
                                this.carouselItemsL[i].classList.add("qnr-carousel-slidefade-rtl");
                            }
                        }
                        else {
                            if (this.transitionMode == "slideboth") {
                                this.carouselItemsL[i].classList.remove("qnr-carousel-slideout-rtl");
                                this.carouselItemsL[i].classList.remove("qnr-carousel-slideout-ltr");
                            }
                            if (slideDirection == "prev") {
                                this.carouselItemsL[i].classList.add("qnr-carousel-slidein-ltr");
                            }
                            else {
                                this.carouselItemsL[i].classList.add("qnr-carousel-slidein-rtl");
                            }
                        }
                    }
                }
            }
            
            // ----------------------- Set strip thumbs border colors
            if (this.navStrip == "on") {
                var stripThumbs = classObjs("qnr-carousel-thumb", this.object);
                for (i = 0; i < stripThumbs.length; i++) {
                    if (i != newSlideIndex) {
                        if (this.thumbNumbers == "on") {
                            stripThumbs[i].classList.remove("qnr-carousel-thumb-number-active");
                            stripThumbs[i].classList.add("qnr-carousel-thumb-number-inactive");
                        }
                        else {
                            stripThumbs[i].classList.remove("qnr-carousel-thumb-active");
                            stripThumbs[i].classList.add("qnr-carousel-thumb-inactive");
                        }
                        //stripThumbs[i].style.borderColor = this.thumbBorderColor;
                        //stripThumbs[i].style.backgroundColor = this.thumbBGColor;
                    }
                    else { // Thumb of slide being shown
                        if (this.thumbNumbers == "on") {
                            stripThumbs[i].classList.remove("qnr-carousel-thumb-number-inactive");
                            stripThumbs[i].classList.add("qnr-carousel-thumb-number-active");
                        }
                        else {
                            stripThumbs[i].classList.remove("qnr-carousel-thumb-inactive");
                            stripThumbs[i].classList.add("qnr-carousel-thumb-active");
                        }
                        //stripThumbs[i].style.borderColor = this.thumbBorderColorActive;
                        //stripThumbs[i].style.backgroundColor = this.thumbBGColorActive;
                    }
                }
            }
            
            // ----------------------- Set caption
            if (this.captions == "on") {
                this.captionDiv.innerHTML = this.captionItemsL[newSlideIndex];
            }
            // Adjust index property (calls don't)
            this.carouselItemsLIndex = newSlideIndex;
            // Record the time (must be here)
            this.slideShowTime = theDate.getTime();
        }
        // Continue animation, if not hard stop
        if (!this.hardStop) {
            // Not auto, but clicked or key pressed; double the start interval to show slide
            if (!autoSliding && this.resumeAuto == "on") {
                var that = this;
                this.carouselTimer = setTimeout(function() {that.showNextPrevImgSlide("next",true);}, that.carouselStartInterval*1000*2);
            }
            else if (autoSliding) { // Auto
                var that = this;
                this.carouselTimer = setTimeout(function() {that.showNextPrevImgSlide("next",true);}, that.carouselInterval*1000);
            }
        }
    }
    CarouselObject.prototype.getDirection = function(newSlideIndex) {
        // Called on thumb click
        if (this.carouselItemsLIndex > newSlideIndex) return "prev";
        else return "next";
    }
    CarouselObject.prototype.showNextPrevImgSlide = function(dir, auto) {
        // dir = "prev"/"next" string
        // auto = bool for auto sliding
        // ----------------------- Calculate image index to go to
        var callIndex; // Temp index, in case animation fails due to too-fast interaction
        if (dir == "next") {
            callIndex = this.carouselItemsLIndex + 1;
            if (callIndex == this.carouselItemsL.length) {
                callIndex = 0;
            }
        }
        else if (dir == "prev") {
            callIndex = this.carouselItemsLIndex - 1;
            if (callIndex < 0) {
                callIndex = this.carouselItemsL.length-1;
            }
        }
        // Call showing method
        this.showSlide(callIndex, auto, dir);
    }
    CarouselObject.prototype.nextPrevArrowKey = function(event){ // Keyboard navigation
        var keyCode = ('which' in event) ? event.which : event.keyCode;
        if (keyCode == 39) {
            this.showNextPrevImgSlide("next",false);
        }
        else if (keyCode == 37) {
            this.showNextPrevImgSlide("prev",false);
        }
    }
    
    
    // ----------------------- STICKYBAR
    
    function StickybarObject() {
        this.object = null;
        this.newObject = null;
        this.madesticky = false;
        this.objYPos = 0;
    }
    StickybarObject.prototype.initialize = function() {
        this.objYPos = getYPos(this.object, 0);
        // Clone object, without child nodes, as placeholder
        this.newObject = this.object.cloneNode(false);
        // Size the placeholder height the same as actual widget height
        // Used independently on window resize
        this.sizePlaceholder();
        // Update UI
        this.manageSticky();
    }
    StickybarObject.prototype.sizePlaceholder = function() {
        // Width is assumed to be expressed as 100%
        this.newObject.style.height = this.object.offsetHeight + "px";
        // Update scrollers, accounting for change of scroll
        // This is the only solution that works
        if (QNR_INTER.scrollerObjectsL) {
            for (var i = 0; i < QNR_INTER.scrollerObjectsL.length; i++) {
                QNR_INTER.scrollerObjectsL[i].parallaxScroll();
            }
        }
    }
    StickybarObject.prototype.manageSticky = function() {
        if (!this.madesticky && window.pageYOffset >= this.objYPos) {
            // Place new object before original
            this.newObject = this.object.parentNode.insertBefore(this.newObject,this.object);
            // Display original fixed
            this.object.classList.add("qnr-stickybar-fixed");
            this.madesticky = true;
        }
        else if (this.madesticky && window.pageYOffset < this.objYPos) {
            // Remove newObject and remove fixed styling
            this.newObject.parentNode.removeChild(this.newObject);
            this.object.classList.remove("qnr-stickybar-fixed");
            this.madesticky = false;
        }
    }
    
    
    // ----------------------- SLIDER
    
    function SliderObject() {
        this.object = null;
        // List divs that will slide
        this.slideDivsL = [];
        // Sliding duration, overriden by data-qnr-slide-duration widget attribute
        this.slideDuration = 4;
        // Sliding direction, overriden by data-qnr-slide-direction
        this.slideDirection = "qnr-slide-rtl";
        // Stopping would stop each slide on screen, no off movement
        this.stopping = false;
        // Slide on load, when object initilized?
        this.autoSlide = "on";
    }
    SliderObject.prototype.initialize = function() {
        // Get sliding divs
        this.slideDivsL = [];
        var childObjs = this.object.children;
        for (var i = 0; i < childObjs.length; i++) {
            if (childObjs[i].tagName == "DIV") {
                this.slideDivsL.push(childObjs[i]);
            }
        }
        if (!this.slideDivsL) {
            print("Error: At least one DIV is required for slider widget.");
            return;
        }
        // Get auto slide preference
        if (this.object.dataset.qnrAutoSlide) {
            this.autoSlide = this.object.dataset.qnrAutoSlide;
        }
        // Get animation duration from widget dataset
        if (this.object.dataset.qnrSlideDuration) {
            this.slideDuration = parseFloat(this.object.dataset.qnrSlideDuration);
        }
        // Get direction, and stopping boolean from it
        if (this.object.dataset.qnrSlideDirection) {
            this.slideDirection = "qnr-slide-" + this.object.dataset.qnrSlideDirection;
            if (this.object.dataset.qnrSlideDirection.split("-").pop() == "stop") {
                this.stopping = true;
            }
        }
        // CSS style slide items
        for (var i = 0; i < this.slideDivsL.length; i++) {
            this.slideDivsL[i].classList.add("qnr-slide-item");
            // Set anim duration on the slides
            if (this.slideDivsL[i].style.animationDuration !== undefined) {
                this.slideDivsL[i].style.animationDuration = this.slideDuration + "s";
            }
            else {
                this.slideDivsL[i].style.webkitAnimationDuration = this.slideDuration + "s";
            }
        }
        if (this.autoSlide == "on") this.animate();
    }
    SliderObject.prototype.animate = function() {
        var maxC = this.slideDivsL.length - 1;
        // Calculate anim delay from number of items and anim duration
        var aDelay = (this.slideDuration/4)*3;
        for (var i = this.slideDivsL.length - 1; i >= 0; i--) {
            var itemDelay = (Math.abs(i-maxC)*aDelay);
            if (this.slideDivsL[i].style.animationDelay !== undefined) {
                this.slideDivsL[i].style.animationDelay = itemDelay + "s";
            }
            else {
                this.slideDivsL[i].style.webkitAnimationDelay = itemDelay + "s";
            }
            // Set up slide 0 (last slide, top DIV in HTML) to stop after half the anim time
            // But if marked with a "-stop" variety, animate fully
            if (i === 0 && !this.stopping) {
                var that = this;
                if (this.slideDivsL[i].style.animationPlayState !== undefined) {
                    this.slideDivsL[i].addEventListener("animationstart", function(event){
                        window.setTimeout(function(){
                            event.target.style.animationPlayState = "paused";
                        },(that.slideDuration/2)*1000);
                    });
                }
                else { // Webkit prefix
                    this.slideDivsL[i].addEventListener("webkitAnimationStart", function(event){
                        window.setTimeout(function(){
                            event.target.style.webkitAnimationPlayState = "paused";
                        },(that.slideDuration/2)*1000);
                    });
                }
            }
            // Apply animating CSS rule
            this.slideDivsL[i].classList.add(this.slideDirection);
            this.slideDivsL[i].classList.remove("qnr-slide-none");
        }
    }
    
    
    // ----------------------- IMAGE ANIMATOR
    
    function ImageanimObject() {
        this.object = null;
        this.objHeight = 0;
        this.objWidth = 0;
        // List of divs that will replace images
        this.imgDivsL = [];
        // List of img classes defining CSS animating selectors
        this.imgClassesL = [];
        // Animation duration, overriden by data-qnr-anim-duration attribute
        this.animDuration = 4;
    }
    ImageanimObject.prototype.initialize = function() {
        // Convert IMG tags to DIVs, with image background
        var imgTagsL = tagObjs("img", this.object);
        if (!imgTagsL || imgTagsL.length < 2) {
            print("Error: At least two IMG tags required for image animator widget.");
            return;
        }
        // Get animation duration from widget dataset
        if (this.object.dataset.qnrAnimDuration) {
            this.animDuration = parseInt(this.object.dataset.qnrAnimDuration);
        }
        // Set widget object dimensions, must be here
        this.objHeight = this.object.offsetHeight;
        this.objWidth = this.object.offsetWidth;
        // Create imgDivs
        for (var i = 0; i < imgTagsL.length; i++) {
            // Get img classes defining animation to use on the image
            this.imgClassesL.push(imgTagsL[i].className);
            var imgDiv = document.createElement("div");
            // Style the imgDiv in CSS stylesheet
            // CSS stylesheet takes care of upsizing of bg img
            imgDiv.className = "qnr-img-anim-div";
            imgDiv.style.backgroundImage = "url('"+imgTagsL[i].src+"')";
            if (imgDiv.style.animationDuration !== undefined) {
                imgDiv.style.animationDuration = this.animDuration + "s";
            }
            else {
                imgDiv.style.webkitAnimationDuration = this.animDuration + "s";
            }
            // List imgDiv
            this.imgDivsL.push(imgDiv);
            // Place imgDiv in widget
            this.object.insertBefore(imgDiv,imgTagsL[0]);
        }
        // Get rid of IMGs, must be in reverse for loop
        for (var i = imgTagsL.length - 1; i >= 0; i--) {
            imgTagsL[i].parentNode.removeChild(imgTagsL[i]);
        }
        this.animate();
    }
    ImageanimObject.prototype.animate = function() {
        // Calculate anim delay from anim duration
        var aDelay = (this.animDuration/3)*2;
        for (var i = this.imgDivsL.length - 1; i >= 0; i--) {
            // Set animation CSS rule from img classes
            this.imgDivsL[i].className = this.imgDivsL[i].className + " " + this.imgClassesL[i];
            if (i != this.imgDivsL.length - 1) { // Delay all but the top layer
                if (this.imgDivsL[i].style.animationDelay !== undefined) {
                    this.imgDivsL[i].style.animationDelay = (Math.abs(i-(this.imgDivsL.length-1))*aDelay) + "s";
                }
                else {
                    this.imgDivsL[i].style.webkitAnimationDelay = (Math.abs(i-(this.imgDivsL.length-1))*aDelay) + "s";
                }
            }
        }
    }
    
    
    // ----------------------- SCROLLER
    
    function ScrollerObject() {
        this.object = null;
        // Offset from default calculated scroll of bg image TODO
        this.scrollOffset = 0;
    }
    // Initialize scroller objects
    ScrollerObject.prototype.initialize = function(offset) {
        // Set the scroll offset for this instance
        if (offset) this.scrollOffset = offset;
        this.parallaxScroll();
    }
    ScrollerObject.prototype.parallaxScroll = function() {
        var objYPos             = getYPos(this.object, 0);
        var objYPosBottom       = objYPos + this.object.offsetHeight;
        var objVisPos           = objYPos - window.pageYOffset;
        var objVisPosBottom     = objYPosBottom - window.pageYOffset;
        var cW                  = objHtml().clientWidth;
        var cH                  = objHtml().clientHeight;
        var cHW                 = cH/cW;
        var bPosL               = window.getComputedStyle(this.object, "").backgroundPosition.split(" ");
        // Test that top or bottom of object are within view
        if ((objVisPos < cH) && (objVisPosBottom > 0)) {
            // Position percentage is in relation to max possible vertical position in the view
            var posPercent = rangeToPercent(objVisPosBottom, 0, cH + this.object.offsetHeight);
            // Position image
            var newbPosY = Math.max(0, Math.min(100, posPercent+this.scrollOffset));
            this.object.style.backgroundPosition = bPosL[0] + " " + newbPosY + "%";
            //this.object.style.backgroundPosition = "50% " + Math.max(0, Math.min(100, posPercent+this.scrollOffset)) + "%";
        }
    }
    
    
    // ----------------------- NAVMENU
    
    function NavmenuObject() {
        this.object         = null;
        this.menuUL         = null;
        this.menuItemsL     = [];
        this.itemHeight     = 0;
        this.menuIcon       = null;
        this.direction      = "vertical";
        this.menuWrapper    = null;
        // Record of window scroll when wrapper shown
        this.winScroll      = 0;
    }
    NavmenuObject.prototype.initialize = function() {
        if (this.object.dataset.qnrDirection) this.direction = this.object.dataset.qnrDirection;
        this.menuUL = this.object.querySelector("ul");
        // Add a class to the UL to make the next line more specific
        this.menuUL.classList.add("qnr-navmenu-ul");
        this.menuItemsL = this.object.querySelectorAll("ul.qnr-navmenu-ul > li");
        // Get dimensions of first LI item (traversing all does not work)
        this.itemHeight = this.menuItemsL[0].offsetHeight;
        // Style menu, expanded or collapsed
        this.stylemenu();
        // Make the menu DIV visible (from hidden, in CSS file)
        // If collapsed, UL within will not be displayed, until icon is clicked
        this.object.style.visibility = "visible";
    }
    NavmenuObject.prototype.stylemenu = function() {
        // The following must work on load and on resize...
        // Show the UL by setting the class, so measurements can be done
        // If top of items less than item height, style expanded, else collapsed
        this.object.classList.remove("qnr-navmenu-collapsed");
        this.object.classList.add("qnr-navmenu-expanded");
        // Remove "qnr-hmenu-in-collapsed" class from any LI items as hmenu widgets
        for (var x = 0; x < this.menuItemsL.length; x++) {
            if (this.menuItemsL[x].classList.contains("qnr-hmenu-in-collapsed")) {
                this.menuItemsL[x].classList.remove("qnr-hmenu-in-collapsed");
            }
        }
        if (this.menuWrapper) {
            // Remove wrapper and place menu list back in widget DIV
            this.object.appendChild(this.menuWrapper.removeChild(this.menuUL));
            this.menuWrapper.parentNode.removeChild(this.menuWrapper);
            this.menuWrapper = null;
        }
        if (this.menuIcon) {
            this.menuUL.classList.remove("qnr-navmenu-vertical");
            this.menuUL.classList.remove("qnr-navmenu-vertical-horizontal");
            if (this.direction == "vertical") {
                this.menuUL.classList.remove("qnr-navmenu-vertical-show");
                this.menuUL.classList.remove("qnr-navmenu-vertical-hide");
                this.menuUL.classList.remove("qnr-navmenu-vertical-hidden");
            }
            else {
                this.menuUL.classList.remove("qnr-navmenu-vertical-show-right");
                this.menuUL.classList.remove("qnr-navmenu-vertical-hide-left");
                this.menuUL.classList.remove("qnr-navmenu-vertical-hidden-left");
            }
            this.object.removeChild(this.menuIcon);
            this.menuIcon = null;
        }
        for (var i = 0; i < this.menuItemsL.length; i++) {
            // Test for line wrap in nav menu, and collapse it
            if (this.menuItemsL[i].offsetTop >= this.itemHeight) {
                this.object.classList.remove("qnr-navmenu-expanded");
                this.object.classList.add("qnr-navmenu-collapsed");
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
                    this.menuWrapper = document.createElement("div");
                    this.menuWrapper.className = "qnr-navmenu-wrapper";
                    this.menuWrapper.appendChild(this.object.removeChild(this.menuUL));
                    // Place wrapped menu after menu DIV
                    this.object.parentNode.insertBefore(this.menuWrapper, this.object.nextSibling);
                }
                if (this.direction == "vertical") {
                    this.menuUL.classList.add("qnr-navmenu-vertical");
                    this.menuUL.classList.add("qnr-navmenu-vertical-hidden");
                }
                else {
                    this.menuUL.classList.add("qnr-navmenu-vertical-horizontal");
                    this.menuUL.classList.add("qnr-navmenu-vertical-hidden-left");
                }
                if (!this.menuIcon) this.createMenuIcon();
                break;
            }
        }
        //if (this.object.classList.contains("qnr-navmenu-expanded") && QNR_HMENU.hmenuObjectsL) {
            //QNR_HMENU.hmenuObjectsL[0].hideMenus();
        //}
    }
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
                    this1.hideVerticalMenu();
                }
                event.stopPropagation();
            }
        };
        this.menuIcon.classList.add("qnr-navmenu-icon");
        this.menuIcon.classList.add("qnr-navmenu-icon-open");
        // Place menu icon in widget DIV, now possibly empty
        this.object.appendChild(this.menuIcon);
    }
    NavmenuObject.prototype.showVerticalMenu = function() {
        // Change the icon to close
        this.menuIcon.classList.remove("qnr-navmenu-icon-open");
        this.menuIcon.classList.add("qnr-navmenu-icon-close");
        // Set top of menu wrapper (absolute positioned) to after widget
        if (this.menuWrapper && window.getComputedStyle(this.object, "").position == "fixed") {
            this.menuWrapper.style.top = (this.object.offsetTop + this.object.offsetHeight + window.pageYOffset) + "px";
            // Record window scroll for later comparison on scrolling
            this.winScroll = window.pageYOffset;
        }
        else if (this.menuWrapper) { // Widget positioned relative or absolute
            this.menuWrapper.style.top = (this.object.offsetTop + this.object.offsetHeight) + "px";
        }
        // Show vertical menu list
        if (this.direction == "vertical") {
            this.menuUL.classList.remove("qnr-navmenu-vertical-hide");
            this.menuUL.classList.remove("qnr-navmenu-vertical-hidden");
            this.menuUL.classList.add("qnr-navmenu-vertical");
            this.menuUL.classList.add("qnr-navmenu-vertical-show");
        }
        else {
            this.menuUL.classList.remove("qnr-navmenu-vertical-hide-left");
            this.menuUL.classList.remove("qnr-navmenu-vertical-hidden-left");
            this.menuUL.classList.add("qnr-navmenu-vertical-horizontal");
            this.menuUL.classList.add("qnr-navmenu-vertical-show-right");
        }
    }
    NavmenuObject.prototype.hideVerticalMenu = function() {
        // Change icon to open
        this.menuIcon.classList.remove("qnr-navmenu-icon-close");
        this.menuIcon.classList.add("qnr-navmenu-icon-open");
        // Hide vertical menu list
        if (this.direction == "vertical") {
            this.menuUL.classList.remove("qnr-navmenu-vertical-show");
            this.menuUL.classList.remove("qnr-navmenu-vertical-hidden");
            this.menuUL.classList.add("qnr-navmenu-vertical-hide");
            var that = this;
            window.setTimeout(function(){
                that.menuUL.classList.add("qnr-navmenu-vertical-hidden");
                that.menuUL.classList.remove("qnr-navmenu-vertical-hide");
            },1000);
        }
        else {
            this.menuUL.classList.remove("qnr-navmenu-vertical-show-right");
            this.menuUL.classList.remove("qnr-navmenu-vertical-hidden-left");
            this.menuUL.classList.add("qnr-navmenu-vertical-hide-left");
        }
        // Hide any hmenus
        if (QNR_HMENU.hmenuObjectsL) {
            QNR_HMENU.hmenuObjectsL[0].hideMenus();
        }
    }
    NavmenuObject.prototype.onWinScroll = function() {
        // Move the wrapper up with scrolling if it was shown at scroll > 0, used when widget is "fixed"
        if (this.menuWrapper && window.pageYOffset < this.winScroll) {
            this.menuWrapper.style.top = (this.menuWrapper.offsetTop - (this.winScroll - window.pageYOffset)) + "px";
            this.winScroll = window.pageYOffset;
        }
        // Hide vertical menu if scrolled down to its last item
        else if (this.menuWrapper && window.getComputedStyle(this.object,"").position == "fixed" && 
                                                                window.pageYOffset > this.winScroll) {
            if (this.menuIcon.classList.contains("qnr-navmenu-icon-close")) {
                // getYPos required instead of offsetTop
                if (getYPos(this.menuItemsL[this.menuItemsL.length-1],0) <= 
                                getYPos(this.object,0) + this.object.offsetHeight + window.pageYOffset) {
                    this.hideVerticalMenu();
                }
            }
        }
    }
    
    
    // ----------------------- X-ICON
    
    function XiconObject() {
        this.object = null;
    }
    // Initialize x-icon objects
    XiconObject.prototype.initialize = function() {
        // Place x-icon SPAN on element, absolute
        var xSpan = document.createElement("span");
        xSpan.innerHTML = "x";
        xSpan.classList.add("qnr-x-icon-btn");
        xSpan.style.display             = "block";
        xSpan.style.position            = "absolute";
        this.object.style.transition    = "opacity 0.2s";
        this.object.appendChild(xSpan);
    }
    
    
    // ----------------------- ACCORDION
    
    function AccordionObject() {
        this.object = null;
        this.heights = []; // Heights of accordion answer DIVs
    }
    AccordionObject.prototype.initializeItems = function() {
        // Initialize accordion items, with SPAN wraps and data- attributes
        var accordionChildren = this.object.childNodes;
        var accordionItems = [];
        for (var i = 0; i < accordionChildren.length; i++) {
            if (accordionChildren[i].tagName == "DIV") accordionItems.push(accordionChildren[i]);
        }
        for (var i = 0; i < accordionItems.length; i++) {
            var aItem = accordionItems[i];
            // Record item height
            this.heights.push(aItem.offsetHeight);
            // Style item hidden
            aItem.style.display     = "inline-block";
            aItem.style.position    = "relative";
            aItem.style.opacity     = "0";
            aItem.style.top         = "-"+aItem.offsetHeight+"px";
            aItem.style.transition  = "opacity 0.6s";
            // Wrap item DIV in outer control SPAN
            // Using SPAN instead of DIV because the item collection is live (<- not any more)
            aItem.outerHTML = "<span class='qnr-accordion-item-wrap'>"+aItem.outerHTML+"</span>";
        }
        // Style & hide the SPANs, must be done in new loop as aItem.outerHTML doesn't include the SPAN
        var newItems = classObjs("qnr-accordion-item-wrap", this.object);
        for (var i = 0; i < newItems.length; i++) {
            // Create ID and height data- attributes on the SPANs
            newItems[i].dataset.qnrAccordionItemId = i;
            newItems[i].dataset.qnrAccordionItemHeight = this.heights[i];
            // Style item hidden
            newItems[i].style.display       = "inline-block";
            newItems[i].style.position      = "relative";
            newItems[i].style.margin        = "0";
            newItems[i].style.padding       = "0";
            newItems[i].style.height        = "0";
            newItems[i].style.overflow      = "hidden";
            newItems[i].style.opacity       = "0";
            newItems[i].style.transition    = "opacity 0.6s";
        }
        this.object.style.opacity = 1; // Show the widget now that it is initialized
    }
    AccordionObject.prototype.itemWrapAnim = function(obj, mode) {
        var intervalObj = null;
        window.setTimeout(function(){window.clearInterval(intervalObj);}, 1000);
        intervalObj = window.setInterval(function(){
            animCallback(obj, intervalObj, [mode]);
            // Update scrollers, accounting for change of scroll
            // This is the only solution that works
            if (QNR_INTER.scrollerObjectsL) {
                for (var i = 0; i < QNR_INTER.scrollerObjectsL.length; i++) {
                    QNR_INTER.scrollerObjectsL[i].parallaxScroll();
                }
            }
        }, 8);
    }
    
    
    // ----------------------- WIDGET ACTION
    
    function widgetAction(widget, clicked, eventType) {
        // "clicked" can be null if eventType is "scroll"
        
        if (eventType == "click") {
            // ----------------------- X-icon
            
            if (clicked.classList.contains("qnr-x-icon-btn")) {
                widget.style.opacity = 0;
                if (widget.classList.contains("qnr-remove")) {
                    window.setTimeout(function(){
                        widget.style.display = "none";
                        // Update scrollers, accounting for change of scroll
                        // This is the only solution that works
                        if (QNR_INTER.scrollerObjectsL) {
                            for (var i = 0; i < QNR_INTER.scrollerObjectsL.length; i++) {
                                QNR_INTER.scrollerObjectsL[i].parallaxScroll();
                            }
                        }
                    },400);
                }
                else {
                    window.setTimeout(function(){widget.style.visibility = "hidden";},400);
                }
                // TODO: callback to window, for more activity if needed (or just another function)
            }
            
            // ----------------------- Accordion
            
            if ((widget.nodeName == "DIV" && widget.classList.contains("qnr-accordion")) &&
                (clicked.nodeName == "P" && clicked.parentNode.classList.contains("qnr-accordion"))) {
                // Identify the clicked widget object
                var thisAccordion = QNR_INTER.accordionObjectsL[parseInt(widget.dataset.qnrAccordionId)];
                // Next element after the P is assumed to be SPAN wrap
                var toggleSpan = clicked.nextElementSibling;
                // SPAN was shown
                if (toggleSpan.style.opacity != 0) {
                    thisAccordion.itemWrapAnim(toggleSpan, "accordion hide");
                }
                else { // SPAN was hidden
                    if (!widget.classList.contains("qnr-multi")) {
                        // Hide any shown SPANs
                        togglableSpans = classObjs("qnr-accordion-item-wrap", widget);
                        for (var i = 0; i < togglableSpans.length; i++) {
                            if (togglableSpans[i].style.opacity != 0) {
                                thisAccordion.itemWrapAnim(togglableSpans[i], "accordion hide");
                            }
                        }
                    }
                    // Show our SPAN
                    thisAccordion.itemWrapAnim(toggleSpan, "accordion show");
                }
            }
            
        }
    }
    
    
    // ----------------------- ONLOAD
    
    window.addEventListener("load", function() {
        // Needed for accurate element position measurement on load
        window.scrollBy(0, 1);
        window.scrollBy(0, -1);
        
        
        // ----------------------- Aspect Keepers
        QNR_INTER.aspectkeepersL = classObjs("qnr-aspect-keeper");
        if (QNR_INTER.aspectkeepersL) {
            for (var i = 0; i < QNR_INTER.aspectkeepersL.length; i++) {
                // Create a data- id attribute on the aspect keeper
                QNR_INTER.aspectkeepersL[i].dataset.qnrAspectkeeperId = i;
                // Create a new JS object for the aspect keeper
                QNR_INTER.aspectkeeperObjectsL.push(new AspectkeeperObject());
                QNR_INTER.aspectkeeperObjectsL[i].object = QNR_INTER.aspectkeepersL[i];
                // Initialize object
                QNR_INTER.aspectkeeperObjectsL[i].initialize();
            }
        }
        
        
        // ----------------------- Arrow Anims
        QNR_INTER.arrowanimsL = classObjs("qnr-arrow-anim");
        if (QNR_INTER.arrowanimsL) {
            for (var i = 0; i < QNR_INTER.arrowanimsL.length; i++) {
                // Create a data- id attribute on the arrow anim
                QNR_INTER.arrowanimsL[i].dataset.qnrArrowanimId = i;
                // Create a new JS object for the arrow anim
                QNR_INTER.arrowanimObjectsL.push(new ArrowanimObject());
                QNR_INTER.arrowanimObjectsL[i].object = QNR_INTER.arrowanimsL[i];
                // Initialize object
                QNR_INTER.arrowanimObjectsL[i].initialize();
            }
        }
        
        
        // ----------------------- Responsives
        QNR_INTER.responsivesL = classObjs("qnr-responsive");
        if (QNR_INTER.responsivesL) {
            for (var i = 0; i < QNR_INTER.responsivesL.length; i++) {
                // Create a data- id attribute on the responsive
                QNR_INTER.responsivesL[i].dataset.qnrResponsiveId = i;
                // Create a new JS object for the responsive
                QNR_INTER.responsiveObjectsL.push(new ResponsiveObject());
                QNR_INTER.responsiveObjectsL[i].object = QNR_INTER.responsivesL[i];
                // Initialize object
                QNR_INTER.responsiveObjectsL[i].initialize();
            }
        }
        
        
        // ----------------------- Winscrollers
        QNR_INTER.winscrollersL = classObjs("qnr-winscroller");
        if (QNR_INTER.winscrollersL) {
            for (var i = 0; i < QNR_INTER.winscrollersL.length; i++) {
                // Create a data- id attribute on the winscroller
                QNR_INTER.winscrollersL[i].dataset.qnrWinscrollerId = i;
                // Create a new JS object for the winscroller
                QNR_INTER.winscrollerObjectsL.push(new WinscrollerObject());
                QNR_INTER.winscrollerObjectsL[i].object = QNR_INTER.winscrollersL[i];
                // Initialize object
                QNR_INTER.winscrollerObjectsL[i].initialize();
            }
        }
        
        // ----------------------- Carousel JS objects
        
        QNR_INTER.carouselsL = classObjs("qnr-carousel");
        if (QNR_INTER.carouselsL) {
            for (var i = 0; i < QNR_INTER.carouselsL.length; i++) {
                // Create a data- id attribute on the carousel widget
                QNR_INTER.carouselsL[i].dataset.qnrCarouselId = i;
                // Create a new JS object for the carousel
                QNR_INTER.carouselObjectsL.push(new CarouselObject());
                QNR_INTER.carouselObjectsL[i].object = QNR_INTER.carouselsL[i];
                // Initialize object
                QNR_INTER.carouselObjectsL[i].initialize();
            }
        }
        
        
        // ----------------------- Scrolling JS objects
        
        QNR_INTER.scrollersL = classObjs("qnr-scroller");
        if (QNR_INTER.scrollersL) {
            for (var i = 0; i < QNR_INTER.scrollersL.length; i++) {
                // Create a data- id attribute on the scroller widget
                QNR_INTER.scrollersL[i].dataset.qnrScrollerId = i;
                // Create a new JS object for the scroller
                QNR_INTER.scrollerObjectsL.push(new ScrollerObject());
                QNR_INTER.scrollerObjectsL[i].object = QNR_INTER.scrollersL[i];
                // Initialize object
                QNR_INTER.scrollerObjectsL[i].initialize();
            }
        }
        
        // ----------------------- Navmenu JS object
        
        if (objClass("qnr-navmenu")) {
            QNR_INTER.navmenuObject = new NavmenuObject();
            QNR_INTER.navmenuObject.object = objClass("qnr-navmenu");
            QNR_INTER.navmenuObject.initialize();
        }
        
        // ----------------------- Stickybar JS object
        
        if (objClass("qnr-stickybar")) {
            QNR_INTER.stickybarObject = new StickybarObject();
            QNR_INTER.stickybarObject.object = objClass("qnr-stickybar");
            QNR_INTER.stickybarObject.initialize();
        }
        
        // ----------------------- Slider JS objects
        
        QNR_INTER.slidersL = classObjs("qnr-slider");
        if (QNR_INTER.slidersL) {
            for (var i = 0; i < QNR_INTER.slidersL.length; i++) {
                // Create a data- id attribute on the slider widget
                QNR_INTER.slidersL[i].dataset.qnrSliderId = i;
                // Create a new JS object for the slider
                QNR_INTER.sliderObjectsL.push(new SliderObject());
                QNR_INTER.sliderObjectsL[i].object = QNR_INTER.slidersL[i];
                // Initialize slide items of each slider
                QNR_INTER.sliderObjectsL[i].initialize();
            }
        }
        
        // ----------------------- Image animator JS objects
        
        QNR_INTER.imageanimsL = classObjs("qnr-img-anim");
        if (QNR_INTER.imageanimsL) {
            for (var i = 0; i < QNR_INTER.imageanimsL.length; i++) {
                // Create a data- id attribute on the imageanim widget
                QNR_INTER.imageanimsL[i].dataset.qnrImageanimId = i;
                // Create a new JS object for the imageanim
                QNR_INTER.imageanimObjectsL.push(new ImageanimObject());
                QNR_INTER.imageanimObjectsL[i].object = QNR_INTER.imageanimsL[i];
                // Initialize image items of each imageanim
                QNR_INTER.imageanimObjectsL[i].initialize();
            }
        }
        
        // ----------------------- X-icon JS objects
        
        QNR_INTER.xiconsL = classObjs("qnr-x-icon");
        if (QNR_INTER.xiconsL) {
            for (var i = 0; i < QNR_INTER.xiconsL.length; i++) {
                // Create a data- id attribute on the x-icon widget
                QNR_INTER.xiconsL[i].dataset.qnrXIconId = i;
                // Create a new JS object for the x-icon
                QNR_INTER.xiconObjectsL.push(new XiconObject());
                QNR_INTER.xiconObjectsL[i].object = QNR_INTER.xiconsL[i];
                // Initialize object
                QNR_INTER.xiconObjectsL[i].initialize();
            }
        }
        
        // ----------------------- Accordion JS objects
        
        QNR_INTER.accordionsL = classObjs("qnr-accordion");
        if (QNR_INTER.accordionsL) {
            for (var i = 0; i < QNR_INTER.accordionsL.length; i++) {
                // Create a data- id attribute on the accordion widget
                QNR_INTER.accordionsL[i].dataset.qnrAccordionId = i;
                // Create a new JS object for the accordion
                QNR_INTER.accordionObjectsL.push(new AccordionObject());
                QNR_INTER.accordionObjectsL[i].object = QNR_INTER.accordionsL[i];
                // Initialize & hide items of each accordion
                QNR_INTER.accordionObjectsL[i].initializeItems();
            }
        }
        
        
        // ----------------------- Font Resizes
        
        // Make this one last...
        QNR_INTER.fontresizesL = classObjs("qnr-font-resize");
        if (QNR_INTER.fontresizesL) {
            for (var i = 0; i < QNR_INTER.fontresizesL.length; i++) {
                // Create a data- id attribute on the font resize
                QNR_INTER.fontresizesL[i].dataset.qnrFontresizeId = i;
                // Create a new JS object for the font resize
                QNR_INTER.fontresizeObjectsL.push(new FontresizeObject());
                QNR_INTER.fontresizeObjectsL[i].object = QNR_INTER.fontresizesL[i];
                // Initialize object
                QNR_INTER.fontresizeObjectsL[i].initialize();
            }
        }
    }, false);
    
    
    // ----------------------- ONCLICK
    
    // Find the widget the clicked object belongs to
    // UI elements working independently must capture their events
    window.addEventListener("click", function(event) {
        var clicked = event.target;
        var widget = clicked;  
        var clickedWidget = false;
        
        // ----------------------- Navmenu icon
        
        //if (clicked.classList.contains("qnr-navmenu-icon")) {
            //if (clicked.classList.contains("qnr-navmenu-icon-open")) {
                //QNR_INTER.navmenuObject.showVerticalMenu();
            //}
            //else { // Closed
                //QNR_INTER.navmenuObject.hideVerticalMenu();
            //}
        //}
        // Dismiss navmenu on any click
        //else if (QNR_INTER.navmenuObject && document.querySelector("div.qnr-navmenu-icon-close")) {
        if (QNR_INTER.navmenuObject && document.querySelector("div.qnr-navmenu-icon-close") &&
                        !clicked.classList.contains("qnr-hmenu")) { // Don't close on click on hmenu widget
            QNR_INTER.navmenuObject.hideVerticalMenu();
        }
        
        // ----------------------- X-icon
        
        if (clicked.classList.contains("qnr-x-icon-btn")) {
            widget = clicked.parentNode;
            clickedWidget = true;
        }
        
        // ----------------------- Accordion
        
        else {
            // Test if clicked in widget
            while (widget.nodeName != "BODY" && widget.nodeName != "HTML") {
                if (widget.classList.contains("qnr-accordion")) {
                    clickedWidget = true;
                    break;
                }
                else {
                    widget = widget.parentNode;
                }
            }
        }
        
        if (!clickedWidget) return;
        // We have a widget
        widgetAction(widget, clicked, "click");
    }, false);
    
    
    // ----------------------- ONKEYDOWN
    
    window.addEventListener("keydown",function(event){
        if (event.target.tagName == "BODY" && QNR_INTER.carouselObjectsL && QNR_INTER.carouselObjectsL.length == 1) {
            QNR_INTER.carouselObjectsL[0].nextPrevArrowKey(event);
        }
    }, false);
    
    
    // ----------------------- ONBLUR
    
    window.addEventListener("blur",function(event){
        if (QNR_INTER.carouselObjectsL) {
            for (var i = 0; i < QNR_INTER.carouselObjectsL.length; i++) {
                QNR_INTER.carouselObjectsL[i].pauseCarousel();
            }
        }
    },false);
    
    
    // ----------------------- ONFOCUS
    
    window.addEventListener("focus",function(event){
        if (QNR_INTER.carouselObjectsL) {
            for (var i = 0; i < QNR_INTER.carouselObjectsL.length; i++) {
                // Use the scroll pauser to start if not offscreen
                QNR_INTER.carouselObjectsL[i].onScrollCarousel();
            }
        }
    },false);
    
    
    // ----------------------- ONRESIZE
    
    window.addEventListener("resize", function(event) {
        if (QNR_INTER.navmenuObject) QNR_INTER.navmenuObject.stylemenu();
        if (QNR_INTER.stickybarObject && QNR_INTER.stickybarObject.madesticky) QNR_INTER.stickybarObject.sizePlaceholder();
        if (QNR_INTER.aspectkeepersL) {
            for (var i = 0; i < QNR_INTER.aspectkeeperObjectsL.length; i++) {
                QNR_INTER.aspectkeeperObjectsL[i].setHeight();
            }
        }
        if (QNR_INTER.fontresizesL) {
            for (var i = 0; i < QNR_INTER.fontresizeObjectsL.length; i++) {
                QNR_INTER.fontresizeObjectsL[i].resize();
            }
        }
        if (QNR_INTER.carouselsL) {
            for (var i = 0; i < QNR_INTER.carouselObjectsL.length; i++) {
                QNR_INTER.carouselObjectsL[i].styleCarousel();
            }
        }
    }, false);
    
            
    // ----------------------- ONSCROLL
    
    // Set up scroll event listener, must be on window
    window.addEventListener("scroll", function(event){
        
        // ----------------------- Winscroller arrow
        if (objClass("qnr-winscroller-arrow")) {
            var wsArrow = objClass("qnr-winscroller-arrow"); // Only one arrow can exist
            // Show winscroller arrow if we're scrolled down > this.winscrollFraction
            if (window.pageYOffset > window.innerHeight * wsArrow.dataset.qnrWinscrollFraction) {
                wsArrow.style.visibility = "visible";
            }
            else {
                wsArrow.style.visibility = "hidden";
            }
        }
        
        // ----------------------- Navmenu
        if (QNR_INTER.navmenuObject) {
            QNR_INTER.navmenuObject.onWinScroll();
        }
        
        // ----------------------- Carousels
        if (QNR_INTER.carouselObjectsL) {
            for (var i = 0; i < QNR_INTER.carouselObjectsL.length; i++) {
                QNR_INTER.carouselObjectsL[i].onScrollCarousel();
            }
        }
        
        // ----------------------- Scrollers (Parallax)
        if (QNR_INTER.scrollerObjectsL) {
            for (var i = 0; i < QNR_INTER.scrollerObjectsL.length; i++) {
                QNR_INTER.scrollerObjectsL[i].parallaxScroll();
            }
        }
        
        // ----------------------- Stickybar
        if (QNR_INTER.stickybarObject) {
            QNR_INTER.stickybarObject.manageSticky();
        }
    },false);
    

    // ----------------------- Widget JS object getters

    function sliderID(id) {
        return QNR_INTER.sliderObjectsL[parseInt(objID(id).dataset.qnrSliderId)];
    }

    function carouselID(id) {
        return QNR_INTER.carouselObjectsL[parseInt(objID(id).dataset.qnrCarouselId)];
    }

})() // End of Quicknr Interface


/* ===================== UTILITY FUNCTIONS ===========================
 * 
 * Moved out of the self-executing function above to the global name-
 * space, available to scripts further up the chain
 * 
 * ===================================================================*/

// ----------------------- Mobile device & Chrome detectors

function deviceIsMobile() {
    var isMobile = /iPhone|iPad|iPod|Android|Blackberry|Nokia|Opera mini|Windows mobile|Windows phone|iemobile/i.test(navigator.userAgent);
    return isMobile;
}

function browserIsChrome() {
    return /Chrome/i.test(navigator.userAgent);
}

// ----------------------- File & Dir Path Getters

function getHrefDirPath() {
    return window.location.href.substr(0,window.location.href.lastIndexOf("/"));
}

function getHrefDirName() {
    var wDirPath = getHrefDirPath();
    return wDirPath.substr(wDirPath.lastIndexOf("/")+1);
}

function getHrefFileName() {
    var fn =  window.location.href.split("/").pop();
    if (!fn) fn = "index.html"; // Avoid empty name
    else {
        fn = fn.split("#")[0];
        fn = fn.split("?")[0];
    }
    return fn;
}

// ----------------------- Percentage function

function rangeToPercent(number, min, max) {
    return ((number - min) / (max - min)) * 100;
}

// ----------------------- Position functions

// Returns Y position of element, with given offset
function getYPos(elem, offsetPos) {
    if (!offsetPos) offsetPos = 0;
    var oPos = offsetPos;
    if (elem.offsetParent) {
        do {
            oPos += elem.offsetTop;
        } while (elem = elem.offsetParent);
    }
    return oPos;
}

// Returns X position of element, with given offset
function getXPos(elem, offsetPos) {
    oPos = offsetPos;
    if (elem.offsetParent) {
        do {
            oPos += elem.offsetLeft;
        } while (elem = elem.offsetParent);
    }
    return oPos;
}

// ----------------------- Image preloader

function loadImagesIntoMemory(imgList) {
    for (var i = 0; i < imgList.length; i++) {
        var img = new Image();
        img.src = imgList[i];
    }
}

// ----------------------- Other functions

function async(fn, args) {
    // Execute the passed function asynchronously
    setTimeout(function() {fn(args);}, 0);
}

function print(args) {
    console.log(args);
}

// ----------------------- Convenience object-getting functions

function objHtml() {
    return document.documentElement;
}

function objClass(name, parent) {
    if (!parent) {
        return document.getElementsByClassName(name)[0];
    }
    else {
        return parent.getElementsByClassName(name)[0];
    }
}

function classObjs(name, parent) {
    if (!parent) {
        return document.getElementsByClassName(name);
    }
    else {
        return parent.getElementsByClassName(name);
    }
}

function objID(id, parent) {
    if (!parent) {
        return document.getElementById(id);
    }
    else {
        return parent.getElementById(id);
    }
    
}

function objTag(tag, parent) {
    if (!parent) {
        return document.getElementsByTagName(tag)[0];
    }
    else {
        return parent.getElementsByTagName(tag)[0];
    }
}

function tagObjs(tag, parent) {
    if (!parent) {
        return document.getElementsByTagName(tag);
    }
    else {
        return parent.getElementsByTagName(tag);
    }
}
