/* ==================================================================
 *
 *            QUICKNR INTERFACE 1.5.0+
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
     * elements.
     * 
     * All properties and methods of widgets are accessible in your own
     * code through the global "QNR_INTER" object. See the end of the
     * Carousel section for an example.
     * 
     * 
     *                         Arrow Anim
     * 
     * A DIV with the class of "qnr-arrow-anim" is a widget that will
     * display as a circle containing a downward pointing, animated 
     * arrow. The widget uses the "quicknr-interface.woff" font for the
     * glyph. The DIV must be empty and will have the arrow inserted as
     * a SPAN element.
     * 
     * The direction of the arrow and its animation can be changed with
     * the dataset "data-qnr-arrow-dir" attribute set to "up", "left" or
     * "right". Default is "down".
     * 
     * Up and down arrows should also be winscroller widgets, see below.
     * Left and right arrows should have an onclick function assigned.
     * 
     * The arrow can be set to stop a carousel animating, with the 
     * "data-qnr-stop-carousel" attribute set to the ID of the carousel.
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
     * will be the display area size with the minimum font size.
     * 
     * The minimum percentage size can be specified with the dataset
     * "data-qnr-font-min" attribute, default value being "80".
     * 
     * If the "data-qnr-obj-max-width" dataset attribute is used and set
     * to a positive integer, the width of the widget element will be
     * used for calculating font size instead of total display area. The
     * dataset sets the maximum width that will activate font resizing.
     * 
     * Likewise, if the "data-qnr-win-max-width" attribute is used in 
     * the same way, the sizing will be according to window width.
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
     * and margins set to 0.
     * 
     * Item DIVs will be assigned the class of "qnr-responsive-item",
     * with margins of 0, and padding set to "0.5em 2em". In some use
     * cases, particularly at small display sizes, you may need to
     * override this.
     * 
     * Further overrides may be needed when narrower minimum widths of
     * the item DIVs are desired, as in columns of links in a footer. In
     * this case, override the flex and width properties of the two 
     * classes mentioned above.
     * 
     * Remember that a responsive row of more than 4 blocks makes for 
     * poor user experience (except for image galleries).
     * 
     * 
     *                         Carousel
     * 
     * A carousel widget is a DIV with the class of "qnr-carousel". 
     * Items may be DIVs or IMGs and require no additional classes.
     * 
     * If items are IMGs, the code will convert them to DIVs with 
     * background images. Items will be assigned the class of 
     * "qnr-carousel-item".
     * 
     * If the widget has an attribute of "data-qnr-captions" 
     * set to "on", ALT attribute values of item IMGs will be placed in 
     * a caption DIV under the carousel, created by the code. The DIV 
     * will be assigned the class of "qnr-carousel-caption".
     * 
     * Three navigation arrow types are supported, set with the 
     * "data-qnr-arrow-type" attribute: "regular" for a thick arrow in 
     * a circle, "big" for bigger thin arrows without a circle 
     * background, and "sticky" for smaller thin arrows on rounded 
     * square backgrounds flush with the left and right edges of the 
     * widget.
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
     * font, derived from the open source "open-iconic" font.
     * 
     * The code creates a control strip in the widget, featuring tiny 
     * selector circles for each slide that the user can click or tap.
     * 
     * The control strip will be assigned the class of 
     * "qnr-carousel-controlstrip", and the circles "qnr-carousel-thumb".
     * 
     * If the user device is not mobile, the little selector circles on 
     * the control strip will on hover display small animated previews 
     * and have an additional class of "qnr-carousel-thumb-preview".
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
     * them Parallax Scroller widgets.
     * 
     * The time it takes between loading of the page and the start of 
     * play can be set with the "data-qnr-start-interval" 
     * attribute, set to "4" by default (in seconds). The interval 
     * between slides can be set with the "data-qnr-interval" 
     * attribute, by default set to "3".
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
     * Transition duration must be shorter than interval.
     * 
     * If there is only one carousel widget on the page, keyboard 
     * navigation is supported with left and right keys.
     * 
     * If the carousel is invisible due to page scrolling, its animation
     * will stop, and resume when back in view. The same when the 
     * browser window loses and regains focus.
     * 
     * A scroll offset can be set with the "data-qnr-scroll-offset"
     * attribute, default is "0". Set it to a negative value if using
     * a fixed navbar, so that the slideshow will pause even though the
     * carousel is not completely off screen.
     * 
     * When a slide has been selected by the user rather than shown 
     * automatically, its display time is doubled, then the show 
     * continues.
     * 
     * To stop the slideshow when a slide is manually selected, set the
     * "data-qnr-resume-auto" attribute on the widget to "off". Default
     * is "on". This is useful if the slides are DIVs showing more than
     * images, with the user being able to interact with the content.
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
     * fewer, under 1MB each, should be fine.
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
     * ...or resume play with the ".resumeCarousel()" method.
     * 
     * Scrolling the page, carousel in view, will resume play unless
     * auto resume is off as detailed above.
     * 
     * For a hard stop, "QNR_INTER.carouselObjectsL[X].stopCarousel()"
     * can be used. Subsequent navigation will only start the automatic 
     * sliding again after "QNR_INTER.carouselObjectsL[X].hardStop" is 
     * set to false (and auto resume is on).
     * 
     * 
     *                         Stickybar
     * 
     * If you want a DIV, that spans 100% of the page width and appears
     * at a distance down from the top, perhaps after a header image, to
     * "stick" to the top of the window like a menu bar when the window
     * is scrolled down beyond it, mark the DIV with the "qnr-stickybar"
     * class.
     * 
     * The widget will be assigned the class of "qnr-stickybar-fixed" 
     * for its fixed appearance.
     * 
     * 
     *                         Slider
     * 
     * A DIV marked with the class of "qnr-slider" is a non-looping,
     * introductory slider of one or more DIVs it contains. The top DIV 
     * in HTML is the last item to play and will end the animation.
     * 
     * The widget supports the "data-qnr-slide-duration" attribute with
     * a value of seconds, "4" being default.
     * 
     * Direction of the sliding can be set to "rtl" (default), "ltr", 
     * "up" or "down" with the "data-qnr-slide-direction" attribute.
     * 
     * If the direction is appended with "-stop", the animation will 
     * stop as the slide is fully on screen, no off movement to follow.
     * 
     * By default the sliding will start on load, when the slider is
     * initialized. To prevent this, use the "data-qnr-auto-slide"
     * attribute, set to "off".
     * 
     * The item DIVs require no special markup, but "qnr-slide-none" is
     * available if a DIV needs to be hidden (perhaps to prevent it
     * flashing on load).
     * 
     * The DIV items are marked with the class of "qnr-slide-item" by 
     * the code.
     * 
     * For more functionality, use a carousel.
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
     * image with movement", not a slideshow. Use with 3-6 images.
     * 
     * For the sake of the bottom image of the stack (top IMG tag in 
     * HTML), "qnr-img-zoom-in-opaque" and "qnr-img-zoom-out-opaque"
     * are also supported, with no fading.
     * 
     * The following anchoring classes are supported on the IMG tags:
     * "qnr-centered", "qnr-topright", "qnr-topleft", "qnr-bottomright",
     * "qnr-bottomleft", "qnr-leftcenter", "qnr-rightcenter", top left 
     * being the default.
     * 
     * Animation timing function classes on the IMGs can also be used:
     * "qnr-ease", "qnr-ease-in", "qnr-ease-out", "qnr-linear", linear 
     * being the default - ease-out should be used on last shown image.
     * 
     * The widget DIV containing the IMGs supports a data- attribute of
     * "data-qnr-anim-duration", with a value in seconds, like "4", the
     * default. The duration is for each image layer.
     * 
     * IMG tags may be followed by a DIV with the class of 
     * "qnr-img-translucent-cover", to darken the images.
     * 
     * The cover may be succeeded by any other element that is to appear
     * over the image animation - it may require to be CSS positioned.
     * 
     * The code converts the IMGs to DIVs, with the classes transferred,
     * plus the class of "qnr-img-anim-div".
     * 
     * Other animation parameters were coded but removed due to poor 
     * performance. If the present functionality is not sufficient, 
     * consider creating a video instead.
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
     * viewport height if header fixed.
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
     *                         Scrollers
     * 
     * Scrollers are widgets that will be affected by scrolling in some
     * way, and have the class "qnr-scroller".
     * 
     * A scroller with a background-image applied will by default be 
     * parallax scrolled. The image should be larger in height than the 
     * containing DIV, or it won't scroll.
     * 
     * Other widgets that affect the window scroll must update the
     * scrollers.
     * 
     *                         Winscroller
     * 
     * A clickable element with the class of "qnr-winscroller" is a
     * widget that on click will scroll the window to a target object.
     * 
     * The target ID is set on the widget with the "data-qnr-target" 
     * attribute; default target is the BODY object. A pixel scrolling 
     * offset may also be set with "data-qnr-offset", may be negative.
     * 
     * If the widget is an A tag, the preventDefault() method of the 
     * event object will be used for the scroll to work.
     * 
     * If the widget is BODY, a SPAN object will be created and placed
     * as the first element in the BODY, with the class of 
     * "qnr-winscroller-arrow", appearing as an arrow fixed to the right
     * of the screen, that the user can click/tap to return to the top  
     * of the page. The "quicknr-interface.woff" font is used for the 
     * arrow glyph.
     * 
     * Further, "data-qnr-winscroll-fraction" can be set on the BODY 
     * tag, a float controlling the proportion of the window that must 
     * be scrolled down before the arrow will be displayed; default 
     * value is "0.25".
     * 
     * The scrolling is limited to 1 second before it cancels.
     * 
     * 
     *                         X-icons
     * 
     * X-icons are widgets with the class of "qnr-x-icon". The code will
     * place a closing "x" on the widget that the user can click.
     * 
     * X-icons with additional class of "qnr-remove", will on click
     * set display of the widget to "none" instead of merely hiding it.
     * 
     * The X-icon widget element must be CSS positioned.
     * 
     * X-icons cannot be used on other widgets (accordions), they are
     * meant for the closing of static elements like image boxes and
     * notifications.
     * 
     * 
     *                         Accordions
     * 
     * Accordions are widgets with the class of "qnr-accordion".
     * 
     * Accordions with additional class of "qnr-multi" will show more
     * than one item at a time if more than one is clicked.
     * 
     * Accordion items consist of <P> questions and <DIV> answers. They
     * don't require any classes, and the DIVs may contain <P> and 
     * <DIV> tags, as well as others. Other tags such as headings 
     * may be placed between question-answer groups, but the question 
     * <P> and answer <DIV> must be next to each other.
     * 
     * 
     *                         Aspect Keeper
     * 
     * An element with the class of "qnr-aspect-keeper" will keep its
     * aspect ratio, 1.5 by default, and settable with the dataset
     * attribute "data-qnr-aspect-ratio". The aspect ratio is preserved 
     * by using the width as the base of calculation for the height. 
     * Computed CSS values for min and max height are respected.
     * 
     * 
     *                         Button Toggle
     * 
     * Buttons with "qnr-button-toggle" class assigned may have callback
     * functions set, controlling action on Up and Down states of the 
     * button. The callback is set with the dataset attribute
     * "data-qnr-button-toggle-up" / "data-qnr-button-toggle-down".
     * 
     * By default, the widget will send a custom event when toggled, 
     * "QIEvent_ButtonToggle". This can be prevented with the dataset
     * attribute "data-qnr-button-toggle-events" set to "no". The event
     * will have its message property set to "up"/"down" as appropriate.
     * 
     * The state of the button is controlled with CSS classes 
     * "qnr-button-toggle-up" and "qnr-button-toggle-down".
     * 
     * 
     *                         Popout Slider
     * 
     * Elements, usually DIVs, with the "qnr-popout-slider" class will 
     * have a button for showing of the animated slider created, and a
     * button for hiding it, with class names "qnr-popout-slider-button
     * -show" and "qnr-popout-slider-button-hide".
     * 
     * Dataset attributes may be set, controlling several aspects:
     * 
     * qnr-popout-slide-type: sliding direction; left,right,up,down
     * qnr-popout-anim-duration: duration in seconds, default 0.4
     * qnr-popout-title: title attribute of the show button
     * qnr-popout-reset: non-empty value for resetting HTML content
     * qnr-popout-show-callback: callback function to run on show
     * qnr-popout-hide-callback: callback function to run on hide
     * 
     * By default, the slider is styled for presentation of an email
     * subscription form, but can be adapted to other uses, such as
     * a menu drawer.
     * 
     * 
     *                         Thumb Strip
     * 
     * Elements, usually DIVs, with the "qnr-thumb-strip" class will 
     * function as a scrolling strip of thumb cells, bounded by 
     * navigation left and right arrows if there are more cells than
     * would fit in the strip bounded by its container.
     * 
     * If the widget also contains the "qnr-thumb-strip-vertical" class,
     * the strip will be vertical.
     * 
     * With addition of the "qnr-thumb-strip-noanim" class, animation 
     * can be turned off, useful when thumb cells link to pages.
     * 
     * The cells are assigned the "qnr-thumb-strip-cell" class if strip
     * is horizontal, otherwise "qnr-thumb-strip-cell-vertical".
     * 
     * The left/right margin to accommodate navigation arrows and their
     * spacing is by default 70px (30px per nav arrow + 5px margin). 
     * This can be changed with the dataset attribute taking an integer 
     * value, "data-qnr-thumb-strip-margin".
     * 
     * Navigation arrows are assigned the "qnr-thumb-strip-nav-left" /
     * "-right" / "-top" / "-bottom" class as appropriate.
     * 
     * A nav arrow will have the "qnr-thumb-strip-nav-disabled" class
     * applied when no further content is available to be scrolled.
     * 
     * Parent object of this widget is assumed to have no horizontal 
     * padding.
     * 
     * At least three cells of the strip must be displayed for the strip
     * to work correctly. If only one cell is shown it won't work. (The
     * number of shown cells is always odd.)
     * 
     * 
     *                         Layout Helpers
     * 
     * The "qnr-interface.css" file contains a section of layout helper
     * style rules, with 2 items: ".center" and ".center-bottom". These
     * are not widgets but can be applied to block elements.
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
    QNR_INTER.accordionsL = []; // List of accordion elements in doc
    QNR_INTER.accordionObjectsL = []; // List of JS accordion objects
    
    QNR_INTER.xiconsL = [];
    QNR_INTER.xiconObjectsL = [];
    
    QNR_INTER.scrollersL = [];
    QNR_INTER.scrollerObjectsL = [];
    
    // Only one menu object, no list
    QNR_INTER.navmenuObject = null;
    
    QNR_INTER.stickybarObject = null;

    QNR_INTER.imageanimsL = [];
    QNR_INTER.imageanimObjectsL = [];
    
    QNR_INTER.slidersL = [];
    QNR_INTER.sliderObjectsL = [];
    
    QNR_INTER.carouselsL = [];
    QNR_INTER.carouselObjectsL = [];
    
    QNR_INTER.winscrollersL = [];
    QNR_INTER.winscrollerObjectsL = [];
    
    QNR_INTER.responsivesL = [];
    QNR_INTER.responsiveObjectsL = [];
    
    QNR_INTER.fontresizesL = [];
    QNR_INTER.fontresizeObjectsL = [];
    
    QNR_INTER.arrowanimsL = [];
    QNR_INTER.arrowanimObjectsL = [];
    
    QNR_INTER.aspectkeepersL = [];
    QNR_INTER.aspectkeeperObjectsL = [];
    
    QNR_INTER.btntogglesL = [];
    QNR_INTER.btntoggleObjectsL = [];
    
    QNR_INTER.popoutSlidersL = [];
    QNR_INTER.popoutSliderObjectsL = [];
    
    QNR_INTER.thumbstripsL = [];
    QNR_INTER.thumbstripObjectsL = [];
    
    
    // ----------------------- THUMB STRIP
    
    function ThumbStripObject() {
        this.object = null;
        this.orientation = "horizontal";
        this.animate = true; // Cell animation can be turned off, useful when cells link to pages
        this.arrowsSizePlusMargin = 70; // Assuming 30px per nav arrow + 5px margin (may be set by user)
        this.cellItemsL = [];
        this.cellsTotalSize = 0;
        this.objectWrap = null;
        this.resizeTimer = null;
    }
    ThumbStripObject.prototype.initialize = function() {
        if (this.object.classList.contains("qnr-thumb-strip-vertical")) this.orientation = "vertical";
        if (this.object.classList.contains("qnr-thumb-strip-noanim")) this.animate = false;
        if (this.object.dataset.qnrThumbStripMargin) this.arrowsSizePlusMargin = this.object.dataset.qnrThumbStripMargin;
        var objCells = this.object.children;
        for (var i = 0; i < objCells.length; i++) {
            if (this.orientation == "horizontal") objCells[i].classList.add("qnr-thumb-strip-cell");
            else objCells[i].classList.add("qnr-thumb-strip-cell-vertical");
            this.cellItemsL.push(objCells[i]);
            if (this.animate) {
                var this1 = this;
                // We define onclick handlers on cells even if not interactive as then no effect
                objCells[i].addEventListener("click", function(event) {
                    this1.thumbCellClicked(event);
                }, false);
            }
            if (this.orientation == "horizontal") {
                this.cellsTotalSize += objCells[i].offsetWidth;
            } else { // Vertical
                this.cellsTotalSize += objCells[i].offsetHeight;
            }
        }
        //if ((this.orientation == "horizontal" && this.cellsTotalSize > this.object.offsetWidth) 
                //|| (this.orientation != "horizontal" && this.cellsTotalSize > this.object.offsetHeight)) {
            this.wrapObject();
            this.disableArrows();
        //}
    }
    ThumbStripObject.prototype.wrapObject = function() {
        if (this.orientation == "horizontal") {
            var oWrap = document.createElement("div");
            oWrap.className = "qnr-thumb-strip-wrap";
            // Make object width odd number of visible cell widths
            var objWidth = this.object.offsetWidth;
            var newObjW = objWidth - this.arrowsSizePlusMargin;
            var numVisCells = Math.floor(newObjW/this.cellItemsL[0].offsetWidth);
            newObjW = numVisCells * this.cellItemsL[0].offsetWidth;
            if (numVisCells/2 == Math.floor(numVisCells/2)) newObjW -= this.cellItemsL[0].offsetWidth;
            oWrap.style.width = (newObjW + this.arrowsSizePlusMargin) + "px";
            this.object.style.width = newObjW + "px";
            // Insert object in wrap
            this.object.parentNode.insertBefore(oWrap, this.object);
            oWrap.appendChild(this.object.parentNode.removeChild(this.object));
            // Create navigation arrows
            var aLeft = document.createElement("span");
            aLeft.className = "qnr-thumb-strip-nav-left";
            this.object.parentNode.insertBefore(aLeft, this.object);
            var aRight = document.createElement("span");
            aRight.className = "qnr-thumb-strip-nav-right";
            this.object.parentNode.appendChild(aRight);
            this.objectWrap = oWrap;
            var this2 = this;
            aLeft.addEventListener("click", function(event) {
                this2.navClicked(event, "left");
            }, false);
            var this3 = this;
            aRight.addEventListener("click", function(event) {
                this3.navClicked(event, "right");
            }, false);
            if (newObjW >= this.cellsTotalSize) {
                aLeft.style.visibility = "hidden";
                aRight.style.visibility = "hidden";
            } else {
                aLeft.style.visibility = "visible";
                aRight.style.visibility = "visible";
            }
        } else { // Vertical
            var oWrap = document.createElement("div");
            oWrap.className = "qnr-thumb-strip-wrap-vertical";
            // Make object height odd number of visible cell heights
            var objHeight = this.object.offsetHeight;
            var newObjH = objHeight - this.arrowsSizePlusMargin;
            var numVisCells = Math.floor(newObjH/this.cellItemsL[0].offsetHeight);
            newObjH = numVisCells * this.cellItemsL[0].offsetHeight;
            if (numVisCells/2 == Math.floor(numVisCells/2)) newObjH -= this.cellItemsL[0].offsetHeight;
            oWrap.style.height = (newObjH + this.arrowsSizePlusMargin) + "px";
            this.object.style.height = newObjH + "px";
            // Insert object in wrap
            this.object.parentNode.insertBefore(oWrap, this.object);
            oWrap.appendChild(this.object.parentNode.removeChild(this.object));
            // Create navigation arrows
            var aTop = document.createElement("span");
            aTop.className = "qnr-thumb-strip-nav-top";
            this.object.parentNode.insertBefore(aTop, this.object);
            var aBottom = document.createElement("span");
            aBottom.className = "qnr-thumb-strip-nav-bottom";
            this.object.parentNode.appendChild(aBottom);
            this.objectWrap = oWrap;
            var this2 = this;
            aTop.addEventListener("click", function(event) {
                this2.navClicked(event, "top");
            }, false);
            var this3 = this;
            aBottom.addEventListener("click", function(event) {
                this3.navClicked(event, "bottom");
            }, false);
            if (newObjH >= this.cellsTotalSize) {
                aTop.style.visibility = "hidden";
                aBottom.style.visibility = "hidden";
            } else {
                aTop.style.visibility = "visible";
                aBottom.style.visibility = "visible";
            }
        }
    }
    ThumbStripObject.prototype.navClicked = function(event, side) {
        if (this.orientation == "horizontal") {
            cellWidth = this.cellItemsL[0].offsetWidth;
            contWidth = this.object.offsetWidth;
            if (side == "left") {
                // Scroll the whole strip, except for starting and ending cells
                this.scrollObject(this.object, -1 * contWidth + (cellWidth*1));
            } else { // Right
                this.scrollObject(this.object, contWidth - (cellWidth*1));
            }
        } else { // Vertical
            cellHeight = this.cellItemsL[0].offsetHeight;
            contHeight = this.object.offsetHeight;
            if (side == "top") {
                // Scroll the whole strip, except for starting and ending cells
                this.scrollObject(this.object, -1 * contHeight + (cellHeight*1));
            } else { // Bottom
                this.scrollObject(this.object, contHeight - (cellHeight*1));
            }
        }
    }
    ThumbStripObject.prototype.thumbCellClicked = function(event) {
        if (this.orientation == "horizontal") {
            var cell = event.currentTarget; // Current target always the cell, even if child clicked
            var cellCont = this.object;
            var cellContWidth = cellCont.offsetWidth;
            var cellContHalfWidth = Math.round(cellContWidth/2);
            
            var cellPos = cell.offsetLeft;
            var cellWidth = cell.offsetWidth;
            var cellCenterPos = Math.round(cellPos + cellWidth/2) - cellCont.scrollLeft;
            
            var amountToScroll = cellCenterPos - cellContHalfWidth;
        } else { // Vertical
            var cell = event.currentTarget;
            var cellCont = this.object;
            var cellContHeight = cellCont.offsetHeight;
            var cellContHalfHeight = Math.round(cellContHeight/2);
            
            var cellPos = cell.offsetTop;
            var cellHeight = cell.offsetHeight;
            var cellCenterPos = Math.round(cellPos + cellHeight/2) - cellCont.scrollTop;
            
            var amountToScroll = cellCenterPos - cellContHalfHeight;
        }
        this.scrollObject(cellCont, amountToScroll);
    }
    ThumbStripObject.prototype.scrollObject = function(obj, amount) {
        var aYet = Math.abs(amount);
        var timer = null;
        clearTimeout(timer);
        var this5 = this;

        function step() {
            sA = Math.round(amount/22);
            amount = sA*20;
            if (this5.orientation == "horizontal") {
                obj.scrollLeft = obj.scrollLeft + sA;
            } else { // Vertical
                obj.scrollTop = obj.scrollTop + sA;
            }
            aYet -= Math.abs(sA);
            if (aYet > 1) {
                timer = setTimeout(step, 10);
            }
            else {
                clearTimeout(timer);
                this5.disableArrows();
            }
        }

        timer = setTimeout(step, 10);
    }
    ThumbStripObject.prototype.disableArrows = function() {
        if (this.orientation == "horizontal") {
            objQuery("span.qnr-thumb-strip-nav-left", this.objectWrap).classList.remove("qnr-thumb-strip-nav-disabled");
            objQuery("span.qnr-thumb-strip-nav-right", this.objectWrap).classList.remove("qnr-thumb-strip-nav-disabled");
            if (this.object.scrollLeft == 0) {
                objQuery("span.qnr-thumb-strip-nav-left", this.objectWrap).classList.add("qnr-thumb-strip-nav-disabled");
            } else if (this.object.scrollLeft >= this.cellsTotalSize - this.object.offsetWidth) {
                objQuery("span.qnr-thumb-strip-nav-right", this.objectWrap).classList.add("qnr-thumb-strip-nav-disabled");
            }
        } else { // Vertical 
            objQuery("span.qnr-thumb-strip-nav-top", this.objectWrap).classList.remove("qnr-thumb-strip-nav-disabled");
            objQuery("span.qnr-thumb-strip-nav-bottom", this.objectWrap).classList.remove("qnr-thumb-strip-nav-disabled");
            if (this.object.scrollTop == 0) {
                objQuery("span.qnr-thumb-strip-nav-top", this.objectWrap).classList.add("qnr-thumb-strip-nav-disabled");
            } else if (this.object.scrollTop >= this.cellsTotalSize - this.object.offsetHeight) {
                objQuery("span.qnr-thumb-strip-nav-bottom", this.objectWrap).classList.add("qnr-thumb-strip-nav-disabled");
            }
        }
    }
    ThumbStripObject.prototype.resize = function(event) {
        // Wrap-removing code did not work because the object does not resize when wrap removed,
        //   therefore we let the wrap always be there but invisible if not needed
        if (this.orientation == "horizontal") {
            if (deviceIsMobile()) { // TODO eliminate reload...
                window.setTimeout(function(){
                    window.location.reload(false);
                },10);
            } else this.resizeWrapH();
        } // No action needed on vertical
    }
    ThumbStripObject.prototype.resizeWrapH = function() {
        // Parent object of wrap assumed to have no horizontal padding
        var objectWrapParent = this.objectWrap.parentNode;
        if (this.objectWrap.offsetWidth != objectWrapParent.offsetWidth) {
            var newObjW = objectWrapParent.offsetWidth - this.arrowsSizePlusMargin;
            var numVisCells = Math.floor(newObjW/this.cellItemsL[0].offsetWidth);
            newObjW = numVisCells * this.cellItemsL[0].offsetWidth;
            newObjW = Math.min(newObjW, this.cellsTotalSize);
            if (newObjW < this.cellsTotalSize && numVisCells/2 == Math.floor(numVisCells/2)) newObjW -= this.cellItemsL[0].offsetWidth;
            this.objectWrap.style.width = (newObjW + this.arrowsSizePlusMargin) + "px";
            this.object.style.width = newObjW + "px";
            if (newObjW >= this.cellsTotalSize) {
                objClass("qnr-thumb-strip-nav-left", this.objectWrap).style.visibility = "hidden";
                objClass("qnr-thumb-strip-nav-right", this.objectWrap).style.visibility = "hidden";
            } else {
                objClass("qnr-thumb-strip-nav-left", this.objectWrap).style.visibility = "visible";
                objClass("qnr-thumb-strip-nav-right", this.objectWrap).style.visibility = "visible";
            }
        }
    }

    
    // ----------------------- BUTTON TOGGLE
    
    function ButtonToggleObject() {
        this.object = null;
        this.downCallback = "";
        this.upCallback = "";
        this.events = "yes";
    }
    // Initialize button toggle objects
    ButtonToggleObject.prototype.initialize = function() {
        if (this.object.dataset.qnrButtonToggleDown) this.downCallback = this.object.dataset.qnrButtonToggleDown;
        if (this.object.dataset.qnrButtonToggleUp) this.upCallback = this.object.dataset.qnrButtonToggleUp;
        if (this.object.dataset.qnrButtonToggleEvents) this.events = this.object.dataset.qnrButtonToggleEvents;
        // Set up onclick handlers
        var this1 = this;
        this.object.onclick = function(event) {
            // Toggle up button to down state
            if (!this1.object.classList.contains("qnr-button-toggle-down")) {
                if (this1.object.classList.contains("qnr-button-toggle-up")) this1.object.classList.remove("qnr-button-toggle-up");
                this1.object.classList.add("qnr-button-toggle-down");
                this1.downAction();
            } else if (!this1.object.classList.contains("qnr-button-toggle-up")) { // Toggle down to up state
                if (this1.object.classList.contains("qnr-button-toggle-down")) this1.object.classList.remove("qnr-button-toggle-down");
                this1.object.classList.add("qnr-button-toggle-up");
                this1.upAction();
            }
        };
    }
    ButtonToggleObject.prototype.downAction = function() {
        if (this.events == "yes") this.sendEvent("down");
        eval(this.downCallback);
    }
    ButtonToggleObject.prototype.upAction = function() {
        if (this.events == "yes") this.sendEvent("up");
        eval(this.upCallback);
    }
    ButtonToggleObject.prototype.sendEvent = function(mode) {
        var e = document.createEvent("MessageEvent");
        var message = mode;
        e.initMessageEvent("QIEvent_ButtonToggle", true, true, message, "", "", null, []);
        this.object.dispatchEvent(e);
    }
    
     
    // ----------------------- POPOUT SLIDER
    // TODO: dataset to control button creation, and bg overlay
    
    function PopoutSliderObject() {
        this.object = null;
        this.showButton = null;
        this.hideButton = null;
        this.slideType = "left"; // Default direction of anim
        this.animDuration = 0.4; // Seconds
        this.showCallback = null; // Set by consumer JS or dataset
        this.hideCallback = null;
        this.title = ""; // HTML title attribute on the show button
        this.reset = false; // Reset HTML content of slider on hide
        this.resetObject = ""; // HTML element stored for reset
    }
    PopoutSliderObject.prototype.initialize = function() {
        if (this.object.dataset.qnrPopoutSlideType) this.slideType = this.object.dataset.qnrPopoutSlideType;
        if (this.object.dataset.qnrPopoutAnimDuration) this.animDuration = this.object.dataset.qnrPopoutAnimDuration;
        if (this.object.dataset.qnrPopoutTitle) this.title = this.object.dataset.qnrPopoutTitle;
        if (this.object.dataset.qnrPopoutReset) this.reset = this.object.dataset.qnrPopoutReset;
        if (this.object.dataset.qnrPopoutShowCallback) this.showCallback = this.object.dataset.qnrPopoutShowCallback;
        if (this.object.dataset.qnrPopoutHideCallback) this.hideCallback = this.object.dataset.qnrPopoutHideCallback;
        // Store HTML element for reset, if set so
        if (this.reset) this.resetObject = this.object.cloneNode(true);
        // Create the button to popout the slider, leaving it to CSS to set the content
        this.showButton = document.createElement("button");
        this.showButton.innerHTML = "<span></span>";
        this.showButton.classList.add("qnr-popout-slider-button-show");
        if (this.title) this.showButton.setAttribute("title", this.title);
        var this1 = this;
        this.showButton.onclick = function(event) {
            this1.show();
        }
        this.object.parentNode.insertBefore(this.showButton, this.object);
        // Create close icon on slider, leaving it to CSS to set the content
        this.hideButton = document.createElement("button");
        this.hideButton.innerHTML = "<span></span>";
        this.hideButton.classList.add("qnr-popout-slider-button-hide");
        var this1 = this;
        this.hideButton.onclick = function(event) {
            this1.hide();
        }
        this.object.appendChild(this.hideButton);
    }
    PopoutSliderObject.prototype.show = function() {
        animObj(this.object, this.slideType, "", this.animDuration);
        this.showAction(this.showCallback);
    }
    PopoutSliderObject.prototype.hide = function() {
        animObj(this.object, "back", "", this.animDuration);
        this.hideAction(this.hideCallback);
        // Reset
        window.setTimeout(function(self){
            if (self.reset) {
                var pN = self.object.parentNode;
                pN.removeChild(self.showButton);
                self.object.removeChild(self.hideButton);
                pN.removeChild(self.object);
                self.object = self.resetObject.cloneNode(true);
                pN.appendChild(self.object);
                self.initialize();
            }
        },1000 * this.animDuration, this);
    }
    PopoutSliderObject.prototype.showAction = function(fn) {
        if (this.showCallback) fn();
    }
    PopoutSliderObject.prototype.hideAction = function(fn) {
        if (this.hideCallback) fn();
    }
    
    
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
        this.origSize               = 0; // Original font size
        this.min                    = 80.0;
        this.rangeF                 = 20.0; // Inverse of min
        // The difference between min and max area that is considered
        this.rangeA                 = 1106400;
        this.objMaxWidth            = 0; // If set (integer in px), object bounds will be used instead of whole page
        this.winMaxWidth            = 0; // Win bounds instead
    }
    FontresizeObject.prototype.initialize = function() {
        if (this.object.dataset.qnrFontMin) this.min = parseFloat(this.object.dataset.qnrFontMin);
        if (this.object.dataset.qnrObjMaxWidth) this.objMaxWidth = parseInt(this.object.dataset.qnrObjMaxWidth);
        if (this.object.dataset.qnrWinMaxWidth) this.winMaxWidth = parseInt(this.object.dataset.qnrWinMaxWidth);
        this.origSize = parseFloat(window.getComputedStyle(this.object, "").fontSize.replace("px",""));
        this.rangeF = 100.0 - this.min;
        this.resize();
    }
    FontresizeObject.prototype.resize = function() {
        if (!this.objMaxWidth && !this.winMaxWidth) {
            // Resize according to display area
            var area = objHtml().clientWidth * objHtml().clientHeight;
            if (102400 <= area && area <= 1260000) {
                var delta = 1260000 - area;
                var factor = delta/this.rangeA;
                this.object.style.fontSize = (((100.0 - (this.rangeF * factor))/100.0) * this.origSize) + "px";
            }
            else if (area > 1260000) this.object.style.fontSize = this.origSize + "px";
        } else if (this.objMaxWidth) { // Use object bounds
            if (this.object.offsetWidth < this.objMaxWidth) {
                var factor = this.object.offsetWidth / this.objMaxWidth;
                var newSize = (((100.0 - (this.rangeF * (1.0 - factor)))/100.0) * this.origSize) + "px";
                this.object.style.fontSize = newSize;
            } else this.object.style.fontSize = this.origSize + "px";
        } else if (this.winMaxWidth) { // Use window bounds
            if (objHtml().clientWidth < this.winMaxWidth) {
                var factor = objHtml().clientWidth / this.winMaxWidth;
                var newSize = (((100.0 - (this.rangeF * (1.0 - factor)))/100.0) * this.origSize) + "px";
                this.object.style.fontSize = newSize;
            } else this.object.style.fontSize = this.origSize + "px";
        }
    }
    
    
    // ----------------------- RESPONSIVE
    
    function ResponsiveObject() {
        this.object                 = null;
        this.divItemsL              = [];
    }
    ResponsiveObject.prototype.initialize = function() {
        var objChildren = this.object.children;
        for (var i = 0; i < objChildren.length; i++) {
            objChildren[i].classList.add("qnr-responsive-item");
            this.divItemsL.push(objChildren[i]);
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
            window.setTimeout(function() {
                window.clearInterval(intervalObj);
            }, 1000);
            intervalObj = window.setInterval(function() {
                animCallback(obj, intervalObj, ["window scroll", off]);
            }, 16);
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
        this.navPreviews            = "off"; // Note thumbPreviews also
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
            var error1 = "Carousel requires at least 2 DIV or IMG items.";
            try {error1 = QNRWP_JS_Global.i18n.interface.error1;} catch (e) {}
            print("Error: "+error1);
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
    
    function StickybarObject() { // TODO needs work
        this.object = null;
        this.newObject = null;
        this.madesticky = false;
        this.objYPos = 0;
    }
    StickybarObject.prototype.initialize = function() {
        this.objYPos = getYPos(this.object, 0);
        // Clone object, without child nodes, as placeholder TODO
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
        if (QNR_INTER.scrollerObjectsL && QNR_INTER.scrollerObjectsL.length > 0) {
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
        if (!this.slideDivsL.length > 0) {
            var error2 = "At least one DIV is required for slider widget.";
            try {error2 = QNRWP_JS_Global.i18n.interface.error2;} catch (e) {}
            print("Error: "+error2);
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
            var error3 = "At least two IMG tags required for image animator widget.";
            try {error3 = QNRWP_JS_Global.i18n.interface.error3;} catch (e) {}
            print("Error: "+error3);
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
        this.menuIconStill  = "false"; // Will icon change on toggle?
        this.direction      = "vertical";
        this.menuWrapper    = null;
        this.bgShim         = null; // Shim used for drawer type
        this.navmenuType    = "drawer"; // Or "list"
        this.fixedHeader    = false;
        // Record of window scroll when wrapper shown
        this.winScroll      = 0;
    }
    NavmenuObject.prototype.initialize = function() {
        if (this.object.dataset.qnrMenuIconStill) this.menuIconStill = this.object.dataset.qnrMenuIconStill;
        if (this.object.dataset.qnrNavmenuType) this.navmenuType = this.object.dataset.qnrNavmenuType;
        if (this.object.dataset.qnrDirection) this.direction = this.object.dataset.qnrDirection;
        this.menuUL = this.object.querySelector("ul");
        this.menuItemsL = this.object.querySelectorAll("ul:first-child > li"); // Not a live collection...
        // Get dimensions of first LI item (traversing all does not work)
        this.itemHeight = this.menuItemsL[0].offsetHeight;
        // Is header fixed? Check for special class in doc (used in QNRWP-A on header and content rows)
        if (objClass("qnrwp-has-fixed-header")) this.fixedHeader = true;
        // Style menu, expanded or collapsed
        this.stylemenu();
        // Make the menu DIV visible (from hidden, in CSS file)
        // If collapsed, UL within will not be displayed, until icon is clicked
        this.object.style.visibility = "visible";
    }
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
            // Test for line wrap in nav menu, and collapse it
            if (this.menuItemsL[i].offsetTop >= this.itemHeight) {
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
                    this1.hideVerticalMenu(event);
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
        if (this.navmenuType != "drawer") {
            if (this.fixedHeader && deviceIsMobile()) {
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
    }
    NavmenuObject.prototype.hideVerticalMenu = function(event) {
        if (this.navmenuType != "drawer") {
            if (this.fixedHeader && deviceIsMobile()) {
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
            if (this.fixedHeader && deviceIsMobile()) {
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
                    if (that.fixedHeader && deviceIsMobile()) {
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
    }
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
                //// getYPos required instead of offsetTop
                //if (getYPos(this.menuItemsL[this.menuItemsL.length-1],0) <= 
                                //getYPos(this.object,0) + this.object.offsetHeight + window.pageYOffset) {
                    //this.hideVerticalMenu();
                //}
            //}
        //}
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
        window.setTimeout(function(){
            window.clearInterval(intervalObj);
        }, 1000);
        intervalObj = window.setInterval(function(){
            animCallback(obj, intervalObj, [mode]);
            // Update scrollers, accounting for change of scroll
            // This is the only solution that works
            if (QNR_INTER.scrollerObjectsL.length > 0) {
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
            
            if (clicked.className && clicked.classList.contains("qnr-x-icon-btn")) {
                widget.style.opacity = 0;
                if (widget.className && widget.classList.contains("qnr-remove")) {
                    window.setTimeout(function(){
                        widget.style.display = "none";
                        // Update scrollers, accounting for change of scroll
                        // This is the only solution that works
                        if (QNR_INTER.scrollerObjectsL.length > 0) {
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
            
            if ((widget.nodeName == "DIV" && widget.className && widget.classList.contains("qnr-accordion")) &&
                (clicked.nodeName == "P" && clicked.parentNode.className && clicked.parentNode.classList.contains("qnr-accordion"))) {
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
    
        
        // ----------------------- Thumb Strip
        QNR_INTER.thumbstripsL = classObjs("qnr-thumb-strip");
        if (QNR_INTER.thumbstripsL.length > 0) {
            for (var i = 0; i < QNR_INTER.thumbstripsL.length; i++) {
                // Create a data- id attribute on the thumb strip
                QNR_INTER.thumbstripsL[i].dataset.qnrThumbStripId = i;
                // Create a new JS object for the thumb strip
                QNR_INTER.thumbstripObjectsL.push(new ThumbStripObject());
                QNR_INTER.thumbstripObjectsL[i].object = QNR_INTER.thumbstripsL[i];
                // Initialize object
                QNR_INTER.thumbstripObjectsL[i].initialize();
            }
        }
        
        // ----------------------- Toggle Button
        
        QNR_INTER.btntogglesL = classObjs("qnr-button-toggle");
        if (QNR_INTER.btntogglesL.length > 0) {
            for (var i = 0; i < QNR_INTER.btntogglesL.length; i++) {
                // Create a data- id attribute on the button toggle widget
                QNR_INTER.btntogglesL[i].dataset.qnrButtonToggleId = i;
                // Create a new JS object for the button toggle
                QNR_INTER.btntoggleObjectsL.push(new ButtonToggleObject());
                QNR_INTER.btntoggleObjectsL[i].object = QNR_INTER.btntogglesL[i];
                // Initialize object
                QNR_INTER.btntoggleObjectsL[i].initialize();
            }
        }
        
        
        // ----------------------- Popout Sliders
        QNR_INTER.popoutSlidersL = classObjs("qnr-popout-slider");
        if (QNR_INTER.popoutSlidersL.length > 0) {
            for (var i = 0; i < QNR_INTER.popoutSlidersL.length; i++) {
                // Create a data- id attribute on the popout slider
                QNR_INTER.popoutSlidersL[i].dataset.qnrPopoutSliderId = i;
                // Create a new JS object for the popout slider
                QNR_INTER.popoutSliderObjectsL.push(new PopoutSliderObject());
                QNR_INTER.popoutSliderObjectsL[i].object = QNR_INTER.popoutSlidersL[i];
                // Initialize object
                QNR_INTER.popoutSliderObjectsL[i].initialize();
            }
        }
    
        
        // ----------------------- Aspect Keepers
        QNR_INTER.aspectkeepersL = classObjs("qnr-aspect-keeper");
        if (QNR_INTER.aspectkeepersL.length > 0) {
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
        if (QNR_INTER.arrowanimsL.length > 0) {
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
        if (QNR_INTER.responsivesL.length > 0) {
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
        if (QNR_INTER.winscrollersL.length > 0) {
            for (var i = 0; i < QNR_INTER.winscrollersL.length; i++) {
                // Prevent doubled winscroller by checking for dataset id...
                if (QNR_INTER.winscrollersL[i].dataset.qnrWinscrollerId === undefined) {
                    // Create a data- id attribute on the winscroller
                    QNR_INTER.winscrollersL[i].dataset.qnrWinscrollerId = i;
                    // Create a new JS object for the winscroller
                    QNR_INTER.winscrollerObjectsL.push(new WinscrollerObject());
                    QNR_INTER.winscrollerObjectsL[i].object = QNR_INTER.winscrollersL[i];
                    // Initialize object
                    QNR_INTER.winscrollerObjectsL[i].initialize();
                }
            }
        }
        
        // ----------------------- Carousel JS objects
        
        QNR_INTER.carouselsL = classObjs("qnr-carousel");
        if (QNR_INTER.carouselsL.length > 0) {
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
        if (QNR_INTER.scrollersL.length > 0) {
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
        if (QNR_INTER.slidersL.length > 0) {
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
        if (QNR_INTER.imageanimsL.length > 0) {
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
        if (QNR_INTER.xiconsL.length > 0) {
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
        if (QNR_INTER.accordionsL.length > 0) {
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
        if (QNR_INTER.fontresizesL.length > 0) {
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
            QNR_INTER.navmenuObject.hideVerticalMenu(event);
        }
        
        // ----------------------- X-icon
        
        if (clicked.className && clicked.classList.contains("qnr-x-icon-btn")) {
            widget = clicked.parentNode;
            clickedWidget = true;
        }
        
        // ----------------------- Accordion
        
        else if (widget) {
            // Test if clicked in widget
            while (widget && widget.nodeName != "BODY" && widget.nodeName != "HTML") {
                if (widget.className && widget.classList.contains("qnr-accordion")) {
                    clickedWidget = true;
                    break;
                } else {
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
        if (QNR_INTER.carouselObjectsL.length > 0) {
            for (var i = 0; i < QNR_INTER.carouselObjectsL.length; i++) {
                QNR_INTER.carouselObjectsL[i].pauseCarousel();
            }
        }
    },false);
    
    
    // ----------------------- ONFOCUS
    
    window.addEventListener("focus",function(event){
        if (QNR_INTER.carouselObjectsL.length > 0) {
            for (var i = 0; i < QNR_INTER.carouselObjectsL.length; i++) {
                // Use the scroll pauser to start if not offscreen
                QNR_INTER.carouselObjectsL[i].onScrollCarousel();
            }
        }
    },false);
    
    
    // ----------------------- ONRESIZE
    
    window.addEventListener("resize", function(event) {
        if (QNR_INTER.stickybarObject && QNR_INTER.stickybarObject.madesticky) QNR_INTER.stickybarObject.sizePlaceholder();
        if (QNR_INTER.thumbstripsL.length > 0) {
            for (var i = 0; i < QNR_INTER.thumbstripObjectsL.length; i++) {
                QNR_INTER.thumbstripObjectsL[i].resize();
            }
        }
        if (QNR_INTER.aspectkeepersL.length > 0) {
            for (var i = 0; i < QNR_INTER.aspectkeeperObjectsL.length; i++) {
                QNR_INTER.aspectkeeperObjectsL[i].setHeight();
            }
        }
        if (QNR_INTER.fontresizesL.length > 0) {
            for (var i = 0; i < QNR_INTER.fontresizeObjectsL.length; i++) {
                QNR_INTER.fontresizeObjectsL[i].resize();
            }
        }
        if (QNR_INTER.carouselsL.length > 0) {
            for (var i = 0; i < QNR_INTER.carouselObjectsL.length; i++) {
                QNR_INTER.carouselObjectsL[i].styleCarousel();
            }
        }
        if (QNR_INTER.navmenuObject) QNR_INTER.navmenuObject.stylemenu(); // Must be after font resize, so let it be last
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
            QNR_INTER.navmenuObject.onWinScroll(event);
        }
        
        // ----------------------- Carousels
        if (QNR_INTER.carouselObjectsL.length > 0) {
            for (var i = 0; i < QNR_INTER.carouselObjectsL.length; i++) {
                QNR_INTER.carouselObjectsL[i].onScrollCarousel();
            }
        }
        
        // ----------------------- Scrollers (Parallax)
        if (QNR_INTER.scrollerObjectsL.length > 0) {
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
     * If the hmenu object (the DIV containing the UL) is in a collapsed
     * navmenu (contained in a LI item of the navmenu), hmenu will be 
     * classed as "qnr-hmenu-in-collapsed"
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
                if (this.hoverOpen == "yes" && !deviceIsMobile()) {
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
                        QNR_INTER.navmenuObject.hideVerticalMenu(event);
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
    }
    
    
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
    
    
    if (!deviceIsMobile()) {
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

function deviceIsIOS() {
    var isMobile = /iPhone|iPad|iPod/i.test(navigator.userAgent);
    return isMobile;
}

function browserIsChrome() {
    return /Chrome/i.test(navigator.userAgent);
}

function browserIs(browser) {
    switch (browser){
        case "Firefox":
            return /Firefox/i.test(navigator.userAgent);
            break;
        case "Safari":
            return /Safari/i.test(navigator.userAgent);
            break;
        case "Chrome":
            return /Chrome/i.test(navigator.userAgent);
            break;
        case "Internet Explorer":
            return /MSIE|Trident/i.test(navigator.userAgent);
            break;
        case "MS Edge":
            return /Edge/i.test(navigator.userAgent);
            break;
        case "Opera":
            return /Opera|OPR/i.test(navigator.userAgent);
            break;
        default:
            return false;
    }
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

// Returns Y position of element, with given offset (required)
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

// Returns X position of element, with given offset (required)
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

/* NOTE: a jQuery like 'nodeGet' and 'getNodes' were attempted to replace
 * the following functions, but abandoned because they required regex */

