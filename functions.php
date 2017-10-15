<?php
/**
 * QNRWP-A functions.php
 */

// Load up functions-admin.php if on an Admin page
if (is_admin()) require_once('functions-admin.php');


// ===================== GENERIC FUNCTIONS =====================

// ----------------------- Post Thumbnail URL getter

//function qnrwp_get_post_thumbnail_url($thumbHtml) { // NO LONGER USED
  //// Get URL from IMG src attribute for CSS background styling
  //// TODO get size
  //$mm = preg_match('@src="(https?://[^\"]+)"@i', $thumbHtml, $matches);
  //if ($mm) {
    //return $matches[1];
  //}
  //else return '';
//}

function qnrwp_debug_printout($valueToPrint, $append=false) { // Append must be false...
  // Print any variable to the debug-printout.txt file
  $debugPrintFile = trailingslashit(dirname(__FILE__)) . 'debug-printout.txt';
  $dpfSize = filesize($debugPrintFile);
  $tv = print_r($valueToPrint, $return=true) . PHP_EOL;
  // Limit printout to max 5000 chars
  if (strlen($tv) > 5000) $tv = 'DEBUG PRINTOUT ERROR: Length limit exceeded.';
  // Limit printout text file size to 9K
  if ($dpfSize !== false && $dpfSize < 10000) {
    if ($append) file_put_contents($debugPrintFile, $tv, FILE_APPEND);
    else {
      //unlink(trailingslashit(dirname(__FILE__)) . 'debug-printout.txt'); // Needed
      file_put_contents($debugPrintFile, $tv);
    }
  } // Else do nothing
}

// ===================== TEST PRINTOUT ===================== TODO
// Throws a circular reference error for var_export on News page

//$GLOBALS['actionsAndFilters'] = '';
///* Hook to the 'all' action */
//function backtrace_filters_and_actions() {
  ///* The arguments are not truncated, so we get everything */
  //$arguments = func_get_args();
  //$tag = array_shift($arguments); /* Shift the tag */

  ///* Get the hook type by backtracing */
  //$backtrace = debug_backtrace();
  //$hook_type = $backtrace[3]['function'];
  
  //$GLOBALS['actionsAndFilters'] .= "<i>$hook_type</i> <b>$tag</b>\n";
  //foreach ( $arguments as $argument ) {
    //$GLOBALS['actionsAndFilters'] .= "\t\t" . htmlentities(var_export($argument, true)) . "\n";
  //}
  //$GLOBALS['actionsAndFilters'] .= "\n";
//}
//add_action('all', 'backtrace_filters_and_actions');



// ----------------------- Layout type getter

function qnrwp_get_layout() {
  // Get layout type according to active sidebars
  $layout = 'single';
  if (is_active_sidebar('qnrwp-sidebar-1') && is_active_sidebar('qnrwp-sidebar-2')) $layout = 'three-cols';
  else if (is_active_sidebar('qnrwp-sidebar-1')) $layout = 'left-sidebar';
  else if (is_active_sidebar('qnrwp-sidebar-2')) $layout = 'right-sidebar';
  return $layout;
}


// ----------------------- Interpret user boolean input, usually string

function qnrwp_interpret_bool_input($bool) {
  if (strtolower($bool) === 'yes' || strtolower($bool) === 'y' 
                                  || strtolower($bool) === 'true' 
                                  || $bool === '1' 
                                  || $bool === 1 
                                  || $bool === true) return true;
  else return false;
}


// ----------------------- Contact Form generator, called by shortcode

function qnrwp_contact_form($options) {
  // $options = array('subject','subject-line','message','name','warnings' etc.)
  // Convert string boolean inputs into booleans (subject-line etc. not boolean)
  $subjectBool = qnrwp_interpret_bool_input($options['subject']);
  $messageBool = qnrwp_interpret_bool_input($options['message']);
  $nameBool = qnrwp_interpret_bool_input($options['name']);
  $warningsBool = qnrwp_interpret_bool_input($options['warnings']);
  $emailSent = false;
  $emailSendAttempted = false;
  // Test for adequate input to proceed
  if ($_SERVER['REQUEST_METHOD'] === 'POST' 
        && isset($_POST['email']) 
        && isset($_POST['form-name-hidden'])
        && isset($_POST['client-ip-hidden'])
        && (($messageBool && isset($_POST['message'])) || !$messageBool)) {
    $emailSendAttempted = true;
    
    // If data won't validate, $emailSent will remain false
    // Using 'name' as an input name doesn't work, so we use 'emailname'
    if (strlen($_POST['email']) < 100 
          && is_email($_POST['email']) 
          && strlen(get_bloginfo('admin_email')) < 100 
          && is_email(get_bloginfo('admin_email')) 
          && ((isset($_POST['emailname']) && strlen($_POST['emailname']) < 100) || !isset($_POST['emailname'])) 
          && ((isset($_POST['subject']) && strlen($_POST['subject']) < 100) || !isset($_POST['subject'])) 
          && (($messageBool && strlen($_POST['message']) < 501) || !$messageBool)) {
      // Construct the message
      $emailAddress = $_POST['email'];
      $emailSubject = ($subjectBool && isset($_POST['subject'])) ? $_POST['subject'] : '';
      $emailMessage = ($messageBool && isset($_POST['message'])) ? $_POST['message'] : '';
      $emailName = ($nameBool && isset($_POST['emailname'])) ? sanitize_text_field($_POST['emailname']) : '';
      // If no subject, pass the generic one constructed in caller or specified in shortcode
      if (!$emailSubject) $emailSubject = $options['subject-line'];
      // If no message, construct generic one, only email submitted, probably for subscription
      if (!$emailMessage) {
        $emailMessage = 'Email contact details submitted via online form:' . PHP_EOL . PHP_EOL;
        if ($emailName) $emailMessage .= 'Name: ' . $emailName . PHP_EOL . PHP_EOL;
        $emailMessage .= 'Email address: ' . $emailAddress;
      } else if ($emailName) { // Place the name in the message, for more reliable visibility than only in Reply-To
        $emailMessage = 'Message from "' . $emailName . '":' . PHP_EOL . PHP_EOL . $emailMessage;
      }
      $mT = $emailMessage . PHP_EOL . PHP_EOL;
      // Append a note about the message source
      $mT .= '=========='.PHP_EOL;
      $mT .= 'Message sent from IP '.$_POST['client-ip-hidden'].', '.PHP_EOL;
      $mT .= 'using the online contact form at '.PHP_EOL;
      $mT .= '<'. get_permalink().'>.'.PHP_EOL;
      $mT .= '=========='.PHP_EOL;
      // Set Reply-To header to user's email, not WP
      if ($emailName && !preg_match('/[^a-zA-Z\. -]+/',$emailName)) $headers = array('Reply-To: '.$emailName.' <'.$emailAddress.'>');
      else $headers = array('Reply-To: <'.$emailAddress.'>');
    
      // Check transient records for recent mailings
      // Control mailing times sitewide
      $tlmsOldRecord = get_transient('qnrwp_last_mailing_sitewide');
      if ($tlmsOldRecord !== false) { // Transient record exists, not expired, disallow email
        $sitewideBlockingMinutesRemaining = max(0, 5 - intval(ceil((time() - $tlmsOldRecord)/60)));
      }
      if (!isset($sitewideBlockingMinutesRemaining) || !$sitewideBlockingMinutesRemaining) {
        // Send the email
        $emailSent = wp_mail(get_bloginfo('admin_email'), $emailSubject, $mT, $headers);
        set_transient('qnrwp_last_mailing_sitewide', time(), 5 * MINUTE_IN_SECONDS);
      }
    }
  } else { // Not sending email, but showing the form
    // Set global count of contact forms, to set unique form names and IDs
    if (isset($GLOBALS['QNRWP_GLOBALS']['ContactFormsCount'])) $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount'] += 1;
    else $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount'] = 1;
  }
  ?>
<?php if (!$emailSent && !$emailSendAttempted): // Show the form if not submitted yet ?>
<div class="contact-block <?php echo $options['form-class']; ?>">
  <form name="<?php echo 'contact-form' . $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount']; ?>" 
        id="<?php echo 'contact-form' . $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount']; ?>" 
        action="<?php echo get_permalink(); ?>" method="post">
    <input type="hidden" name="form-name-hidden" value="<?php echo 'contact-form' . $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount']; ?>">
    <input type="hidden" name="client-ip-hidden" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
    <div class="contact-emailframe contact-subframe">
      <span class="label label-email">Your email address:</span>
      <input type="email" name="email" class="email user-entry" maxlength="60" required autofocus>
    </div>
<?php if ($nameBool): // Must use 'emailname' instead of 'name'... ?>
    <div class="contact-nameframe contact-subframe">
      <span class="label label-name">Your name:</span>
      <input type="text" name="emailname" class="name user-entry" maxlength="60">
    </div>
<?php endif; ?>
<?php if ($subjectBool): ?>
    <div class="contact-subjectframe contact-subframe">
      <span class="label label-subject">Subject:</span>
      <input type="text" name="subject" class="subject user-entry" maxlength="60" placeholder="<?php echo $options['subject-line']; ?>">
    </div>
<?php endif; ?>
<?php if ($messageBool): ?>
    <div class="contact-textframe contact-subframe">
      <span class="label label-message">Message:</span>
      <textarea pattern=".{40,500}" required cols="20" name="message" class="message user-entry" rows="10" minlength="40" maxlength="500" onkeyup="CountTextarea(this,'count')" onblur="CountTextarea(this,'reset')"></textarea>
<?php if ($warningsBool): ?>
<p class="user-info"><span class="textarea-count">Max 500 characters</span><span class="client-ip">Your IP: <?php echo $_SERVER['REMOTE_ADDR']; ?></span>
</p>
<?php endif; ?>
    </div>
<?php endif; ?>
    <div class="contact-send-btn">
      <button type="submit" name="form-send" class="form-send" value="Send" onclick="SendEmail(event)">Send</button>
    </div>
  </form>
<?php endif; // End of outermost test for not email sent nor attempted ?>
<?php
if ($emailSendAttempted && !$emailSent) {
  // Instruct number of minutes to wait if too many attempts sitewide
  if (isset($sitewideBlockingMinutesRemaining) && $sitewideBlockingMinutesRemaining) {
    // Call it a subscription if no message field present
    $messOrSub = $messageBool ? 'message' : 'subscription';
    $minutesInResponse = ($sitewideBlockingMinutesRemaining == 1) ? ' minute' : ' minutes';
    echo '<div class="contact-block fail-reply '.$options['form-class'].'">'.PHP_EOL
          .'Sorry, due to high level of traffic on the site your '.$messOrSub.' could not be sent. '
          .'Please try again in '.$sitewideBlockingMinutesRemaining.$minutesInResponse.'.'.PHP_EOL;
  } else { // Some other failure
    echo '<div class="contact-block fail-reply '.$options['form-class'].'">'.PHP_EOL.$options['fail-reply'].PHP_EOL;
  }
}
else if ($emailSendAttempted && $emailSent) 
  echo '<div class="contact-block sent-reply '.$options['form-class'].'">'.PHP_EOL.$options['sent-reply'].PHP_EOL;
if ($emailSendAttempted || $emailSent) {
  // In either of the above cases, provide a close box on the block, to return to page sans POST
  echo '<a href="'.get_permalink().'"><span class="qnr-glyph qnr-glyph-circle-xmark"></span></a>';
}
?>
</div>
  <?php
} // End of QNRWP Contact Form function


// ----------------------- Meta, OpenGraph, Twitter card tags generator

