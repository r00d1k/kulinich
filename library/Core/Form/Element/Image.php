<?php
class Core_Form_Element_Image extends Core_Form_Element_Hidden
{
    /**
     * Initialization.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->setDecorators(array('ViewHelper',new Core_Form_Decorator_FormLine()));
        $this
            ->addFilter(new Zend_Filter_StringTrim())
            ->addFilter(new Zend_Filter_StripNewlines())
            ->addFilter(new Zend_Filter_StripTags());
    }
    /**
     * Returns true if element is required.
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->_required;
    }

    protected $_endpoint = null;

    public function setEndpoint($endpoint)
    {
        $this->_endpoint = $endpoint;
        return $this;
    }
    public function getEndpoint()
    {
        return $this->_endpoint;
    }

    public function render(Zend_View $view = null)
    {
        return parent::render($view) . '
            <script type="text/javascript">
                $(document).ready(function(){
                    var uploadContainer = $("<div></div>");
                    $("#' . $this->getId() . '").parent().append(uploadContainer);
                    uploadContainer.fineUploader({
                        request: {
                            endpoint: "' .$this->getEndpoint() . '"
                        },
                        validation:{
                            allowedExtensions:["jpg","png","gif"]
                        },
                        text: {
                            uploadButton: \'<i class="icon-upload icon-white upload-indicator"></i> ' . $this->getLabel() . '\'
                        },
                        template: \'<div class="qq-uploader">\' +
                         \'<pre class="qq-upload-drop-area span12" style="display:none;"><span>{dragZoneText}</span></pre>\' +
                          \'<div class="qq-upload-button btn btn-success" style="display:inline-block">{uploadButtonText}</div>\' +
                          \'<span class="qq-drop-processing" style="display:none;"><span>{dropProcessingText}</span><span class="qq-drop-processing-spinner"></span></span>\' +
                          \'<ul class="qq-upload-list" style="display:none;"></ul>\' +
                        \'</div>\'
                    })
                    .on("submitted", function(event, id, name) {
                        $(this).find(".upload-indicator")
                            .removeClass("icon-upload")
                            .removeClass("icon-white")
                            .addClass("facebook-loader")
                    })
                    .on(\'error\', function(event, id, name, reason) {
                        $(this).find(".upload-indicator")
                            .addClass("icon-upload")
                            .addClass("icon-white")
                            .removeClass("facebook-loader")
                    })
                    .on(\'complete\', function(event, id, name, responseJSON){
                        $(this).find(".upload-indicator")
                            .addClass("icon-upload")
                            .addClass("icon-white")
                            .removeClass("facebook-loader")
                        var previewImage = $(this).find(".core-form-preview-image");
                        if(previewImage.length == 0)
                        {
                            previewImage = $(\'<img src="" class="core-form-preview-image" />\');
                            $(this).append(previewImage);
                        }
                        previewImage.attr("src", responseJSON.resultFile);
                        $("#' . $this->getId() . '").val(responseJSON.resultFile)
                    });

                    if($("#' . $this->getId() . '").val() != "")
                    {
                        var previewImage = uploadContainer.find(".core-form-preview-image");
                        if(previewImage.length == 0)
                        {
                            previewImage = $(\'<img src="" class="core-form-preview-image" />\');
                            uploadContainer.append(previewImage);
                        }
                         previewImage.attr("src", $("#' . $this->getId() . '").val());
                    }
                });
            </script>
        ';
    }
}