function objHtml() {
    return document.documentElement;
}

function objClass(name, parent) {
    if (!parent) {
        return document.getElementsByClassName(name)[0];
    } else {
        return parent.getElementsByClassName(name)[0];
    }
}

function classObjs(name, parent) {
    if (!parent) {
        return document.getElementsByClassName(name);
    } else {
        return parent.getElementsByClassName(name);
    }
}

function objQuery(query, parent) {
    if (!parent) {
        return document.querySelector(query);
    } else {
        return parent.querySelector(query);
    }
}

function queryObjs(query, parent) {
    if (!parent) {
        return document.querySelectorAll(query);
    } else {
        return parent.querySelectorAll(query);
    }
}

function objID(id, parent) {
    if (!parent) {
        return document.getElementById(id);
    } else {
        return parent.getElementById(id);
    }
}

function objTag(tag, parent) {
    if (!parent) {
        return document.getElementsByTagName(tag)[0];
    } else {
        return parent.getElementsByTagName(tag)[0];
    }
}

function tagObjs(tag, parent) {
    if (!parent) {
        return document.getElementsByTagName(tag);
    } else {
        return parent.getElementsByTagName(tag);
    }
}

function ancestorObj(obj, queryTag, queryClass) {
    // Returns first ancestor of given object, with given tag and/or class
    if (!(queryTag || queryClass) || !obj) return null;
    var ancestor = obj;
    do {
        ancestor = ancestor.parentNode;
        if (ancestor) {
            if (queryTag && !ancestor.tagName(queryTag.toUpperCase())) continue;
            if (!queryClass || (queryClass && ancestor.className && ancestor.classList.contains(queryClass))) {
                return ancestor;
            }
        }
    } while (ancestor);
    return null;
}

