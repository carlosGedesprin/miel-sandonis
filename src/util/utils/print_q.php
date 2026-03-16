<?php
namespace src\util\utils;

use setasign\Fpdi\Tcpdf\Fpdi;

/**
 * Trait print
 * @package Utils
 */
trait print_q
{
    /**
    * parm data        Doc to be printed
    *
    */
    public function printDoc( $doc_row, $out_folder , $out_type )
    {
$myfile = fopen(APP_ROOT_PATH.'/var/logs/utils_'.__METHOD__.'.txt', 'w') or die('Unable to open file!');
$txt = 'utils '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
$txt = PHP_EOL.'Document ==>'.print_r( $doc_row, true ).PHP_EOL; fwrite($myfile, $txt);

        // Add % to the begin and end of the to compare with the text
        $assign_vars = array();
        $assign_vars_tmp = unserialize($doc_row['assign_vars']);
//fwrite($myfile, print_r($assign_vars_tmp, TRUE));

        // Adding % to the keys to match the "variables" on text field
        foreach($assign_vars_tmp as $key=>$value)
        {
            $assign_vars['%'.$key.'%'] = $value;
        }
        unset($assign_vars_tmp);
//fwrite($myfile, print_r($assign_vars, TRUE));

        $template = $this->db->fetchOne( 'document', '*', ['reference' => $doc_row['template']]);

        // create new PDF document
        // default args => $orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false
        // DEF CONST   PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true,          'UTF-8',             false);

//fwrite($myfile, print_r($template, TRUE));

//$txt = 'Template pdf ('.$template['template'].')'.PHP_EOL;
//fwrite($myfile, $txt);
            $pdf_with_path = 'bundles/framework/documents/pdf_templates/'.$template['template'];
            // initiate FPDI
            $pdf = new Fpdi();

            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            $pageCount = $pdf->setSourceFile($pdf_with_path);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);

                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);
                // Size array
                // [width] => 209.90281305556, [height] => 296.68611111111,
                // [0] => 209.90281305556, [1] => 296.68611111111,
                // [orientation] => P
//fwrite($myfile, print_r($size, TRUE));
                $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

                // use the imported page
                $pdf->useTemplate($templateId);

//                $pdf->SetFont('Helvetica');
//                $pdf->SetXY(5, 5);
//                $pdf->Write(8, 'A complete document imported with FPDI');
            }
        

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor( $doc_row['web_name'] );
        $pdf->SetTitle( $template['description'] );
        $pdf->SetSubject( $template['description'] );
        $pdf->SetKeywords( '"' . $doc_row['web_name'] . ', ' . $template['description'] . '"');

        //Setup PDF File defaults
        $pdf->SetTitle($template['description']);
        $pdf->SetMargins(5, 5);
        $pdf->SetAutoPageBreak(TRUE);
        $pdf->setPrintHeader(FALSE);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetFont('helvetica', '', 9);

        //$pdf->SetCompression(true);