function qnrwp_meta_opengraph_twitter_tags() {
  $rHtml = '';
  if (get_option('qnrwp_use_meta_tags')) {
    // ----------------------- Description
    // Get description from first paragraph if news post
    if (is_singular() && get_post_type() == 'post') {
      $postFirstPara = qnrwp_get_news_first_para_excerpt();
      if ($postFirstPara) $rHtml .= '<meta name="description" content="'.esc_attr(trim($postFirstPara)).'">'.PHP_EOL;
      else $rHtml .= '<meta name="description" content="'.esc_attr(trim(get_bloginfo('description'))).'">'.PHP_EOL;
    } else { // Other pages get description from settings option or site tagline
      if (get_option('qnrwp_meta_description')) 
        $rHtml .= '<meta name="description" content="'.esc_attr(trim(get_option('qnrwp_meta_description'))).'">'.PHP_EOL;
      else
        $rHtml .= '<meta name="description" content="'.esc_attr(trim(get_bloginfo('description'))).'">'.PHP_EOL;
    }
    // ----------------------- Keywords
    if (get_option('qnrwp_meta_keywords')) 
      $rHtml .= '<meta name="keywords" content="'.esc_attr(trim(get_option('qnrwp_meta_keywords'))).'">'.PHP_EOL;
    // ----------------------- Author
    if (get_option('qnrwp_meta_author')) 
      $rHtml .= '<meta name="author" content="'.esc_attr(trim(get_option('qnrwp_meta_author'))).'">'.PHP_EOL;
  }
    
  //<!-- Open Graph -->
  //<meta name="og:title" content="">
  //<meta name="og:description" content="">
  //<meta name="og:type" content="website">
  //<meta name="og:site_name" content="">
  //<meta name="og:url" content="">
  //<meta name="og:image" content="">
  if (get_option('qnrwp_use_opengraph_tags')) {
    $rHtml .= '<!-- Open Graph -->'.PHP_EOL;
    $genericOG = false; // Avoid some code duplication later
    // News post; ensure title matches post only if excerpt obtained
    if (is_singular() && get_post_type() == 'post') {
      if (!isset($postFirstPara) || empty($postFirstPara)) $postFirstPara = qnrwp_get_news_first_para_excerpt();
      if ($postFirstPara) {
        $rHtml .= '<meta name="og:title" content="'.esc_attr(trim(single_post_title('', $display=false))).'">'.PHP_EOL;
        $rHtml .= '<meta name="og:description" content="'.esc_attr(trim($postFirstPara)).'">'.PHP_EOL;
      }
      else { // Both title and description generic together
        $genericOG = true;
      }
    } else { // Other pages get title and description from settings option, meta tag, or site tagline
      $genericOG = true;
    }
    if ($genericOG) {
      // ----------------------- Title
      if (get_option('qnrwp_opengraph_title')) 
        $rHtml .= '<meta name="og:title" content="'.esc_attr(trim(get_option('qnrwp_opengraph_title'))).'">'.PHP_EOL;
      else
        $rHtml .= '<meta name="og:title" content="'.esc_attr(trim(get_bloginfo('name'))).'">'.PHP_EOL;
      // ----------------------- Description
      if (get_option('qnrwp_opengraph_description')) 
        $rHtml .= '<meta name="og:description" content="'.esc_attr(trim(get_option('qnrwp_opengraph_description'))).'">'.PHP_EOL;
      else if (get_option('qnrwp_meta_description')) 
        $rHtml .= '<meta name="og:description" content="'.esc_attr(trim(get_option('qnrwp_meta_description'))).'">'.PHP_EOL;
      else 
        $rHtml .= '<meta name="og:description" content="'.esc_attr(trim(get_bloginfo('description'))).'">'.PHP_EOL;
    }
    // Site type, and name and URL, always from Site Title and URL settings (custom for news posts)
    $rHtml .= '<meta name="og:type" content="website">'.PHP_EOL;
    $rHtml .= '<meta name="og:site_name" content="'.esc_attr(trim(get_bloginfo('name'))).'">'.PHP_EOL;
    // ----------------------- Page and image URLs, generic or post Custom Field or Featured Image
    if (is_singular() && get_post_type() == 'post') {
      $rHtml .= '<meta name="og:url" content="'.esc_attr(get_permalink()).'">'.PHP_EOL;
      if (get_post_custom_values('OpenGraph-Twitter-Card-Image'))
        $rHtml .= '<meta name="og:image" content="'.esc_attr(esc_url(trim(get_post_custom_values('OpenGraph-Twitter-Card-Image')[0]))).'">'.PHP_EOL;
      else if (has_post_thumbnail())
        $rHtml .= '<meta name="og:image" content="'.esc_attr(get_the_post_thumbnail_url(null, $size='large')).'">'.PHP_EOL;
      else if (get_option('qnrwp_opengraph_imageurl')) 
        $rHtml .= '<meta name="og:image" content="'.esc_attr(trim(get_option('qnrwp_opengraph_imageurl'))).'">'.PHP_EOL;
    } else {
      $rHtml .= '<meta name="og:url" content="'.esc_attr(trim(get_bloginfo('url'))).'">'.PHP_EOL;
      if (get_option('qnrwp_opengraph_imageurl')) 
        $rHtml .= '<meta name="og:image" content="'.esc_attr(trim(get_option('qnrwp_opengraph_imageurl'))).'">'.PHP_EOL;
    }
  }
  
  //<!-- Twitter Card -->
  //<meta name="twitter:card" content="summary">
  //<!-- alernative content: summary_large_image -->
  //<meta name="twitter:site" content="">
  //<meta name="twitter:title" content="">
  //<meta name="twitter:description" content="">
  //<meta name="twitter:image" content="">
  if (get_option('qnrwp_use_twitter_tags')) {
    $rHtml .= '<!-- Twitter Card -->'.PHP_EOL;
    $rHtml .= '<meta name="twitter:card" content="summary">'.PHP_EOL;
    if (get_option('qnrwp_twitter_site')) 
      $rHtml .= '<meta name="twitter:site" content="'.esc_attr(trim(get_option('qnrwp_twitter_site'))).'">'.PHP_EOL;
  
    $genericTW = false; // Avoid some code duplication later
    // News post; ensure title matches post only if excerpt obtained
    if (is_singular() && get_post_type() == 'post') {
      if (!isset($postFirstPara) || empty($postFirstPara)) $postFirstPara = qnrwp_get_news_first_para_excerpt();
      if ($postFirstPara) {
        $rHtml .= '<meta name="twitter:title" content="'.esc_attr(trim(single_post_title('', $display=false))).'">'.PHP_EOL;
        $rHtml .= '<meta name="twitter:description" content="'.esc_attr(trim($postFirstPara)).'">'.PHP_EOL;
      }
      else { // Both title and description generic together
        $genericTW = true;
      }
    } else { // Other pages get title and description from settings option, meta tag, or site tagline
      $genericTW = true;
    }
    if ($genericTW) {
      // ----------------------- Title
      if (get_option('qnrwp_twitter_title')) 
        $rHtml .= '<meta name="twitter:title" content="'.esc_attr(trim(get_option('qnrwp_twitter_title'))).'">'.PHP_EOL;
      else
        $rHtml .= '<meta name="twitter:title" content="'.esc_attr(trim(get_bloginfo('name'))).'">'.PHP_EOL;
      // ----------------------- Description
      if (get_option('qnrwp_twitter_description')) 
        $rHtml .= '<meta name="twitter:description" content="'.esc_attr(trim(get_option('qnrwp_twitter_description'))).'">'.PHP_EOL;
      else if (get_option('qnrwp_meta_description')) 
        $rHtml .= '<meta name="twitter:description" content="'.esc_attr(trim(get_option('qnrwp_meta_description'))).'">'.PHP_EOL;
      else 
        $rHtml .= '<meta name="twitter:description" content="'.esc_attr(trim(get_bloginfo('description'))).'">'.PHP_EOL;
    }
    // ----------------------- Image URLs, generic or post Custom Field or Featured Image
    if (is_singular() && get_post_type() == 'post') {
      if (get_post_custom_values('OpenGraph-Twitter-Card-Image'))
        $rHtml .= '<meta name="twitter:image" content="'.esc_attr(esc_url(trim(get_post_custom_values('OpenGraph-Twitter-Card-Image')[0]))).'">'.PHP_EOL;
      else if (has_post_thumbnail())
        $rHtml .= '<meta name="twitter:image" content="'.esc_attr(get_the_post_thumbnail_url(null, $size='large')).'">'.PHP_EOL;
      else if (get_option('qnrwp_twitter_imageurl')) 
        $rHtml .= '<meta name="twitter:image" content="'.esc_attr(trim(get_option('qnrwp_twitter_imageurl'))).'">'.PHP_EOL;
    } else if (get_option('qnrwp_twitter_imageurl')) {
      $rHtml .= '<meta name="twitter:image" content="'.esc_attr(trim(get_option('qnrwp_twitter_imageurl'))).'">'.PHP_EOL;
    }
  }
  return $rHtml;
}


//function qnrwp_get_subheader_htmlOLD($widgetDefPageID) {
  //// $widgetDefPageID - ID of the page defining the subheader
  
  //// Get subheader attributes from the defining page
  //$subheaderAttributes = '';
  //// No validation, we trust the settings text to be untampered
  //$rawS = get_post_field('post_content', $widgetDefPageID);
  //if (stripos($rawS, '{subheader-options') !== false) { // TODO make it JSON compliant and use functions
    //$rawS = preg_replace('@\s*//.*?(?:\n+|$)@i', ' ', $rawS); // Remove comments
    //$rawS = preg_replace('@\s*\{subheader-options\s*@i', '', $rawS); // Remove "{subheader=options"
    //$rawS = preg_replace('@\s*\}@i', '', $rawS); // Remove ending "}"
    //$rawS = preg_replace('@\s+@i', ' ', $rawS); // Convert whitespace to a space
    //$subheaderAttributes = ' '.$rawS; // Add a space to place attributes in tag
  //}
  
  //$rHtml = '';
  //$shOptionsL = array(); // Array of page name => image URL
  //$shOptionsL['*'] = ''; // Avoid an error later if key/value not set
  //$shOptionsL['News'] = ''; // Likewise, prefer no-URL if News not present
  
  //// Get Featured Image from definition page, as default for pages not defined by child pages
  //if (has_post_thumbnail($widgetDefPageID)) {
    //$thumbHtml = get_the_post_thumbnail($widgetDefPageID, 'qnrwp-largest'); // Largest theme size TODO
    //$shOptionsL['*'] = qnrwp_get_post_thumbnail_url($thumbHtml);
    ////// TEST TODO
    ////qnrwp_debug_printout(wp_get_attachment_metadata(get_post_thumbnail_id($widgetDefPageID)));
  //}
  
  //// Get child pages and their Featured Images
  //$widgetChildren = get_page_children($widgetDefPageID, get_pages());
  //if (count($widgetChildren) > 0) {
    //foreach ($widgetChildren as $widgetChild) {
      //// Store name and img URL as key => value
      //if (has_post_thumbnail($widgetChild)) {
        //$thumbHtml = get_the_post_thumbnail($widgetChild, 'qnrwp-largest');
        //$shOptionsL[$widgetChild->post_title] = qnrwp_get_post_thumbnail_url($thumbHtml);
      //}
    //}
  //}

  //if ($GLOBALS['isNews']) { // Create News header, for all News pages
    //$headerTitleText = 'News';
    //$headerURL = $shOptionsL['News'];
  //}
  //else if ($GLOBALS['postsAmount'] == 'single') { // Create Page header
    //if ($GLOBALS['pageTitle'] == 'Home') {
      //// TODO use page content instead
      //$headerTitleText = '<b><big>'.get_bloginfo('name').'</big></b><br>'.PHP_EOL;
      //$headerTitleText .= get_bloginfo('description');
    //}
    //else {
      //$headerTitleText = $GLOBALS['pageTitle'];
    //}
    //try { // Match URLs to pages, named, or as assigned to *
      //$headerURL = $shOptionsL[$GLOBALS['pageTitle']];
    //}
    //catch (Exception $e) {
      //$headerURL = $shOptionsL['*'];
    //}
  //}
  //$rHtml = '<div'.$subheaderAttributes.' style="background-image:url(\''.$headerURL.'\');">'.PHP_EOL;
  //$rHtml .= '<div><p>'.$headerTitleText.'</p></div></div>'.PHP_EOL;
  //return $rHtml;