// getElementsByName available only on document, not used

//function objName(name, parent) {
    //if (!parent) {
        //return document.getElementsByName(name)[0];
    //} else {
        //return parent.getElementsByName(name)[0];
    //}
//}

//function nameObjs(name, parent) {
    //if (!parent) {
        //return document.getElementsByName(name);
    //} else {
        //return parent.getElementsByName(name);
    //}
//}


// ----------------------- HTML HELPERS

function wrapTag(insideText, outsideHTML) {
    // Returns insideText wrapped between two items of outsideHTML array,
    //   but can also construct closing tag if only opener is supplied,
    //   as string or one-item array
    try {
        var openTag = "";
        var closeTag = "";
        if (objType(outsideHTML) == "string") {
            // Create closing tag from the supplied opener
            openTag = outsideHTML;
            closeTag = "</" + outsideHTML.match(/^<\w+/)[0].slice(1) + ">";
        } else if (objType(outsideHTML) == "array") {
            openTag = outsideHTML[0];
            if (outsideHTML[1] === undefined) closeTag = "</" + outsideHTML.match(/^<\w+/)[0].slice(1) + ">";
            else closeTag = outsideHTML[1];
        }
    } catch (e) {
        var error4 = "Invalid HTML.";
        try {error4 = QNRWP_JS_Global.i18n.interface.error4;} catch (e) {}
        console.error(error4);
    }
    return openTag + insideText + closeTag + "\n";
}


