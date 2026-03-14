<?php
namespace src\util\utils;

/**
 * Trait images
 * @package Utils
 */
trait images
{
    /**
    * Resiza image
    *
    * @param $id      account id
    * @return mixed   Account name
    */
    public function image_resize( $source, $width, $height, $new_width, $new_height) {
        $resized_image = imagecreatetruecolor( $new_width, $new_height);
        imagecopyresampled( $resized_image, $source,0,0,0,0, $new_width, $new_height, $width, $height);
        return $resized_image;
    }
}