//}

// ----------------------- Custom Widget subheader HTML getter from defining page ID

function qnrwp_get_subheader_html($widgetDefPageID) {
  // $widgetDefPageID - ID of the page defining the subheader
  
  // Get subheader attributes from the defining page
  $subheaderAttributes = '';
  // No validation, we trust the settings text to be untampered
  $rawS = get_post_field('post_content', $widgetDefPageID);
  if (stripos($rawS, '{subheader-options') !== false) { // TODO make it JSON compliant and use functions
    $rawS = preg_replace('@\s*//.*?(?:\n+|$)@i', ' ', $rawS); // Remove comments
    $rawS = preg_replace('@\s*\{subheader-options\s*@i', '', $rawS); // Remove "{subheader=options"
    $rawS = preg_replace('@\s*\}@i', '', $rawS); // Remove ending "}"
    $rawS = preg_replace('@\s+@i', ' ', $rawS); // Convert whitespace to a space
    $subheaderAttributes = ' '.$rawS; // Add a space to place attributes in tag
  }
  
  $rHtml = '';
  $wcContent = ''; // Widget child page content for display in subheader on matching named page
  $shOptionsL = array(); // Array of page name => image attachment ID
  $shOptionsL['*'] = ''; // Avoid an error later if key/value not set
  $shOptionsL['News'] = ''; // Likewise, prefer no-URL if News not present
  
  // Get Featured Image from definition page, as default for pages not defined by child pages
  if (has_post_thumbnail($widgetDefPageID)) {
    $shOptionsL['*'] = get_post_thumbnail_id($widgetDefPageID);
  }

  // Get child pages and their Featured Images / content
  $widgetChildren = get_page_children($widgetDefPageID, get_pages());
  if (count($widgetChildren) > 0) {
    foreach ($widgetChildren as $widgetChild) {
      // Get any content from child page for its matching named page
      if ($widgetChild->post_title == $GLOBALS['pageTitle']) {
        $wcContent = apply_filters('the_content', get_post_field('post_content', $widgetChild->ID));
      }
      // Store name and img URL as key => value
      if (has_post_thumbnail($widgetChild)) {
        $shOptionsL[$widgetChild->post_title] = get_post_thumbnail_id($widgetChild);
      }
    }
  }
  
  $headerTitleText = $wcContent ? $wcContent : $GLOBALS['pageTitle']; // May be overriden below
  
  if ($GLOBALS['isNews']) { // Create News header, for all News pages, if no content defined
    $headerTitleText = $wcContent ? $wcContent : 'News';
    $attID = $shOptionsL['News'];
  }
  else if ($GLOBALS['postsAmount'] == 'single') { // Create Page header
    if (isset($shOptionsL[$GLOBALS['pageTitle']]) && !empty($shOptionsL[$GLOBALS['pageTitle']])) {
      $attID = $shOptionsL[$GLOBALS['pageTitle']];
    } else {
      $attID = $shOptionsL['*'];
    }
  }
  
  // Get different sizes of image
  $attMeta = wp_get_attachment_metadata($attID);
  $mHtml = ''; // CSS media rules
  $upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
  $uploaded_image_location = $upload_dir['baseurl'] . '/' . $attMeta['file']; // Note the 'baseurl', not 'basedir'
  if (is_ssl()) $uploaded_image_location = set_url_scheme($uploaded_image_location, 'https'); // Convert to HTTPS if used
  $image_subdir = substr($attMeta['file'], 0, strrpos($attMeta['file'], '/'));
  $largestImage = $uploaded_image_location; // Just in case, we fall back on full size file
  foreach ($attMeta['sizes'] as $size => $sizeArray) {
    // Create file path for intermediate size image
    $imgPath = $upload_dir['baseurl'] . '/' . $image_subdir . '/' . $sizeArray['file'];
    if (is_ssl()) $imgPath = set_url_scheme($imgPath, 'https');
    // Limit to min 600px width or height to avoid stretching low res on mobiles
    if ($sizeArray['width'] > 600 && $sizeArray['height'] > 600) {
      // Prepend presumably increasing sizes to media html
      $mItem = '@media (max-width: '.$sizeArray['width'].'px) {'.PHP_EOL;
      $mItem .= 'div#subheader {background-image:url("'.$imgPath.'");}'.PHP_EOL;
      $mItem .= '}'.PHP_EOL;
      $mHtml = $mItem . $mHtml; // Concatenate in reverse
    }
    $largestImage = $imgPath; // Record last item as largest, the default in style block
  }
  
  // Prepare style block for responsive bg image size
  $sHtml = '<style>'.PHP_EOL;
  $sHtml .= 'div#subheader {background-image:url("'.$largestImage.'");}'.PHP_EOL;
  $sHtml .= $mHtml;
  $sHtml .= '</style>'.PHP_EOL;
  
  $rHtml = $sHtml . '<div id="subheader"'.$subheaderAttributes.'>'.PHP_EOL;
  if ($wcContent) $rHtml .= $headerTitleText; // We already have all the code if $wcContent
  else $rHtml .= '<div><p>'.$headerTitleText.'</p></div>'; // TODO simplify?
  return $rHtml . '</div>' . PHP_EOL;
}


function qnrwp_get_attachment_sizes_urls($attID) {
  // Helper function, returns array of size => URL, for different sizes of image inc. full
  $attSizesURLs = [];
  $attMeta = wp_get_attachment_metadata($attID);
  $upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
  $uploaded_image_location = $upload_dir['baseurl'] . '/' . $attMeta['file']; // Note the 'baseurl', not 'basedir'
  if (is_ssl()) $uploaded_image_location = set_url_scheme($uploaded_image_location, 'https'); // Convert to HTTPS if used
  $image_subdir = substr($attMeta['file'], 0, strrpos($attMeta['file'], '/'));
  foreach ($attMeta['sizes'] as $size => $sizeArray) {
    // Create URL for intermediate size image
    $imgURL = $upload_dir['baseurl'] . '/' . $image_subdir . '/' . $sizeArray['file'];
    if (is_ssl()) $imgURL = set_url_scheme($imgURL, 'https');
    $attSizesURLs[$size] = $imgURL;
  }
  $attSizesURLs['full'] = $uploaded_image_location; // Add original upload as 'full' size
  return $attSizesURLs;
}


// ----------------------- Custom Widget carousel HTML getter from defining page ID

function qnrwp_get_carousel_html($widgetDefPageID, $imageSize = 'large') {
  // $widgetDefPageID - ID of the page defining the carousel
  // $imageSize - thumbnail, medium, medium_large, large, full, qnrwp-larger, qnrwp-largest, qnrwp-extra
  
  // Get carousel attributes from the defining page
  $carouselDataAttributes = '';
  // No validation, we trust the settings text to be untampered
  $rawS = get_post_field('post_content', $widgetDefPageID);
  if (stripos($rawS, '{carousel-options') !== false) {
    $rawS = preg_replace('@\s*//.*?\n+@i', ' ', $rawS); // Remove comments
    $rawS = preg_replace('@\s*\{carousel-options\s*@i', '', $rawS); // Remove "{carousel=options"
    $rawS = preg_replace('@\s*\}@i', '', $rawS); // Remove ending "}"
    $rawS = preg_replace('@\s+@i', ' ', $rawS); // Convert whitespace to a space
    $carouselDataAttributes = ' '.$rawS; // Add a space to place attributes in tag
  }
  // Construct carousel HTML
  $rHtml = '';
  $msHtml = ''; // Media rules style block
  $bsHtml = ''; // Style HTML for base sizes of slides
  
  // Test if size large enough to bother making it responsive
  $makeResponsive = false;
  $sizesL = array('qnrwp-larger', 'qnrwp-largest', 'qnrwp-extra', 'full');
  if (in_array($imageSize, $sizesL)) $makeResponsive = true;
    
  $attsSizesURLs = []; // List of attachment ID keys, values of size => URL array
  $slideIDs = []; // List of attachment IDs => timestamp DIV IDs for the slides
  
  $widgetChildren = get_page_children($widgetDefPageID, get_pages());
  if (count($widgetChildren) < 1) return ''; // Exit if no slides defined
  
  // Get child pages as content DIVs
  $iC = 0;
  foreach ($widgetChildren as $widgetChild) {
    if (!has_post_thumbnail($widgetChild)) return ''; // Exit if a post thumb not set on a slide
    
    // Get data for responsive styling
    $attID = get_post_thumbnail_id($widgetChild);
    $attsSizesURLs[$attID] = qnrwp_get_attachment_sizes_urls($attID); // ID => size => URL
    $slideIDs[$attID] = 'cSlide' . str_replace('.', '', strval(microtime(true)));
    
    // Construct item DIV for carousel
    $thumbBG = '';
    // Hide all but the first two (JS will show them)
    // Hoping that will speed up the load: only first image loaded at first, then when displayed, the rest
    
    if ($iC < 1) $thumbBG = ' style="background-image:url(\''.$attsSizesURLs[$attID][$imageSize].'\')"';
    else $thumbBG = ' style="display:none;background-image:url(\''.$attsSizesURLs[$attID][$imageSize].'\')"';
    
    $bsHtml .= 'div#'.$slideIDs[$attID].' {background-image:url("'.$attsSizesURLs[$attID][$imageSize].'");}'.PHP_EOL;
    
    $htmlContent = apply_filters('the_content', get_post_field('post_content', $widgetChild->ID));
    // Wrap the content with centered inner DIV for easier styling
    $htmlContent = '<div class="slide-inner center">'.PHP_EOL.$htmlContent.'</div>'.PHP_EOL;
    $rHtml .= '<div id="'.$slideIDs[$attID].'"'.$thumbBG.'>'.PHP_EOL.$htmlContent.'</div>'.PHP_EOL;
    $iC += 1;
  }
  // Get sizes as pixels from last attachment, assumed representative of all
  $sizesALL = [];
  $attMeta = wp_get_attachment_metadata($attID);
  foreach ($attMeta['sizes'] as $size => $sizeArray) {
    $sizesALL[$size] = $sizeArray['width'];
  }
  // Get pixel width of imageSize
  $imageWidth = $sizesALL[$imageSize];
  
  if ($makeResponsive) {
    // Create media blocks for large to imageSize
    foreach ($sizesALL as $size => $width) {
      if ($width < $imageWidth && $width > 1000) {
        // Create the media wrap
        $mItem = '@media (max-width: '.$width.'px) {'.PHP_EOL;
        // Iterate images for each size
        $msItems = '';
        foreach ($attsSizesURLs as $attID => $sizeArray) {
          $msItems .= 'div#'.$slideIDs[$attID].' {background-image:url("'.$sizeArray[$size].'");}'.PHP_EOL;
        }
        $mItem .= $msItems;
        $mItem .= '}'.PHP_EOL;
        $msHtml = $mItem . $msHtml; // Prepend larger before smaller
      }
    }
  }
  // Prepend base sizes, complete style block
  $msHtml = '<style>'.PHP_EOL . $bsHtml . $msHtml . '</style>'.PHP_EOL;
    
  // The carousel widget may already have a class assigned in the defining options
  if (stripos($carouselDataAttributes, 'class="') !== false) {
    $carouselDataAttributes = str_replace('class="', 'class="qnr-carousel ', $carouselDataAttributes);
    $rHtml = '<div'.$carouselDataAttributes.'>'.PHP_EOL.$rHtml.'</div>'.PHP_EOL;
  }
  else $rHtml = '<div class="qnr-carousel"'.$carouselDataAttributes.'>'.PHP_EOL.$rHtml.'</div>'.PHP_EOL;
  return $msHtml . $rHtml; // Prepend style block
}