// ----------------------- ERRORS

function sayErrorExit(message) {
    alert(message);
    throw new Error(message);
}

function printErrorExit(message) {
    throw new Error(message);
}


// ----------------------- AJAX

function AjaxSync(url, mode, request, contentTypeL, customHeaderL) {
    // request is query string for GET, data for POST
    // contentTypeL is array of contentType key and value
    // customHeaderL is array of header key and value
    var xhr=new XMLHttpRequest();
    if (mode == "GET") xhr.open("GET", url+request, false);
    else if (mode == "DELETE") xhr.open("DELETE", url+request, false);
    else if (mode == "POST") xhr.open("POST", url, false);
    else if (mode == "PATCH") xhr.open("PATCH", url, false);
    else if (mode == "PUT") xhr.open("PUT", url, false);
    if (contentTypeL && contentTypeL.length > 0) {
        // Headers must be set after open
        xhr.setRequestHeader(contentTypeL[0], contentTypeL[1]);
        //xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
        //xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
    }
    if (customHeaderL && customHeaderL.length > 0) {
        xhr.setRequestHeader(customHeaderL[0], customHeaderL[1]);
    }
    if (mode == "GET" || mode == "DELETE") xhr.send();
    else if (mode == "POST" || mode == "PATCH" || mode == "PUT") xhr.send(request);
    if (xhr.readyState == 4 && xhr.status == 200) {
        return xhr.responseText;
    }
    return "ERROR: " + xhr.responseText;
}

