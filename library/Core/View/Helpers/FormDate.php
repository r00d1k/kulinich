<?php
require_once 'Zend/View/Helper/FormText.php';

/**
 * Helper to generate a "date" element.
 *
 * @category   Core
 * @package    Core_View
 * @subpackage Helper
 */
class Core_View_Helper_FormDate extends Zend_View_Helper_FormText
{
    /**
     * Generates a 'text' element.
     *
     * @param string|array $name    If a string, the element name.  If an array, all other parameters are ignored, and the array elements are used in place of added parameters.
     * @param mixed        $value   The element value.
     * @param array        $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formDate($name, $value = null, array $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        $config = array(
            'dateFormat: "yy-mm-dd"',
            'altFormat: "' . str_replace('Y', 'yy', $attribs['dateFormat']).'"',
            'altField: "#'.$id.'_alternate"',
            'constrainInput: true',
            'beforeShow:function(input, inst){
                $(input.parentNode).append(inst.dpDiv.get(0))
                inst.dpDiv.css({
                    "position"  : "absolute",
                    "left"      : "0px",
                    "top"       : $(input).height()+"px",
                    "visibility": "hidden"
                });
                setTimeout(function(){
                    inst.dpDiv.css({
                        "position"  : "absolute",
                        "left"      : "0px",
                        "top"       : $(input).height()+"px",
                        "visibility": "visible"
                    });
                },1)
            }',
        );

        $setAttribNames = array('buttonImage', 'buttonImageOnly', 'minDate', 'yearRange', 'maxDate', 'yearSuffix', 'weekHeader', 'stepMonths', 'showWeek', 'showOtherMonths', 'showOptions', 'showMonthAfterYear', 'showCurrentAtPos', 'showButtonPanel', 'showAnim', 'shortYearCutoff', 'selectOtherMonths', 'prevText', 'numberOfMonths', 'nextText', 'navigationAsDateFormat', 'monthNamesShort', 'monthNames', 'isRTL', 'hideIfNoPrevNext', 'gotoCurrent', 'firstDay', 'duration', 'defaultDate', 'dayNamesShort', 'dayNamesMin', 'dayNames', 'currentText', 'closeText', 'changeYear', 'changeMonth', 'calculateWeek', 'buttonText', 'buttonImage', 'autoSize', 'appendText');
        foreach($setAttribNames as $an)
        {
            if(isset($attribs[$an]) && $attribs[$an] != null)
            {
                $config[] = $an . ':' . Zend_Json::encode($attribs[$an]);
                unset($attribs[$an]);
            }
        }
        $xhtml = $this->formText($name, $value, $attribs);

        $altValue = '';
        if(!empty($value))
        {
            $altValue = date($attribs['dateFormat'], strtotime($value));
        }

        $altField = $this->formText($name, $altValue, $attribs);

        $xhtml.='
            <script type="text/javascript">
                $("#'.$id.'")
                    .after(
                        $("'. addslashes($altField).'")
                            .attr({
                                "id": "'.$id.'_alternate",
                                "name": "",
                                "readonly": true
                             })
                            .addClass("hasDatepicker")
                            .val("' . $altValue . '")
                    ).hide();
                    
                $("#'.$id.'_alternate")
                    .click(function(){
                        $("#'.$id.'").trigger("click");
                    })
                    .focus(function(){
                        $("#'.$id.'").trigger("focus");
                    });

                $("#'.$id.'")
                    .attr("readonly",true)
                    .datepicker({'.implode(",\r\n", $config).'});
            </script>
        ';
        return $xhtml;
    }
}