//function qnrwp_get_carousel_htmlOLD($widgetDefPageID, $imageSize = 'large') {
  //// $widgetDefPageID - ID of the page defining the carousel
  //// $imageSize - thumbnail, medium, medium_large, large, full, qnrwp-larger, qnrwp-largest, qnrwp-extra
  
  //// Get carousel attributes from the defining page
  //$carouselDataAttributes = '';
  //// No validation, we trust the settings text to be untampered
  //$rawS = get_post_field('post_content', $widgetDefPageID);
  //if (stripos($rawS, '{carousel-options') !== false) {
    //$rawS = preg_replace('@\s*//.*?\n+@i', ' ', $rawS); // Remove comments
    //$rawS = preg_replace('@\s*\{carousel-options\s*@i', '', $rawS); // Remove "{carousel=options"
    //$rawS = preg_replace('@\s*\}@i', '', $rawS); // Remove ending "}"
    //$rawS = preg_replace('@\s+@i', ' ', $rawS); // Convert whitespace to a space
    //$carouselDataAttributes = ' '.$rawS; // Add a space to place attributes in tag
  //}
  //// Construct carousel HTML
  //$rHtml = '';
  //// Get child pages as content DIVs
  //$widgetChildren = get_page_children($widgetDefPageID, get_pages());
  //$iC = 0;
  //if (count($widgetChildren) > 0) {
    //foreach ($widgetChildren as $widgetChild) {
      //// Construct item DIV for carousel
      //$thumbBG = '';
      //if (has_post_thumbnail($widgetChild)) {
        //// Get BG image from Featured Image, hide all but the first two (JS will show them)
        //// Hoping that will speed up the load: only first image loaded at first, then when displayed, the rest
        //$thumbHtml = get_the_post_thumbnail($widgetChild, $imageSize);
        
        //if ($iC < 1) $thumbBG = ' style="background-image:url(\''.qnrwp_get_post_thumbnail_url($thumbHtml).'\')"';
        //else $thumbBG = ' style="display:none;background-image:url(\''.qnrwp_get_post_thumbnail_url($thumbHtml).'\')"';
      //}
      //$htmlContent = apply_filters('the_content', get_post_field('post_content', $widgetChild->ID));
      //// Wrap the content with centered inner DIV for easier styling
      //$htmlContent = '<div class="slide-inner center">'.PHP_EOL.$htmlContent.'</div>'.PHP_EOL;
      //$rHtml .= '<div'.$thumbBG.'>'.PHP_EOL.$htmlContent.'</div>'.PHP_EOL;
      //$iC += 1;
    //}
  //}
  //// The carousel widget may already have a class assigned in the defining options
  //if (stripos($carouselDataAttributes, 'class="') !== false) {
    //$carouselDataAttributes = str_replace('class="', 'class="qnr-carousel ', $carouselDataAttributes);
    //$rHtml = '<div'.$carouselDataAttributes.'>'.PHP_EOL.$rHtml.'</div>'.PHP_EOL;
  //}
  //else $rHtml = '<div class="qnr-carousel"'.$carouselDataAttributes.'>'.PHP_EOL.$rHtml.'</div>'.PHP_EOL;
  //return $rHtml;
//}


// ----------------------- Samples HTML getter TODO

function qnrwp_get_samples_html($samplesName, $sampleCategories, $sampleSize) {
  // ----------------------- Custom Query
  $the_query = new WP_Query(array('post_type' => 'post', 'nopaging' => true));
  if ($the_query->have_posts()) {
    $samplesCount = 0;
    $rHtml = '<!-- Samples Row -->'.PHP_EOL;
    $rHtml .= '<div class="samples-row">'.PHP_EOL;
    $rHtml .= '<h2 class="samples-list-title">'.$samplesName.'</h2>'.PHP_EOL; // Place before the block
    $rHtml .= '<!-- Samples List -->'.PHP_EOL;
    $rHtml .= '<div class="samples-list-block">'.PHP_EOL; // Opening Samples List block
    // ----------------------- The Loop
    while ($the_query->have_posts()) {
      $the_query->the_post();
      if (in_category($sampleCategories) && has_post_thumbnail()) { // TODO      
        // Custom meta values for the post
        // WP has tricky meta functions with multi-dimensional arrays,
        //   we use a relatively simple one
        $sampleInfo = get_post_custom_values('Sample-Info') ? get_post_custom_values('Sample-Info')[0] : '';
        $sampleLink = get_post_custom_values('Sample-Link') ? get_post_custom_values('Sample-Link')[0] : '';
        if (!$sampleInfo && !$sampleLink) continue;
        // Construct item HTML
        $imageLink = $sampleLink ? $sampleLink : $sampleInfo;
        $rHtml .= '<!-- Samples List Item -->'.PHP_EOL;
        $rHtml .= '<div class="samples-list-item">'.PHP_EOL;
        $rHtml .= '<a href="'.$imageLink.'">';
        $rHtml .= get_the_post_thumbnail(get_the_ID(), $sampleSize, array('class' => 'samples-list-item-img'));
        $rHtml .= '</a>';
        $rHtml .= '<div class="samples-list-item-text">'.PHP_EOL;
        $rHtml .= '<h3>'.get_the_title().'</h3>'.PHP_EOL;
        $rHtml .= apply_filters('the_content', get_the_content());
        $rHtml .= '</div>'.PHP_EOL; // End of item text
        $rHtml .= '<div class="samples-list-item-buttons">'.PHP_EOL;
        if ($sampleInfo) {
          $rHtml .= '<a href="'.$sampleInfo.'" title="More info"><span class="qnr-glyph qnr-glyph-info"></span></a>';
        }
        if ($sampleLink) {
          $rHtml .= '<a href="'.$sampleLink.'" title="View the sample"><span class="qnr-glyph qnr-glyph-openpage"></span></a>'.PHP_EOL;
        }
        $rHtml .= '</div></div><!-- End of Samples List Item -->'.PHP_EOL; // 
        $samplesCount += 1;
      }
    }
    // Restore original Post Data
    wp_reset_postdata();
    if ($samplesCount > 0) {
      $rHtml .= '</div></div><!-- End of Samples List and Row -->'.PHP_EOL; // Closing Samples List block
      return $rHtml; // Return nothing if no Samples List items obtained
    }
  }
  return ''; // Either no posts found or no samples
}


// ----------------------- Custom Widget picker menu

function qnrwp_get_widget_defs_menu($existingVal, $outputID, $outputName) {
  // Returns HTML select/options menu listing widgets defined as "QNRWP-Widget-" prefixed pages
  // No output field required, the Widget updating works with "selected" <option> in <select>
  $pages = get_pages();
  $rP = '';
  $selected = '';
  foreach ($pages as $page) {
    // Omit pages that are children of other pages (widgets are defined as main pages)
    // wp_get_post_parent_id is supposed to return false when no parent, but returns 0
    if (! wp_get_post_parent_id($page->ID) && stripos($page->post_title, 'QNRWP-Widget-') !== false) {
      // Save the page ID instead of name, for faster retrieval later
      if ($page->ID == $existingVal) $selected = ' selected="selected"';
      $rP .= '<option '.$selected.' value="'.esc_attr($page->ID).'">'.esc_html($page->post_title).'</option>'.PHP_EOL;
      // Reset $selected for next item
      $selected = '';
    }
  }
  if ($rP) {
    $rP = '<p>Select the widget to display:</p><select name="'.$outputName.'" id="'.$outputID.'">'.PHP_EOL . $rP . '</select><br>'.PHP_EOL;
  }
  else $rP = '<p>No pages defining QNRWP widgets exist yet.</p>'.PHP_EOL;
  return $rP;
}


// ----------------------- Custom Widget pages-to-display-on selector

function qnrwp_get_pages_form_for_widget($pagesVal, $pagesL, $outputID, $outputName) {
  // Returns HTML to use in a widget form so pages can be selected for widget to appear on
  // $pagesVal - string value that is stored in db
  // $pagesL - array exploded from pagesVal, listing checked pages options
  // $outputID - output field ID matching the ID for saving the value that is updated by JS on checkbox clicks
  // $outputName - output field Name
  $pages = get_pages(); 
  $rP = '';
  $pageCheckbox = '';
  $pageParentID = false;
  foreach ($pages as $page) {
    // Omit pages set up as a widget's contents with "QNRWP-Widget-" prefix, and their children (one level only)
    $pageParentID = wp_get_post_parent_id($page->ID);
    // wp_get_post_parent_id is supposed to return false when no parent, but returns 0
    $pageParentTitle = $pageParentID ? get_post($pageParentID)->post_title : '';
    if (stripos($page->post_title, 'QNRWP-Widget-') === false && stripos($pageParentTitle, 'QNRWP-Widget-') === false) {
      // Test if this page is in $pagesL array parameter, select if so
      $checked = in_array($page->ID, $pagesL) ? ' checked="checked"' : ''; // Don't convert page ID to string...
      $pageCheckbox = '&nbsp;&nbsp;<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
                          .' value="'.$page->ID.'">'.esc_html($page->post_title).'</label><br>'.PHP_EOL;
      $rP .= $pageCheckbox;
    }
  }
  if ($rP) {
    // All News Posts checkbox (reverse order of concatenating)
    $checked = in_array('-1', $pagesL) ? ' checked="checked"' : '';
    $rP = '&nbsp;&nbsp;<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
            .' value="-1" name="qnrwp-all-news" id="qnrwp-all-news">All News Posts</label><br>'.PHP_EOL . $rP;
    // All Pages Except the Following checkbox
    $checked = in_array('-2', $pagesL) ? ' checked="checked"' : '';
    $rP = '<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
            .' value="-2" name="qnrwp-all-except" id="qnrwp-all-except">All except the following:</label><br>'.PHP_EOL . $rP;
    // Input field collecting the checked values as a setting to save, ID passed in param, as well as previous value
    // Class attribute is for JS identification as the ID and name are set by WP code
    $rP .= 'Output: <input name="'.$outputName.'" id="'.$outputID.'" class="qnrwp-setting-output-field" value="'.$pagesVal.'">'.PHP_EOL;
    // No <form> wrap, that's handled by the WP widget code, but DIV parent wrap for JS
    $rP = '<div style="box-sizing:border-box;padding:1em;height:200px;overflow:auto;border:solid thin #EEE">'.PHP_EOL . $rP;
    $rP = '<p>Select the page(s) to display this Widget. Click "All except the following:" to exclude the selected.</p>'.PHP_EOL . $rP;
    $rP .= '</div>'.PHP_EOL;
  }
  else $rP = '<p>No pages that could display the widget exist yet.</p>';
  return $rP;
}

// ===================== WP FUNCTIONS =====================