function AjaxAsync(url, mode, request, contentTypeL, customHeaderL, timeOut, cb_timeout, cb_success, cb_error) {
    // request is query string for GET, data for POST
    // contentTypeL is array of contentType key and value
    // customHeaderL is array of header key and value
    // timeOut is in ms
    // cb_timeout, cb_success, cb_error are callback functions for each type of result
    var xhr=new XMLHttpRequest();
    if (mode == "GET") xhr.open("GET", url+request, true);
    else if (mode == "DELETE") xhr.open("DELETE", url+request, true);
    else if (mode == "POST") xhr.open("POST", url, true);
    else if (mode == "PATCH") xhr.open("PATCH", url, true);
    else if (mode == "PUT") xhr.open("PUT", url, true);
    if (contentTypeL && contentTypeL.length > 0) {
        // Headers must be set after open
        xhr.setRequestHeader(contentTypeL[0], contentTypeL[1]);
        //xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
        //xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
    }
    if (customHeaderL && customHeaderL.length > 0) {
        xhr.setRequestHeader(customHeaderL[0], customHeaderL[1]);
    }
    // Callbacks
    xhr.onload = function (e) {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) { // Success
                cb_success(xhr, e);
                //console.log(xhr.responseText);
            } else { // Error
                cb_error(xhr, e);
                //console.error(xhr.statusText);
                //console.error(xhr.responseText);
            }
        }
    };
    xhr.onerror = function (e) {
        cb_error(xhr, e);
        //console.error(xhr.statusText);
    };
    xhr.ontimeout = function () {
        cb_timeout(xhr);
        //console.warn("The request timed out.");
    };
    xhr.timeout = timeOut;
    if (mode == "GET" || mode == "DELETE") xhr.send();
    else if (mode == "POST" || mode == "PATCH" || mode == "PUT") xhr.send(request);
    return 0;
}


