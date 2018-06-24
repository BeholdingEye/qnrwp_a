<?php

defined( 'ABSPATH' ) || exit;

/**
 * Our own image editor, for higher image quality and better optimization
 */
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
  
} // End QNRWP_Image_Editor class