function qnrwp_combine_stylesheets($stylesheetPathsL, $outFilePath) {
  // $stylesheetPathsL - array of full file paths to stylesheets, in order
  // $outFilePath - main theme or child path for combo file
  $combo = '';
  foreach ($stylesheetPathsL as $stylesheet) {
    $combo .= file_get_contents($stylesheet) . "\n";
  }
  // Replace relative URIs to other assets in main theme res folder (only...!)
  if (is_child_theme()) $combo = preg_replace('/(url\([\'"])\.\.\//', '$1../qnrwp_a/res/', $combo);
  else $combo = preg_replace('/(url\([\'"])\.\.\//', '$1res/', $combo);
  // Minify
  // Remove comments
  $combo = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $combo);
  // Remove tabs, excessive spaces and newlines
  $combo = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $combo);
  $combo = str_replace('{ ', '{', $combo);
  $combo = str_replace(' {', '{', $combo);
  $combo = str_replace(' }', '}', $combo);
  $combo = str_replace('} ', '}', $combo);
  $combo = str_replace('; ', ';', $combo);
  $combo = str_replace(': ', ':', $combo);
  
  file_put_contents($outFilePath, $combo);
}

// ----------------------- Enqueue Scripts & Styles

function qnrwp_enqueue_styles() {
  // -------- Combine and minify stylesheets
  // Create ordered list of relative paths to stylesheets used
  $stylesL = array( '/res/css/qnr-interface.css', 
                    '/res/css/contact.css',
                    '/style.css');
  // Create array of stylesheet file paths, theme files first
  $tF = get_template_directory();
  foreach ($stylesL as $style) $stylesheetPathsL[] = $tF . $style;
  // Add child stylesheet file; child 'res/css' files are not included in combo, 
  //   enqueued conditionally in child functions.php
  if (is_child_theme()) $stylesheetPathsL[] = get_stylesheet_directory() . '/style.css';
  // Test for combo file, in child folder or main
  $fX = false; // File exists?
  $updateCombo = false; // Update combo file?
  if (is_child_theme()) {
    $cfPath = get_stylesheet_directory() . '/combo-style.css';
    $cfURI = get_stylesheet_directory_uri() . '/combo-style.css';
  }
  else {
    $cfPath = get_template_directory() . '/combo-style.css';
    $cfURI = get_template_directory_uri() . '/combo-style.css';
  }
  $fX = file_exists($cfPath);
  if ($fX) { // Combo file exists
    // Test if modification date of any stylesheets is newer than combo file
    $cT = filemtime($cfPath);
    foreach ($stylesheetPathsL as $stylesheet) {
      if (filemtime($stylesheet) > $cT) {
        // We have a stylesheet newer than combo file
        $updateCombo = true;
        break;
      }
    }
  }
  if (!$fX || $updateCombo) { // Create the combo file if it doesn't exist or must be updated
    qnrwp_combine_stylesheets($stylesheetPathsL, $cfPath);
  }
  // Enqueue the combo file
  wp_enqueue_style('combo-stylesheet', $cfURI, null, null);

  //wp_enqueue_style('qnr-interface-stylesheet', get_template_directory_uri() . '/res/css/qnr-interface.css', null, null);
  //wp_enqueue_style('qnr-hmenu-stylesheet', get_template_directory_uri() . '/res/css/qnr-hmenu.css', null, null);
  //// Load parent stylesheet before child's
  //// Retrieve parent theme dir: get_template_directory_uri()
  //// Retrieve child theme dir: get_stylesheet_directory_uri()
  //wp_enqueue_style('theme-stylesheet', get_template_directory_uri() . '/style.css', null, null);
  //if (is_child_theme()) {
    //wp_enqueue_style('child-stylesheet', get_stylesheet_uri(), null, null); // Child theme style.css
  //}
}
add_action('wp_enqueue_scripts', 'qnrwp_enqueue_styles');

function qnrwp_enqueue_scripts() {
  // ----------------------- Minify JS
  // List JS files
  $jsFilesL = array('/res/js/qnr-interface.js',
                    '/res/js/contact.js',
                    '/qnrwp_a-main.js');
  // Create array of JS file paths
  $tF = get_template_directory();
  foreach ($jsFilesL as $jsF) $jsPathsL[] = $tF . $jsF;
  // Test for combo file, in main
  $fX = false; // File exists?
  $updateCombo = false; // Update combo file?
  $cfPath = get_template_directory() . '/combo-js.js';
  $cfURI = get_template_directory_uri() . '/combo-js.js';
  $fX = file_exists($cfPath);
  if ($fX) { // Combo file exists
    // Test if modification date of any JS files is newer than combo file
    $cT = filemtime($cfPath);
    foreach ($jsPathsL as $jsF) {
      if (filemtime($jsF) > $cT) {
        // We have a JS file newer than combo file
        $updateCombo = true;
        break;
      }
    }
  }
  if (!$fX || $updateCombo) { // Create the combo file if it doesn't exist or must be updated
    $combo = '';
    foreach ($jsPathsL as $jsF) {
      $combo .= file_get_contents($jsF) . "\n";
    }
    require_once('JsMin.php'); // Use JsMin from Ryan Grove to minify
    $combo = JsMin::minify($combo);
    file_put_contents($cfPath, $combo);
  }
  // Enqueue the combo file
  wp_enqueue_script('combo-js', $cfURI, null, null, true); // In footer
  
  //wp_enqueue_script('qnr-interface-js', get_template_directory_uri() . '/res/js/qnr-interface.js', null, null, true); // In footer
  //wp_enqueue_script('qnr-hmenu-js', get_template_directory_uri() . '/res/js/qnr-hmenu.js', null, null, true);
  //wp_enqueue_script('qnrwp_a-main-js', get_template_directory_uri() . '/qnrwp_a-main.js', null, null, true);
}
add_action('wp_enqueue_scripts', 'qnrwp_enqueue_scripts');


// ----------------------- FILTERS

// ----------------------- Reduce uploaded image

function qnrwp_reduce_uploaded_image($upload) {
  // $upload = array of 'file', 'url', 'type'
  // There is also a $context argument, but we don't care about it
  // Load uploaded image into editor object
  try {
    $uploaded_image_location = $upload['file'];
    $imageFull = wp_get_image_editor($uploaded_image_location);
    if ($imageFull->get_size()['width'] > 2500) {
      // Set JPEG quality to half way between Media setting and 100, for better quality
      $jpegQ = get_option('qnrwp_jpeg_quality', $default=60);
      $imageFull->set_quality(((100 - $jpegQ)/2) + $jpegQ);
      $imageFull->resize(2500, null, false);
      $imageFull->save($uploaded_image_location);
      unset($imageFull); // Just in case...
    }
    //qnrwp_debug_printout(array('UPload file::', $upload), $append=false);
    return $upload;
  }
  catch (Exception $e) {
    error_log(  'WP cron error:  '.$e->getMessage().' in '.PHP_EOL.$e->getFile().':'.$e->getLine().PHP_EOL
                .'Stack trace:'.PHP_EOL.$e->getTraceAsString().PHP_EOL.'  thrown in '.$e->getFile().' on line '.$e->getLine()  );
    return $upload;
  }
}
add_filter('wp_handle_upload', 'qnrwp_reduce_uploaded_image');

//function qnrwp_reduce_uploaded_image($metadata, $attachment_id) { // NOT USED, causes duplicates (-2000x1333 & -2000x1334)
  //// $metadata = array of attachment metadata
  //// $attachment_id = attachment id
  //// This hooked function may run both on upload and Regenerate Images
  //try {
    //$upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
    //$uploaded_image_location = $upload_dir['basedir'] . '/' . $metadata['file'];
    //// Reduce full-size original if larger than 2500px width
    //if ($metadata['width'] > 2500) {
      //$imageFull = wp_get_image_editor($uploaded_image_location);
      //// Set JPEG quality to half way between Media setting and 100, for better quality
      //$jpegQ = get_option('qnrwp_jpeg_quality', $default=60);
      //$imageFull->set_quality(((100 - $jpegQ)/2) + $jpegQ);
      //$imageFull->resize(2500, null, false);
      //$imageFull->save($uploaded_image_location);
      //// Update the metadata array
      //$metadata['width'] = $imageFull->get_size()['width'];
      //$metadata['height'] = $imageFull->get_size()['height'];
      //wp_update_attachment_metadata($attachment_id, $metadata);
      //unset($imageFull); // Just in case...
    //}
    //qnrwp_debug_printout(array('UPload attachment metadata::', $metadata));
    //return $metadata;
  //}
  //catch (Exception $e) {
    //error_log(  'WP cron error:  '.$e->getMessage().' in '.PHP_EOL.$e->getFile().':'.$e->getLine().PHP_EOL
                //.'Stack trace:'.PHP_EOL.$e->getTraceAsString().PHP_EOL.'  thrown in '.$e->getFile().' on line '.$e->getLine()  );
    //return $metadata;
  //}
//}
//add_filter('wp_generate_attachment_metadata', 'qnrwp_reduce_uploaded_image', 10, 2);

// ----------------------- Add registered custom image sizes to Dashboard, plus post-thumbnail's Medium Large
function qnrwp_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'medium_large' => 'Medium Large',
        'qnrwp-larger' => 'QNRWP-Larger',
        'qnrwp-largest' => 'QNRWP-Largest',
        'qnrwp-extra' => 'QNRWP-Extra',
    ));
}
add_filter('image_size_names_choose', 'qnrwp_custom_image_sizes');

// ----------------------- Search Form filter
function qnrwp_search_form_filter($form) {  
  $form = preg_replace('@\s+<span class="screen-reader-text">[^<]+</span>@i', '', $form);
  $form = preg_replace('@\s+<input type="submit" class="search-submit" value="[^\"]+" />@i', 
                            '<input type="submit" class="search-submit" value="g" />', $form); // No whitespace
  $form = preg_replace('@Search &hellip;@i', 'Search news&hellip;', $form);
  $form = preg_replace('@\s+</?label>@i', '', $form);
  return $form;
}
add_filter('get_search_form', 'qnrwp_search_form_filter');

// ----------------------- Excerpt 'read more' filter
function qnrwp_excerpt_more_filter($moreStr) {
  return '...';
}
add_filter('excerpt_more', 'qnrwp_excerpt_more_filter');

// ----------------------- Main Query filter

// Customize parameters of main Loop query
function qnrwp_main_query_filter($query) {
    if ($query->is_main_query() && !is_page() && !is_admin()) { // Not in Admin screens...
        $query->set('category_name', 'news,uncategorized');
    }
}
add_action('pre_get_posts', 'qnrwp_main_query_filter' );

// ----------------------- General Widget before/after filter

// Take care of things other filters cannot...
function qnrwp_dynamic_sidebar_params($params) {
  // Don't filter positive, we do it negative (generic positives are in sidebar definition)
  // Text widget will have an inconsistent 'textwidget' class only, cannot be filtered
  if ($params[0]['widget_name'] == 'Custom Menu' || $params[0]['widget_name'] == 'Text') {
    $params[0]['before_widget'] = PHP_EOL.'<!-- Widget -->'.PHP_EOL;
    $params[0]['after_widget'] = '';
  }
  return $params;
}
add_filter('dynamic_sidebar_params', 'qnrwp_dynamic_sidebar_params');

// ----------------------- Title filter

// Customize title with blog name, and take care of it on Home page (no longer used)
function qnrwp_title_filter($title) {
  if (empty($title) && (is_home() || is_front_page())) {
    $title = 'Home';
  }
  $title =  get_bloginfo('name') . ' | ' . $title;
  return $title;
}
add_filter('wp_title', 'qnrwp_title_filter');


// ----------------------- Widget Title filter

// Remove titles on widgets that shouldn't have them displayed
function qnrwp_widget_title_filter($wtitle) {
  if (  stripos($wtitle, 'Copyright') !== false || 
        stripos($wtitle, 'Menu') !== false || 
        stripos($wtitle, 'Logo') !== false || 
        stripos($wtitle, 'Search') !== false || 
        stripos($wtitle, 'Social Links') !== false   ) {
    return '';
  }
  return $wtitle;
}
add_filter('widget_title', 'qnrwp_widget_title_filter');


