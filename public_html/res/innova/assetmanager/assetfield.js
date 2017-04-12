aWidth                  = 900;
aHeight                 = 700;
assetUrlFieldID         = null;
var assetItemBaseUrl    = {};
_key                    = null;


function previewAsset( key ){
  var assetUrlFieldID = "assetUrlField" + key;
  var previewID       = "assetPreview" + key;
  var url             = document.getElementById(assetUrlFieldID).value;
  if ( url != ""){
    if (assetItemBaseUrl[key]['type'] == 'direct' && url.indexOf(assetItemBaseUrl[key]['url']) == -1)
    {
        url = assetItemBaseUrl[key]['url'] + url;
    }
    if (url.indexOf('.'))
    {
        document.getElementById(previewID).src = url;
    }
    else
    {
        document.getElementById(previewID).src = url + "width/150/height/150/";
    }

  }
}

function setAssetValue(link){
    if ( link == undefined) link = "";
    link = link.replace('show', 'thumbnail');
    if (assetItemBaseUrl[_key]['type'] == 'direct') {
        link = link.replace(assetItemBaseUrl[_key]['url'], '');
    }
    document.getElementById(assetUrlFieldID).value = link;
    previewAsset( _key );
}

function openAsset( key, assetManagerURL ){
  assetUrlFieldID = "assetUrlField" + key;
  _key            = key;
  if (assetManagerURL == undefined)
  {
    assetManagerURL = defaultAssetManagerURL;
  }
  if(navigator.appName.indexOf('Microsoft')!=-1){
    var link      = window.showModalDialog(assetManagerURL,window,
                    "dialogWidth:"+aWidth+"px;dialogHeight:"+aHeight+"px;edge:Raised;center:Yes;help:No;Resizable:Yes;Maximize:Yes");
    setAssetValue(link);
  }
  else {
    var left = screen.availWidth/2 - aWidth/2;
    var top = screen.availHeight/2 - aHeight/2;
    activeModalWin = window.open(assetManagerURL, "", "width="+aWidth+"px,height="+aHeight+",left="+left+",top="+top+",resizable=yes,scrollbars=yes");
    window.onfocus = function(){if (activeModalWin.closed == false){activeModalWin.focus();};};
  }
}

function clearAsset( key ){
  assetUrlFieldID = "assetUrlField" + key;
  if ( document.getElementById(assetUrlFieldID).value != "" ){
    if ( confirm('Please confirm that you want to clear asset field') ){
      document.getElementById(assetUrlFieldID).value = "";
      document.getElementById("assetPreview"+key).src = "";
    }
  }
}

