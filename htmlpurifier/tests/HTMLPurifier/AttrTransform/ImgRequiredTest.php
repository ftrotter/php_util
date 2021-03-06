<?php

require_once 'HTMLPurifier/AttrTransform/ImgRequired.php';
require_once 'HTMLPurifier/AttrTransformHarness.php';

class HTMLPurifier_AttrTransform_ImgRequiredTest extends HTMLPurifier_AttrTransformHarness
{
    
    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_AttrTransform_ImgRequired();
    }
    
    function test() {
        
        $this->assertResult(
            array(),
            array('src' => '', 'alt' => 'Invalid image')
        );
        
        $this->assertResult(
            array(),
            array('src' => 'blank.png', 'alt' => 'Pawned!'),
            array(
                'Attr.DefaultInvalidImage' => 'blank.png',
                'Attr.DefaultInvalidImageAlt' => 'Pawned!'
            )
        );
        
        $this->assertResult(
            array('src' => '/path/to/foobar.png'),
            array('src' => '/path/to/foobar.png', 'alt' => 'foobar.png')
        );
        
        $this->assertResult(
            array('alt' => 'intrigue'),
            array('src' => '', 'alt' => 'intrigue')
        );
        
    }
    
}

?>