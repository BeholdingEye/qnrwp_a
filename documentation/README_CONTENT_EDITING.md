
CONTENT EDITING
===============

Table of Contents:
-----------------

* [Shortcodes](#s)

    :   [Include](#s1)

    :   [Contact form](#s2)
    
    :   [Carousel](#s3)
    
    :   [Featured Image](#s4)
    
    :   [Menu](#s5)
    
    :   [Samples](#s6)

* [Widgets](#w)

    :   [Visibility](#w1)
    
    :   [QNRWP Custom Widget](#w2)
    
        - Carousel
        
        - SubHeader

    :   [QNRWP Featured News](#w3)


Shortcodes                                                          {#s}
----------

### Include                                                        {#s1}

The "include" shortcode features two modes, "file" and "post", activated
by shortcode attributes of the same names.

In "file" mode, the only attribute that will be considered is "file", 
pointing to a file path of a PHP file that will be executed and its 
output placed in place of the shortcode. The path must be relative to 
the child theme directory. Example:

    [include file="include-parts/test.php"]

In "post" mode, three attributes are supported:

* post: ID number of the post to include

* layout: comma-separated string list of...
    - title
    - date
    - excerpt
    - content
    
    ...that will include the named post data components in the order
    specified. Hooks are provided for their filtering by child theme.

* class: CSS class to add to the wrapping DIV of the included HTML.

Example:

    [include post="21" layout="title,date,content" class="my-class"]


### Contact form                                                   {#s2}

The "contact-form" shortcode enables creation of any type of contact 
form.

The form is styled by CSS in the "contact.css" file in the "res/css" 
directory of the theme, and may be overriden in child theme.

The form will send emails to the admin email address, from
"wordpress@domain.com", and the Reply-To header will be set to the email 
provided by the form user.

The form features strong security: page sessions last 15 minutes, secure
cookies include tracking by IP and browser user agent, mailings are 
limited to one message per form every 5 minutes for one client, with a 
maximum limit of 11 mailings every 30 minutes for the whole site.

All parameters of the form may be controlled with shortcode attributes.
The attributes must not contain characters that are not valid in HTML 
attributes: <>'"&, will throw an error.

All form fields except email address may be omitted (useful for 
subscriptions).

Example usage:

    [contact-form warnings="no"]

Attributes and their default settings:

* subject
    :   yes
    
    *Subject field*


* message
    :   yes
    
    *Message field*


* warnings
    :   yes
    
    *Warnings under message: IP number and text field characters count*


* autofocus
    :   yes
    
    *Autofocus in email or name input*


* name
    :   no
    
    *Name field*


* title
    :   no
    
    *Title for the form*


* intro
    :   no
    
    *Introductory paragraph for the form*


* tooltips
    :   no
    
    *Help tooltips for the form fields*


* title-text
    :   Contact Form
    
    *Text for the form title*


* intro-text
    :   Send us a message and we will respond as soon as possible.
    
    *Text for the introductory paragraph*


* label-email
    :   Your email
    
    *Text for the email address label*


* label-name
    :   Your name
    
    *Text for the name label*


* label-subject
    :   Subject
    
    *Text for the subject label*


* label-message
    :   Message
    
    *Text for the message label*


* label-submit
    :   Send
    
    *Text for the submit button label*


* placeholder-email
    :   you@domain.com
    
    *Text for the email address placeholder*


* placeholder-name
    :   First Last
    
    *Text for the name placeholder*


* placeholder-subject
    :   Enquiry from a website visitor
    
    *Text for the subject placeholder*


* placeholder-message
    :   ''
    
    *Text for message placeholder*


* sent-reply
    :   Your message has been sent. You should receive a reply within 2 
        working days.
    
    *Text for the reply shown to user on send*


* fail-reply
    :   Sorry, your message could not be sent.
    
    *Text for the reply shown to user on failure*


* form-class
    :   contact-form
    
    *CSS class for the form. Should be unique to each form on the page, 
    as hex value of it will be used to id / block repeats*


### Carousel                                                       {#s3}

The "carousel" shortcode uses the Quicknr Interface Javascript carousel
widget. Two attributes are supported in the shortcode:

* name:

    :   The title of a Page defining the parameters of the carousel. The
        title must be "QNRWP-Widget-Carousel-XXXX" where the "XXXX" part
        is the uniquely identifying string for the particular carousel
        instance.
        
        The page content must be a "{carousel-options}" string that
        contains lines of HTML attributes specifying the parameters. The
        attributes are documented in the "res/js/qnr-interface.js" file.
        Comments starting with "//" are supported.
        
        Example:
        
            {carousel-options // HTML attribute format
            data-qnr-arrow-type="big" // regular, big, sticky
            data-qnr-mode="fade" // fade, slideboth, slideover, slidefade
            data-qnr-resume-auto="on"
            data-qnr-interval="6"
            data-qnr-transition="2"
            data-qnr-previews="on"
            data-qnr-scroller="on"
            style="height:400px;width:100%;margin:1em auto;"
            class="qnr-aspect-keeper"
            data-qnr-aspect-ratio="1.3"
            }
        
        
        Child pages of the defining page will be slides of the carousel,
        with the Featured Image assigned to the child page used as the
        background image for the slide.
        
        The carousel defining page and its children must be published as
        Private.
        
        It is good practice to set the Order of the carousel defining
        page to a high value such as 9999 to push these pages to the
        bottom of the list on the Pages page.

* size:

    :   Name of a registered image size: one of "thumbnail", "medium", 
        "medium_large", "large", "full", "qnrwp-larger", 
        "qnrwp-largest", "qnrwp-extra". The chosen size will be the 
        largest shown, responsively adjusting down to screen size.


### Featured Image                                                 {#s4}

The "featured-image" shortcode will place the Featured Image assigned to
the post in its place. One parameter is supported, "size" with the same
range of values as the carousel size attribute. Example:

    [featured-image size="large"]


### Menu                                                           {#s5}

The "menu" shortcode will place a WP menu, using three parameters:

* name:

    : Name of the menu

* id:

    : ID for the menu container

* depth:

    : Depth of submenu levels

Example:

    [menu name='Test_Menu_Flat' id='test-menu-3' depth='-1']


### Samples                                                        {#s6}

The "samples" shortcode will place Material Design cards, with an image,
title, short description, and a link to the sample. An additional link 
to a news post about the work can also be set. With custom CSS styling, 
presentation may be changed as needed.

Samples are created as posts, and distinguished by their assigned 
category. Custom Fields are used for the links, while the displayed 
image is the post’s assigned Featured Image.

The content of the sample post will be the description in the card, 
while the link to the info about the sample may be set in a custom field
named "Sample-Info". The link to the actual sample to be set in the
"Sample-Link" custom field.

If more samples matching the specified categories are available, a 
"Load more" button will appear.

Supported shortcode parameters:

* name:

    :   Title of the samples panel

* size:

    :   Image size name

* number:

    :   Number of cards to show. For best display, let this be a 
        multiple of 6 (divisible by 3 and 2)

* categories:

    :   Comma-separated string of post categories to present as samples

Example:

    [samples name="Samples" size="medium_large" number=6 
    categories="sample-work"]


Widgets                                                             {#w}
-------

### Visibility                                                     {#w1}

Widgets in this theme feature an easy-to-use visibility interface,
checkboxes for the pages where the widget output should appear. If no
widgets in a sidebar are to appear on a page, that sidebar will not 
render and the layout will adjust.

This feature may conflict with similar third-party functionality, such
as the Jetpack plugin, and can therefore be turned off in the theme
preferences. Hooks are provided for the coding of alternative handling
of widget visibility in the child theme.

### QNRWP Custom Widget                                            {#w2}

Enables the selection of a custom widget to display. Custom widgets are 
defined in pages titled "QNRWP-Widget-WidgetType-XXXX", where 
"WidgetType" is either "Carousel" or "SubHeader", and the "XXXX" part 
is the unique identifier of the particular widget instance.
        
* Carousel:

    :   The system of pages defining the carousel is the same as
        used for the shortcode. The advantage of displaying the
        carousel via the widget is that it can be set to appear
        in a sidebar, on a number of pages, rather than in the
        content of a specific page.
        
        The widget implementation lacks the size attribute of the 
        shortcode. The size will be set to the max of 2000px width,
        scaling down responsively to screen size.
        
        If the carousel will be much smaller than the browser window
        width, you may get better results using the shortcode with a 
        smaller size attribute, perhaps in a Custom HTML widget.

* SubHeader:

    :   The SubHeader widget is defined similarly to carousels, with a
        "{subheader-options}" string containing lines of HTML attributes
        controlling the appearance of the subheader. Example:
        
            {subheader-options // HTML attribute format
            class="qnr-aspect-keeper qnr-scroller" // Make it parallax
            data-qnr-aspect-ratio="3.8"
            }
        
        Child pages of the widget defining page should be named the same
        as the pages on which the widget is to appear. Featured Images
        of the child pages will be used for the background image in the
        subheader, and the child page content will be the subheader
        content. The Featured Image of the widget defining page will be
        used as the subheader image on any page not represented by a 
        child page.
        
        The SubHeader defining page and its children must be published 
        as Private.

### QNRWP Featured News                                            {#w3}

This widget will display a row of 4 cards of latest news posts that have 
a Featured Image assigned. The description part of the card will be
derived from the first 110 characters of the post's excerpt if it is 
set, or from the post's first paragraph.