// ----------------------- COOKIES

function createOrUpdateCookie(cookieName, cookieValue, cookieDuration, cookieSecure) {
    // cookieName = cookie name
    // cookieValue = cookie value, unencoded
    // cookieDuration = duration of the cookie in seconds
    // cookieSecure = boolean for secure flag
    // Chrome doesn't work with cookies correctly when using local "file:///" protocol, but works fine online
    var cExpiry = cookieDuration ? "; expires="+new Date(Date.now() + (cookieDuration * 1000)).toGMTString() : "";
    var cSecure = cookieSecure ? ";secure" : "";
    document.cookie = cookieName+"="+encodeURIComponent(cookieValue)+cExpiry+"; path=/"+cSecure;
}

function getCookieValue(cookieName) {
    // Returns value of given cookie name, may return empty if no such cookie
    if (document.cookie) {
        var cookiesL = document.cookie.split("; ");
        for (var i = 0; i < cookiesL.length; i++) {
            if (cookiesL[i].split("=")[0] == cookieName) 
                return decodeURIComponent(cookiesL[i].split("=")[1]);
        }
    }
    return "";
}

function deleteCookie(cookieName) { // TODO may not work, missing parameters??
    // Deletes cookie by nulling value and setting expiry in the past
    if (document.cookie) document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
}
  