//$txt = 'Template id ('.$template['id'].')'.PHP_EOL;
//fwrite($myfile, $txt);
        $template_rows = $this->db->fetchAll('document_item', '*', ['document' => $template['id']], 'ORDER BY page');
        foreach ( $template_rows as $template_row )
        {
            $pdf->setPage($template_row['page'], true);
//$txt = 'Template type ('.$template_row['type'].')'.PHP_EOL;
//fwrite($myfile, $txt);
            // Replace text, if is not empty with assigned vars in the appropiate cases
            if ( in_array( $template_row['type'], ['1', '2', '3', '4', '5', '6'] ) ) {
                if ($template_row['text'] != "") $template_row['text'] = str_replace(array_keys($assign_vars), array_values($assign_vars), $template_row['text']);
            }
            // Process each template item
            switch ( $template_row['type'] ) {
                case '1':
                    // Text

                    $pdf->SetXY(
                                $template_row['column_start'],      // The value of the abscissa.
                                $template_row['line_start'],        // The value of the ordinate.
                                true                                // if true always uses the page top-left corner as origin of axis.
                    );
                    $pdf->SetFont(
                                $template_row['font_family'],    // Family font. It can be either a name defined by AddFont() or one of the standard Type1 families (case insensitive):
                                $template_row['font_style'],     // Font style. Possible values are (case insensitive) '' regular B bold I italic U underline D line trough O overline
                                $template_row['font_size'],      // Font size in points. The default value is the current size. If no size has been specified since the beginning of the document, the value taken is 12
                                ''                               // The font definition file. By default, the name is built from the family and style, in lower case with no spaces.
                    );
                    list($r, $g, $b) = $this->hex_to_rgb( $template_row['font_color'] );
                    $pdf->SetTextColor( $r, $g, $b );
                    $pdf->Write
                                (  1,                        // Line height
                                    $template_row['text'],   // String to print
                                    $template_row['url'],    // URL or identifier returned by AddLink() Link = nil
                                    0,                       // Indicates if the background must be painted (1) or transparent (0). fill = 0
                                    '',                      // Allows to center or align the text. Possible values are:
                                                             //          L or empty string: left align (default value)
                                                             //          C: center
                                                             //          R: right align
                                                             //          J: justify
                                    false,                   // if true set cursor at the bottom of the line, otherwise set cursor at the top of the line. ln = false
                                    0,                       // stretch carachter mode:
                                                             //          0 = disabled
                                                             //          1 = horizontal scaling only if necessary
                                                             //          2 = forced horizontal scaling
                                                             //          3 = character spacing only if necessary
                                                             //          4 = forced character spacing
                                    false,                   // if true prints only the first line and return the remaining string. firstline = false
                                    false,                   // if true the string is the starting of a line. firstblock = false
                                    0                        // maximum height. The remaining unprinted text will be returned.
                                                             // It should be >= :h and less then remaining space to the bottom of the page, or 0 for disable this feature. maxh = 0
                                );

                    break;
                case '2':
                    // Text box
                    
                    // set rotation
                    if ( $template_row['rotation'] == '1' )
                    {
                        // Start Transformation
                        $pdf->StartTransform();
                        // Rotate $template_row['rotation_degree'] degrees counter-clockwise centered by ($template_row['line_end'],$template_row['column_start']) which is the lower left corner
                        $pdf->Rotate($template_row['rotation_degree'], $template_row['column_start'], $template_row['line_end']);
                    }
                    $pdf->SetXY(
                                $template_row['column_start'],      // The value of the abscissa.
                                $template_row['line_start'],        // The value of the ordinate.
                                true                                // if true always uses the page top-left corner as origin of axis.
                    );
                    $width = $template_row['column_end'] - $template_row['column_start'];
                    $height = $template_row['line_end'] - $template_row['line_start'];

                    // Border
                    if ( $template_row['border'] == '1' )
                    {
                        // set border width
                        $pdf->SetLineWidth( $template_row['border_w'] );

                        // set border color
                        list($r, $g, $b) = $this->hex_to_rgb( $template_row['border_color'] );
                        $pdf->SetDrawColor( $r, $g, $b );
                    }

                    // set background color
                    $fill_background = '0';
                    if ( $template_row['background_color'] != '-' )
                    {
                        $fill_background = '1';
                        list($r, $g, $b) = $this->hex_to_rgb( $template_row['background_color'] );
                        $pdf->SetFillColor( $r, $g, $b );
                    }


                    $pdf->SetFont(
                        $template_row['font_family'],    // Family font. It can be either a name defined by AddFont() or one of the standard Type1 families (case insensitive):
                        $template_row['font_style'],     // Font style. Possible values are (case insensitive) '' regular B bold I italic U underline D line trough O overline
                        $template_row['font_size'],      // Font size in points. The default value is the current size. If no size has been specified since the beginning of the document, the value taken is 12
                        ''                               // The font definition file. By default, the name is built from the family and style, in lower case with no spaces.
                    );

                    // set text color
                    list($r, $g, $b) = $this->hex_to_rgb( $template_row['font_color'] );
                    $pdf->SetTextColor( $r, $g, $b );

                    $pdf->setCellPaddings( $template_row['padding'], $template_row['padding'], $template_row['padding'], $template_row['padding']);
                    $pdf->setCellMargins(0,0,0,0);

                    $pdf->Cell(
                                $width,                     // Cell width. If 0, the cell extends up to the right margin.
                                $height,                    // Cell height. Default value: 0.
                                $template_row['text'],      // String to print. Default value: empty string.
                                $template_row['border'],    // Indicates if borders must be drawn around the cell. The value can be either a number:
                                                            //       0: no border (default)
                                                            //       1: frame
                                                            // or a string containing some or all of the following characters (in any order):
                                                            //       L: left
                                                            //       T: top
                                                            //       R: right
                                                            //       B: bottom
                                1,                          // Indicates where the current position should go after the call. Possible values are:
                                                            //       0: to the right
                                                            //       1: to the beginning of the next line
                                                            //       2: below
                                                            // Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
                                $template_row['text_align'],// Allows to center or align the text. Possible values are:
                                                            //      L or empty string: left align (default value)
                                                            //      C: center
                                                            //      R: right
                                $fill_background,           // Indicates if the cell background must be painted (1) or transparent (0). Default value: 0.
                                $template_row['url'],       // URL or identifier returned by AddLink().
                                1,                          // stretch character mode:
                                                            //      0 = disabled
                                                            //      1 = horizontal scaling only if necessary
                                                            //      2 = forced horizontal scaling
                                                            //      3 = character spacing only if necessary
                                                            //      4 = forced character spacing
                                false,                      // if true ignore automatic minimum height value.
                                'T',                        // cell vertical alignment relative to the specified Y value. Possible values are:
                                                            //      T : cell top
                                                            //      C : center
                                                            //      B : cell bottom
                                                            //      A : font top
                                                            //      L : font baseline
                                                            //      D : font bottom
                                $template_row['text_valign'] // text vertical alignment inside the cell. Possible values are:
                                                            //      T : top
                                                            //      M : middle, default
                                                            //      B : bottom
                    );
                    if ( $template_row['rotation'] == '1' )
                    {
                        // Stop Transformation
                        $pdf->StopTransform();
                    }

                    unset($width, $height, $fill_background, $r, $g, $b);

                    break;
                case '3':
                    // Box with text
                    str_replace('\n', "\r\n", $template_row['text']);
                    
                    // set rotation
                    if ( $template_row['rotation'] == '1' )
                    {
                        // Start Transformation
                        $pdf->StartTransform();
                        // Rotate $template_row['rotation_degree'] degrees counter-clockwise centered by ($template_row['line_end'],$template_row['column_start']) which is the lower left corner
                        $pdf->Rotate($template_row['rotation_degree'], $template_row['column_start'], $template_row['line_end']);
                    }
                    
                    $pdf->SetXY(
                        $template_row['column_start'],      // The value of the abscissa.
                        $template_row['line_start'],        // The value of the ordinate.
                        true                                // if true always uses the page top-left corner as origin of axis.
                    );
                    $width = $template_row['column_end'] - $template_row['column_start'];
                    $height = $template_row['line_end'] - $template_row['line_start'];

                    // Border
                    if ( $template_row['border'] != '0' )
                    {
                        // set border width
                        $pdf->SetLineWidth( $template_row['border_w'] );

                        // set border color
                        list($r, $g, $b) = $this->hex_to_rgb( $template_row['border_color'] );
                        $pdf->SetDrawColor( $r, $g, $b );
                    }

                    // set background color
                    $fill_background = '0';
                    if ( $template_row['background_color'] != '-' )
                    {
                        $fill_background = '1';
                        list($r, $g, $b) = $this->hex_to_rgb( $template_row['background_color'] );
                        $pdf->SetFillColor( $r, $g, $b );
                    }

                    $pdf->SetFont(
                        $template_row['font_family'],    // Family font. It can be either a name defined by AddFont() or one of the standard Type1 families (case insensitive):
                        $template_row['font_style'],     // Font style. Possible values are (case insensitive) '' regular B bold I italic U underline D line trough O overline
                        $template_row['font_size'],      // Font size in points. The default value is the current size. If no size has been specified since the beginning of the document, the value taken is 12
                        ''                               // The font definition file. By default, the name is built from the family and style, in lower case with no spaces.
                    );

                    // set text color
                    list($r, $g, $b) = $this->hex_to_rgb( $template_row['font_color'] );
                    $pdf->SetTextColor( $r, $g, $b );

                    $pdf->setCellPaddings( $template_row['padding'], $template_row['padding'], $template_row['padding'], $template_row['padding']);
                    $pdf->setCellMargins(0,0,0,0);

                    $pdf->MultiCell(
                                    $width,                     // Cell width. If 0, the cell extends up to the right margin.
                                    $height,                    // Cell height. Default value: 0.
                                    $template_row['text'],      // String to print. Default value: empty string.
                                    $template_row['border'],    // Indicates if borders must be drawn around the cell. The value can be either a number:
                                                                //       0: no border (default)
                                                                //       1: frame
                                                                // or a string containing some or all of the following characters (in any order):
                                                                //       L: left
                                                                //       T: top
                                                                //       R: right
                                                                //       B: bottom

                                    $template_row['text_align'],// Allows to center or align the text. Possible values are:
                                                                //      L or empty string: left align (default value)
                                                                //      C: center
                                                                //      R: right
                                                                //      J: justification (default value when :ishtml=false)

                                    $fill_background,           // Indicates if the cell background must be painted (1) or transparent (0). Default value: 0.
                                    1,                          // Indicates where the current position should go after the call. Possible values are:
                                                                //       0: to the right
                                                                //       1: to the beginning of the next line
                                                                //       2: below
                                    $template_row['column_start'],  // x position in user units
                                    $template_row['line_start'],    // y position in user units
                                    true,                       // if true reset the last cell height (default true).
                                    $template_row['stretch_char'], // stretch character mode:
                                                                //      0 = disabled
                                                                //      1 = horizontal scaling only if necessary
                                                                //      2 = forced horizontal scaling
                                                                //      3 = character spacing only if necessary
                                                                //      4 = forced character spacing
                                    false,                      // isHTML set to true if :txt is HTML content (default = false).
                                    true,                       // if true, uses internal padding and automatically adjust it to account for line width.
                                    0                           // maximum height. It should be >= :h and less then remaining space to the bottom of the page, or 0 for disable this feature.
                                                                // This feature works only when :ishtml=false.
                    );

                    if ( $template_row['rotation'] == '1' )
                    {
                        // Stop Transformation
                        $pdf->StopTransform();
                    }
                    
                    break;

                case '4':
                    // Bar code

                    $width = $template_row['column_end'] - $template_row['column_start'];
                    $height = $template_row['line_end'] - $template_row['line_start'];

                    // define barcode style
                    $style = array(
                                    'position' => '',           // string $style['position'] barcode position inside the specified width:
                                                                //      L = left (default for LTR);
                                                                //      C = center;
                                                                //      R = right (default for RTL);
                                                                //      S = stretch
                                    'align' => 'C',
                                    'stretch' => false,
                                    'fitwidth' => true,
                                    'cellfitalign' => '',
                                    'border' => true,           // boolean $style['border'] if true prints a border around the barcode
                                    //padding => '1',           // int $style['padding'] padding to leave around the barcode in user units
                                    'hpadding' => 'auto',
                                    'vpadding' => 'auto',
                                    'fgcolor' => array(0,0,0),  // array $style['fgcolor'] color array for bars and text
                                    'bgcolor' => false,         // mixed $style['bgcolor'] color array for background or false for transparent
                                    'text' => true,             // boolean $style["text"] boolean if true prints text below the barcode
                                    'font' => $template_row['font_family'],     // string $style['font'] font name for text
                                    'fontsize' => $template_row['font_size'],   // int $style['fontsize'] font size for text
                                    'stretchtext' => 4          // int $style['stretchtext'] options are:
                                                                //      0 = disabled;
                                                                //      1 = horizontal scaling only if necessary;
                                                                //      2 = forced horizontal scaling;
                                                                //      3 = character spacing only if necessary;
                                                                //      4 = forced character spacing
                    );

                    //$pdf->Cell(0, 0, 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9', 0, 1);
                    //write1DBarcode( string $code, string $type, [int $x = ''], [int $y = ''], [int $w = ''], [int $h = ''], [float $xres = 0.4], [array $style = ''], [string $align = ''])
                    $pdf->write1DBarcode(
                                            $template_row['text'],          // Text to be represented by the bar code
                                            $template_row['barcode_type'],  // type of barcode
                                            $template_row['column_start'],  // x position in user units
                                            $template_row['line_start'],    // y position in user units
                                            $width,                         // width in user units
                                            $height,                        // height in user units
                                            0.4,            // width of the smallest bar in user units
                                            $style,         // Array with barcode style
                                            'N'             // Indicates the alignment of the pointer next to barcode insertion relative to barcode height.
                                                            // The value can be:
                                                            //      T: top-right for LTR or top-left for RTL
                                                            //      M: middle-right for LTR or middle-left for RTL
                                                            //      B: bottom-right for LTR or bottom-left for RTL
                                                            //      N: next line
                    );

                    break;

                case '5':
                    // Image

                    // set JPEG quality
                    $pdf->setJPEGQuality(75);

                    // Image method signature:
                    // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

                    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                    // Example of Image from data stream ('PHP rules')
                    //$imgdata = base64_decode('iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABlBMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDrEX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==');
                    // The '@' character is used to indicate that follows an image data stream and not an image file name
                    //$pdf->Image('@'.$imgdata);
                    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                    // Image example with resizing
                    //$pdf->Image('images/image_demo.jpg', 15, 140, 75, 113, 'JPG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 1, false, false, false);
                    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                    /*
                    Puts an image in the page. The upper-left corner must be given. The dimensions can be specified in different ways:
                            - explicit width and height (expressed in user unit)
                            - one explicit dimension, the other being calculated automatically in order to keep the original proportions
                            - no explicit dimension, in which case the image is put at 72 dpi
                    Supported formats are PNG images whitout RMagick library and JPEG and GIF images supported by RMagick. For JPEG, all flavors are allowed:
                            - gray scales
                            - true colors (24 bits)
                            - CMYK (32 bits)
                    For PNG, are allowed:
                            - gray scales on at most 8 bits (256 levels)
                            - indexed colors
                            - true colors (24 bits)
                    If a transparent color is defined, it will be taken into account (but will be only interpreted by Acrobat 4 and above).
                    The format can be specified explicitly or inferred from the file extension. It is possible to put a link on the image.

                    Remark: if an image is used several times, only one copy will be embedded in the file.
                    */

                    $width = $template_row['column_end'] - $template_row['column_start'];
                    $height = $template_row['line_end'] - $template_row['line_start'];

                    $pdf->Image(
                                $template_row['text'],          // Name of the file containing the image
                                $template_row['column_start'],  // Abscissa of the upper-left corner.
                                $template_row['line_start'],    // Ordinate of the upper-left corner
                                $width,                         // Width of the image in the page. If not specified or equal to zero, it is automatically calculated.
                                $height,                        // Height of the image in the page. If not specified or equal to zero, it is automatically calculated.
                                '',                             // type Image format. Possible values are (case insensitive): JPG, JPEG, PNG. If not specified, the type is inferred from the file extension.
                                $template_row['url'],           // URL or identifier returned by AddLink().
                                '',                             // align Indicates the alignment of the pointer next to image insertion relative to image height. The value can be:
                                                                //      T: top-right for LTR or top-left for RTL
                                                                //      M: middle-right for LTR or middle-left for RTL
                                                                //      B: bottom-right for LTR or bottom-left for RTL
                                                                //      N: next line
                                false,                          // resize If true resize (reduce) the image to fit :w and :h (requires RMagick library);
                                                                // if false do not resize; if 2 force resize in all cases (upscaling and downscaling).
                                300,                            // dpi dot-per-inch resolution used on resize
                                '',                             // palign Allows to center or align the image on the current line. Possible values are:
                                                                //      L : left align
                                                                //      C : center
                                                                //      R : right align
                                                                //      '' : empty string : left for LTR or right for RTL
                                false,                          // ismask   true if this image is a mask, false otherwise
                                false,                          // imgmask image object returned by this function or false
                                $template_row['border'],        // Indicates if borders must be drawn around the cell. The value can be either a number:
                                                                //       0: no border (default)
                                                                //       1: frame
                                                                // or a string containing some or all of the following characters (in any order):
                                                                //       L: left
                                                                //       T: top
                                                                //       R: right
                                                                //       B: bottom
                                true,                          // fitbox If true scale image dimensions proportionally to fit within the (:w, :h) box.
                                false,                          // hidden if true do not display the image.
                                true                            // fitonpage if true the image is resized to not exceed page dimensions.
                    );

                    break;

                case '6':
                    // HTML


                    // NO FUNCIONA BIEN la funcion del TCPDF

                    // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
                    // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

                    //list($r, $g, $b) = $this->hex_to_rgb( $template_row['font_color'] );
                    //$pdf->SetTextColor( $r, $g, $b );
                    // output the HTML content
                    //writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')

                    $pdf->SetXY(
                        $template_row['column_start'],      // The value of the abscissa.
                        $template_row['line_start'],        // The value of the ordinate.
                        true                                // if true always uses the page top-left corner as origin of axis.
                    );
//----------------------------------------------------------------
/*
                    // Border
                    if ( $template_row['border'] != '0' )
                    {
                        // set border width
                        $pdf->SetLineWidth( $template_row['border_w'] );

                        // set border color
                        list($r, $g, $b) = $this->hex_to_rgb( $template_row['border_color'] );
                        $pdf->SetDrawColor( $r, $g, $b );
                    }

                    // set background color
                    $fill_background = '0';
                    if ( $template_row['background_color'] != '-' )
                    {
                        $fill_background = '1';
                        list($r, $g, $b) = $this->hex_to_rgb( $template_row['background_color'] );
                        $pdf->SetFillColor( $r, $g, $b );
                    }

                    $pdf->SetFont(
                        $template_row['font_family'],    // Family font. It can be either a name defined by AddFont() or one of the standard Type1 families (case insensitive):
                        $template_row['font_style'],     // Font style. Possible values are (case insensitive) '' regular B bold I italic U underline D line trough O overline
                        $template_row['font_size'],      // Font size in points. The default value is the current size. If no size has been specified since the beginning of the document, the value taken is 12
                        ''                               // The font definition file. By default, the name is built from the family and style, in lower case with no spaces.
                    );
*/

                    // set text color
                    list($r, $g, $b) = $this->hex_to_rgb( $template_row['font_color'] );
                    $pdf->SetTextColor( $r, $g, $b );
//----------------------------------------------------------------

                    $html = file_get_contents( APP_ROOT_PATH . '/web/bundles/framework/documents/miscelaneous/' . $template_row['text'] );
                    $html = utf8_encode($html);
/*
echo $html;
                    $pdf->writeHTML(
                                        $html,      // text to display
                                        false,      // if true add a new line after text (default = true)
                                        false,      // Indicates if the background must be painted (1:true) or transparent (0:false).
                                        true,       // if true reset the last cell height (default false).
                                        false,      // if true add the default c_margin space to each Write (default false).
                                        ''          // Allows to center or align the text. Possible values are:
                                                    //      L : left align
                                                    //      C : center
                                                    //      R : right align
                                                    //      '' : empty string : left for LTR or right for RTL
                    );
*/
                    // writeHTMLCell(    $w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
                    $pdf->writeHTMLCell( 80, '', '', '', $html, 0, 0, 0, true, 'J', true);

                    break;
                /*
                default:
                */
            }

        }

        $file_name = $doc_row['template'] . '_' . time() . '_' . $doc_row['id'].'.pdf';
        $file_output = APP_ROOT_PATH.'/web/bundles/framework/documents/'.$out_folder.'/'.$file_name;