// ----------------------- Recent Posts widget args filter

function qnrwp_recent_posts_widget_args($args) {
  $args['category_name'] = 'news,uncategorized';
  return $args;
}
add_filter('widget_posts_args', 'qnrwp_recent_posts_widget_args'); 


// ----------------------- Custom Menu widget args filters

function qnrwp_nav_menu_args($args) {
  // Make main nav menu a QI Navmenu
  if($args['menu'] == wp_get_nav_menu_object('QNRWP Main Nav Menu')) {
    //$args['depth'] = -1; // Make it flat, no submenus
    // Cannot concatenate to default container class...
    $args['container_class'] = 'widget qnr-navmenu';
  }
  // Class footer menu
  else if($args['menu'] == wp_get_nav_menu_object('QNRWP Footer Menu')) {
    $args['container_class'] = 'widget qnr-footer-menu';
  }
  //// Class test menu - Doesn't work from shortcode...
  //else if($args['menu'] == wp_get_nav_menu_object('Test_Menu')) {
    //$args['container_class'] = 'qnr-hmenu';
  //}
	return $args;
}
add_filter('wp_nav_menu_args', 'qnrwp_nav_menu_args');

function qnrwp_menu_classes($classes, $item, $args, $depth) {
  // Make submenu of main nav menu a qnr-hmenu
  // Note that args and its menu are objects in this hook...
  if ($args->menu == wp_get_nav_menu_object('QNRWP Main Nav Menu') && 
            $depth == 0 && in_array('menu-item-has-children', $classes)) {
    $classes[] = 'qnr-hmenu';
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'qnrwp_menu_classes', 10, 4);


// ----------------------- Excerpt length and form filters

function qnrwp_custom_excerpt_length($length) {
  $rN = 35; // On average there are 5 characters per word
  if (isset($GLOBALS['MaxExcerptLength']))
    $rN = intval(($GLOBALS['MaxExcerptLength']/5) + 5);
	return $rN;
}
add_filter('excerpt_length', 'qnrwp_custom_excerpt_length', 999);

// Used in both excerpt hook and custom calls (meta description)
function qnrwp_get_pretty_excerpt($excerpt) {
  // Reduce length to max 110 chars
  $excerptDecode = wp_kses_decode_entities($excerpt); // Decode numerical entities, only for counting
  $eLen = 110;
  // Consider the global limit (for the sake of generic use in meta description)
  if (isset($GLOBALS['MaxExcerptLength'])) $eLen = $GLOBALS['MaxExcerptLength'];
  if (substr($excerptDecode, 0, $eLen) !== substr($excerpt, 0, $eLen)) {
    $eLen += strlen($excerpt) - strlen($excerptDecode);
  }
  $excerpt = trim(substr($excerpt, 0, $eLen)); // Limit to 110 chars
  // Delete last, possibly truncated word
  $excerpt = preg_replace('/(\S+)\s+\S*$/', '$1', $excerpt);
  // Remove single closing word after sentence
  $excerpt = preg_replace('/([.!?]+)\s+\S*$/', '$1', $excerpt);
  // Remove closing punctuation (not including ; as it may end a char entity)
  $excerpt = preg_replace('/[,:.!?-]+$/', '', $excerpt);
  return $excerpt . '...';
}
add_filter('get_the_excerpt', 'qnrwp_get_pretty_excerpt');

// NOT USED for excerpt; output too long for small tablet window size
function qnrwp_get_news_first_para_excerpt1() {
  // We get first paragraph from HTML content, as classed by content filter
  $htmlContent = apply_filters('the_content', get_the_content());
  $rc = preg_match('/<p[^>]+news-post-first-para[^>]+>(.+?)<\/p>/', $htmlContent, $matches);
  if ($rc) {
    $rT = wp_strip_all_tags($matches[1], true);
    if (strlen($rT) < 255) {
      return $rT;
    }
  }
  // If our excerpt too long, use reqular excerpt function
  return apply_filters('the_excerpt', get_the_excerpt());
}

// New version, working with $post global
function qnrwp_get_news_first_para_excerpt() {
  global $post;
  // Remove HTML/PHP tags
  $content = strip_tags($post->post_content);
  // Remove shortcodes
  // First convert double brackets to safe strings
  $content = preg_replace('/\[\[/', '<<<qnr_gnfpe_s<<<', $content);
  $content = preg_replace('/\]\]/', '>>>qnr_gnfpe_e>>>', $content);
  $content = preg_replace('/\s*\[[^\]]*\]\s*/', ' ', $content);
  $content = preg_replace('/<<<qnr_gnfpe_s<<</', '[', $content);
  $content = preg_replace('/>>>qnr_gnfpe_e>>>/', ']', $content);
  $cL = preg_split('/\s*\n\s*\n+/', $content);
  if (isset($cL) && !empty($cL)) {
    $rT = trim($cL[0]);
    // Keep it sane, reduce to <= 255 chars
    if (strlen($rT) > 255) {
      $GLOBALS['MaxExcerptLength'] = 255;
      $rT = qnrwp_get_pretty_excerpt($rT);
      // Reset to usual 110 for excerpts
      $GLOBALS['MaxExcerptLength'] = 110;
    }
    return $rT;
  }
  else return '';
}


// ----------------------- News Post first paragraph classing filter

add_filter('the_content', function($content) {
  // Test if inside the main loop, a news post
  // Test for 'is_single()' removed, so that qnrwp_get_news_first_para_excerpt() will work (reinstated)
  if (is_single() && in_the_loop() && is_main_query() && get_post_type() == 'post' 
                  && !is_admin() && in_category(array('news', 'uncategorized'))) {
    // Class first paragraph as "news-post-first-para", perhaps after featured image possibly wrapped in A and P tags
    // Won't work if a carousel precedes the first paragraph, but that will be caught by CSS
    //qnrwp_debug_printout($content, $append=false);
    $gP = '/^(\s*((<p>\s*<a[^>]+>\s*<img[^>]+>\s*<\/a>\s*<\/p>)|(<img[^>]+>))?\s*<p)>/';
    $content = preg_replace($gP, '$1 class="news-post-first-para">', $content);
  }
  return $content;
}, 999); // Run late so we're working with complete HTML after shortcodes


// ----------------------- WIDGETS

// ----------------------- Custom Widget definition

class QNRWP_Custom_Widget extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_custom_widget',
			'description' => 'Custom Widget to display.',
		);
		parent::__construct('qnrwp_custom_widget', 'QNRWP Custom Widget', $widget_ops);
	}
  
	public function widget($args, $instance) {
    // Get the pages list from string value
    $pagesL = explode(',', $instance['mypages']);
    // Decide whether to show the widget on this page
    $allExcept = in_array('-2', $pagesL);
    $allNews = in_array('-1', $pagesL);
    $thisPage = get_the_ID();
    $showWidget = false;
    $postType = get_post_type();
    // TODO: News page has a different post ID from its page ID
    if ($postType == 'post' && ! is_home() && $allNews && ! $allExcept) $showWidget = true;
    else if ($postType == 'post' && ! is_home() && ! $allNews && $allExcept) $showWidget = true;
    else if (($postType == 'page' || is_home()) && in_array($thisPage, $pagesL) && ! $allExcept) $showWidget = true;
    else if (($postType == 'page' || is_home()) && ! in_array($thisPage, $pagesL) && $allExcept) $showWidget = true;
    // Display
    if ($showWidget) {
      $rHtml = '';
      // Carousel
      if (stripos(get_post($instance['mywidget'])->post_title, 'Carousel') !== false) {
        $rHtml = qnrwp_get_carousel_html($instance['mywidget'], 'large'); // Other sizes supported by shortcode
      }
      // Sub Header
      else if (stripos(get_post($instance['mywidget'])->post_title, 'SubHeader') !== false) {
        $rHtml = qnrwp_get_subheader_html($instance['mywidget']);
      }
      echo $rHtml; // Done
    }
	}
  
	public function form($instance) {
		// ----------------------- Output widget admin options form
    // Add JS for widget form
    wp_enqueue_script('qnrwp_a-adminwidgets-js', get_template_directory_uri() . '/qnrwp_a-adminwidgets.js', null, null);
    // Page(s) to display the widget field
		$pagesVal = !empty($instance['mypages']) ? $instance['mypages'] : '';
    $pagesL = explode(',', $pagesVal);
    $pagesOutputID = esc_attr($this->get_field_id('mypages'));
    $pagesOutputName = esc_attr($this->get_field_name('mypages'));
    // Widget (defined by a page) to display field
		$widget = (!empty($instance['mywidget'])) ? $instance['mywidget'] : '';
    $fieldWidgetID = esc_attr($this->get_field_id('mywidget'));
    $fieldWidgetName = esc_attr($this->get_field_name('mywidget'));
    // HTML form
    echo qnrwp_get_pages_form_for_widget($pagesVal, $pagesL, $pagesOutputID, $pagesOutputName);
    echo qnrwp_get_widget_defs_menu($widget, $fieldWidgetID, $fieldWidgetName);
	}
  
	public function update($new_instance, $old_instance) {
		// Process and save widget options
		$instance = array();
		$instance['mypages'] = (!empty($new_instance['mypages'])) ? strip_tags($new_instance['mypages']) : '';
		$instance['mywidget'] = (!empty($new_instance['mywidget'])) ? strip_tags($new_instance['mywidget']) : '';
		return $instance;
	}
}


// ----------------------- Content widget definition

class QNRWP_Content extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_content',
			'description' => 'Page or post content from main edit screen.',
		);
		parent::__construct('qnrwp_content', 'QNRWP Page or Post Content', $widget_ops);
	}
  
	public function widget($args, $instance) {
		// Echo global with pre-constructed page or post content HTML
    echo $GLOBALS['contentHtml'];
	}
}


// ----------------------- Featured News widget definition

class QNRWP_Featured_News extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_featured_news',
			'description' => 'Excerpts of 4 latest Posts, with category News or Uncategorized, with Featured Images, to appear on the static Home page.',
		);
		parent::__construct('qnrwp_featured_news', 'QNRWP Featured News', $widget_ops);
	}
  
	public function widget($args, $instance) {
    if (is_front_page()) {
      // ----------------------- Custom Query
      $the_query = new WP_Query(array('post_type' => 'post', 'nopaging' => true));
      if ($the_query->have_posts()) {
        $featuredCount = 0;
        $rHtml = '<!-- Featured News -->'.PHP_EOL;
        $rHtml .= '<div class="featured-news-block">'.PHP_EOL; // Opening Featured News block
        $rHtml .= '<div>'.PHP_EOL; // Opening of first of two item-of-two DIVs
        // ----------------------- The Loop
        while ($the_query->have_posts()) {
          $the_query->the_post();
          if (in_category(array('news', 'uncategorized')) && has_post_thumbnail()) {
            //$thumbHtml = get_the_post_thumbnail(get_the_ID(), 'medium');
            //$thumbUrl = qnrwp_get_post_thumbnail_url($thumbHtml);
            $thumbUrl = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'medium');
            if (is_ssl()) $thumbUrl = set_url_scheme($thumbUrl, 'https'); // Convert to HTTPS if used
            $postLink = get_the_permalink(get_the_ID());
            $rHtml .= '<a class="featured-news-item" href="'.$postLink.'">'.PHP_EOL; // Opening item
            $rHtml .= '<div class="featured-news-item-header" style="background-image:url(\''.$thumbUrl.'\')">&nbsp;</div>'.PHP_EOL;
            $rHtml .= '<div class="featured-news-item-text">'.PHP_EOL;
            $rHtml .= '<h1>'.get_the_title().'</h1>'.PHP_EOL;
            $GLOBALS['MaxExcerptLength'] = 115;
            $rHtml .= '<div class="featured-news-item-excerpt">'.PHP_EOL.get_the_excerpt().PHP_EOL.'</div>'.PHP_EOL;
            $GLOBALS['MaxExcerptLength'] = 110;
            $rHtml .= '</div>'.PHP_EOL.'</a>'.PHP_EOL; // Closing item
            $featuredCount += 1;
            if ($featuredCount == 2) $rHtml .= '</div><div><!-- No whitespace -->'.PHP_EOL; // Close first item-of-two, open next
          }
          if ($featuredCount == 4) break;
        }
        $rHtml .= '</div>'.PHP_EOL.'</div><!-- End of Featured News -->'.PHP_EOL; // Closing second item-of-two and Featured News block
        if ($featuredCount == 4) echo $rHtml; // Echo nothing if 4 Featured News items not obtained
        
        // Restore original Post Data
        wp_reset_postdata();
      }
      else {
        // No posts found
      }
    } // If not on front page, do nothing
	}
  
	public function form($instance) {
		// Output widget admin options form
    ?>
		<p>Excerpts of 4 latest Posts, with category News or Uncategorized, with Featured Images, to appear on the static Home page.</p>
		<?php 
	}
  
}


