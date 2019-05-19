<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP contact form class
 */
class QNRWP_Contact_Form {
  /**
   * Send email, by Ajax, called statically
   */
  public static function send_email($datajson) {
    // Called by Ajax handler, using POST to pass datajson
    $dataL = json_decode($datajson, $assoc = true);
    // On error, returned string must begin with "ERROR:"
    if (!isset($dataL) || empty($dataL)) return 'ERROR: '.__('No data to email', 'qnrwp');
    
    // Check transient records for count of all recent mailings by all users
    $allMailingsCount = get_transient('qnrwp_mailings_count');
    if ($allMailingsCount && $allMailingsCount > 10) { // Allow 11 mailings per 30 mins on whole site TODO pref setting
      return 'ERROR: '.__('Sorry, due to heavy traffic on the site, your message could not be sent. You may try again in 30 minutes.', 'qnrwp');
    }
    
    // Check transient records for recent mailings by this client, per form class
    $tlmsOldRecord = get_transient('qnrwp_last_mailing_'.$_COOKIE['qnrwp_site_cookie'].$dataL['formclass']);
    if ($tlmsOldRecord !== false) { // Transient record exists, not expired, disallow email
      $sitewideBlockingMinutesRemaining = max(0, 5 - intval(ceil((time() - $tlmsOldRecord)/60)));
      if ($sitewideBlockingMinutesRemaining) {
        // Return special error
        $errorMinsRemain = esc_html__('You have already sent a message using this form.', 'qnrwp');
        $errorMinsRemain .= ' ' . sprintf(esc_html(_n('You may try again in %s minute.', 'You may try again in %s minutes.', $sitewideBlockingMinutesRemaining, 'qnrwp')), 
                                    $sitewideBlockingMinutesRemaining);
        return 'ERROR: ' . $errorMinsRemain;
      }
    }
    if (!isset($sitewideBlockingMinutesRemaining) || !$sitewideBlockingMinutesRemaining) {
      // Get options of the originating form
      $nameBool = false;
      $subjectBool = false;
      $messageBool = false;
      if (isset($dataL['options'])) {
        switch (intval($dataL['options'])) {
          case 1:
            $nameBool = true;
            break;
          case 2:
            $subjectBool = true;
            break;
          case 3:
            $nameBool = true;
            $subjectBool = true;
            break;
          case 4:
            $messageBool = true;
            break;
          case 5:
            $nameBool = true;
            $messageBool = true;
            break;
          case 6:
            $subjectBool = true;
            $messageBool = true;
            break;
          case 7:
            $nameBool = true;
            $subjectBool = true;
            $messageBool = true;
            break;
          default:
        }
      } else {
        return 'ERROR: ' . __('Email not sent due to invalid data', 'qnrwp'); // Return error if options not set (shouldn't happen)
      }
      $emailSent = false; // Almost redundant, but kept
      // Test for adequate input to proceed
      if (isset($dataL['email']) 
            && isset($dataL['clientip'])
            && isset($dataL['permalink'])
            && (($messageBool && isset($dataL['message'])) || !$messageBool)) {
        
        // If data won't validate, $emailSent will remain false
        // Using 'name' as an INPUT name doesn't work, we use 'emailname'
        if (strlen($dataL['email']) < 100 
              && is_email($dataL['email']) 
              && strlen(get_bloginfo('admin_email')) < 100 
              && is_email(get_bloginfo('admin_email')) 
              && ((isset($dataL['emailname']) && mb_strlen($dataL['emailname']) < 100) || !isset($dataL['emailname'])) 
              && ((isset($dataL['subject']) && mb_strlen($dataL['subject']) < 100) || !isset($dataL['subject'])) 
              && (($messageBool && mb_strlen($dataL['message']) < 501) || !$messageBool)) {
          // Construct the message
          $emailAddress = $dataL['email'];
          $emailSubject = ($subjectBool && isset($dataL['subject'])) ? $dataL['subject'] : '';
          $emailMessage = ($messageBool && isset($dataL['message'])) ? $dataL['message'] : '';
          $emailName = ($nameBool && isset($dataL['emailname'])) ? sanitize_text_field($dataL['emailname']) : '';
          // If no subject (and no placeholder obtained by JS caller), create default, do not allow empty
          if (!$emailSubject) {
            $subjectFirstWord = (!$subjectBool && !$messageBool) ? __('Subscription', 'qnrwp') : __('Enquiry', 'qnrwp');
            $emailSubject = (mb_strlen(get_bloginfo('name')) > 50) 
                            ? $subjectFirstWord.' ' . __('from a website visitor', 'qnrwp')
                            : $subjectFirstWord.' '.sprintf(__('from a %s website visitor', 'qnrwp'), sanitize_text_field(get_bloginfo('name')));
          }
          // If no message, construct generic one, only email address submitted, probably for subscription
          if (!$emailMessage) {
            $emailMessage = __('Email contact details submitted via online form', 'qnrwp').':' . PHP_EOL . PHP_EOL;
            if ($emailName) $emailMessage .= __('Name', 'qnrwp').': ' . $emailName . PHP_EOL . PHP_EOL;
            $emailMessage .= __('Email address', 'qnrwp').': ' . $emailAddress;
          } else if ($emailName) { // Place the name in the message, for more reliable visibility than only in Reply-To
            $emailMessage = __('Message from', 'qnrwp').' "' . $emailName . '":' . PHP_EOL . PHP_EOL . $emailMessage;
          }
          $mT = $emailMessage . PHP_EOL . PHP_EOL;
          // Append a note about the message source TODO improve tranlations
          $mT .= '=========='.PHP_EOL;
          $mT .= __('Message sent from', 'qnrwp').' IP '.$dataL['clientip'].PHP_EOL;
          $mT .= __('using the online contact form at', 'qnrwp').' '.PHP_EOL;
          $mT .= '<'. $dataL['permalink'].'>'.PHP_EOL;
          $mT .= '=========='.PHP_EOL;
          // Set Reply-To header to user's email, not WP
          if ($emailName && !preg_match('/[^a-zA-Z\. -]+/',$emailName)) $headers = array('Reply-To: '.$emailName.' <'.$emailAddress.'>');
          else $headers = array('Reply-To: <'.$emailAddress.'>');
          
          // Send the email, record transients and return
          $emailSent = wp_mail(get_bloginfo('admin_email'), $emailSubject, $mT, $headers);
          if ($emailSent) {
            // Record mailing counter
            if ($allMailingsCount === false) set_transient('qnrwp_mailings_count', 0, 30 * MINUTE_IN_SECONDS);
            else set_transient('qnrwp_mailings_count', $allMailingsCount + 1, 30 * MINUTE_IN_SECONDS);
            // Record last mailing time for this client
            set_transient('qnrwp_last_mailing_'.$_COOKIE['qnrwp_site_cookie'].$dataL['formclass'], time(), 5 * MINUTE_IN_SECONDS);
            return 'Success: ' . __('Email sent', 'qnrwp');
          }
          else return 'ERROR: ' . __('Email sending failed', 'qnrwp');
        }
      }
      return 'ERROR: ' . __('Email could not be sent', 'qnrwp');
    }
  }
  