//$txt = 'File output id ('.$file_output.')'.PHP_EOL;
//fwrite($myfile, $txt);
        if ( $out_type == 'F' )
        {
            $pdf->Output($file_output, $out_type);
            $pdf_file = $file_output;
        }
        else 
        {
            $pdf_file = $pdf->Output($file_name, $out_type);
        }

        unset($pdf);
/*
        // Example multiple destinations
        switch($dest)
        {
            case 'I':
                // Send to standard output
                $this->_checkoutput();
                if(PHP_SAPI!='cli')
                {
                    // We send to a browser
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: inline; filename="'.$name.'"');
                    header('Cache-Control: private, max-age=0, must-revalidate');
                    header('Pragma: public');
                }
                echo $this->buffer;
                break;
            case 'D':
                // Download file
                $this->_checkoutput();
                header('Content-Type: application/x-download');
                header('Content-Disposition: attachment; filename="'.$name.'"');
                header('Cache-Control: private, max-age=0, must-revalidate');
                header('Pragma: public');
                echo $this->buffer;
                break;
            case 'F':
                // Save to local file
                $f = fopen($name,'wb');
                if(!$f)
                    $this->Error('Unable to create output file: '.$name);
                fwrite($f,$this->buffer,strlen($this->buffer));
                fclose($f);
                break;
            case 'S':
                // Return as a string
                return $this->buffer;
            default:
                $this->Error('Incorrect output destination: '.$dest);
        }
*/
//$txt = 'util_'.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
//fclose($myfile);
        return $pdf_file;
    }

    private function hex_to_rgb( $hex )
    {
        list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
        return array($r, $g, $b);
    }
}