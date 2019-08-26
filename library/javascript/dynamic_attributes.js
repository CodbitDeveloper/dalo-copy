var dictCounter = 1;		// counter for the different attributes html elements
var ajax = new Array();

// recieves the select box object of the vendors list
function getVendorsList(sel) {

        document.getElementById(sel).options.length = 1;     // empty attributes list

        var index = ajax.length;
	ajax[index] = new sack();

        ajax[index].requestFile = 'include/management/dynamic_attributes.php?getVendorsList=yes';  // Specifying which file to get
        ajax[index].onCompletion = function(){ createVendors(index,sel) };        // Specify function that will be executed after file has been processed
        ajax[index].runAJAX();          // Execute AJAX function

}

function createVendors(index,sel) {
	var objVendors = document.getElementById(sel);
        eval(ajax[index].response);     // Executing the response from Ajax as Javascript code
}


function getAttributesList(sel,attributesSel) {

        var vendorName = sel.options[sel.selectedIndex].value;

        document.getElementById(attributesSel).options.length = 0;     // empty attributes list
        if(vendorName.length>0) {

              var index = ajax.length;
                ajax[index] = new sack();

                ajax[index].requestFile = 'include/management/dynamic_attributes.php?vendorAttributes='+vendorName;  // Specifying which file to get
                ajax[index].onCompletion = function(){ createAttributes(index,attributesSel) };        // Specify function that will be executed after file has bee$
                ajax[index].runAJAX();          // Execute AJAX function
        }
}

function createAttributes(index,attributesSel) {
        var objAttributes = document.getElementById(attributesSel);
        eval(ajax[index].response);     // Executing the response from Ajax as Javascript code
}

function getValuesList(sel,valuesSel,opSel,tableSel,attrTooltip,attrType,attrHelper) {

        var attributeName = document.getElementById(sel).value;

        document.getElementById(valuesSel).value = '';		 // clear input
        document.getElementById(opSel).options.length = 0;       // clear select box
	if (document.getElementById(tableSel).type == "select")
	        document.getElementById(tableSel).options.length = 0;    // clear select box


        document.getElementById(attrType).value = '';       	 // clear input
        document.getElementById(attrTooltip).value = '';         // clear input
	document.getElementById(attrHelper).value = '';

	num = dictCounter - 1;

        if(attributeName.length>0) {
                var index = ajax.length;
                ajax[index] = new sack();
                ajax[index].requestFile = 'include/management/dynamic_attributes.php?getValuesForAttribute='+attributeName+'&instanceNum='+num+'&dictValueId='+valuesSel;    // Specifying which file to get
                ajax[index].onCompletion = function(){ createValues(index,valuesSel,opSel,tableSel,attrTooltip,attrType,attrHelper) };   // Specify function that will be executed after file has been found
                ajax[index].runAJAX();          // Execute AJAX function
        }
}

function createValues(index,valuesSel,opSel,tableSel,attrTooltip,attrType,attrHelper) {

	var objHelper = document.getElementById(attrHelper);
	var objTooltip = document.getElementById(attrTooltip);
	var objType = document.getElementById(attrType);
        var objValues = document.getElementById(valuesSel);
        var objOP = document.getElementById(opSel);
        var objTable = document.getElementById(tableSel);
        eval(ajax[index].response);     // Executing the response from Ajax as Javascript code
}




function parseAttribute(attrElement) {

	//var enableTable = 1;
	
	var attributeCustom = document.getElementById('dictAttributesCustom');
	var attributeCustomVal = attributeCustom.value;

	if (attrElement == 1) {
	        var attributeOfDatabase = document.getElementById('dictAttributesDatabase');
	        var attributeOfDatabaseVal = attributeOfDatabase.options[attributeOfDatabase.selectedIndex].value;
		addElement(1, 'dictAttributesDatabase');
	} else {
		addElement(1, 'dictAttributesCustom');
	}

}

function addElement(enableTable, elementId) {

  dictCounter++;			// incrementing elements counter

  var divContainer = document.getElementById('divContainer');
  var divCounter = document.getElementById('divCounter');
  var num = (document.getElementById('divCounter').value -1)+ 2;
  divCounter.value = num;

  var attributeDiv = document.createElement('div');
  var divIdName = 'attrib'+num+'Div';
  attributeDiv.setAttribute('id',divIdName);


  if (enableTable == 1) {
        tableElement = ""+
                "<div class='form-group col-md-3'>"+
		"<label>Target:</label>"+	
	 	"<div class='select-wrapper'><select id='dictTable"+num+"' name='dictValues"+dictCounter+"[]' class='form input-bordered'>"+
		"</select></div></div>";
  } else {
	tableElement = "<input type='hidden' id='dictTable"+num+"' name='dictValues"+dictCounter+"[]' >";
  };

        // get top-page attribute's value

      var srcElem = document.getElementById(elementId);

	if (elementId == 'dictAttributesDatabase') {
	      var elemVal = srcElem.options[srcElem.selectedIndex].value;
	} else {
	      var elemVal = srcElem.value;
	}

  var content = "" +
	""+
        "<div class='form-row mt-3'>"+
	"       <div class='form-group col-md-3'>"+
        "            <label><b class='text-muted'>Attribute</b></label>"+
        "            <input type='text' id='dictAttributes1' name='dictValues"+dictCounter+"[]' value='"+elemVal+"' "+
	""+
	"		class='form input-bordered' readonly>"+
	""+
        "       </div>"+
	""+
        "      <div class='form-group col-md-3'>"+
        "       <label>Value</label>"+
        "       <input type='text' id='dictValues"+num+"' name='dictValues"+dictCounter+"[]' class='form input-bordered' >"+
	""+
        "       <span id='dictHelper"+num+"'>"+
        "	"+
        "       </span>"+
        "       </div>"+
        "       <div class='form-group col-md-3'>" +
        "       <label>Op</label>"+
        "       <div class='select-wraper'>" +
        "       <select id='dictOP"+num+"' name='dictValues"+dictCounter+"[]' class='form input-bordered' >"+
        "       </select></div>"+
	"       </div>"+
	tableElement+
	""+
        ""+
	""+
        "     <div id='dictInfo"+num+"' style='display:none;visibility:visible'>"+
	""+
        ""+
	""+
        "                <span id='dictTooltip"+num+"'>"+
        "                        <b>Description:</b>"+
        "                </span>"+
        "                <br/>"+
	""+
        "                <span id='dictType"+num+"'>"+
        "                        <b>Type<b/>"+
        "                </span>"+
	""+
        "        <br/></div>"+
	""+
        ""+
	""+
	"<a href='javascript:void(0)' name='removeAttributes' onclick=\"javascript:removeElement(\'"+divIdName+"\');\" class='button badge badge-danger'>Remove</a>&nbsp;"+
	"<a href='javascript:void(0)' name='infoAttribute' onclick=\"javascript:toggleShowDiv(\'dictInfo"+num+"\');\" class='button badge badge-primary'>Info</a>"+
        "</div>"+
	"<div id='chooserSpan"+num+"' class='dateChooser select-free' style='display: none; visibility: hidden; width: 160px;'></div>";


  attributeDiv.innerHTML = content;
  divContainer.appendChild(attributeDiv);

  getValuesList(elementId,'dictValues'+num,'dictOP'+num,'dictTable'+num,'dictTooltip'+num,'dictType'+num,'dictHelper'+num);

}


function removeElement(divNum) {
  var divContainer = document.getElementById('divContainer');
  var attributeDiv = document.getElementById(divNum);
  divContainer.removeChild(attributeDiv);
}
