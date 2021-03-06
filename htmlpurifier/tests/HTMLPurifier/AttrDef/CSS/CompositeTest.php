<?php

require_once 'HTMLPurifier/AttrDef/CSS/Composite.php';
require_once 'HTMLPurifier/AttrDefHarness.php';

class HTMLPurifier_AttrDef_CSS_Composite_Testable extends
      HTMLPurifier_AttrDef_CSS_Composite
{
    
    // we need to pass by ref to get the mocks in
    function HTMLPurifier_AttrDef_CSS_Composite_Testable(&$defs) {
        $this->defs =& $defs;
    }
    
}

class HTMLPurifier_AttrDef_CSS_CompositeTest extends HTMLPurifier_AttrDefHarness
{
    
    var $def1, $def2;
    
    function test() {
        
        generate_mock_once('HTMLPurifier_AttrDef');
        
        $config = HTMLPurifier_Config::createDefault();
        $context = new HTMLPurifier_Context();
        
        // first test: value properly validates on first definition
        // so second def is never called
        
        $def1 = new HTMLPurifier_AttrDefMock($this);
        $def2 = new HTMLPurifier_AttrDefMock($this);
        $defs = array(&$def1, &$def2);
        $def = new HTMLPurifier_AttrDef_CSS_Composite_Testable($defs);
        $input = 'FOOBAR';
        $output = 'foobar';
        $def1_params = array($input, $config, $context);
        $def1->expectOnce('validate', $def1_params);
        $def1->setReturnValue('validate', $output, $def1_params);
        $def2->expectNever('validate');
        
        $result = $def->validate($input, $config, $context);
        $this->assertIdentical($output, $result);
        
        $def1->tally();
        $def2->tally();
        
        // second test, first def fails, second def works
        
        $def1 = new HTMLPurifier_AttrDefMock($this);
        $def2 = new HTMLPurifier_AttrDefMock($this);
        $defs = array(&$def1, &$def2);
        $def = new HTMLPurifier_AttrDef_CSS_Composite_Testable($defs);
        $input = 'BOOMA';
        $output = 'booma';
        $def_params = array($input, $config, $context);
        $def1->expectOnce('validate', $def_params);
        $def1->setReturnValue('validate', false, $def_params);
        $def2->expectOnce('validate', $def_params);
        $def2->setReturnValue('validate', $output, $def_params);
        
        $result = $def->validate($input, $config, $context);
        $this->assertIdentical($output, $result);
        
        $def1->tally();
        $def2->tally();
        
        // third test, all fail, so composite faiils
        
        $def1 = new HTMLPurifier_AttrDefMock($this);
        $def2 = new HTMLPurifier_AttrDefMock($this);
        $defs = array(&$def1, &$def2);
        $def = new HTMLPurifier_AttrDef_CSS_Composite_Testable($defs);
        $input = 'BOOMA';
        $output = false;
        $def_params = array($input, $config, $context);
        $def1->expectOnce('validate', $def_params);
        $def1->setReturnValue('validate', false, $def_params);
        $def2->expectOnce('validate', $def_params);
        $def2->setReturnValue('validate', false, $def_params);
        
        $result = $def->validate($input, $config, $context);
        $this->assertIdentical($output, $result);
        
        $def1->tally();
        $def2->tally();
        
    }
    
}

?>