// ----------------------- GENERIC

function keyValue(obj, key) {
    // Returns value even if obj or key not defined or null, preventing 
    //   errors and avoiding verbose code
    if (obj === undefined || obj[key] === undefined) return undefined;
    else if (obj === null || obj[key] === null) return null;
    else return obj[key];
}

function objContainsValue(obj, val) {
    // Returns number of times value appears in object, may be treated as boolean for yes/no
    // ES6 equivalent to this function: Object.values(obj).includes(val)
    return Object.keys(obj).filter(function(i){return obj[i]==val;}).length;
}

function objType(obj) {
    // Returns type of object, more accurate than typeof and instanceof
    // This won't work if the object (rather than its member) is undefined, an error will result
    try {var rT = Object.prototype.toString.call(obj).split(" ")[1].slice(0,-1).toLowerCase();}
    catch (e) {var rT = "undefined";}
    return rT;
}

function microtime() {
    // Returns microseconds, a combination of milliseconds and a random number
    return parseInt(Date.now().toString()+(Math.random()*100000).toString().split(".")[0]);
}


// ----------------------- ANIMATION

function animObj(obj, slideType, fadeType, animDuration) {
    // Animates passed element, according to animation parameters
    // slideType = up/down/left/right/back
    // fadeType = in/out
    // animDuration = seconds
    obj.style.webkitAnimationDuration = animDuration + "s"; // iPhone
    obj.style.animationDuration = animDuration + "s";
    
    if (slideType == "up") {
        if (obj.className) obj.classList.remove("qnr-anim-popout-up-back");
        obj.classList.add("qnr-anim-popout-up");
    } else if (slideType == "down") {
        if (obj.className) obj.classList.remove("qnr-anim-popout-down-back");
        obj.classList.add("qnr-anim-popout-down");
    } else if (slideType == "left") {
        if (obj.className) obj.classList.remove("qnr-anim-popout-left-back");
        obj.classList.add("qnr-anim-popout-left");
    } else if (slideType == "right") {
        if (obj.className) obj.classList.remove("qnr-anim-popout-right-back");
        obj.classList.add("qnr-anim-popout-right");
    } else if (slideType == "back") {
        if (obj.className && obj.classList.contains("qnr-anim-popout-up")) {
            obj.classList.remove("qnr-anim-popout-up");
            obj.classList.add("qnr-anim-popout-up-back");
        } else if (obj.className && obj.classList.contains("qnr-anim-popout-down")) {
            obj.classList.remove("qnr-anim-popout-down");
            obj.classList.add("qnr-anim-popout-down-back");
        } else if (obj.className && obj.classList.contains("qnr-anim-popout-left")) {
            obj.classList.remove("qnr-anim-popout-left");
            obj.classList.add("qnr-anim-popout-left-back");
        } else if (obj.className && obj.classList.contains("qnr-anim-popout-right")) {
            obj.classList.remove("qnr-anim-popout-right");
            obj.classList.add("qnr-anim-popout-right-back");
        }
    }
    if (fadeType == "in") {
        if (obj.className) obj.classList.remove("qnr-anim-fade-out");
        obj.classList.add("qnr-anim-fade-in");
    } else if (fadeType == "out") {
        if (obj.className) obj.classList.remove("qnr-anim-fade-in");
        obj.classList.add("qnr-anim-fade-out");
    }
}