  /**
   * Renders the contact form, called by theme shortcode, statically
   * 
   * @param array   $options    Array of 'subject','message','name','warnings' etc.
   */
  public static function contact_form_render($options) {
    // Convert string boolean inputs into booleans (placeholders etc. not boolean)
    $nameBool = qnrwp_interpret_bool_input($options['name']);
    $subjectBool = qnrwp_interpret_bool_input($options['subject']);
    $messageBool = qnrwp_interpret_bool_input($options['message']);
    $titleBool = qnrwp_interpret_bool_input($options['title']);
    $introBool = qnrwp_interpret_bool_input($options['intro']);
    $tooltipsBool = qnrwp_interpret_bool_input($options['tooltips']);
    $warningsBool = qnrwp_interpret_bool_input($options['warnings']);
    $autofocusBool = qnrwp_interpret_bool_input($options['autofocus']);
    $optionsInt = ($nameBool?1:0)+($subjectBool?2:0)+($messageBool?4:0); // Similar to 1,2,4 of Unix permissions
    // Set global count of contact forms, to set unique form names and IDs
    if (isset($GLOBALS['QNRWP_GLOBALS']['ContactFormsCount'])) $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount'] += 1;
    else $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount'] = 1;
    ?>
  <div class="contact-block <?php echo $options['form-class']; // No esc_attr needed, test done in shortcode caller ?>">
    <?php if ($titleBool) echo '<p class="contact-title">'.esc_html($options['title-text']).'</p>' ?>
    <?php if ($introBool) echo '<p class="contact-intro">'.esc_html($options['intro-text']).'</p>' ?>
    <form name="<?php echo 'contact-form' . $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount']; ?>" 
          id="<?php echo 'contact-form' . $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount']; ?>" 
          action="" 
          method="post"
          onsubmit="QNRWP.Contact.send_email(this, event)"><?php // TODO namespace JS functions ?>
      <input type="hidden" name="form-name-hidden" class="form-name-hidden" value="<?php echo 'contact-form' . $GLOBALS['QNRWP_GLOBALS']['ContactFormsCount']; ?>">
      <input type="hidden" name="client-ip-hidden" class="client-ip-hidden" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
      <input type="hidden" name="permalink-hidden" class="permalink-hidden" value="<?php echo esc_url(get_permalink()); ?>">
      <input type="hidden" name="options-hidden" class="options-hidden" value="<?php echo $optionsInt; ?>">
      <?php 
      // Thank you page, if set
      if (isset($options['thank-slug']) && !empty($options['thank-slug'])): ?>
      <input type="hidden" name="thank-slug-hidden" class="thank-slug-hidden" value="<?php echo base64_encode(get_permalink(get_page_by_path($options['thank-slug']))); ?>">
      <?php endif; ?>
      <input type="hidden" name="sent-reply-hidden" class="sent-reply-hidden" value="<?php echo base64_encode($options['sent-reply']); ?>">
      <input type="hidden" name="fail-reply-hidden" class="fail-reply-hidden" value="<?php echo base64_encode($options['fail-reply']); ?>">
      <input type="hidden" name="form-class-hidden" class="form-class-hidden" value="<?php echo bin2hex($options['form-class']); ?>">
  <?php if ($nameBool): // Must use 'emailname' instead of 'name'... ?>
      <div class="contact-nameframe contact-subframe">
        <?php if ($options['label-name']): ?><span class="label label-name"><?php echo esc_html($options['label-name']); ?></span><?php endif; ?><input 
                    type="text" 
                    name="emailname" 
                    placeholder="<?php echo esc_attr($options['placeholder-name']); ?>" 
                    class="name user-entry" 
                    maxlength="60"<?php echo $autofocusBool?' autofocus':''; ?><?php echo $tooltipsBool?' title="'.esc_attr__('Your name', 'qnrwp').'"':''; ?>>
      </div>
  <?php endif; ?>
      <div class="contact-emailframe contact-subframe">
        <!-- No whitespace -->
        <?php if ($options['label-email']): ?><span class="label label-email"><?php echo esc_html($options['label-email']); ?></span><?php endif; ?><input 
                    type="email" 
                    name="email" 
                    class="email user-entry" 
                    maxlength="60" 
                    required 
                    placeholder="<?php echo esc_attr($options['placeholder-email']); ?>" 
                    <?php echo ($autofocusBool && !$nameBool)?'autofocus':''; ?><?php echo $tooltipsBool?' title="'.esc_attr__('Your email address', 'qnrwp').'"':''; ?>>
      </div>
  <?php if ($subjectBool): ?>
      <div class="contact-subjectframe contact-subframe">
        <?php if ($options['label-subject']): ?><span class="label label-subject"><?php echo esc_html($options['label-subject']); ?></span><?php endif; ?><input 
                    type="text" 
                    name="subject" 
                    placeholder="<?php echo esc_attr($options['placeholder-subject']); ?>" 
                    class="subject user-entry" 
                    maxlength="60">
      </div>
  <?php endif; ?>
  <?php if ($messageBool): ?>
      <div class="contact-textframe contact-subframe">
        <?php if ($options['label-message']): ?><span class="label label-message"><?php echo esc_html($options['label-message']); ?></span><?php endif; ?><textarea 
                    pattern=".{40,500}" 
                    required 
                    cols="20" 
                    name="message" 
                    placeholder="<?php echo esc_attr($options['placeholder-message']); ?>" 
                    class="message user-entry" 
                    rows="10" 
                    minlength="40" 
                    maxlength="500" 
                    onkeyup="QNRWP.Contact.count_text_area(this,'count')" 
                    onblur="QNRWP.Contact.count_text_area(this,'reset')"></textarea>
  <?php if ($warningsBool): ?>
  <p class="user-info"><span class="textarea-count"><?php esc_html_e('Max 500 characters', 'qnrwp'); ?></span><span class="client-ip"><?php esc_html_e('Your IP', 'qnrwp'); ?>: <?php echo $_SERVER['REMOTE_ADDR']; ?></span>
  </p>
  <?php endif; ?>
      </div>
  <?php endif; ?>
      <div class="contact-send-btn">
        <button 
                    type="submit" 
                    name="form-send" 
                    class="form-send" 
                    value="Send"><?php echo esc_html($options['label-submit']); ?></button>
      </div>
    </form>
  </div>
    <?php
  } // End of contact_form_render function


} // End QNRWP_Contact_Form class