// ----------------------- Samples List widget definition TODO

class QNRWP_Samples_List extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_samples_list',
			'description' => 'List of sample links with Featured Images, to appear on the static Home page.',
		);
		parent::__construct('qnrwp_samples_list', 'QNRWP Samples List', $widget_ops);
	}
  
	public function widget($args, $instance) {
    if (is_front_page()) {
      // Params: name, categories, size
      echo qnrwp_get_samples_html('Samples', array('sample-web-design', 'sample-website', 'sample-work'), 'medium_large');
    } // If not on front page, do nothing
	}
  
	public function form($instance) {
		// Output widget admin options form
    ?>
		<p>List of sample links with Featured Images, to appear on the static Home page.</p>
		<?php 
	}
  
}


// ----------------------- Sidebar & Widget registration

function qnrwp_widgets_init() {
  // ----------------------- Header Row
  register_sidebar(array(
    'name'          => 'Header Row',
    'id'            => 'qnrwp-row-1',
    'description'   => 'Widgets in this area will be shown on all posts and pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Sub Header Row (within possibly narrower content & sidebars row)
  register_sidebar(array(
    'name'          => 'Sub Header Row',
    'id'            => 'qnrwp-subrow-1',
    'description'   => 'Widgets in this area will be shown on all or some posts and pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Left Sidebar
  register_sidebar(array(
    'name'          => 'Left Sidebar',
    'id'            => 'qnrwp-sidebar-1',
    'description'   => 'Widgets in this area will be shown on all posts but not on pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Main Content Box
  register_sidebar(array(
    'name'          => 'Main Content Row',
    'id'            => 'qnrwp-row-2',
    'description'   => 'Widgets in this area will be shown on all or some posts and pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Right Sidebar
  register_sidebar(array(
    'name'          => 'Right Sidebar',
    'id'            => 'qnrwp-sidebar-2',
    'description'   => 'Widgets in this area will be shown on all posts but not on pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Footer Row
  register_sidebar(array(
    'name'          => 'Footer Row',
    'id'            => 'qnrwp-row-3',
    'description'   => 'Widgets in this area will be shown on all posts and pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  register_widget('QNRWP_Custom_Widget');
  register_widget('QNRWP_Content');
  register_widget('QNRWP_Featured_News');
  register_widget('QNRWP_Samples_List');
}
add_action('widgets_init', 'qnrwp_widgets_init');


function qnrwp_setup() {
  // ----------------------- Post Thumbnails support
  add_theme_support('post-thumbnails');
  
  // ----------------------- Title tag support
  add_theme_support('title-tag');
  
  // ----------------------- HTML5 support
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	));
  
  // ----------------------- Add new image sizes
  add_image_size('qnrwp-larger', 1600, 0, false);
  add_image_size('qnrwp-largest', 2000, 0, false);
  add_image_size('qnrwp-extra', 2500, 0, false);
}
add_action('after_setup_theme', 'qnrwp_setup');



// ----------------------- Shortcodes definitions

// Content argument is for content enclosed in open/closed shortcodes

// [featured-image size=large align=center link=no] TODO process options
function qnrwp_featuredimage_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'size' => 'large',
    'align' => 'center',
    'link' => 'no',
  ), $atts);
  if (has_post_thumbnail()) {
    $id = get_the_ID(); // Post id, obtained from WP global
    return get_the_post_thumbnail($post=$id,$size=$a['size']);
  }
}
add_shortcode('featured-image', 'qnrwp_featuredimage_shortcode');

// [include file='fileURL']
function qnrwp_include_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'file' => '',
  ), $atts);
  if ($a['file'] !== '') {
    // Assume file parameter is relative to child theme directory, or theme if no child
    //$e = include(trailingslashit(get_stylesheet_directory()) . $a['file']);
    //return eval($e);
    
        ob_start();
        //include $filename;
        include(trailingslashit(get_stylesheet_directory()) . $a['file']);
        return ob_get_clean();
  }
}
add_shortcode('include', 'qnrwp_include_shortcode');

// [contact-form]
// Used for mail contact and subscription without message/subject/name
function qnrwp_contact_form_shortcode($atts, $content = null) {
  // Email will be sent from wordpress@domain.com
  // Reply-To header will be set to the user's email
  // The 'warnings' parameter controls display of max chars and IP under 
  //   message if message is used
  
  // Construct default subject-line
  $subjectLine = (strlen(get_bloginfo('name')) > 50) 
                        ? 'Enquiry from a website visitor' 
                        : 'Enquiry from a '.sanitize_text_field(get_bloginfo('name')).' website visitor';
  $a = shortcode_atts(array(
    'subject' => 'yes',
    'subject-line' => $subjectLine,
    'sent-reply' => 'Your message has been sent. You should receive a reply within 2 working days.',
    'fail-reply' => 'Sorry, your message could not be sent.',
    'message' => 'yes',
    'warnings' => 'yes',
    'name' => 'no',
    'form-class' => 'contact-form',
  ), $atts); // As a minimum, email field required, for subscribing
  ob_start();
  qnrwp_contact_form($a);
  return ob_get_clean();
}
add_shortcode('contact-form', 'qnrwp_contact_form_shortcode');

// [menu name='menuName']
function qnrwp_menu_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'name' => '',
    'id' => '',
    'depth' => 0,
  ), $atts);
  $mymenu = wp_nav_menu(array(
    'menu'              => $a['name'],
    'echo'              => false,
    'container_class'   => 'test-menu qnr-hmenu',
    'container_id'      => $a['id'],
    'depth'             => $a['depth'],
    //'walker' => new QNRWP_Walker_Nav_Menu()
  ));
  return $mymenu;
}
add_shortcode('menu', 'qnrwp_menu_shortcode');

// [carousel name="QNRWP-Widget-Carousel-1" size="large"]
function qnrwp_carousel_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'name' => '',
    'size' => 'large',
  ), $atts);
  $pages = get_pages();
  $rHtml = '';
  foreach ($pages as $page) {
    if ($page->post_title === $a['name']) {
      $rHtml = qnrwp_get_carousel_html($page->ID, $a['size']);
      break;
    }
  }
  return $rHtml;
}
add_shortcode('carousel', 'qnrwp_carousel_shortcode');

// [samples categories="cat1, cat2, cat3"] shortcode version of custom widget TODO
function qnrwp_samples_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'name' => 'Samples',
    'size' => 'medium_large',
    'categories' => 'sample-web-design, sample-website, sample-work',
  ), $atts);
  $sCatsL = preg_split('/,\s+/', $a['categories']);
  $rHtml = qnrwp_get_samples_html($a['name'], $sCatsL, $a['size']);
  return $rHtml; // Could be empty
}
add_shortcode('samples', 'qnrwp_samples_shortcode');


// ----------------------- Disable WP emojis

function qnrwp_disable_wp_emojis() {
  // Remove all actions related to emojis
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');

  //// Remove TinyMCE emojis
  //add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
  
  add_filter('emoji_svg_url', '__return_false');
}
add_action('init', 'qnrwp_disable_wp_emojis');


// ----------------------- Require authenticated user for REST API

add_filter('rest_authentication_errors', function($result) {
  if (!empty($result)) {
    return $result;
  }
  if (!is_user_logged_in()) {
    return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
  }
  return $result;
});


// ===================== IMAGE EDITING =====================

// ----------------------- Override ImageMagick image engine

function qnrwp_image_editor_init($editors) {
  if (!class_exists('QNRWP_Image_Editor')) {
    class QNRWP_Image_Editor extends WP_Image_Editor_Imagick {
      
      /**
       * Sets Image Compression quality on a 1-100% scale.
       * Overriding WP_Image_Editor_Imagick for better compression
       *
       * @since 3.5.0
       * @access public
       *
       * @param int $quality Compression Quality. Range: [1,100]
       * @return true|WP_Error True if set successfully; WP_Error on failure.
       */
      public function set_quality($quality = null) {
        $quality_result = parent::set_quality($quality);
        if (is_wp_error($quality_result)) {
          return $quality_result;
        } else {
          $quality = $this->get_quality();
        }
        try {
          if ($this->mime_type == 'image/jpeg') {
            $this->image->setImageCompressionQuality($quality);
            $this->image->setImageCompression(imagick::COMPRESSION_JPEG);
            // Set chroma to 4:2:0
            $this->image->setSamplingFactors(array('2x2', '1x1', '1x1'));
            // Set progressive interlacing
            $this->image->setInterlaceScheme(Imagick::INTERLACE_LINE);
          }
          else {
            // QNRWP: if PNG, set compression quality to 95 (a compound number...)
            if ($this->mime_type == 'image/png') $this->image->setImageCompressionQuality(95);
            else $this->image->setImageCompressionQuality($quality);
            
            //$this->image->setImageCompressionQuality($quality);
          }
        }
        catch (Exception $e) {
          return new WP_Error('image_quality_error', $e->getMessage());
        }
        return true;
      }

      /**
       * Efficiently resize the current image
       * Overriden to change filter to Lanczos
       *
       * This is a WordPress specific implementation of Imagick::thumbnailImage(),
       * which resizes an image to given dimensions and removes any associated profiles.
       *
       * @since 4.5.0
       * @access protected
       *
       * @param int    $dst_w       The destination width.
       * @param int    $dst_h       The destination height.
       * @param string $filter_name Optional. The Imagick filter to use when resizing. Default 'FILTER_TRIANGLE'.
       * @param bool   $strip_meta  Optional. Strip all profiles, excluding color profiles, from the image. Default true.
       * @return bool|WP_Error
       */
      protected function thumbnail_image( $dst_w, $dst_h, $filter_name = 'FILTER_TRIANGLE', $strip_meta = true ) {
        $allowed_filters = array(
          'FILTER_POINT',
          'FILTER_BOX',
          'FILTER_TRIANGLE',
          'FILTER_HERMITE',
          'FILTER_HANNING',
          'FILTER_HAMMING',
          'FILTER_BLACKMAN',
          'FILTER_GAUSSIAN',
          'FILTER_QUADRATIC',
          'FILTER_CUBIC',
          'FILTER_CATROM',
          'FILTER_MITCHELL',
          'FILTER_LANCZOS',
          'FILTER_BESSEL',
          'FILTER_SINC',
        );

        /**
         * Set the filter value if '$filter_name' name is in our whitelist and the related
         * Imagick constant is defined or fall back to our default filter.
         */
        if ( in_array( $filter_name, $allowed_filters ) && defined( 'Imagick::' . $filter_name ) ) {
          if ($this->mime_type == 'image/jpeg') {
            $filter = defined( 'Imagick::FILTER_LANCZOS' ) ? Imagick::FILTER_LANCZOS : false;
          } else { // PNG etc.
            $filter = constant( 'Imagick::' . $filter_name );
          }
        } else {
          if ($this->mime_type == 'image/jpeg') {
            $filter = defined( 'Imagick::FILTER_LANCZOS' ) ? Imagick::FILTER_LANCZOS : false;
          } else { // PNG etc.
            $filter = defined( 'Imagick::FILTER_TRIANGLE' ) ? Imagick::FILTER_TRIANGLE : false;
          }
        }

        /**
         * Filters whether to strip metadata from images when they're resized.
         *
         * This filter only applies when resizing using the Imagick editor since GD
         * always strips profiles by default.
         *
         * @since 4.5.0
         *
         * @param bool $strip_meta Whether to strip image metadata during resizing. Default true.
         */
        if ( apply_filters( 'image_strip_meta', $strip_meta ) ) {
          $this->strip_meta(); // Fail silently if not supported.
        }

        try {
          /*
           * To be more efficient, resample large images to 5x the destination size before resizing
           * whenever the output size is less that 1/3 of the original image size (1/3^2 ~= .111),
           * unless we would be resampling to a scale smaller than 128x128.
           */
          if ( is_callable( array( $this->image, 'sampleImage' ) ) ) {
            $resize_ratio = ( $dst_w / $this->size['width'] ) * ( $dst_h / $this->size['height'] );
            $sample_factor = 5;

            if ( $resize_ratio < .111 && ( $dst_w * $sample_factor > 128 && $dst_h * $sample_factor > 128 ) ) {
              $this->image->sampleImage( $dst_w * $sample_factor, $dst_h * $sample_factor );
            }
          }

          /*
           * Use resizeImage() when it's available and a valid filter value is set.
           * Otherwise, fall back to the scaleImage() method for resizing, which
           * results in better image quality over resizeImage() with default filter
           * settings and retains backward compatibility with pre 4.5 functionality.
           */
          if ( is_callable( array( $this->image, 'resizeImage' ) ) && $filter ) {
            $this->image->setOption( 'filter:support', '2.0' );
            $this->image->resizeImage( $dst_w, $dst_h, $filter, 1 );
          } else {
            $this->image->scaleImage( $dst_w, $dst_h );
          }

          // Set appropriate quality settings after resizing.
          if ( 'image/jpeg' == $this->mime_type ) {
            if ( is_callable( array( $this->image, 'unsharpMaskImage' ) ) ) {
              $this->image->unsharpMaskImage( 0.25, 0.25, 8, 0.065 );
            }

            $this->image->setOption( 'jpeg:fancy-upsampling', 'off' );
          }

          if ( 'image/png' === $this->mime_type ) {
            $this->image->setOption( 'png:compression-filter', '5' );
            $this->image->setOption( 'png:compression-level', '9' );
            $this->image->setOption( 'png:compression-strategy', '0' ); // QNRWP changed from 1 for better compression
            $this->image->setOption( 'png:exclude-chunk', 'all' );
          }

          /*
           * If alpha channel is not defined, set it opaque.
           *
           * Note that Imagick::getImageAlphaChannel() is only available if Imagick
           * has been compiled against ImageMagick version 6.4.0 or newer.
           */
          if ( is_callable( array( $this->image, 'getImageAlphaChannel' ) )
            && is_callable( array( $this->image, 'setImageAlphaChannel' ) )
            && defined( 'Imagick::ALPHACHANNEL_UNDEFINED' )
            && defined( 'Imagick::ALPHACHANNEL_OPAQUE' )
          ) {
            if ( $this->image->getImageAlphaChannel() === Imagick::ALPHACHANNEL_UNDEFINED ) {
              $this->image->setImageAlphaChannel( Imagick::ALPHACHANNEL_OPAQUE );
            }
          }

          // Limit the bit depth of resized images to 8 bits per channel.
          if ( is_callable( array( $this->image, 'getImageDepth' ) ) && is_callable( array( $this->image, 'setImageDepth' ) ) ) {
            if ( 8 < $this->image->getImageDepth() ) {
              $this->image->setImageDepth( 8 );
            }
          }

          // QNRWP: keep interlacing, for JPEGS
          if ($this->mime_type == 'image/jpeg') {
            if ( is_callable( array( $this->image, 'setInterlaceScheme' ) ) && defined( 'Imagick::INTERLACE_LINE' ) ) {
              $this->image->setInterlaceScheme( Imagick::INTERLACE_LINE );
            }
          } else { // PNG etc.
            if ( is_callable( array( $this->image, 'setInterlaceScheme' ) ) && defined( 'Imagick::INTERLACE_NO' ) ) {
              $this->image->setInterlaceScheme( Imagick::INTERLACE_NO );
            }
          }

        }
        catch ( Exception $e ) {
          return new WP_Error( 'image_resize_error', $e->getMessage() );
        }
      }
    }
  }

	array_unshift($editors, 'QNRWP_Image_Editor');
	return $editors;
}
add_filter('wp_image_editors', 'qnrwp_image_editor_init');
// Set quality with filter hook and settings option rather than hardcoding in class
add_filter('jpeg_quality', function($args) {return get_option('qnrwp_jpeg_quality', $default='60');});


// ----------------------- Regenerate Images

// Must be here, not in functions-admin.php, because called by cron, not from admin UI
function qnrwp_regenerate_images_cron() {
  // Unlimit max execution time
  ini_set('max_execution_time', 0);
  try {
    if (!function_exists('wp_generate_attachment_metadata')) require_once(ABSPATH . 'wp-admin/includes/image.php'); // Required
    // Get the saved options/record array from database, assumed already created as part of UI code and populated partly by scheduler
    $riSavedOptions = get_option('qnrwp_regenerate_images_record');
    // Get the image attachments from database
    $images_query = new WP_Query(array( 'post_type' => 'attachment', 
                                        'post_status' => 'any', 
                                        'nopaging' => true, 
                                        'post_mime_type' => array('image/gif','image/jpeg','image/png')));
    $amL = []; // Attachment metadata list
    foreach ($images_query->posts as $post) {
      $amL[$post->ID] = wp_get_attachment_metadata($post->ID);
    }
    unset($images_query); // Get rid of the query object, no longer needed
    if (count($amL) == 0) { // Set the error if no attachments found
      $riSavedOptions['error'] = 'No images found';
    }
    // Read the Media admin settings options to compare with previous run
    // We assume settings have been saved just before the cron run 
    $mSetsL = array(  'thumbnail_size_w' => get_option('thumbnail_size_w'),
                      'thumbnail_size_h' => get_option('thumbnail_size_h'),
                      'thumbnail_crop' => get_option('thumbnail_crop'),
                      'medium_size_w' => get_option('medium_size_w'),
                      'medium_size_h' => get_option('medium_size_h'),
                      'large_size_w' => get_option('large_size_w'),
                      'large_size_h' => get_option('large_size_h'),
                      'uploads_use_yearmonth_folders' => get_option('uploads_use_yearmonth_folders'),
                      'qnrwp_jpeg_quality' => get_option('qnrwp_jpeg_quality'));
    // If there was a previous run and did not finish, and settings are unchanged, complete it
    if (!$riSavedOptions['end-time'] && $riSavedOptions['settings-used'] == $mSetsL && $riSavedOptions['processed-ids']) {
      $partDone = true;
    } else { // Start from the beginning
      $partDone = false;
    }
    // Regenerate sized images
    // Set in-progress options/record
    $riSavedOptions['end-time'] = 0; // Reset in case a previous run completed (should be redundant, set to 0 by scheduler)
    $riSavedOptions['images-count'] = count($amL); // Update just in case
    if (!$partDone) $riSavedOptions['processed-ids'] = [];
    $riSavedOptions['settings-used'] = $mSetsL;
    $upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
    //$debugCount = 0; // TEST
    foreach ($amL as $attach_id => $image_data) {
      if (!$partDone || ($partDone && !in_array($attach_id, $riSavedOptions['processed-ids']))) {
        //$debugCount += 1;
        $uploaded_image_location = $upload_dir['basedir'] . '/' . $image_data['file'];
        $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image_location); // May include a filter hook update
        if ($image_data != $attach_data) wp_update_attachment_metadata($attach_id, $attach_data); // May be redundant, unavoidably
        $riSavedOptions['processed-ids'][] = $attach_id;
        $riSavedOptions['last-update'] = time();
        // Reduce full-size original if larger than 2500px width (may be redundant if filter hook being used)
        if ($attach_data['width'] > 2500) { // Due to the above, we are now testing possibly updated metadata, including by any filter hook
          $imageFull = wp_get_image_editor($uploaded_image_location);
          // Set JPEG quality to half way between Media setting and 100, for better quality
          $imageFull->set_quality(((100 - $mSetsL['qnrwp_jpeg_quality'])/2) + $mSetsL['qnrwp_jpeg_quality']);
          //qnrwp_debug_printout(array('TEST in loop::', $imageFull, $uploaded_image_location, $attach_data)); // TEST
          $imageFull->resize(2500, null, false);
          $imageFull->save($uploaded_image_location);
          // Update the metadata array again
          $attach_data['width'] = $imageFull->get_size()['width'];
          $attach_data['height'] = $imageFull->get_size()['height'];
          wp_update_attachment_metadata($attach_id, $attach_data);
          unset($imageFull); // Just in case...
        }
        // Save the options/record
        if (!update_option('qnrwp_regenerate_images_record', $riSavedOptions)) throw new Exception('Database options during processing for Regenerate Images could not be saved.');
      }
    }
    // Start-time was set by scheduler in functions-admin.php
    $riSavedOptions['last-update'] = time();
    $riSavedOptions['end-time'] = date('r');
    // Save the options/record
    if (!update_option('qnrwp_regenerate_images_record', $riSavedOptions)) throw new Exception('Database options for Regenerate Images could not be saved.');
    //qnrwp_debug_printout(array('Regenerate Images cron run', date('r'), $riSavedOptions)); // TEST
  }
  catch (Exception $e) {
    error_log(  'WP cron error:  '.$e->getMessage().' in '.PHP_EOL.$e->getFile().':'.$e->getLine().PHP_EOL
                .'Stack trace:'.PHP_EOL.$e->getTraceAsString().PHP_EOL.'  thrown in '.$e->getFile().' on line '.$e->getLine()  );
  }
  return 0;
  
  //// List all sized images NOT USED, but kept for reference
  //$upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
  //foreach ($amL as $image_data) {
    //$uploaded_image_location = $upload_dir['basedir'] . '/' . $image_data['file'];
    //$sized_images_dir = $upload_dir['basedir'] . '/' . substr($image_data['file'], 0, strrpos($image_data['file'], '/'));
    //foreach ($image_data['sizes'] as $sized_image) {
      //$siL[] = $sized_images_dir . '/' . $sized_image['file'];
    //}
  //}
  
  ////qnrwp_debug_printout(array('Pre-option saving::',$option,$old_value,$value,$amL,wp_upload_dir()));
  //qnrwp_debug_printout(array('Pre-option saving::',$option,$old_value,$value));
  //return 0;
}
add_action('qnrwp_regenerate_images_hook', 'qnrwp_regenerate_images_cron', 10);


// ===================== MORE TESTS (WPSE) =====================

//// CUSTOM ADMIN MENU LINK FOR ALL SETTINGS
   //function all_settings_link() {
    //add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
   //}
   //add_action('admin_menu', 'all_settings_link');
   
?>